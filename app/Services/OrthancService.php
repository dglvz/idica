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
    public function uploadDicom($filePath)
    {
        // Detectar si es un archivo ZIP
        $mimeType = mime_content_type($filePath);
        if ($mimeType === 'application/zip' || $mimeType === 'application/x-zip-compressed') {
            return $this->uploadZip($filePath);
        }

        $response = $this->client->post('/instances', [
            'multipart' => [
                [
                    'name' => 'file',
                    'contents' => fopen($filePath, 'r')
                ]
            ]
        ]);

        return json_decode($response->getBody(), true);
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
