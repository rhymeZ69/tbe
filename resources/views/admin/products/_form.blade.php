@php
  $isEdit = $product !== null;
  $val = fn ($field, $default = '') => old($field, $isEdit ? $product->{$field} : $default);

  $varieties = old('varieties', $isEdit ? $product->varieties->toArray() : []);
  $specs     = old('specs',     $isEdit ? $product->specs->toArray()     : []);
@endphp

<div class="form-sections">

  {{-- ================= IDENTITY ================= --}}
  <div class="form-section">
    <h3 class="form-section__title">Identity</h3>

    <div class="form-grid">
      <div class="field">
        <label for="category_id">Category <span class="req">*</span></label>
        <select id="category_id" name="category_id" required class="{{ $errors->has('category_id') ? 'is-invalid' : '' }}">
          <option value="">Select category…</option>
          @foreach ($categories as $cat)
            <option value="{{ $cat->id }}" {{ (int) $val('category_id') === $cat->id ? 'selected' : '' }}>
              {{ $cat->name }}
            </option>
          @endforeach
        </select>
        @error('category_id') <span class="field-error">{{ $message }}</span> @enderror
      </div>

      <div class="field">
        <label for="name">Product Name <span class="req">*</span></label>
        <input type="text" id="name" name="name" value="{{ $val('name') }}"
               placeholder="e.g. Premium Beef" required maxlength="120"
               class="{{ $errors->has('name') ? 'is-invalid' : '' }}">
        @error('name') <span class="field-error">{{ $message }}</span> @enderror
      </div>

      <div class="field">
        <label for="slug">Slug</label>
        <input type="text" id="slug" name="slug" value="{{ $val('slug') }}"
               placeholder="auto-generated-from-name" maxlength="120"
               style="font-family:'SFMono-Regular',Consolas,monospace;font-size:.85rem;"
               class="{{ $errors->has('slug') ? 'is-invalid' : '' }}">
        <span class="field-hint">Leave empty to auto-generate</span>
        @error('slug') <span class="field-error">{{ $message }}</span> @enderror
      </div>

      <div class="field">
        <label for="tagline">Tagline</label>
        <input type="text" id="tagline" name="tagline" value="{{ $val('tagline') }}"
               placeholder="Fresh &amp; Chilled" maxlength="60">
      </div>

      <div class="field field--full">
        <label for="short_description">Short Description</label>
        <textarea id="short_description" name="short_description" rows="2"
                  placeholder="One or two sentences for the product card"
                  class="setting-input setting-input--textarea">{{ $val('short_description') }}</textarea>
      </div>

      <div class="field field--full">
        <label for="description">Full Description</label>
        <textarea id="description" name="description" rows="5"
                  placeholder="Detailed description (optional)"
                  class="setting-input setting-input--textarea">{{ $val('description') }}</textarea>
      </div>

      <div class="field">
        <label for="sort_order">Sort Order</label>
        <input type="number" id="sort_order" name="sort_order" value="{{ $val('sort_order', 0) }}"
               min="0" max="9999">
        <span class="field-hint">Lower numbers appear first</span>
      </div>
    </div>
  </div>

  {{-- ================= VARIETIES ================= --}}
  <div class="form-section">
    <h3 class="form-section__title">
      Varieties / Cuts
      <button type="button" class="repeat-add" data-add="varieties">+ Add</button>
    </h3>
    <p class="form-section__hint">E.g. <em>Whole Carcass, Topside, Striploin</em> for meat, or <em>Super Basmati, 1121</em> for rice.</p>

    <div class="repeat-list" data-list="varieties">
      @foreach ($varieties as $i => $v)
        <div class="repeat-row" data-index="{{ $i }}">
          <input type="text" name="varieties[{{ $i }}][name]" value="{{ $v['name'] ?? '' }}"
                 placeholder="Variety name (e.g. Topside)" class="repeat-row__main">
          <input type="text" name="varieties[{{ $i }}][code]" value="{{ $v['code'] ?? '' }}"
                 placeholder="Code (optional)" class="repeat-row__side">
          <button type="button" class="repeat-remove" aria-label="Remove">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
          </button>
        </div>
      @endforeach
    </div>
  </div>

  {{-- ================= SPECS ================= --}}
  <div class="form-section">
    <h3 class="form-section__title">
      Specifications
      <button type="button" class="repeat-add" data-add="specs">+ Add</button>
    </h3>
    <p class="form-section__hint">Label + value pairs shown on the product card, e.g. <em>Chilled Temperature</em> / <em>0°C – 4°C</em>.</p>

    <div class="repeat-list" data-list="specs">
      @foreach ($specs as $i => $s)
        <div class="repeat-row" data-index="{{ $i }}">
          <input type="text" name="specs[{{ $i }}][value]" value="{{ $s['value'] ?? '' }}"
                 placeholder="Value (e.g. 0°C – 4°C)" class="repeat-row__side">
          <input type="text" name="specs[{{ $i }}][label]" value="{{ $s['label'] ?? '' }}"
                 placeholder="Label (e.g. Chilled Temperature)" class="repeat-row__main">
          <button type="button" class="repeat-remove" aria-label="Remove">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
          </button>
        </div>
      @endforeach
    </div>
  </div>

  {{-- ================= IMAGES ================= --}}
  <div class="form-section">
    <h3 class="form-section__title">Images</h3>
    <p class="form-section__hint">Upload one or more photos. The primary image appears on the product card.</p>

    @if ($isEdit && $product->images->count())
      <div class="existing-images">
        @foreach ($product->images as $img)
          <div class="existing-image">
            <div class="existing-image__thumb" style="background-image:url('{{ $img->url }}')"></div>
            <label class="existing-image__primary">
              <input type="radio" name="primary_image" value="existing-{{ $img->id }}"
                     {{ $img->is_primary ? 'checked' : '' }}>
              <span>Primary</span>
            </label>
            <label class="existing-image__remove">
              <input type="checkbox" name="remove_images[]" value="{{ $img->id }}">
              <span>Remove</span>
            </label>
          </div>
        @endforeach
      </div>
    @endif

    <div class="image-uploads" id="imageUploads">
      <div class="image-upload-row">
        <input type="file" name="images[]" accept="image/*" class="image-file">
        <input type="text" name="image_alts[]" placeholder="Alt text (optional)" class="image-alt">
        <label class="image-primary-check">
          <input type="radio" name="primary_image" value="new-0">
          <span>Primary</span>
        </label>
      </div>
    </div>

    <button type="button" class="repeat-add" id="addImageBtn">+ Add another image</button>
  </div>

  {{-- ================= SEO ================= --}}
  <div class="form-section">
    <h3 class="form-section__title">SEO (Optional)</h3>

    <div class="form-grid">
      <div class="field field--full">
        <label for="meta_title">Meta Title</label>
        <input type="text" id="meta_title" name="meta_title" value="{{ $val('meta_title') }}" maxlength="255">
      </div>
      <div class="field field--full">
        <label for="meta_description">Meta Description</label>
        <textarea id="meta_description" name="meta_description" rows="3"
                  class="setting-input setting-input--textarea">{{ $val('meta_description') }}</textarea>
      </div>
    </div>
  </div>

  {{-- ================= VISIBILITY ================= --}}
  <div class="form-section">
    <h3 class="form-section__title">Visibility</h3>

    <div class="checkbox-stack">
      <label class="checkbox-row">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" {{ $val('is_active', 1) ? 'checked' : '' }}>
        <span class="checkbox-row__box">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5"><path d="M20 6L9 17l-5-5"/></svg>
        </span>
        <div class="checkbox-row__text">
          <strong>Active</strong>
          <span>Show this product on the website</span>
        </div>
      </label>

      <label class="checkbox-row">
        <input type="hidden" name="is_featured" value="0">
        <input type="checkbox" name="is_featured" value="1" {{ $val('is_featured') ? 'checked' : '' }}>
        <span class="checkbox-row__box">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5"><path d="M20 6L9 17l-5-5"/></svg>
        </span>
        <div class="checkbox-row__text">
          <strong>Featured</strong>
          <span>Show this product on the homepage</span>
        </div>
      </label>
    </div>
  </div>

  {{-- ================= ACTIONS ================= --}}
  <div class="form-actions">
    <a href="{{ route('admin.products.index') }}" class="btn-toolbar btn-toolbar--ghost">Cancel</a>
    <button type="submit" class="btn-navy-md">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M20 6L9 17l-5-5"/></svg>
      {{ $isEdit ? 'Save Changes' : 'Create Product' }}
    </button>
  </div>

</div>

@push('scripts')
<script>
(function () {
  /* ============================================================
     Repeatable rows (varieties + specs)
     ============================================================ */
  function nextIndex(list) {
    const rows = list.querySelectorAll('.repeat-row');
    if (rows.length === 0) return 0;
    const max = Math.max(...Array.from(rows).map(r => parseInt(r.dataset.index || '0', 10)));
    return max + 1;
  }

  function bindRemove(btn) {
    btn.addEventListener('click', () => {
      const row = btn.closest('.repeat-row');
      if (row) row.remove();
    });
  }

  document.querySelectorAll('.repeat-remove').forEach(bindRemove);

  document.querySelectorAll('.repeat-add').forEach(btn => {
    btn.addEventListener('click', () => {
      const listName = btn.dataset.add;
      const list = document.querySelector(`.repeat-list[data-list="${listName}"]`);
      if (!list) return;

      const i = nextIndex(list);

      let html = '';
      if (listName === 'varieties') {
        html = `
          <div class="repeat-row" data-index="${i}">
            <input type="text" name="varieties[${i}][name]" placeholder="Variety name (e.g. Topside)" class="repeat-row__main">
            <input type="text" name="varieties[${i}][code]" placeholder="Code (optional)" class="repeat-row__side">
            <button type="button" class="repeat-remove" aria-label="Remove">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>
          </div>`;
      } else {
        html = `
          <div class="repeat-row" data-index="${i}">
            <input type="text" name="specs[${i}][value]" placeholder="Value (e.g. 0°C – 4°C)" class="repeat-row__side">
            <input type="text" name="specs[${i}][label]" placeholder="Label (e.g. Chilled Temperature)" class="repeat-row__main">
            <button type="button" class="repeat-remove" aria-label="Remove">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>
          </div>`;
      }

      list.insertAdjacentHTML('beforeend', html);
      bindRemove(list.lastElementChild.querySelector('.repeat-remove'));
      list.lastElementChild.querySelector('input').focus();
    });
  });

  /* ============================================================
     Dynamic image upload rows
     ============================================================ */
  const uploads   = document.getElementById('imageUploads');
  const addImgBtn = document.getElementById('addImageBtn');

  if (addImgBtn && uploads) {
    addImgBtn.addEventListener('click', () => {
      const index = uploads.querySelectorAll('.image-upload-row').length;

      const html = `
        <div class="image-upload-row">
          <input type="file" name="images[]" accept="image/*" class="image-file">
          <input type="text" name="image_alts[]" placeholder="Alt text (optional)" class="image-alt">
          <label class="image-primary-check">
            <input type="radio" name="primary_image" value="new-${index}">
            <span>Primary</span>
          </label>
          <button type="button" class="repeat-remove" aria-label="Remove">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
          </button>
        </div>`;

      uploads.insertAdjacentHTML('beforeend', html);

      const newRow = uploads.lastElementChild;
      newRow.querySelector('.repeat-remove').addEventListener('click', () => newRow.remove());
    });
  }
})();
</script>
@endpush