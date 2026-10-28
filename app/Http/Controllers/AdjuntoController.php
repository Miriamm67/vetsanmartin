<?php

namespace App\Http\Controllers;

use App\Models\AdjuntoConsulta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AdjuntoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'id_mascota'  => 'required|exists:mascotas,id_mascota',
            'id_consulta' => 'nullable|exists:consultas,id_consulta',
            'categoria'   => 'required|in:radiografia,laboratorio,foto_clinica,documento,otro',
            'archivo'     => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $destino = $this->uploadBase() . '/adjuntos';
        File::ensureDirectoryExists($destino);

        $file   = $request->file('archivo');
        $nombre = 'adj_' . time() . '_' . str_replace(' ', '-', $file->getClientOriginalName());
        $file->move($destino, $nombre);

        AdjuntoConsulta::create([
            'id_mascota'      => $request->id_mascota,
            'id_consulta'     => $request->id_consulta,
            'id_usuario'      => auth()->id(),
            'categoria'       => $request->categoria,
            'nombre_original' => $file->getClientOriginalName(),
            'ruta_archivo'    => 'uploads/adjuntos/' . $nombre,
            'tipo_mime'       => $file->getClientMimeType(),
            'peso_kb'         => (int) round($file->getSize() / 1024),
        ]);

        return back()->with('ok', 'Archivo adjuntado correctamente.');
    }

    public function destroy($id)
    {
        $adjunto = AdjuntoConsulta::findOrFail($id);
        if (file_exists(public_path($adjunto->ruta_archivo))) {
            unlink(public_path($adjunto->ruta_archivo));
        }
        $adjunto->delete();
        return back()->with('ok', 'Adjunto eliminado.');
    }

    private function uploadBase(): string
    {
        return env('UPLOAD_PATH', public_path('uploads'));
    }
}