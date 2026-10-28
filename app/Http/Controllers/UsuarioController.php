<?php
namespace App\Http\Controllers;
use App\Models\Usuario; use App\Models\Propietario;
use Illuminate\Http\Request; use Illuminate\Support\Facades\Auth; use Illuminate\Support\Facades\Hash;

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
        $data = $r->validate([
            'nombre_usuario' => 'required|string|max:50|unique:usuarios,nombre_usuario,' . $u->id_usuario . ',id_usuario',
            'rol' => 'required|in:admin,cliente',
            'id_propietario' => 'nullable|exists:propietarios,id_propietario',
            'estado' => 'required|in:activo,inactivo',
        ]);
        $u->update($data);
        return redirect()->route('admin.usuarios.index')->with('ok','Usuario actualizado');
    }
        public function editPassword()
    {
        return view('auth.password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_actual' => 'required|string',
            'password_nueva'  => 'required|string|min:6|confirmed',
        ], [
            'password_actual.required' => 'Debe ingresar su contraseña actual.',
            'password_nueva.min'       => 'La contraseña nueva debe tener al menos 6 caracteres.',
            'password_nueva.confirmed' => 'La confirmación de la contraseña nueva no coincide.',
        ]);

        if (!Hash::check($request->password_actual, auth()->user()->contrasena_hash)) {
            return back()->withErrors(['password_actual' => 'La contraseña actual no es correcta.'])->withInput();
        }

        auth()->user()->update(['contrasena_hash' => Hash::make($request->password_nueva)]);
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('ok', 'Contraseña actualizada. Inicia sesión con tu nueva contraseña.');
    }
    public function destroy($id)
    {
        Usuario::findOrFail($id)->update(['estado'=>'inactivo']);
        return redirect()->route('admin.usuarios.index')->with('ok','Usuario desactivado');
    }
}