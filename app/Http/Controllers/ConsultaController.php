<?php
namespace App\Http\Controllers;
use App\Models\Consulta; use App\Models\Mascota; use App\Models\Receta;
use Illuminate\Http\Request;

class ConsultaController extends Controller
{
    public function create(Request $r)
    {
        return view('consultas.form', ['c'=>new Consulta,'mascota'=>Mascota::find($r->mascota_id),'mascotas'=>Mascota::activas()->get()]);
    }
    public function store(Request $r)
    {
        $r->validate(['id_mascota'=>'required|exists:mascotas,id_mascota','motivo'=>'required|max:200','diagnostico'=>'required|max:200']);
        $c = Consulta::create($r->only(['id_mascota','motivo','enfermedades_anteriores','tratamientos_recientes','ultima_desparasitacion','peso','temperatura','fc','fr','mucosa','rc_min','inspeccion','palpacion','sintomas','diagnostico','observaciones','evolucion_pronostico','proxima_cita'])
            + ['id_usuario'=>auth()->id(),'fecha_hora'=>now()]);
        $this->guardarTratamientos($r,$c); $this->guardarMetodos($r,$c);
        if ($r->boolean('con_receta')) Receta::create(['id_consulta'=>$c->id_consulta,'notas'=>$r->notas_receta]);
        return redirect()->route('admin.mascotas.show',$r->id_mascota)->with('ok','Consulta registrada');
    }
    public function edit($id)
    {
        $c = Consulta::with(['tratamientos','metodos'])->findOrFail($id);
        return view('consultas.form', ['c'=>$c,'mascota'=>$c->mascota,'mascotas'=>Mascota::activas()->get()]);
    }
    public function update(Request $r, $id)
    {
        $c = Consulta::findOrFail($id);
        $c->update($r->only(['motivo','enfermedades_anteriores','tratamientos_recientes','ultima_desparasitacion','peso','temperatura','fc','fr','mucosa','rc_min','inspeccion','palpacion','sintomas','diagnostico','observaciones','evolucion_pronostico','proxima_cita']));
        $c->tratamientos()->delete(); $c->metodos()->delete();
        $this->guardarTratamientos($r,$c); $this->guardarMetodos($r,$c);
        return redirect()->route('admin.mascotas.show',$c->id_mascota)->with('ok','Consulta actualizada');
    }
    private function guardarTratamientos(Request $r, Consulta $c): void
    {
        foreach ($r->input('medicamento', []) as $i => $med) {
            if (trim($med) !== '')
                $c->tratamientos()->create(['fecha'=>$r->f_trat[$i] ?? null,'medicamento'=>$med,'dosis'=>$r->dosis[$i] ?? null,'duracion'=>$r->duracion[$i] ?? null]);
        }
    }
    private function guardarMetodos(Request $r, Consulta $c): void
    {
        foreach ($r->input('examen', []) as $i => $ex) {
            if (trim($ex) !== '')
                $c->metodos()->create(['fecha_orden'=>$r->f_met[$i] ?? null,'examen'=>$ex,'resultados'=>$r->resultados[$i] ?? null]);
        }
    }
}