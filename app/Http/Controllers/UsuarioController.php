<?php
namespace App\Http\Controllers;
use App\Models\Usuario; use App\Models\Propietario;
use Illuminate\Http\Request; use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index() { return view('usuarios.index', ['usuarios'=>Usuario::paginate(10)]); }
    public function create() { return view('usuarios.form', ['u'=>new Usuario,'propietarios'=>Propietario::activos()->get()]); }
    public function store(Request $r)
    {
        $r->validate(['nombre_usuario'=>'required|unique:usuarios|max:50','contrasena'=>'required|min:6','rol'=>'required|in:admin,cliente']);
        Usuario::create(['nombre_usuario'=>$r->nombre_usuario,'contrasena_hash'=>Hash::make($r->contrasena),'rol'=>$r->rol,'id_propietario'=>$r->id_propietario ?: null,'estado'=>'activo']);
        return redirect()->route('admin.usuarios.index')->with('ok','Usuario creado');
    }
    public function edit($id) { return view('usuarios.form', ['u'=>Usuario::findOrFail($id),'propietarios'=>Propietario::activos()->get()]); }
    public function update(Request $r, $id)
    {
        $u = Usuario::findOrFail($id);
        $u->update(['rol'=>$r->rol,'id_propietario'=>$r->id_propietario ?: null,'estado'=>$r->estado ?? 'activo']);
        if ($r->filled('contrasena')) $u->update(['contrasena_hash'=>Hash::make($r->contrasena)]);
        return redirect()->route('admin.usuarios.index')->with('ok','Usuario actualizado');
    }
    public function destroy($id)
    {
        Usuario::findOrFail($id)->update(['estado'=>'inactivo']);
        return redirect()->route('admin.usuarios.index')->with('ok','Usuario desactivado');
    }
}