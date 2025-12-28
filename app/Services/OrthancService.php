<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class OrthancService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => config('orthanc.url'),
            'auth' => [config('orthanc.user'), config('orthanc.pass')],
            'verify' => false, // ya que usamos HTTP
            'timeout' => 300, // Timeout de 5 minutos para evitar cortes en subidas grandes
        ]);
    }

    // Listar todos los pacientes
    public function listPatients()
    {
        $response = $this->client->get('/patients');
        return json_decode($response->getBody(), true);
    }

    // Obtener detalles de un paciente
    public function getPatient($id)
    {
        $response = $this->client->get("/patients/{$id}");
        return json_decode($response->getBody(), true);
    }

    // Subir archivo DICOM
    public function uploadDicom($filePath, $paciente = null)
    {
        // Detectar si es un archivo ZIP
        $mimeType = mime_content_type($filePath);
        $response = [];

        if ($mimeType === 'application/zip' || $mimeType === 'application/x-zip-compressed') {
            $response = $this->uploadZip($filePath);
        } else {
            $httpResponse = $this->client->post('/instances', [
                'multipart' => [
                    [
                        'name' => 'file',
                        'contents' => fopen($filePath, 'r')
                    ]
                ]
            ]);
            $response = json_decode($httpResponse->getBody(), true);
        }

        // Si se proporcionó un paciente y la subida fue exitosa, sincronizamos los metadatos en Orthanc
        if ($paciente && !empty($response['ParentStudy'])) {
            $newStudyId = $this->assignPatientToStudy($response['ParentStudy'], $paciente);
            if ($newStudyId) {
                $response['ParentStudy'] = $newStudyId;
            }
        }

        return $response;
    }

    // Método auxiliar para procesar ZIPs
    protected function uploadZip($filePath)
    {
        $zip = new \ZipArchive;
        $studyId = null;

        if ($zip->open($filePath) === true) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $filename = $zip->getNameIndex($i);
                // Ignorar directorios
                if (substr($filename, -1) === '/') continue;

                // Obtener stream del archivo dentro del zip sin extraerlo a disco
                $stream = $zip->getStream($filename);
                
                try {
                    $response = $this->client->post('/instances', [
                        'body' => $stream
                    ]);
                    $data = json_decode($response->getBody(), true);
                    
                    // Guardamos el ID del primer estudio que se cree/detecte
                    if (!$studyId && isset($data['ParentStudy'])) {
                        $studyId = $data['ParentStudy'];
                    }
                } catch (\Exception $e) {
                    // Continuar si un archivo dentro del zip no es DICOM válido
                    Log::warning("Fallo al subir archivo del ZIP a Orthanc: " . $filename . " - Error: " . $e->getMessage());
                    continue;
                }
            }
            $zip->close();
        }

        return ['ParentStudy' => $studyId];
    }

    // Asignar metadatos del paciente de Laravel al estudio de Orthanc
    protected function assignPatientToStudy($studyId, $paciente)
    {
        try {
            // Usamos el endpoint /modify para sobrescribir los tags DICOM
            // Esto crea un nuevo estudio con los datos corregidos y borra el anterior
            $response = $this->client->post("/studies/{$studyId}/modify", [
                'json' => [
                    'Replace' => [
                        'PatientID'   => (string) $paciente->cedula,
                        'PatientName' => (string) $paciente->nombre,
                    ],
                    'KeepSource' => false, // Borra el estudio original (con datos incorrectos)
                    'Force' => true,       // Fuerza la modificación
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            return $data['ID'] ?? null;
        } catch (\Exception $e) {
            Log::error("Error al sincronizar estudio {$studyId} con paciente {$paciente->cedula}: " . $e->getMessage());
            return null; // Si falla, retornamos null para conservar el ID original
        }
    }

    // Listar todos los estudios
    public function listStudies()
    {
        $response = $this->client->get('/studies');
        return json_decode($response->getBody(), true);
    }

    // Obtener metadatos de un estudio
    public function getStudy($id)
    {
        $response = $this->client->get("/studies/{$id}");
        return json_decode($response->getBody(), true);
    }
}
