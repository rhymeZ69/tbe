@php
  $isEdit = $category !== null;
  $val = fn ($field, $default = '') => old($field, $isEdit ? $category->{$field} : $default);
@endphp

<div class="form-sections">

  {{-- ================= IDENTITY ================= --}}
  <div class="form-section">
    <h3 class="form-section__title">Identity</h3>

    <div class="form-grid">
      <div class="field field--full">
        <label for="name">Category Name <span class="req">*</span></label>
        <input type="text" id="name" name="name"
               value="{{ $val('name') }}"
               placeholder="e.g. Meat"
               required maxlength="120"
               class="{{ $errors->has('name') ? 'is-invalid' : '' }}">
        @error('name') <span class="field-error">{{ $message }}</span> @enderror
      </div>

      <div class="field">
        <label for="slug">Slug</label>
        <input type="text" id="slug" name="slug"
               value="{{ $val('slug') }}"
               placeholder="auto-generated-from-name"
               maxlength="120"
               style="font-family: 'SFMono-Regular', Consolas, monospace; font-size: .85rem;"
               class="{{ $errors->has('slug') ? 'is-invalid' : '' }}">
        <span class="field-hint">Leave empty to auto-generate from the name</span>
        @error('slug') <span class="field-error">{{ $message }}</span> @enderror
      </div>

      <div class="field">
        <label for="tagline">Tagline</label>
        <input type="text" id="tagline" name="tagline"
               value="{{ $val('tagline') }}"
               placeholder="Fresh &amp; Chilled"
               maxlength="60"
               class="{{ $errors->has('tagline') ? 'is-invalid' : '' }}">
        <span class="field-hint">Shown as the badge on the category card</span>
        @error('tagline') <span class="field-error">{{ $message }}</span> @enderror
      </div>

      <div class="field field--full">
        <label for="short_description">Short Description</label>
        <textarea id="short_description" name="short_description" rows="2"
                  maxlength="500"
                  placeholder="A brief one or two-sentence summary shown on the product card"
                  class="setting-input setting-input--textarea {{ $errors->has('short_description') ? 'is-invalid' : '' }}">{{ $val('short_description') }}</textarea>
        <span class="field-hint">Keep it under 200 characters for best display</span>
        @error('short_description') <span class="field-error">{{ $message }}</span> @enderror
      </div>

      <div class="field field--full">
        <label for="description">Full Description</label>
        <textarea id="description" name="description" rows="5"
                  maxlength="5000"
                  placeholder="Detailed description (used on the category detail page, not on the homepage)"
                  class="setting-input setting-input--textarea {{ $errors->has('description') ? 'is-invalid' : '' }}">{{ $val('description') }}</textarea>
        @error('description') <span class="field-error">{{ $message }}</span> @enderror
      </div>
    </div>
  </div>

  {{-- ================= APPEARANCE ================= --}}
  <div class="form-section">
    <h3 class="form-section__title">Appearance</h3>

    <div class="form-grid">
      <div class="field">
        <label for="gradient_class">Gradient Class</label>
        <input type="text" id="gradient_class" name="gradient_class"
               value="{{ $val('gradient_class') }}"
               placeholder="prod-top garments"
               maxlength="80"
               style="font-family: 'SFMono-Regular', Consolas, monospace; font-size: .85rem;"
               class="{{ $errors->has('gradient_class') ? 'is-invalid' : '' }}">
        <span class="field-hint">
          CSS classes used when there's no image. Available:
          <code>prod-top</code>,
          <code>prod-top garments</code>,
          <code>prod-top rice</code>,
          <code>prod-top vegetables</code>
        </span>
        @error('gradient_class') <span class="field-error">{{ $message }}</span> @enderror
      </div>

      <div class="field">
        <label for="sort_order">Sort Order</label>
        <input type="number" id="sort_order" name="sort_order"
               value="{{ $val('sort_order', 0) }}"
               min="0" max="9999"
               class="{{ $errors->has('sort_order') ? 'is-invalid' : '' }}">
        <span class="field-hint">Lower numbers appear first on the homepage</span>
        @error('sort_order') <span class="field-error">{{ $message }}</span> @enderror
      </div>

      <div class="field">
        <label for="icon">Icon Path</label>
        <input type="text" id="icon" name="icon"
               value="{{ $val('icon') }}"
               placeholder="img/icons/meat.svg"
               maxlength="255"
               class="{{ $errors->has('icon') ? 'is-invalid' : '' }}">
        <span class="field-hint">Optional path to an SVG or PNG icon</span>
        @error('icon') <span class="field-error">{{ $message }}</span> @enderror
      </div>

      <div class="field">
        <label for="image">Cover Image</label>
        <input type="text" id="image" name="image"
               value="{{ $val('image') }}"
               placeholder="images/categories/meat.jpg"
               maxlength="255"
               class="{{ $errors->has('image') ? 'is-invalid' : '' }}">
        <span class="field-hint">Path relative to <code>public/</code>. Optional.</span>
        @error('image') <span class="field-error">{{ $message }}</span> @enderror
      </div>
    </div>
  </div>

  {{-- ================= SEO ================= --}}
  <div class="form-section">
    <h3 class="form-section__title">SEO (Optional)</h3>

    <div class="form-grid">
      <div class="field field--full">
        <label for="meta_title">Meta Title</label>
        <input type="text" id="meta_title" name="meta_title"
               value="{{ $val('meta_title') }}"
               placeholder="Premium Halal Meat — Three Brothers Enterprises"
               maxlength="255"
               class="{{ $errors->has('meta_title') ? 'is-invalid' : '' }}">
        @error('meta_title') <span class="field-error">{{ $message }}</span> @enderror
      </div>

      <div class="field field--full">
        <label for="meta_description">Meta Description</label>
        <textarea id="meta_description" name="meta_description" rows="3"
                  maxlength="500"
                  placeholder="A short description shown in search engine results"
                  class="setting-input setting-input--textarea {{ $errors->has('meta_description') ? 'is-invalid' : '' }}">{{ $val('meta_description') }}</textarea>
        @error('meta_description') <span class="field-error">{{ $message }}</span> @enderror
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
          <span>Show this category and its products on the website</span>
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
          <span>Highlight this category in featured sections</span>
        </div>
      </label>
    </div>
  </div>

  {{-- ================= ACTIONS ================= --}}
  <div class="form-actions">
    <a href="{{ route('admin.categories.index') }}" class="btn-toolbar btn-toolbar--ghost">Cancel</a>
    <button type="submit" class="btn-navy-md">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M20 6L9 17l-5-5"/></svg>
      {{ $isEdit ? 'Save Changes' : 'Create Category' }}
    </button>
  </div>

</div>