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
        $m = $this->mascotaConHistorial($id);

        return $this->historialPdf($m)->download("historial_{$m->nombre}.pdf");
    }

    public function historialPreview($id)
    {
        $m = $this->mascotaConHistorial($id);

        return view('mascotas.historial', compact('m'));
    }

    public function historialPdfPreview($id)
    {
        $m = $this->mascotaConHistorial($id);

        return $this->historialPdf($m)->stream("historial_{$m->nombre}.pdf");
    }

    private function mascotaConHistorial($id): Mascota
    {
        return Mascota::with([
            'propietario',
            'consultas' => fn ($query) => $query
                ->with(['veterinario', 'tratamientos', 'metodos', 'receta', 'adjuntos'])
                ->orderByDesc('fecha_hora'),
            'vacunas',
        ])->findOrFail($id);
    }

    private function historialPdf(Mascota $m)
    {
        return Pdf::loadView('pdf.historial', compact('m'))->setPaper('a4');
    }
}