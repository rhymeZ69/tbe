@php
  $isEdit = $page !== null;
  $val = fn ($field, $default = '') => old($field, $isEdit ? $page->{$field} : $default);

  // Format published_at for datetime-local input
  $publishedAtValue = '';
  if ($isEdit && $page->published_at) {
      $publishedAtValue = $page->published_at->format('Y-m-d\TH:i');
  } elseif (old('published_at')) {
      $publishedAtValue = old('published_at');
  }
@endphp

<div class="form-sections">

  {{-- ================= CONTENT ================= --}}
  <div class="form-section">
    <h3 class="form-section__title">Content</h3>

    <div class="form-grid">
      <div class="field field--full">
        <label for="title">Page Title <span class="req">*</span></label>
        <input type="text" id="title" name="title"
               value="{{ $val('title') }}"
               placeholder="e.g. About Us"
               required maxlength="200"
               class="{{ $errors->has('title') ? 'is-invalid' : '' }}">
        @error('title') <span class="field-error">{{ $message }}</span> @enderror
      </div>

      <div class="field field--full">
        <label for="slug">Slug / URL</label>
        <div class="slug-input">
          <span class="slug-input__prefix">{{ url('/') }}/</span>
          <input type="text" id="slug" name="slug"
                 value="{{ $val('slug') }}"
                 placeholder="auto-generated-from-title"
                 maxlength="200"
                 style="font-family:'SFMono-Regular',Consolas,monospace;font-size:.86rem;"
                 class="{{ $errors->has('slug') ? 'is-invalid' : '' }}">
        </div>
        <span class="field-hint">Leave empty to auto-generate from the title</span>
        @error('slug') <span class="field-error">{{ $message }}</span> @enderror
      </div>

      <div class="field field--full">
        <label for="excerpt">Excerpt</label>
        <textarea id="excerpt" name="excerpt" rows="2"
                  placeholder="A short summary (shown in listings and search results)"
                  maxlength="500"
                  class="setting-input setting-input--textarea">{{ $val('excerpt') }}</textarea>
        <span class="field-hint">Recommended: 120–160 characters</span>
      </div>

      <div class="field field--full">
        <label for="content">Content</label>
        <div class="editor-toolbar">
          <button type="button" class="editor-btn" data-editor="bold"      title="Bold"><strong>B</strong></button>
          <button type="button" class="editor-btn" data-editor="italic"    title="Italic"><em>I</em></button>
          <button type="button" class="editor-btn" data-editor="underline" title="Underline"><u>U</u></button>
          <span class="editor-divider"></span>
          <button type="button" class="editor-btn" data-editor="h2"        title="Heading">H2</button>
          <button type="button" class="editor-btn" data-editor="h3"        title="Subheading">H3</button>
          <span class="editor-divider"></span>
          <button type="button" class="editor-btn" data-editor="ul"        title="Bullet list">• List</button>
          <button type="button" class="editor-btn" data-editor="ol"        title="Numbered list">1. List</button>
          <button type="button" class="editor-btn" data-editor="link"      title="Insert link">🔗</button>
          <span class="editor-divider"></span>
          <button type="button" class="editor-btn" data-editor="preview"   title="Toggle preview">👁 Preview</button>
        </div>
        <textarea id="content" name="content" rows="18"
                  placeholder="Write your page content here. HTML is supported."
                  class="setting-input setting-input--textarea editor-textarea">{{ $val('content') }}</textarea>
        <div class="editor-preview" id="editorPreview" hidden></div>
        <span class="field-hint">
          Basic HTML supported: <code>&lt;p&gt;</code>, <code>&lt;h2&gt;</code>, <code>&lt;ul&gt;</code>, <code>&lt;a&gt;</code>, <code>&lt;strong&gt;</code>, etc.
        </span>
      </div>
    </div>
  </div>

  {{-- ================= FEATURED IMAGE ================= --}}
  <div class="form-section">
    <h3 class="form-section__title">Featured Image</h3>
    <p class="form-section__hint">
      Optional. Used as the page's banner and in listings.
    </p>

    @if ($isEdit && $page->featured_image)
      <div class="existing-image">
        <div class="existing-image__thumb" style="background-image:url('{{ asset($page->featured_image) }}')"></div>
        <div class="existing-image__meta">
          <strong>Current featured image</strong>
          <span>{{ $page->featured_image }}</span>
          <label class="existing-image__remove">
            <input type="checkbox" name="remove_featured_image" value="1">
            <span>Remove this image</span>
          </label>
        </div>
      </div>
    @endif

    <div class="field">
      <label for="featured_image_file">{{ $isEdit && $page->featured_image ? 'Replace image' : 'Upload image' }}</label>
      <input type="file" id="featured_image_file" name="featured_image_file"
             accept="image/jpeg,image/png,image/webp"
             class="image-file">
      <span class="field-hint">JPG, PNG or WebP · max 4 MB · landscape (16:9) recommended</span>
      @error('featured_image_file') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    <div class="poster-preview" id="imagePreview" hidden>
      <span class="poster-preview__label">New image preview</span>
      <div class="poster-preview__img" id="imagePreviewImg"></div>
    </div>
  </div>

  {{-- ================= PUBLISHING ================= --}}
  <div class="form-section">
    <h3 class="form-section__title">Publishing</h3>

    <div class="publish-grid">
      <label class="checkbox-row">
        <input type="hidden" name="is_published" value="0">
        <input type="checkbox" name="is_published" value="1"
               {{ $val('is_published', $isEdit ? null : 1) ? 'checked' : '' }}>
        <span class="checkbox-row__box">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5"><path d="M20 6L9 17l-5-5"/></svg>
        </span>
        <div class="checkbox-row__text">
          <strong>Published</strong>
          <span>Make this page publicly accessible on the website</span>
        </div>
      </label>

      <div class="field">
        <label for="published_at">Publish Date &amp; Time</label>
        <input type="datetime-local" id="published_at" name="published_at"
               value="{{ $publishedAtValue }}"
               class="{{ $errors->has('published_at') ? 'is-invalid' : '' }}">
        <span class="field-hint">
          Leave empty to publish immediately.
          <strong>Future dates schedule the page.</strong>
        </span>
        @error('published_at') <span class="field-error">{{ $message }}</span> @enderror
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
               placeholder="Overrides the page title in search engines"
               maxlength="255">
        <span class="field-hint">Recommended: 50–60 characters</span>
      </div>

      <div class="field field--full">
        <label for="meta_description">Meta Description</label>
        <textarea id="meta_description" name="meta_description" rows="3"
                  placeholder="A short description shown in search engine results"
                  maxlength="500"
                  class="setting-input setting-input--textarea">{{ $val('meta_description') }}</textarea>
        <span class="field-hint">Recommended: 150–160 characters</span>
      </div>
    </div>
  </div>

  {{-- ================= ACTIONS ================= --}}
  <div class="form-actions">
    <a href="{{ route('admin.pages.index') }}" class="btn-toolbar btn-toolbar--ghost">Cancel</a>
    <button type="submit" class="btn-navy-md">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M20 6L9 17l-5-5"/></svg>
      {{ $isEdit ? 'Save Changes' : 'Create Page' }}
    </button>
  </div>

</div>

@push('scripts')
<script>
(function () {
  /* ============================================================
     Auto-generate slug from title
     ============================================================ */
  const titleInput = document.getElementById('title');
  const slugInput  = document.getElementById('slug');

  function slugify(str) {
    return str.toLowerCase()
      .replace(/[^\w\s-]/g, '')
      .replace(/\s+/g, '-')
      .replace(/-+/g, '-')
      .replace(/^-|-$/g, '');
  }

  if (titleInput && slugInput) {
    let slugTouched = slugInput.value.trim().length > 0;

    slugInput.addEventListener('input', () => { slugTouched = slugInput.value.length > 0; });
    titleInput.addEventListener('input', () => {
      if (! slugTouched) slugInput.value = slugify(titleInput.value);
    });
  }

  /* ============================================================
     Tiny editor toolbar (inserts HTML around selection)
     ============================================================ */
  const textarea = document.getElementById('content');
  const preview  = document.getElementById('editorPreview');

  function wrapSelection(prefix, suffix = '') {
    if (! textarea) return;
    const start = textarea.selectionStart;
    const end   = textarea.selectionEnd;
    const val   = textarea.value;
    const sel   = val.substring(start, end);

    const replacement = prefix + (sel || 'text') + suffix;

    textarea.value = val.substring(0, start) + replacement + val.substring(end);

    // Restore selection inside the wrapped content
    const newStart = start + prefix.length;
    const newEnd   = newStart + (sel || 'text').length;
    textarea.focus();
    textarea.setSelectionRange(newStart, newEnd);
  }

  document.querySelectorAll('.editor-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const action = btn.dataset.editor;

      switch (action) {
        case 'bold':      wrapSelection('<strong>', '</strong>'); break;
        case 'italic':    wrapSelection('<em>', '</em>'); break;
        case 'underline': wrapSelection('<u>', '</u>'); break;
        case 'h2':        wrapSelection('\n<h2>', '</h2>\n'); break;
        case 'h3':        wrapSelection('\n<h3>', '</h3>\n'); break;
        case 'ul':        wrapSelection('\n<ul>\n  <li>', '</li>\n</ul>\n'); break;
        case 'ol':        wrapSelection('\n<ol>\n  <li>', '</li>\n</ol>\n'); break;
        case 'link':
            window.tbe.prompt({
                title:       'Insert Link',
                message:     'Enter the URL the selected text should link to.',
                value:       'https://',
                placeholder: 'https://example.com',
                okText:      'Insert Link'
            }).then(url => {
                if (url && url.trim() && url.trim() !== 'https://') {
                wrapSelection(`<a href="${url}">`, '</a>');
                }
            });
            break;

        case 'preview':
          if (preview.hidden) {
            preview.innerHTML = textarea.value || '<em style="color:#999">Nothing to preview yet.</em>';
            preview.hidden = false;
            btn.textContent = '✕ Close Preview';
          } else {
            preview.hidden = true;
            btn.textContent = '👁 Preview';
          }
          break;
      }
    });
  });

  /* ============================================================
     Featured image preview
     ============================================================ */
  const imageInput = document.getElementById('featured_image_file');
  const imgPreview = document.getElementById('imagePreview');
  const imgPreviewImg = document.getElementById('imagePreviewImg');

  if (imageInput && imgPreview && imgPreviewImg) {
    imageInput.addEventListener('change', (e) => {
      const file = e.target.files[0];
      if (! file) { imgPreview.hidden = true; return; }

      const reader = new FileReader();
      reader.onload = (ev) => {
        imgPreviewImg.style.backgroundImage = `url('${ev.target.result}')`;
        imgPreview.hidden = false;
      };
      reader.readAsDataURL(file);
    });
  }
})();
</script>
@endpush