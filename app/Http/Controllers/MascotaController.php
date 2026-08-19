<?php
namespace App\Http\Controllers;
use App\Models\Mascota; use App\Models\Propietario; use Illuminate\Http\Request;

class MascotaController extends Controller
{
    public function index(Request $r)
    {
        $mascotas = Mascota::activas()->with('propietario')
            ->when($r->q, fn($q)=>$q->where(fn($w)=>$w
                ->where('mascotas.nombre','like',"%{$r->q}%")
                ->orWhere('raza','like',"%{$r->q}%")
                ->orWhereHas('propietario', fn($wp)=>$wp->where('nombre_completo','like',"%{$r->q}%"))))
            ->when($r->especie, fn($q)=>$q->where('especie',$r->especie))
            ->paginate(10)->withQueryString();
        return view('mascotas.index', compact('mascotas'));
    }
    public function create() { return view('mascotas.form', ['m'=>new Mascota,'propietarios'=>Propietario::activos()->get()]); }
    public function store(Request $r)
    {
        $r->validate(['id_propietario'=>'required|exists:propietarios,id_propietario','nombre'=>'required|max:80','especie'=>'required|in:perro,gato,ave,roedor']);
        Mascota::create($r->only(['id_propietario','nombre','especie','raza','edad','fecha_nacimiento','sexo','color','senas_particulares']) + ['estado'=>'activo']);
        return redirect()->route('admin.mascotas.index')->with('ok','Mascota registrada');
    }
    public function show($id) { return view('mascotas.show', ['m'=>Mascota::with(['consultas.tratamientos','consultas.metodos','vacunas'])->findOrFail($id)]); }
    public function edit($id) { return view('mascotas.form', ['m'=>Mascota::findOrFail($id),'propietarios'=>Propietario::activos()->get()]); }
    public function update(Request $r, $id)
    {
        $r->validate(['nombre'=>'required|max:80','especie'=>'required|in:perro,gato,ave,roedor']);
        Mascota::findOrFail($id)->update($r->only(['id_propietario','nombre','especie','raza','edad','fecha_nacimiento','sexo','color','senas_particulares']));
        return redirect()->route('admin.mascotas.show',$id)->with('ok','Mascota actualizada');
    }
    public function destroy($id)
    {
        Mascota::findOrFail($id)->update(['estado'=>'inactivo']);
        return redirect()->route('admin.mascotas.index')->with('ok','Mascota dada de baja (lógica)');
    }
}