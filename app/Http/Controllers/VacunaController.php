<?php
namespace App\Http\Controllers;
use App\Models\Vacuna; use Illuminate\Http\Request;

class VacunaController extends Controller
{
    public function store(Request $r)
    {
        $r->validate(['id_mascota'=>'required|exists:mascotas,id_mascota','tipo_vacuna'=>'required|max:80','fecha_aplicacion'=>'required|date']);
        Vacuna::create($r->only(['id_mascota','tipo_vacuna','lote','fecha_aplicacion','fecha_proxima_dosis']));
        return back()->with('ok','Vacuna registrada');
    }
    public function update(Request $r, $id)
    {
        Vacuna::findOrFail($id)->update($r->only(['tipo_vacuna','lote','fecha_aplicacion','fecha_proxima_dosis']));
        return back()->with('ok','Vacuna actualizada');
    }
}