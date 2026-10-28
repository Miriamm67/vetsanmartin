@extends('layouts.app')
@section('contenido')
<h4>{{ $p->id_propietario ? 'Editar' : 'Nuevo' }} Propietario</h4>
<div class="card"><div class="card-body">
<form method="POST" action="{{ $p->id_propietario ? route('admin.propietarios.update',$p->id_propietario) : route('admin.propietarios.store') }}">
  @csrf @if($p->id_propietario) @method('PUT') @endif
  <div class="row g-2">
    <div class="col-md-6"><label class="form-label">Nombre y apellidos *</label>
      <input name="nombre_completo" class="form-control" value="{{ old('nombre_completo',$p->nombre_completo) }}" maxlength="120" required></div>
    <div class="col-md-3"><label class="form-label">N.° Cel. *</label>
      <input name="telefono" class="form-control" value="{{ old('telefono',$p->telefono) }}" maxlength="20" required></div>
    <div class="col-md-3"><label class="form-label">N. Reg.</label>
      <input name="nro_registro" class="form-control" value="{{ old('nro_registro',$p->nro_registro) }}"></div>
    <div class="col-md-4"><label class="form-label">Zona</label>
      <input name="zona" class="form-control" value="{{ old('zona',$p->zona) }}"></div>
    <div class="col-md-6"><label class="form-label">Calle/Av.</label>
      <input name="calle_av" class="form-control" value="{{ old('calle_av',$p->calle_av) }}"></div>
    <div class="col-md-2"><label class="form-label">N.°</label>
      <input name="numero" class="form-control" value="{{ old('numero',$p->numero) }}"></div>
    <div class="col-12"><label class="form-label">Dirección (referencia)</label>
      <input name="direccion" class="form-control" value="{{ old('direccion',$p->direccion) }}"></div>
  </div>
  <div class="mt-3">
    <button class="btn btn-primary">Guardar</button>
    <a href="{{ route('admin.propietarios.index') }}" class="btn btn-secondary">Volver</a>
  </div>
</form>
</div></div>
@endsection