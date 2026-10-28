<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Veterinario;
use Illuminate\Http\Request;

class VeterinarioController extends Controller
{
    public function index()
    {
        $veterinarios = Veterinario::with('usuario')->orderBy('nombre_completo')->paginate(10);
        return view('veterinarios.index', compact('veterinarios'));
    }

    public function create()
    {
        $usuarios = Usuario::whereDoesntHave('veterinario')
                           ->where('estado', 'activo')
                           ->orderBy('nombre_usuario')
                           ->get();
        return view('veterinarios.form', compact('usuarios'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_usuario'      => 'required|exists:usuarios,id_usuario|unique:veterinarios,id_usuario',
            'nombre_completo' => 'required|string|max:120',
            'nro_colegiado'   => 'nullable|string|max:40',
            'especialidad'    => 'nullable|string|max:80',
        ]);
        Veterinario::create($data + ['estado' => 'activo']);
        return redirect()->route('admin.veterinarios.index')->with('ok', 'Veterinario registrado correctamente.');
    }

    public function edit($id)
    {
        $veterinario = Veterinario::findOrFail($id);
        $usuarios = Usuario::where('estado', 'activo')
            ->where(function ($q) use ($veterinario) {
                $q->whereDoesntHave('veterinario')
                  ->orWhere('id_usuario', $veterinario->id_usuario);
            })
            ->orderBy('nombre_usuario')
            ->get();
        return view('veterinarios.form', compact('veterinario', 'usuarios'));
    }

    public function update(Request $request, $id)
    {
        $veterinario = Veterinario::findOrFail($id);
        $data = $request->validate([
            'id_usuario'      => 'required|exists:usuarios,id_usuario|unique:veterinarios,id_usuario,' . $id . ',id_veterinario',
            'nombre_completo' => 'required|string|max:120',
            'nro_colegiado'   => 'nullable|string|max:40',
            'especialidad'    => 'nullable|string|max:80',
            'estado'          => 'required|in:activo,inactivo',
        ]);
        $veterinario->update($data);
        return redirect()->route('admin.veterinarios.index')->with('ok', 'Veterinario actualizado correctamente.');
    }

    public function toggleEstado($id)
    {
        $v = Veterinario::findOrFail($id);
        $v->update(['estado' => $v->estado === 'activo' ? 'inactivo' : 'activo']);
        return back()->with('ok', 'Estado del veterinario actualizado.');
    }
}