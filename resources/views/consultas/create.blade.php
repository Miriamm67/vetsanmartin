{{-- SELECT DE VETERINARIO (obligatorio) --}}
<div class="mb-3">
    <label class="form-label fw-bold small">Veterinario que atiende *</label>
    <select name="id_veterinario" class="form-select form-control-vet" required>
        <option value="">-- Seleccione --</option>
        @foreach ($veterinarios as $v)
            <option value="{{ $v->id_veterinario }}" @selected(old('id_veterinario') == $v->id_veterinario)>
                {{ $v->nombre_completo }} (Matrícula {{ $v->nro_colegiado }})
            </option>
        @endforeach
    </select>
    @error('id_veterinario')<div class="text-danger small">{{ $message }}</div>@enderror
</div>

{{-- BOTÓN DE ADJUNTAR ARCHIVOS --}}
<div class="mb-3">
    <label class="form-label fw-bold small"><i class="bi bi-paperclip"></i> Adjuntar archivos (radiografías, fotos, documentos)</label>
    <input type="file" name="archivos[]" id="input-archivos" class="form-control form-control-vet" multiple accept=".jpg,.jpeg,.png,.pdf" data-max-files="5" data-max-size="5242880" data-allowed-extensions="jpg,jpeg,png,pdf">
    <div class="form-text">Máximo 5 archivos de 5 MB (JPG, PNG o PDF).</div>
    <div id="categorias-adjuntos" class="mt-2"></div>
    @error('archivos')<div class="text-danger small">{{ $message }}</div>@enderror
    @error('archivos.*')<div class="text-danger small">{{ $message }}</div>@enderror
</div>