@php
  $isEdit = $testimonial !== null;
  $val = fn ($field, $default = '') => old($field, $isEdit ? $testimonial->{$field} : $default);
  $currentRating = (int) old('rating', $isEdit ? $testimonial->rating : 5);
@endphp

<div class="form-sections">

  {{-- ================= CLIENT ================= --}}
  <div class="form-section">
    <h3 class="form-section__title">Client</h3>

    <div class="form-grid">
      <div class="field">
        <label for="client_name">Client Name <span class="req">*</span></label>
        <input type="text" id="client_name" name="client_name"
               value="{{ $val('client_name') }}"
               placeholder="e.g. Ahmed Al-Rashid"
               required maxlength="120"
               class="{{ $errors->has('client_name') ? 'is-invalid' : '' }}">
        @error('client_name') <span class="field-error">{{ $message }}</span> @enderror
      </div>

      <div class="field">
        <label for="company">Company</label>
        <input type="text" id="company" name="company"
               value="{{ $val('company') }}"
               placeholder="e.g. Gulf Foods Trading LLC"
               maxlength="120">
      </div>

      <div class="field">
        <label for="position">Position / Title</label>
        <input type="text" id="position" name="position"
               value="{{ $val('position') }}"
               placeholder="e.g. Procurement Director"
               maxlength="120">
      </div>

      <div class="field">
        <label for="country_id">Country</label>
        <select id="country_id" name="country_id">
          <option value="">Select country…</option>
          @foreach ($countries as $country)
            <option value="{{ $country->id }}" {{ (int) $val('country_id') === $country->id ? 'selected' : '' }}>
              {{ $country->flag_emoji }} {{ $country->name }}
            </option>
          @endforeach
        </select>
      </div>
    </div>
  </div>

  {{-- ================= TESTIMONIAL ================= --}}
  <div class="form-section">
    <h3 class="form-section__title">Testimonial</h3>

    <div class="field" style="margin-bottom: 20px;">
      <label>Rating <span class="req">*</span></label>
      <div class="star-picker" id="starPicker" data-value="{{ $currentRating }}">
        @for ($i = 1; $i <= 5; $i++)
          <button type="button"
                  class="star-picker__star {{ $i <= $currentRating ? 'is-on' : '' }}"
                  data-value="{{ $i }}"
                  aria-label="{{ $i }} star{{ $i > 1 ? 's' : '' }}">
            <svg viewBox="0 0 24 24"><path d="M12 2l3 7h7l-5.5 4.5L18 21l-6-4-6 4 1.5-7.5L2 9h7z"/></svg>
          </button>
        @endfor
      </div>
      <input type="hidden" id="rating" name="rating" value="{{ $currentRating }}">
      <span class="field-hint" id="ratingLabel">
        {{ $currentRating }} out of 5 stars
      </span>
    </div>

    <div class="field">
      <label for="quote">Quote <span class="req">*</span></label>
      <textarea id="quote" name="quote" rows="6"
                placeholder="The exact words of the client — keep it authentic"
                required maxlength="2000"
                class="setting-input setting-input--textarea {{ $errors->has('quote') ? 'is-invalid' : '' }}">{{ $val('quote') }}</textarea>
      <span class="field-hint">Recommended: 20–60 words for best display</span>
      @error('quote') <span class="field-error">{{ $message }}</span> @enderror
    </div>
  </div>

  {{-- ================= AVATAR ================= --}}
  <div class="form-section">
    <h3 class="form-section__title">Avatar</h3>
    <p class="form-section__hint">
      Optional. If no avatar is uploaded, the client's first initial is shown instead.
    </p>

    @if ($isEdit && $testimonial->avatar)
      <div class="existing-avatar">
        <div class="existing-avatar__thumb" style="background-image:url('{{ asset($testimonial->avatar) }}')"></div>
        <div class="existing-avatar__meta">
          <strong>Current avatar</strong>
          <span>{{ $testimonial->avatar }}</span>
          <label class="existing-avatar__remove">
            <input type="checkbox" name="remove_avatar" value="1">
            <span>Remove this avatar</span>
          </label>
        </div>
      </div>
    @endif

    <div class="field">
      <label for="avatar_file">{{ $isEdit && $testimonial->avatar ? 'Replace avatar' : 'Upload avatar' }}</label>
      <input type="file" id="avatar_file" name="avatar_file" accept="image/jpeg,image/png,image/webp"
             class="image-file">
      <span class="field-hint">JPG, PNG, or WebP · max 2 MB · square recommended</span>
      @error('avatar_file') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    <div class="avatar-preview" id="avatarPreview" hidden>
      <span class="avatar-preview__label">New avatar preview</span>
      <div class="avatar-preview__img" id="avatarPreviewImg"></div>
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
          <span>Show this testimonial on the website</span>
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
          <span>Highlight this testimonial in featured sections</span>
        </div>
      </label>
    </div>

    <div class="field" style="margin-top: 18px;">
      <label for="sort_order">Sort Order</label>
      <input type="number" id="sort_order" name="sort_order"
             value="{{ $val('sort_order', 0) }}"
             min="0" max="9999">
      <span class="field-hint">Lower numbers appear first</span>
    </div>
  </div>

  {{-- ================= ACTIONS ================= --}}
  <div class="form-actions">
    <a href="{{ route('admin.testimonials.index') }}" class="btn-toolbar btn-toolbar--ghost">Cancel</a>
    <button type="submit" class="btn-navy-md">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M20 6L9 17l-5-5"/></svg>
      {{ $isEdit ? 'Save Changes' : 'Create Testimonial' }}
    </button>
  </div>

</div>

@push('scripts')
<script>
(function () {
  /* ============================================================
     Star picker
     ============================================================ */
  const picker = document.getElementById('starPicker');
  const hidden = document.getElementById('rating');
  const label  = document.getElementById('ratingLabel');

  const ratingLabels = {
    1: '1 out of 5 stars — Poor',
    2: '2 out of 5 stars — Fair',
    3: '3 out of 5 stars — Good',
    4: '4 out of 5 stars — Very Good',
    5: '5 out of 5 stars — Excellent',
  };

  function setRating(value) {
    hidden.value = value;
    picker.dataset.value = value;

    picker.querySelectorAll('.star-picker__star').forEach(star => {
      star.classList.toggle('is-on', parseInt(star.dataset.value, 10) <= value);
    });

    if (label) label.textContent = ratingLabels[value] || `${value} out of 5 stars`;
  }

  if (picker) {
    picker.querySelectorAll('.star-picker__star').forEach(star => {
      star.addEventListener('click', () => {
        setRating(parseInt(star.dataset.value, 10));
      });

      // Hover preview
      star.addEventListener('mouseenter', () => {
        const hoverVal = parseInt(star.dataset.value, 10);
        picker.querySelectorAll('.star-picker__star').forEach(s => {
          s.classList.toggle('is-hover', parseInt(s.dataset.value, 10) <= hoverVal);
        });
      });
    });

    picker.addEventListener('mouseleave', () => {
      picker.querySelectorAll('.star-picker__star').forEach(s => s.classList.remove('is-hover'));
    });
  }

  /* ============================================================
     Avatar preview
     ============================================================ */
  const avatarInput = document.getElementById('avatar_file');
  const preview     = document.getElementById('avatarPreview');
  const previewImg  = document.getElementById('avatarPreviewImg');

  if (avatarInput && preview && previewImg) {
    avatarInput.addEventListener('change', (e) => {
      const file = e.target.files[0];
      if (! file) { preview.hidden = true; return; }

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