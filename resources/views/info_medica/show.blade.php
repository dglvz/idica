<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Historia Médica</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <x-navbar title="Detalle de Historia Médica"/>
    <div class="container">
        <h2>Detalle del Examen</h2>
        
        <div style="background: #f9f9f9; padding: 20px; border-radius: 8px; border: 1px solid #ddd;">
            <p><strong>Paciente:</strong> {{ $info_medica->paciente->nombre ?? 'Desconocido' }}</p>
            <p><strong>Cédula:</strong> {{ $info_medica->paciente->cedula ?? 'N/A' }}</p>
            <p><strong>Tipo de Examen:</strong> {{ $info_medica->tipo_examen }}</p>
            <p><strong>Fecha:</strong> {{ $info_medica->created_at->format('d/m/Y H:i') }}</p>
            
            <hr>
            
            <h4>Información Clínica</h4>
            <p>{{ $info_medica->informacion }}</p>

            @if(!empty($info_medica->orthanc_study_id))
                <div style="margin-top: 20px;">
                    <h4 style="margin-bottom: 10px; color: #333;">Visualizador DICOM (OHIF)</h4>
                    <iframe 
                        src="{{ $orthanc_url . config('orthanc.viewer_path') }}?StudyInstanceUIDs={{ $study_instance_uid }}" 
                        style="width: 100%; height: 600px; border: 1px solid #ccc; border-radius: 4px; background-color: #000;"
                        allowfullscreen>
                    </iframe>
                    <div style="text-align: right; margin-top: 8px;">
                        <a href="{{ $orthanc_url . config('orthanc.viewer_path') }}?StudyInstanceUIDs={{ $study_instance_uid }}" target="_blank" style="color: #4a90e2; text-decoration: none; font-size: 14px;">Abrir en ventana completa &nearr;</a>
                    </div>
                </div>
            @endif

            @if($info_medica->imagenes->isNotEmpty())
                <hr>
                <h4>Imágenes Adjuntas</h4>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    @foreach($info_medica->imagenes as $img)
                        <a href="{{ asset('storage/' . $img->ruta) }}" target="_blank">
                            <img src="{{ asset('storage/' . ($img->thumbnail ?? $img->ruta)) }}" style="height: 100px; border-radius: 4px; border: 1px solid #ccc;">
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <div style="margin-top: 20px;">
            <a href="{{ route('info_medica.index') }}" style="text-decoration: none; color: #666;">&larr; Volver al listado</a>
        </div>
    </div>
</body>
</html>