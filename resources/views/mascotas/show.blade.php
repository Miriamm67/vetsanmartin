@extends('layouts.app')
@section('titulo', $m->nombre)
@section('contenido')

<div class="d-flex justify-content-between flex-wrap gap-2 mb-3">
  <h4 class="mb-0">
    🐾 {{ $m->nombre }}
    <small class="text-muted">({{ $m->especie }} · {{ $m->raza ?? 'mestizo' }})</small>
  </h4>
  <div>
    <a href="{{ route('admin.historial.preview',$m->id_mascota) }}" class="btn btn-outline-primary btn-sm">Ver historial clínico</a>
    <a href="{{ route('admin.consultas.create',['id_mascota'=>$m->id_mascota]) }}" class="btn btn-primary btn-sm">+ Nueva Consulta</a>
  </div>
</div>

<div class="row g-3">
  {{-- ============ COLUMNA IZQUIERDA: RESEÑA + VACUNA ============ --}}
  <div class="col-md-4">

    {{-- RESEÑA DEL ANIMAL (con foto) --}}
    <div class="card mb-3">
      <div class="card-header">Reseña del animal</div>
      <div class="card-body small">
        @if($m->foto)
          <div class="text-center mb-2">
            <img src="{{ asset($m->foto) }}" alt="{{ $m->nombre }}" class="rounded border" style="max-width:140px;max-height:140px;object-fit:cover;">
          </div>
        @endif
        <b>Dueño:</b> {{ $m->propietario->nombre_completo }}<br>
        <b>F.N.:</b> {{ $m->fecha_nacimiento?->format('d/m/Y') ?? '-' }} · <b>Edad:</b> {{ $m->edad ?? '-' }}<br>
        <b>Sexo:</b> {{ $m->sexo ?? '-' }} · <b>Color:</b> {{ $m->color ?? '-' }}<br>
        <b>Señas:</b> {{ $m->senas_particulares ?? '-' }}
      </div>
    </div>

    {{-- REGISTRAR VACUNA --}}
    <div class="card">
      <div class="card-header">💉 Registrar vacuna</div>
      <div class="card-body">
        <form method="POST" action="{{ route('admin.vacunas.store') }}">
          @csrf
          <input type="hidden" name="id_mascota" value="{{ $m->id_mascota }}">
          @php($opcionesVacuna = \App\Models\Vacuna::opcionesPorEspecie($m->especie))
          <label for="tipo-vacuna" class="form-label small mb-1">Vacuna <span class="text-muted">(opciones habituales; confirmar con el veterinario)</span></label>
          <select name="tipo_vacuna" id="tipo-vacuna" class="form-select form-select-sm mb-1" required>
            <option value="">Seleccionar vacuna...</option>
            @foreach($opcionesVacuna as $opcion)
              <option value="{{ $opcion }}" @selected(old('tipo_vacuna') === $opcion)>{{ $opcion }}</option>
            @endforeach
            <option value="__otra__" @selected(old('tipo_vacuna') === '__otra__')>Otra (especificar)</option>
          </select>
          <input name="tipo_vacuna_otra" id="tipo-vacuna-otra" class="form-control form-control-sm mb-1" placeholder="Escribe el nombre de la vacuna" value="{{ old('tipo_vacuna_otra') }}" maxlength="80" hidden>
          <input name="lote" class="form-control form-control-sm mb-1" placeholder="Lote" maxlength="40">
          <label class="small">Aplicación</label>
          <input type="date" name="fecha_aplicacion" class="form-control form-control-sm mb-1" max="{{ now()->toDateString() }}" required>
          <label class="small">Próxima dosis</label>
          <input type="date" name="fecha_proxima_dosis" class="form-control form-control-sm mb-2" data-after-field="fecha_aplicacion">
          <button class="btn btn-sm btn-success w-100">Guardar vacuna</button>
        </form>
        <script>
          (() => {
            const select = document.getElementById('tipo-vacuna');
            const otra = document.getElementById('tipo-vacuna-otra');
            const actualizarCampoOtra = () => {
              const mostrar = select.value === '__otra__';
              otra.hidden = !mostrar;
              otra.required = mostrar;
            };
            select.addEventListener('change', actualizarCampoOtra);
            actualizarCampoOtra();
          })();
        </script>
      </div>
    </div>
  </div>

  {{-- ============ COLUMNA DERECHA: HISTORIAL ============ --}}
  <div class="col-md-8">

    <h6 class="fw-bold" id="historial-clinico">Historial clínico (más reciente primero)</h6>

    @forelse($m->consultas as $c)
    <div class="card mb-2">
      <div class="card-header d-flex justify-content-between align-items-center py-2">
        <span class="small">
          <b>{{ $c->fecha_hora->format('d/m/Y H:i') }}</b> — {{ $c->motivo }}
          @if($c->veterinario)
            <span class="badge bg-info text-dark ms-2">🩺 {{ $c->veterinario->nombre_completo }}</span>
          @endif
        </span>
        <span>
          <a class="btn btn-sm btn-outline-primary py-0" href="{{ route('admin.consultas.edit',$c->id_consulta) }}">Editar</a>
          <a class="btn btn-sm btn-outline-success py-0" href="{{ route('admin.receta.pdf',$c->id_consulta) }}">Receta PDF</a>
        </span>
      </div>
      <div class="card-body small">
        <div class="row g-2">
          <div class="col-md-6"><b>Enfermedades anteriores:</b> {{ $c->enfermedades_anteriores ?? '—' }}</div>
          <div class="col-md-6"><b>Tratamientos recientes:</b> {{ $c->tratamientos_recientes ?? '—' }}</div>
          <div class="col-md-6"><b>Última desparasitación:</b> {{ $c->ultima_desparasitacion?->format('d/m/Y') ?? '—' }}</div>
          <div class="col-md-6"><b>Examen:</b> {{ $c->peso ?? '—' }} kg · {{ $c->temperatura ?? '—' }} °C · FC {{ $c->fc ?? '—' }} · FR {{ $c->fr ?? '—' }} · Mucosa {{ $c->mucosa ?? '—' }} · R.C. {{ $c->rc_min ?? '—' }}</div>
          <div class="col-md-6"><b>Inspección:</b> {{ $c->inspeccion ?? '—' }}</div>
          <div class="col-md-6"><b>Palpación:</b> {{ $c->palpacion ?? '—' }}</div>
          <div class="col-12"><b>Síntomas:</b> {{ $c->sintomas ?? '—' }}</div>
          <div class="col-12"><b>Diagnóstico:</b> {{ $c->diagnostico }}</div>
        </div>

        @if($c->tratamientos->count())
          <b>Tratamiento:</b>
          <ul class="mb-1">
            @foreach($c->tratamientos as $t)
              <li>{{ $t->fecha?->format('d/m/Y') ?? '—' }} — {{ $t->medicamento }} — {{ $t->dosis ?? '—' }} — {{ $t->duracion ?? '—' }}</li>
            @endforeach
          </ul>
        @endif

        @if($c->metodos->count())
          <b>Métodos complementarios:</b>
          <ul class="mb-1">
            @foreach($c->metodos as $mt)
              <li>{{ $mt->fecha_orden?->format('d/m/Y') ?? '—' }} — {{ $mt->examen }}: {{ $mt->resultados ?? '—' }}</li>
            @endforeach
          </ul>
        @endif

        {{-- 📎 ADJUNTOS DE ESTA CONSULTA --}}
        @if($c->adjuntos->count())
          <b>📎 Adjuntos:</b>
          <div class="mb-1">
            @foreach($c->adjuntos as $adj)
              <a href="{{ asset($adj->ruta_archivo) }}" target="_blank" class="badge bg-light text-dark text-decoration-none me-1">
                {{ ucfirst(str_replace('_',' ',$adj->categoria)) }}: {{ Str::limit($adj->nombre_original, 25) }} ({{ $adj->peso_kb }} KB)
              </a>
            @endforeach
          </div>
        @endif

        <b>Evolución y pronóstico:</b> {{ $c->evolucion_pronostico ?? '—' }}<br>
        <b>Observaciones:</b> {{ $c->observaciones ?? '—' }}<br>
        <b>Próx. cita:</b> {{ $c->proxima_cita?->format('d/m/Y') ?? '—' }}<br>
        @if($c->receta)
          <b>Notas de receta:</b> {{ $c->receta->notas ?? '—' }}<br>
        @endif
      </div>
    </div>
    @empty
      <p class="text-muted">Sin consultas registradas.</p>
    @endforelse

    {{-- ============ CALENDARIO DE VACUNACIÓN CON CONTROL DE CUMPLIMIENTO ============ --}}
    <div class="card mt-3">
      <div class="card-header">💉 Calendario de vacunación y control de cumplimiento</div>
      <div class="card-body table-responsive p-0">
        <table class="table table-sm align-middle mb-0">
          <thead>
            <tr>
              <th>Vacuna</th><th>Lote</th><th>Aplicación</th><th>Próxima</th>
              <th>Estado</th>              <th>Fecha aplicada</th><th class="text-end">Acciones</th>
            </tr>
          </thead>
          <tbody>
            @forelse($m->vacunas as $v)
              <tr>
                <td>{{ $v->tipo_vacuna }}</td>
                <td>{{ $v->lote ?? '—' }}</td>
                <td>{{ $v->fecha_aplicacion->format('d/m/Y') }}</td>
                <td>{{ $v->fecha_proxima_dosis?->format('d/m/Y') ?? '—' }}</td>
                <td>
                  @if($v->estado_proxima === 'aplicada')
                    <span class="badge bg-success">Aplicada</span>
                  @elseif($v->estado_proxima === 'omitida')
                    <span class="badge bg-secondary">Omitida</span>
                  @elseif($v->fecha_proxima_dosis && $v->fecha_proxima_dosis->lt(now()))
                    <span class="badge bg-danger">Vencida</span>
                  @elseif($v->fecha_proxima_dosis && $v->fecha_proxima_dosis->lte(now()->addDays(30)))
                    <span class="badge bg-warning text-dark">Por vencer</span>
                  @else
                    <span class="badge bg-info text-dark">Programada</span>
                  @endif
                  @if($v->observacion)
                    <br><small class="text-muted">{{ $v->observacion }}</small>
                  @endif
                </td>
                <td>
                  @if($v->estado_proxima === 'aplicada')
                    {{ $v->fecha_aplicacion->format('d/m/Y') }}
                  @else
                    —
                  @endif
                </td>
                <td class="text-end">
                  <div class="d-flex flex-wrap justify-content-end gap-1">
                    <a href="{{ route('admin.vacunas.edit', $v->id_vacuna) }}" class="btn btn-sm btn-outline-primary py-0">Editar</a>
                    @if($v->estado_proxima === 'pendiente')
                      <form action="{{ route('admin.vacunas.aplicada', $v->id_vacuna) }}" method="POST" class="d-inline-flex gap-1 align-items-center">
                        @csrf @method('PATCH')
                        <input type="date" name="fecha_aplicacion" value="{{ now()->toDateString() }}" max="{{ now()->toDateString() }}" class="form-control form-control-sm" style="width:135px" aria-label="Fecha real de aplicación" required>
                        <button class="btn btn-sm btn-outline-success py-0">Ya se aplicó</button>
                      </form>
                      <form action="{{ route('admin.vacunas.omitida', $v->id_vacuna) }}" method="POST" class="d-inline-flex gap-1 align-items-center">
                        @csrf @method('PATCH')
                        <input type="date" name="nueva_fecha" min="{{ now()->addDay()->toDateString() }}" class="form-control form-control-sm" style="width:130px" title="Nueva fecha si desea reprogramar (opcional)">
                        <button class="btn btn-sm btn-outline-danger py-0">No asistió</button>
                      </form>
                    @endif
                  </div>
                </td>
              </tr>
            @empty
              <tr><td colspan="7" class="text-center text-muted py-2">Sin vacunas registradas.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    {{-- ============ PANEL DE DOCUMENTOS DEL PACIENTE ============ --}}
    <div class="card mt-3">
      <div class="card-header">📂 Documentos y archivos del paciente</div>
      <div class="card-body">
        @forelse($m->adjuntos->groupBy('categoria') as $categoria => $archivos)
          <h6 class="text-uppercase text-muted small fw-bold mt-0">
            {{ str_replace('_',' ',$categoria) }}
            <span class="badge bg-light text-dark">{{ $archivos->count() }}</span>
          </h6>
          <ul class="list-unstyled mb-3">
            @foreach($archivos as $a)
              <li class="mb-1 small">
                <a href="{{ asset($a->ruta_archivo) }}" target="_blank">📎 {{ $a->nombre_original }}</a>
                <span class="text-muted">
                  ({{ $a->peso_kb }} KB ·
                  {{ $a->consulta ? 'Consulta '.$a->consulta->fecha_hora->format('d/m/Y') : 'Sin consulta' }})
                </span>
                <form action="{{ route('admin.adjuntos.destroy', $a->id_adjunto) }}" method="POST" class="d-inline">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-link text-danger p-0" onclick="return confirm('¿Eliminar archivo?')">✕</button>
                </form>
              </li>
            @endforeach
          </ul>
        @empty
          <p class="text-muted mb-0 small">Este paciente aún no tiene archivos adjuntos.</p>
        @endforelse

        {{-- SUBIR NUEVO ARCHIVO (sin consulta asociada) --}}
        <form action="{{ route('admin.adjuntos.store') }}" method="POST" enctype="multipart/form-data" class="row g-2 mt-2 border-top pt-3 align-items-end">
          @csrf
          <input type="hidden" name="id_mascota" value="{{ $m->id_mascota }}">
          <div class="col-md-4">
            <label class="form-label small fw-bold mb-1">Categoría</label>
            <select name="categoria" class="form-select form-select-sm" required>
              <option value="radiografia">Radiografía</option>
              <option value="laboratorio">Laboratorio</option>
              <option value="foto_clinica">Foto clínica</option>
              <option value="documento">Documento</option>
              <option value="otro">Otro</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label small fw-bold mb-1">Archivo (máx. 5 MB)</label>
            <input type="file" name="archivo" class="form-control form-control-sm" required accept=".jpg,.jpeg,.png,.pdf" data-max-size="5242880" data-allowed-extensions="jpg,jpeg,png,pdf">
          </div>
          <div class="col-md-2">
            <button class="btn btn-primary btn-sm w-100">📤 Subir</button>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>

@endsection