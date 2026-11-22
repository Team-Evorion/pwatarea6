<div class="row g-3">
  <div class="col-12">
    <label class="form-label" for="name">Nombre del plato</label>
    <input class="form-control" id="name" name="name" maxlength="50" required value="{{ old('name', $dish->name ?? '') }}" placeholder="Nombre del plato">
  </div>
  <div class="col-12">
    <label class="form-label" for="description">Descripción</label>
    <textarea class="form-control" id="description" name="description" rows="4" maxlength="255" placeholder="Ingredientes y detalles">{{ old('description', $dish->description ?? '') }}</textarea>
  </div>
  <div class="col-12 col-md-6">
    <label class="form-label" for="price">Precio ($)</label>
    <input class="form-control" id="price" name="price" type="number" step="0.01" min="0.01" required value="{{ old('price', $dish->price ?? '') }}" placeholder="0.00">
  </div>
  <div class="col-12">
    <label class="form-label" for="image">Fotografía del plato <span class="text-danger" aria-label="obligatoria">*</span></label>
    <input class="form-control" id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp" aria-describedby="imageHelp" {{ (!$dish->exists || !$dish->image_path) ? 'required' : '' }}>
    <div id="imageHelp" class="form-text">JPG, PNG o WebP · Máximo 2 MB (ideal: menos de 500 KB) · Horizontal 1200 × 800 píxeles. La fotografía es obligatoria al crear un plato; al editar puedes conservar la actual.</div>
    @error('image') <p class="text-danger small mt-2 mb-0">{{ $message }}</p> @enderror
  </div>
  <div class="col-12">
    <div class="dish-form-preview" id="dishImagePreview">
      <img id="dishImagePreviewImg" src="{{ $dish->exists && $dish->image_url ? $dish->image_url : '' }}" alt="Vista previa de la fotografía del plato" class="{{ $dish->exists && $dish->image_url ? '' : 'd-none' }}">
      <div id="dishImagePreviewEmpty" class="dish-form-preview-empty {{ $dish->exists && $dish->image_url ? 'd-none' : '' }}">
        <i class="bi bi-image" aria-hidden="true"></i>
        <span>La fotografía aparecerá aquí</span>
      </div>
    </div>
  </div>
</div>
<div class="d-flex justify-content-end flex-wrap gap-2 mt-4">
  <a class="btn btn-soft" href="{{ route('dishes.index') }}">Cancelar</a>
  <button class="btn btn-brand" type="submit">{{ $dish->exists ? 'Guardar cambios' : 'Registrar plato' }}</button>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const upload = document.getElementById('image');
  const preview = document.getElementById('dishImagePreviewImg');
  const empty = document.getElementById('dishImagePreviewEmpty');
  if (!upload || !preview || !empty) return;
  let blobUrl = null;
  upload.addEventListener('change', () => {
    if (blobUrl) URL.revokeObjectURL(blobUrl);
    const file = upload.files?.[0];
    if (!file) return;
    if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {
      upload.value = '';
      alert('Selecciona una imagen JPG, PNG o WebP.');
      return;
    }
    if (file.size > 2 * 1024 * 1024) {
      upload.value = '';
      alert('La fotografía no debe superar los 2 MB.');
      return;
    }
    blobUrl = URL.createObjectURL(file);
    preview.src = blobUrl;
    preview.classList.remove('d-none');
    empty.classList.add('d-none');
  });
});
</script>
@endpush
