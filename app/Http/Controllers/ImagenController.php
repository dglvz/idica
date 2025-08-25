<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paciente;
use App\Models\Imagen;
use Illuminate\Support\Facades\Storage;
use Spatie\Image\Image as SpatieImage;

class ImagenController extends Controller
{
    public function store(Request $request, Paciente $paciente)
    {
        $request->validate([
            'imagen' => 'required|image|max:2048',
            'descripcion' => 'nullable|string|max:255',
        ]);

        $file = $request->file('imagen');

        // Guardar imagen original
        $path = $file->store("pacientes/{$paciente->id}", 'public');

        // Crear thumbnail usando Spatie Image
        $thumbnailName = 'thumb_' . $file->hashName();
        $thumbnailPath = "pacientes/{$paciente->id}/{$thumbnailName}";
        $thumbnailFullPath = storage_path('app/public/' . $thumbnailPath);

        // Crear el thumbnail
        SpatieImage::load($file->getRealPath())
            ->width(300)
            ->save($thumbnailFullPath);

        // Guardar en la base de datos
        $paciente->imagenes()->create([
            'ruta' => $path,
            'thumbnail' => $thumbnailPath,
            'descripcion' => $request->descripcion,
        ]);

        return back()->with('success', 'Imagen subida correctamente');
    }

    public function destroy(Paciente $paciente, Imagen $imagen)
    {
        // Verificar que la imagen pertenezca al paciente
        if ($imagen->paciente_id !== $paciente->id) {
            abort(404);
        }

        // Eliminar los archivos del disco 'public'
        if (Storage::disk('public')->exists($imagen->ruta)) {
            Storage::disk('public')->delete($imagen->ruta);
        }
        if ($imagen->thumbnail && Storage::disk('public')->exists($imagen->thumbnail)) {
            Storage::disk('public')->delete($imagen->thumbnail);
        }

        // Eliminar el registro de la base de datos
        $imagen->delete();

        return back()->with('success', 'Imagen eliminada correctamente');
    }
}