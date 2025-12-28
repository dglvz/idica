<?php

namespace App\Http\Controllers;

use App\Models\InfoMedica;
use App\Models\Paciente;
use Illuminate\Http\Request;
use App\Services\OrthancService;

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
    // Aumentar el tiempo máximo de ejecución a 5 minutos (300 segundos) para subidas grandes
    set_time_limit(300);

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

    // Subir a Orthanc si existe archivo DICOM
    if ($request->hasFile('archivo_dicom')) {
        try {
            $paciente = Paciente::findOrFail($request->paciente_id);
            $filePath = $request->file('archivo_dicom')->getPathname();
            $response = $this->orthancService->uploadDicom($filePath, $paciente);

            // VALIDACIÓN: Si Orthanc no devuelve un ID (ej. ZIP sin DICOMs válidos), lanzamos error
            if (empty($response['ParentStudy'])) {
                return back()->withErrors(['archivo_dicom' => 'El archivo se subió, pero no se detectaron imágenes DICOM válidas para generar un estudio.'])->withInput();
            }

            // Guardamos el ID del estudio de Orthanc para generar el link al visualizador
            $data['orthanc_study_id'] = $response['ParentStudy'];
        } catch (\Exception $e) {
            return back()->withErrors(['archivo_dicom' => 'Error al conectar con Orthanc. Asegúrese de que el servicio esté encendido.'])->withInput();
        }
    }

    $infoMedica = InfoMedica::create($data);

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
        ->route('info_medica.index')
        ->with('success', 'Información médica registrada.');
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
