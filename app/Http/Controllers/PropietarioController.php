<?php
namespace App\Http\Controllers;
use App\Models\Propietario; use Illuminate\Http\Request;

class PropietarioController extends Controller
{
    public function index(Request $r)
    {
        $propietarios = Propietario::activos()
            ->when($r->q, fn($q)=>$q->where('nombre_completo','like',"%{$r->q}%"))
            ->paginate(10)->withQueryString();
        return view('propietarios.index', compact('propietarios'));
    }
    public function create() { return view('propietarios.form', ['p' => new Propietario]); }
    public function store(Request $r)
    {
        $r->validate(['nombre_completo'=>'required|max:120','telefono'=>'required|max:20']);
        Propietario::create($r->only(['nombre_completo','telefono','direccion','zona','calle_av','numero','nro_registro']) + ['estado'=>'activo']);
        return redirect()->route('admin.propietarios.index')->with('ok','Propietario registrado');
    }
    public function edit($id) { return view('propietarios.form', ['p' => Propietario::findOrFail($id)]); }
    public function update(Request $r, $id)
    {
        $r->validate(['nombre_completo'=>'required|max:120','telefono'=>'required|max:20']);
        Propietario::findOrFail($id)->update($r->only(['nombre_completo','telefono','direccion','zona','calle_av','numero','nro_registro']));
        return redirect()->route('admin.propietarios.index')->with('ok','Propietario actualizado');
    }
    public function destroy($id)
    {
        Propietario::findOrFail($id)->update(['estado'=>'inactivo']);
        return redirect()->route('admin.propietarios.index')->with('ok','Propietario dado de baja (lógica)');
    }
}