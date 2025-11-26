<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OrthancService;

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
        $request->validate([
            'dicom_file' => 'required|file',
        ]);

        $filePath = $request->file('dicom_file')->getPathname();
        $result = $this->orthanc->uploadDicom($filePath);

        return response()->json($result);
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
