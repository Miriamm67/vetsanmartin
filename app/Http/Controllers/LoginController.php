<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request; use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show() { return view('auth.login'); }
    public function login(Request $r)
    {
        $d = $r->validate(['nombre_usuario'=>'required','contrasena'=>'required']);
        if (!Auth::attempt(['nombre_usuario'=>$d['nombre_usuario'],'password'=>$d['contrasena']]))
            return back()->withErrors(['nombre_usuario'=>'Credenciales inválidas o usuario inactivo']);
        $r->session()->regenerate();
        return auth()->user()->esAdmin() ? redirect()->route('admin.dashboard') : redirect()->route('cliente.historial');
    }
    public function logout(Request $r)
    {
        Auth::logout(); $r->session()->invalidate(); $r->session()->regenerateToken();
        return redirect()->route('login');
    }
}