@php
  $isEdit = $certification !== null;
  $val = fn ($field, $default = '') => old($field, $isEdit ? $certification->{$field} : $default);

  // Date values need formatting for the HTML date input
  $issuedOn  = $isEdit && $certification->issued_on
                ? $certification->issued_on->format('Y-m-d')
                : '';
  $expiresOn = $isEdit && $certification->expires_on
                ? $certification->expires_on->format('Y-m-d')
                : '';
@endphp

<div class="form-sections">

  {{-- ================= BASIC INFO ================= --}}
  <div class="form-section">
    <h3 class="form-section__title">Basic Information</h3>

    <div class="form-grid">
      <div class="field field--full">
        <label for="name">Certification Name <span class="req">*</span></label>
        <input type="text" id="name" name="name"
               value="{{ $val('name') }}"
               placeholder="e.g. Halal Certified, HACCP, ISO 22000"
               required maxlength="160"
               class="{{ $errors->has('name') ? 'is-invalid' : '' }}">
        @error('name') <span class="field-error">{{ $message }}</span> @enderror
      </div>

      <div class="field field--full">
        <label for="description">Description</label>
        <textarea id="description" name="description" rows="3"
                  placeholder="A short description — what the certification covers or guarantees"
                  maxlength="2000"
                  class="setting-input setting-input--textarea">{{ $val('description') }}</textarea>
      </div>

      <div class="field">
        <label for="issued_by">Issued By</label>
        <input type="text" id="issued_by" name="issued_by"
               value="{{ $val('issued_by') }}"
               placeholder="e.g. Pakistan Halal Authority"
               maxlength="160">
        <span class="field-hint">The body or organization that issued the certificate</span>
      </div>

      <div class="field">
        <label for="certificate_number">Certificate Number</label>
        <input type="text" id="certificate_number" name="certificate_number"
               value="{{ $val('certificate_number') }}"
               placeholder="e.g. PHA-2025-001234"
               maxlength="120"
               style="font-family:'SFMono-Regular',Consolas,monospace;font-size:.85rem;">
      </div>

      <div class="field">
        <label for="sort_order">Sort Order</label>
        <input type="number" id="sort_order" name="sort_order"
               value="{{ $val('sort_order', 0) }}"
               min="0" max="9999">
        <span class="field-hint">Lower numbers appear first</span>
      </div>
    </div>
  </div>

  {{-- ================= VALIDITY ================= --}}
  <div class="form-section">
    <h3 class="form-section__title">Validity</h3>

    <div class="form-grid">
      <div class="field">
        <label for="issued_on">Issued On</label>
        <input type="date" id="issued_on" name="issued_on"
               value="{{ old('issued_on', $issuedOn) }}"
               class="{{ $errors->has('issued_on') ? 'is-invalid' : '' }}">
        @error('issued_on') <span class="field-error">{{ $message }}</span> @enderror
      </div>

      <div class="field">
        <label for="expires_on">Expires On</label>
        <input type="date" id="expires_on" name="expires_on"
               value="{{ old('expires_on', $expiresOn) }}"
               class="{{ $errors->has('expires_on') ? 'is-invalid' : '' }}">
        <span class="field-hint">Leave empty if the certificate never expires</span>
        @error('expires_on') <span class="field-error">{{ $message }}</span> @enderror
      </div>
    </div>
  </div>

  {{-- ================= LOGO ================= --}}
  <div class="form-section">
    <h3 class="form-section__title">Logo</h3>
    <p class="form-section__hint">
      Optional. Shown next to the certification name on the public site.
    </p>

    @if ($isEdit && $certification->logo)
      <div class="existing-logo">
        <div class="existing-logo__thumb" style="background-image:url('{{ asset($certification->logo) }}')"></div>
        <div class="existing-logo__meta">
          <strong>Current logo</strong>
          <span>{{ $certification->logo }}</span>
          <label class="existing-logo__remove">
            <input type="checkbox" name="remove_logo" value="1">
            <span>Remove this logo</span>
          </label>
        </div>
      </div>
    @endif

    <div class="field">
      <label for="logo_file">{{ $isEdit && $certification->logo ? 'Replace logo' : 'Upload logo' }}</label>
      <input type="file" id="logo_file" name="logo_file" accept="image/jpeg,image/png,image/webp,image/svg+xml"
             class="image-file">
      <span class="field-hint">JPG, PNG, WebP, or SVG · max 2 MB · square or landscape recommended</span>
      @error('logo_file') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    <div class="logo-preview" id="logoPreview" hidden>
      <span class="logo-preview__label">New logo preview</span>
      <div class="logo-preview__img" id="logoPreviewImg"></div>
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
          <span>Show this certification on the website</span>
        </div>
      </label>
    </div>
  </div>

  {{-- ================= ACTIONS ================= --}}
  <div class="form-actions">
    <a href="{{ route('admin.certifications.index') }}" class="btn-toolbar btn-toolbar--ghost">Cancel</a>
    <button type="submit" class="btn-navy-md">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M20 6L9 17l-5-5"/></svg>
      {{ $isEdit ? 'Save Changes' : 'Create Certification' }}
    </button>
  </div>

</div>

@push('scripts')
<script>
(function () {
  /* ---------- Logo preview ---------- */
  const logoInput  = document.getElementById('logo_file');
  const preview    = document.getElementById('logoPreview');
  const previewImg = document.getElementById('logoPreviewImg');

  if (logoInput && preview && previewImg) {
    logoInput.addEventListener('change', (e) => {
      const file = e.target.files[0];
      if (! file) {
        preview.hidden = true;
        return;
      }
      const reader = new FileReader();
      reader.onload = (ev) => {
        previewImg.style.backgroundImage = `url('${ev.target.result}')`;
        preview.hidden = false;
      };
      reader.readAsDataURL(file);
    });
  }
})();
</script>
@endpush