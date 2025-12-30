<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OrthancService;
use Illuminate\Support\Facades\Log;

class OrthancController extends Controller
{
    protected $orthanc;

    public function __construct(OrthancService $orthanc)
    {
        $this->orthanc = $orthanc;
    }

    // Listar pacientes
    public function patients()
    {
        return response()->json($this->orthanc->listPatients());
    }

    // Subir un archivo DICOM
    public function uploadDicom(Request $request)
    {
        // Aumentar el tiempo de ejecución a 5 minutos para archivos grandes
        set_time_limit(300);

        try {
            $request->validate([
                'dicom_file' => 'required|file',
            ]);

            $file = $request->file('dicom_file');
            $filePath = $file->getPathname();

            Log::info("Iniciando subida a Orthanc. Archivo: " . $file->getClientOriginalName() . ", Tamaño: " . $file->getSize());

            $result = $this->orthanc->uploadDicom($filePath);

            Log::info("Subida a Orthanc completada con éxito.");
            return response()->json($result);
        } catch (\Exception $e) {
            Log::error("Error al subir archivo a Orthanc: " . $e->getMessage());
            return response()->json(['error' => 'Error al procesar el archivo: ' . $e->getMessage()], 500);
        }
    }

    // Listar estudios
    public function studies()
    {
        return response()->json($this->orthanc->listStudies());
    }

    // Obtener metadatos de un estudio
    public function study($id)
    {
        return response()->json($this->orthanc->getStudy($id));
    }
}
