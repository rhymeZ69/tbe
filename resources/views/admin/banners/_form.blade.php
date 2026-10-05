@php
  $isEdit = $banner !== null;
  $val = fn ($field, $default = '') => old($field, $isEdit ? $banner->{$field} : $default);

  // Format datetime fields for datetime-local input
  $startsAtValue = old('starts_at', $isEdit ? ($banner->starts_at_display ?? '') : '');
  $endsAtValue   = old('ends_at',   $isEdit ? ($banner->ends_at_display   ?? '') : '');
@endphp

<div class="form-sections">

  {{-- ================= CONTENT ================= --}}
  <div class="form-section">
    <h3 class="form-section__title">Content</h3>

    <div class="form-grid">
      <div class="field field--full">
        <label for="title">Title</label>
        <input type="text" id="title" name="title"
               value="{{ $val('title') }}"
               placeholder="e.g. Premium Halal Meat, Delivered Fresh"
               maxlength="160"
               class="{{ $errors->has('title') ? 'is-invalid' : '' }}">
        <span class="field-hint">Optional — leave blank for an image-only banner</span>
        @error('title') <span class="field-error">{{ $message }}</span> @enderror
      </div>

      <div class="field field--full">
        <label for="subtitle">Subtitle</label>
        <textarea id="subtitle" name="subtitle" rows="2"
                  placeholder="A short supporting line beneath the title"
                  maxlength="300"
                  class="setting-input setting-input--textarea">{{ $val('subtitle') }}</textarea>
      </div>

      <div class="field">
        <label for="cta_text">CTA Button Text</label>
        <input type="text" id="cta_text" name="cta_text"
               value="{{ $val('cta_text') }}"
               placeholder="e.g. Request a Quote"
               maxlength="60">
        <span class="field-hint">Shown on the button. Leave blank to hide the button.</span>
      </div>

      <div class="field">
        <label for="cta_url">CTA Link URL</label>
        <input type="text" id="cta_url" name="cta_url"
               value="{{ $val('cta_url') }}"
               placeholder="#contact or https://..."
               maxlength="255"
               style="font-family:'SFMono-Regular',Consolas,monospace;font-size:.86rem;">
        <span class="field-hint">Accepts internal anchors (#contact) or full URLs</span>
      </div>
    </div>
  </div>

  {{-- ================= IMAGES ================= --}}
  <div class="form-section">
    <h3 class="form-section__title">Images</h3>
    <p class="form-section__hint">
      Upload a landscape image for desktop and an optional portrait/square version for mobile.
    </p>

    {{-- Desktop image --}}
    <div class="image-block">
      <h4 class="image-block__title">Desktop Image</h4>

      @if ($isEdit && $banner->image)
        <div class="existing-image">
          <div class="existing-image__thumb" style="background-image:url('{{ asset($banner->image) }}')"></div>
          <div class="existing-image__meta">
            <strong>Current desktop image</strong>
            <span>{{ $banner->image }}</span>
            <label class="existing-image__remove">
              <input type="checkbox" name="remove_image" value="1">
              <span>Remove this image</span>
            </label>
          </div>
        </div>
      @endif

      <div class="field">
        <label for="image_file">{{ $isEdit && $banner->image ? 'Replace desktop image' : 'Upload desktop image' }}</label>
        <input type="file" id="image_file" name="image_file"
               accept="image/jpeg,image/png,image/webp"
               class="image-file"
               data-preview-target="bannerPreviewDesktop">
        <span class="field-hint">
          JPG, PNG, or WebP · max 6 MB ·
          <strong>Recommended: 1920×800</strong> ·
          Accepted: 1600–2560 wide × 700–1200 tall
        </span>
        @error('image_file') <span class="field-error">{{ $message }}</span> @enderror
      </div>

      <div class="image-preview" id="bannerPreviewDesktop" hidden>
        <span class="image-preview__label">New desktop image</span>
        <div class="image-preview__img image-preview__img--wide"></div>
      </div>
    </div>

    {{-- Mobile image --}}
    <div class="image-block" style="margin-top: 24px;">
      <h4 class="image-block__title">Mobile Image</h4>
      <p class="image-block__hint">Optional. If left blank, the desktop image is used on mobile.</p>

      @if ($isEdit && $banner->image_mobile)
        <div class="existing-image">
          <div class="existing-image__thumb existing-image__thumb--square" style="background-image:url('{{ asset($banner->image_mobile) }}')"></div>
          <div class="existing-image__meta">
            <strong>Current mobile image</strong>
            <span>{{ $banner->image_mobile }}</span>
            <label class="existing-image__remove">
              <input type="checkbox" name="remove_image_mobile" value="1">
              <span>Remove this image</span>
            </label>
          </div>
        </div>
      @endif

      <div class="field">
        <label for="image_mobile_file">{{ $isEdit && $banner->image_mobile ? 'Replace mobile image' : 'Upload mobile image' }}</label>
        <input type="file" id="image_mobile_file" name="image_mobile_file"
               accept="image/jpeg,image/png,image/webp"
               class="image-file"
               data-preview-target="bannerPreviewMobile">
        <span class="field-hint">
          JPG, PNG, or WebP · max 4 MB ·
          <strong>Recommended: 800×1000</strong> ·
          Accepted: 600–1200 wide × 800–1400 tall
        </span>
        @error('image_mobile_file') <span class="field-error">{{ $message }}</span> @enderror
      </div>

      <div class="image-preview" id="bannerPreviewMobile" hidden>
        <span class="image-preview__label">New mobile image</span>
        <div class="image-preview__img image-preview__img--tall"></div>
      </div>
    </div>
  </div>

  {{-- ================= PLACEMENT ================= --}}
  <div class="form-section">
    <h3 class="form-section__title">Placement</h3>

    <div class="form-grid">
      <div class="field">
        <label for="position">Position <span class="req">*</span></label>
        <select id="position" name="position" required>
          <option value="hero"   {{ $val('position') === 'hero'   ? 'selected' : '' }}>Hero — top of homepage</option>
          <option value="top"    {{ $val('position') === 'top'    ? 'selected' : '' }}>Top — below header</option>
          <option value="middle" {{ $val('position') === 'middle' ? 'selected' : '' }}>Middle — mid-page promo</option>
          <option value="footer" {{ $val('position') === 'footer' ? 'selected' : '' }}>Footer — bottom strip</option>
        </select>
        @error('position') <span class="field-error">{{ $message }}</span> @enderror
      </div>

      <div class="field">
        <label for="sort_order">Sort Order</label>
        <input type="number" id="sort_order" name="sort_order"
               value="{{ $val('sort_order', 0) }}"
               min="0" max="9999">
        <span class="field-hint">Lower numbers appear first within the same position</span>
      </div>
    </div>
  </div>

  {{-- ================= SCHEDULE ================= --}}
  <div class="form-section">
    <h3 class="form-section__title">Schedule</h3>
    <p class="form-section__hint">
      Optional. Leave both fields blank to show the banner whenever it's active.
    </p>

    <div class="form-grid">
      <div class="field">
        <label for="starts_at">Starts At</label>
        <input type="datetime-local" id="starts_at" name="starts_at"
               value="{{ $startsAtValue }}"
               class="{{ $errors->has('starts_at') ? 'is-invalid' : '' }}">
        <span class="field-hint">Leave blank to start immediately</span>
        @error('starts_at') <span class="field-error">{{ $message }}</span> @enderror
      </div>

      <div class="field">
        <label for="ends_at">Ends At</label>
        <input type="datetime-local" id="ends_at" name="ends_at"
               value="{{ $endsAtValue }}"
               class="{{ $errors->has('ends_at') ? 'is-invalid' : '' }}">
        <span class="field-hint">Leave blank for no end date</span>
        @error('ends_at') <span class="field-error">{{ $message }}</span> @enderror
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
          <span>Show this banner on the website (subject to the schedule above)</span>
        </div>
      </label>
    </div>
  </div>

  {{-- ================= ACTIONS ================= --}}
  <div class="form-actions">
    <a href="{{ route('admin.banners.index') }}" class="btn-toolbar btn-toolbar--ghost">Cancel</a>
    <button type="submit" class="btn-navy-md">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M20 6L9 17l-5-5"/></svg>
      {{ $isEdit ? 'Save Changes' : 'Create Banner' }}
    </button>
  </div>

</div>

@push('scripts')
<script>
(function () {
  /* ============================================================
     Dimension rules — mirrors server-side validation
     ============================================================ */
  const RULES = {
    image_file: {
      label: 'Desktop image',
      minW: 1600, minH: 700,
      maxW: 2560, maxH: 1200,
      recW: 1920, recH: 800,
    },
    image_mobile_file: {
      label: 'Mobile image',
      minW: 600, minH: 800,
      maxW: 1200, maxH: 1400,
      recW: 800, recH: 1000,
    },
  };

  /* ============================================================
     Preview + validation for both image inputs
     ============================================================ */
  document.querySelectorAll('input[type="file"][data-preview-target]').forEach(input => {
    const targetId = input.dataset.previewTarget;
    const wrapper  = document.getElementById(targetId);
    const inner    = wrapper?.querySelector('.image-preview__img');
    const field    = input.closest('.field');

    // Find or create an inline error slot under this field
    let errorEl = field?.querySelector('.field-error[data-js-error]');
    if (! errorEl && field) {
      errorEl = document.createElement('span');
      errorEl.className = 'field-error';
      errorEl.setAttribute('data-js-error', '1');
      field.appendChild(errorEl);
    }

    input.addEventListener('change', (e) => {
      const file = e.target.files[0];

      // Clear previous state
      if (errorEl) { errorEl.textContent = ''; errorEl.style.display = 'none'; }
      input.classList.remove('is-invalid');
      if (wrapper) wrapper.hidden = true;

      if (! file) return;

      const rule = RULES[input.name];
      if (! rule) return;

      // --- Basic file checks ---
      const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
      if (! allowedTypes.includes(file.type)) {
        showError(input, errorEl, `${rule.label} must be a JPG, PNG, or WebP file.`);
        input.value = '';
        return;
      }

      const maxBytes = input.name === 'image_file' ? 6 * 1024 * 1024 : 4 * 1024 * 1024;
      if (file.size > maxBytes) {
        const mb = (maxBytes / 1024 / 1024).toFixed(0);
        showError(input, errorEl, `${rule.label} must not exceed ${mb} MB.`);
        input.value = '';
        return;
      }

      // --- Read the image dimensions ---
      const reader = new FileReader();
      reader.onload = (ev) => {
        const img = new Image();
        img.onload = () => {
          const { width, height } = img;

          // Validate the range
          const tooSmall = width < rule.minW || height < rule.minH;
          const tooBig   = width > rule.maxW || height > rule.maxH;

          if (tooSmall || tooBig) {
            let msg = `${rule.label} is ${width}×${height}px. `;
            msg += tooBig
              ? `It's too large. Max: ${rule.maxW}×${rule.maxH}px.`
              : `It's too small. Min: ${rule.minW}×${rule.minH}px.`;
            msg += ` Recommended: ${rule.recW}×${rule.recH}px.`;

            showError(input, errorEl, msg);
            input.value = '';
            if (wrapper) wrapper.hidden = true;
            return;
          }

          // Valid — show the preview
          if (inner && wrapper) {
            inner.style.backgroundImage = `url('${ev.target.result}')`;
            wrapper.hidden = false;
          }
        };

        img.onerror = () => {
          showError(input, errorEl, `${rule.label} could not be read. Please try a different file.`);
          input.value = '';
        };

        img.src = ev.target.result;
      };

      reader.readAsDataURL(file);
    });
  });

  /* ============================================================
     Helpers
     ============================================================ */
  function showError(input, errorEl, message) {
    input.classList.add('is-invalid');
    if (errorEl) {
      errorEl.textContent = message;
      errorEl.style.display = 'block';
    }
  }

  /* ============================================================
     Block form submission if any client-side error is active
     ============================================================ */
  const form = document.querySelector('.banner-form');
  if (form) {
    form.addEventListener('submit', (e) => {
      const invalid = form.querySelectorAll('.image-file.is-invalid');
      if (invalid.length > 0) {
        e.preventDefault();
        invalid[0].focus();
        window.scrollTo({
          top: invalid[0].getBoundingClientRect().top + window.scrollY - 120,
          behavior: 'smooth',
        });
      }
    });
  }
})();
</script>
@endpush