<?php
namespace App\Http\Controllers;
use App\Models\Mascota; use App\Models\Propietario; use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\Veterinario;

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
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_propietario'     => 'required|exists:propietarios,id_propietario',
            'nombre'             => 'required|string|max:80',
            'especie'            => 'required|in:perro,gato,ave,roedor',
            'raza'               => 'nullable|string|max:60',
            'edad'               => 'nullable|integer|min:0|max:40',
            'fecha_nacimiento'   => 'nullable|date',
            'sexo'               => 'nullable|in:macho,hembra',
            'color'              => 'nullable|string|max:40',
            'senas_particulares' => 'nullable|string|max:200',
            'foto'               => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
        ]);

        if ($request->hasFile('foto')) {
            $data['foto'] = $this->guardarFoto($request);
        }

        Mascota::create($data);
        return redirect()->route('admin.mascotas.index')->with('ok', 'Mascota registrada correctamente.');
    }
       public function show($mascota)
    {
        $m = Mascota::with([
            'propietario',
            'consultas' => fn ($query) => $query
                ->with(['tratamientos', 'veterinario', 'receta', 'metodos', 'adjuntos'])
                ->orderByDesc('fecha_hora'),
            'vacunas',
            'adjuntos.consulta',
            'adjuntos.usuario',
        ])->findOrFail($mascota);

        $veterinarios = \App\Models\Veterinario::activos()->orderBy('nombre_completo')->get();
        return view('mascotas.show', compact('m', 'veterinarios'));
    }
    public function edit($id) { return view('mascotas.form', ['m'=>Mascota::findOrFail($id),'propietarios'=>Propietario::activos()->get()]); }
    public function update(Request $request, $mascota)
    {
        $m = Mascota::findOrFail($mascota);
        $data = $request->validate([
            'id_propietario'     => 'required|exists:propietarios,id_propietario',
            'nombre'             => 'required|string|max:80',
            'especie'            => 'required|in:perro,gato,ave,roedor',
            'raza'               => 'nullable|string|max:60',
            'edad'               => 'nullable|integer|min:0|max:40',
            'fecha_nacimiento'   => 'nullable|date',
            'sexo'               => 'nullable|in:macho,hembra',
            'color'              => 'nullable|string|max:40',
            'senas_particulares' => 'nullable|string|max:200',
            'foto'               => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
        ]);

        if ($request->hasFile('foto')) {
            if ($m->foto && file_exists(public_path($m->foto))) {
                unlink(public_path($m->foto));
            }
            $data['foto'] = $this->guardarFoto($request);
        }

        $m->update($data);
        return redirect()->route('admin.mascotas.show', $mascota)->with('ok', 'Mascota actualizada correctamente.');
    }
    public function destroy($id)
    {
        Mascota::findOrFail($id)->update(['estado'=>'inactivo']);
        return redirect()->route('admin.mascotas.index')->with('ok','Mascota dada de baja (lógica)');
    }
        /**
     * Guarda la foto creando la carpeta si no existe.
     * Compatible con hosting compartido (sin storage:link).
     */
    private function guardarFoto(Request $request): string
    {
        $destino = $this->uploadBase() . '/mascotas';
        \Illuminate\Support\Facades\File::ensureDirectoryExists($destino);
        $file   = $request->file('foto');
        $nombre = 'mascota_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move($destino, $nombre);
        return 'uploads/mascotas/' . $nombre;
    }

    private function uploadBase(): string
    {
        return env('UPLOAD_PATH', public_path('uploads'));
    }
}