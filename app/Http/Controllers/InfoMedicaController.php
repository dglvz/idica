<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessDicomUpload;
use App\Models\InfoMedica;
use App\Models\Paciente;
use Illuminate\Http\Request;
use App\Services\OrthancService;
use Illuminate\Support\Facades\Log;

class InfoMedicaController extends Controller
{
    protected $orthancService;

    public function __construct(OrthancService $orthancService)
    {
        $this->orthancService = $orthancService;
    }

    // Listado con paginación y eager loading del paciente
    public function index()
    {
        $info_medica = InfoMedica::with('paciente')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $orthanc_url = config('orthanc.url');
        return view('info_medica.index', compact('info_medica', 'orthanc_url'));
    }

    // Formulario para crear
    public function create()
    {
        $pacientes = Paciente::latest()->take(10)->get();

        return view('info_medica.create', compact('pacientes'));
    }

    // Almacenar nuevo registro
    public function store(Request $request)
{
    $request->validate([
        'paciente_id'  => 'required|exists:pacientes,id',
        'informacion'  => 'nullable|string',
        'tipo_examen'  => 'required|string|max:255',
        'imagen'       => 'nullable|image|max:2048',
        'descripcion_imagen' => 'nullable|string|max:255',
        'archivo_dicom' => 'nullable|file',
    ]);

    $data = $request->only([
        'paciente_id',
        'informacion',
        'tipo_examen',
    ]);

    // Crear el registro médico inmediatamente con estado 'processing'
    $infoMedica = InfoMedica::create($data + ['status' => 'processing']);

    // Subir a Orthanc si existe archivo DICOM
    if ($request->hasFile('archivo_dicom')) {
        $path = $request->file('archivo_dicom')->store('dicom-uploads', 'local');

        if (!$path) {
            // Falló el almacenamiento del archivo, actualizar estado y registrar error
            $infoMedica->status = 'failed';
            $infoMedica->save();
            Log::error("Error Crítico: No se pudo guardar el archivo subido en el disco local para InfoMedica ID: {$infoMedica->id}. Verifique los permisos de escritura en la carpeta storage/app/dicom-uploads.");
            
            // Redirigir con un error claro
            return redirect()
                ->route('pacientes.show', $request->paciente_id)
                ->with('error', 'Error crítico al guardar el archivo en el servidor. Contacte al administrador.');
        }

        $absolutePath = storage_path('app/' . $path);
        ProcessDicomUpload::dispatch($infoMedica, $absolutePath);
    }

    // Si hay imagen, la guardamos
    if ($request->hasFile('imagen')) {
        $path = $request->file('imagen')->store('pacientes/'.$request->paciente_id, 'public');
        $infoMedica->imagenes()->create([
            'ruta' => $path,
            'descripcion' => $request->descripcion_imagen,
            'paciente_id' => $request->paciente_id, // asegúrate de tener este campo en la tabla imagenes
        ]);
    }

    return redirect()
        ->route('pacientes.show', $request->paciente_id)
        ->with('success', 'El examen ha sido enviado para su procesamiento. Aparecerá en la lista en unos momentos.');
}   

    // Mostrar detalle (con model binding y carga del paciente)
    public function show(InfoMedica $info_medica)
    {
        $info_medica->load('paciente');

        $orthanc_url = config('orthanc.url');
        return view('info_medica.show', compact('info_medica', 'orthanc_url'));
    }

    // Formulario para edición (incluye lista de pacientes)
    public function edit(InfoMedica $info_medica)
    {
        $pacientes = Paciente::all();

        return view('info_medica.edit', compact('info_medica', 'pacientes'));
    }

    // Actualizar registro
    public function update(Request $request, InfoMedica $info_medica)
    {
        $request->validate([
            'paciente_id'  => 'required|exists:pacientes,id',
            'informacion'  => 'nullable|string',
            'tipo_examen'  => 'required|string|max:255',
        ]);

        $info_medica->update($request->only([
            'paciente_id',
            'informacion',
            'tipo_examen',
        ]));

        return redirect()
            ->route('info_medica.index')
            ->with('success', 'Información médica actualizada correctamente.');
    }

    // Eliminar registro
    public function destroy(InfoMedica $info_medica)
    {
        $info_medica->delete();

        return redirect()
            ->route('info_medica.index')
            ->with('success', 'Información médica eliminada correctamente.');
    }
}
