<?php
namespace App\Http\Controllers;
use App\Models\Consulta;
use Barryvdh\DomPDF\Facade\Pdf;

class ClienteController extends Controller
{
    private function misMascotas() { return auth()->user()->propietario?->mascotas()->get() ?? collect(); }
    public function historial() { return view('cliente.historial', ['mascotas'=>$this->misMascotas()]); }
    public function detalle($id)
    {
        $m = $this->misMascotas()->firstWhere('id_mascota', $id);
        abort_unless($m, 403);
        $m->load(['vacunas','consultas.tratamientos']);
        return view('cliente.detalle', ['m'=>$m]);
    }
    public function descargarReceta($id)
    {
        $ids = $this->misMascotas()->pluck('id_mascota');
        $c = Consulta::whereIn('id_mascota', $ids)->with(['mascota.propietario','tratamientos','veterinario','receta'])->findOrFail($id);
        abort_unless($c->receta, 404);
        return Pdf::loadView('pdf.receta', ['c'=>$c])->setPaper('a4')->download("receta_{$c->id_consulta}.pdf");
    }
}