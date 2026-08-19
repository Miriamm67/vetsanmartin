<?php
namespace App\Http\Controllers;
use App\Models\Consulta; use App\Models\Mascota;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    public function receta($id)
    {
        $c = Consulta::with(['mascota.propietario','tratamientos','veterinario','receta'])->findOrFail($id);
        return Pdf::loadView('pdf.receta', compact('c'))->setPaper('a4')->download("receta_{$c->id_consulta}.pdf");
    }
    public function historial($id)
    {
        $m = Mascota::with(['propietario','consultas.tratamientos','consultas.metodos','vacunas'])->findOrFail($id);
        return Pdf::loadView('pdf.historial', compact('m'))->setPaper('a4')->download("historial_{$m->nombre}.pdf");
    }
}