@php
  $isEdit = $country !== null;
  $val = fn ($field, $default = '') => old($field, $isEdit ? $country->{$field} : $default);
@endphp

<div class="form-sections">

  {{-- ================= BASIC INFORMATION ================= --}}
  <div class="form-section">
    <h3 class="form-section__title">Basic Information</h3>

    <div class="form-grid">
      <div class="field field--full">
        <label for="name">Country Name <span class="req">*</span></label>
        <input type="text" id="name" name="name"
               value="{{ $val('name') }}"
               placeholder="e.g. Saudi Arabia"
               required maxlength="120"
               class="{{ $errors->has('name') ? 'is-invalid' : '' }}">
        @error('name') <span class="field-error">{{ $message }}</span> @enderror
      </div>

      <div class="field">
        <label for="code">ISO Code (2 letters) <span class="req">*</span></label>
        <input type="text" id="code" name="code"
               value="{{ $val('code') }}"
               placeholder="SA"
               required maxlength="2"
               style="text-transform: uppercase; font-family: 'SFMono-Regular', Consolas, monospace; letter-spacing: .08em;"
               class="{{ $errors->has('code') ? 'is-invalid' : '' }}">
        <span class="field-hint">ISO 3166-1 alpha-2 — e.g. SA, AE, KW</span>
        @error('code') <span class="field-error">{{ $message }}</span> @enderror
      </div>

      <div class="field">
        <label for="code3">ISO Code (3 letters)</label>
        <input type="text" id="code3" name="code3"
               value="{{ $val('code3') }}"
               placeholder="SAU"
               maxlength="3"
               style="text-transform: uppercase; font-family: 'SFMono-Regular', Consolas, monospace; letter-spacing: .08em;"
               class="{{ $errors->has('code3') ? 'is-invalid' : '' }}">
        <span class="field-hint">ISO 3166-1 alpha-3 — optional</span>
        @error('code3') <span class="field-error">{{ $message }}</span> @enderror
      </div>

      <div class="field">
        <label for="flag_emoji">Flag Emoji</label>
        <input type="text" id="flag_emoji" name="flag_emoji"
               value="{{ $val('flag_emoji') }}"
               placeholder="🇸🇦"
               maxlength="16"
               style="font-size: 1.4rem;"
               class="{{ $errors->has('flag_emoji') ? 'is-invalid' : '' }}">
        <span class="field-hint">
          Paste the flag emoji —
          <a href="https://emojipedia.org/flags" target="_blank" rel="noopener" style="color: var(--gold-600);">browse here</a>
        </span>
        @error('flag_emoji') <span class="field-error">{{ $message }}</span> @enderror
      </div>

      <div class="field">
        <label for="short_label">Short Label</label>
        <input type="text" id="short_label" name="short_label"
               value="{{ $val('short_label') }}"
               placeholder="KSA"
               maxlength="8"
               style="text-transform: uppercase; font-family: 'SFMono-Regular', Consolas, monospace; letter-spacing: .08em;"
               class="{{ $errors->has('short_label') ? 'is-invalid' : '' }}">
        <span class="field-hint">Short code shown on cards — e.g. KSA, UAE</span>
        @error('short_label') <span class="field-error">{{ $message }}</span> @enderror
      </div>

      <div class="field">
        <label for="sort_order">Sort Order</label>
        <input type="number" id="sort_order" name="sort_order"
               value="{{ $val('sort_order', 0) }}"
               min="0" max="9999"
               class="{{ $errors->has('sort_order') ? 'is-invalid' : '' }}">
        <span class="field-hint">Lower numbers appear first</span>
        @error('sort_order') <span class="field-error">{{ $message }}</span> @enderror
      </div>
    </div>
  </div>

  {{-- ================= VISIBILITY & FLAGS ================= --}}
  <div class="form-section">
    <h3 class="form-section__title">Visibility &amp; Flags</h3>

    <div class="checkbox-stack">
      <label class="checkbox-row">
        <input type="hidden" name="is_gcc" value="0">
        <input type="checkbox" name="is_gcc" value="1" {{ $val('is_gcc') ? 'checked' : '' }}>
        <span class="checkbox-row__box">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5"><path d="M20 6L9 17l-5-5"/></svg>
        </span>
        <div class="checkbox-row__text">
          <strong>GCC Market</strong>
          <span>Include in the "Serving the GCC" section on the homepage</span>
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
          <span>Highlight this country as a key destination</span>
        </div>
      </label>

      <label class="checkbox-row">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" {{ $val('is_active', 1) ? 'checked' : '' }}>
        <span class="checkbox-row__box">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5"><path d="M20 6L9 17l-5-5"/></svg>
        </span>
        <div class="checkbox-row__text">
          <strong>Active</strong>
          <span>Show this country on the website. Uncheck to hide it everywhere.</span>
        </div>
      </label>
    </div>
  </div>

  {{-- ================= ACTIONS ================= --}}
  <div class="form-actions">
    <a href="{{ route('admin.countries.index') }}" class="btn-toolbar btn-toolbar--ghost">Cancel</a>
    <button type="submit" class="btn-navy-md">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M20 6L9 17l-5-5"/></svg>
      {{ $isEdit ? 'Save Changes' : 'Create Country' }}
    </button>
  </div>

</div>