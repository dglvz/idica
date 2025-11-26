<?php

namespace App\Services;

use GuzzleHttp\Client;

class OrthancService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('ORTHANC_URL'),
            'auth' => [env('ORTHANC_USER'), env('ORTHANC_PASS')],
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
