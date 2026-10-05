@php
  $isEdit = $tour !== null;
  $val = fn ($field, $default = '') => old($field, $isEdit ? $tour->{$field} : $default);

  // Determine current source type
  $currentSource = $val('video_source', '');
  $isUrl         = preg_match('#^https?://#i', $currentSource);
  $currentType   = old('video_source_type', $isUrl || !$isEdit ? 'link' : 'upload');
@endphp

<div class="form-sections">

  {{-- ================= STAGE IDENTITY ================= --}}
  <div class="form-section">
    <h3 class="form-section__title">Stage Identity</h3>

    <div class="form-grid">
      <div class="field">
        <label for="stage_number">Stage Number <span class="req">*</span></label>
        <input type="number" id="stage_number" name="stage_number"
               value="{{ $val('stage_number', $nextStage ?? 1) }}"
               min="1" max="255" required
               class="{{ $errors->has('stage_number') ? 'is-invalid' : '' }}">
        <span class="field-hint">Shown as "01", "02" on the homepage card</span>
        @error('stage_number') <span class="field-error">{{ $message }}</span> @enderror
      </div>

      <div class="field">
        <label for="sort_order">Sort Order</label>
        <input type="number" id="sort_order" name="sort_order"
               value="{{ $val('sort_order', 0) }}"
               min="0" max="9999">
        <span class="field-hint">Lower numbers appear first</span>
      </div>

      <div class="field field--full">
        <label for="title">Stage Title <span class="req">*</span></label>
        <input type="text" id="title" name="title"
               value="{{ $val('title') }}"
               placeholder="e.g. Livestock Sourcing &amp; Rearing"
               required maxlength="120"
               class="{{ $errors->has('title') ? 'is-invalid' : '' }}">
        @error('title') <span class="field-error">{{ $message }}</span> @enderror
      </div>

      <div class="field">
        <label for="stage_tag">Stage Tag</label>
        <input type="text" id="stage_tag" name="stage_tag"
               value="{{ $val('stage_tag') }}"
               placeholder="e.g. Livestock, Slaughter, Packing"
               maxlength="40">
        <span class="field-hint">Small badge shown top-right of the poster</span>
      </div>

      <div class="field field--full">
        <label for="description">Description</label>
        <textarea id="description" name="description" rows="3"
                  placeholder="A short paragraph describing what this stage covers"
                  maxlength="2000"
                  class="setting-input setting-input--textarea">{{ $val('description') }}</textarea>
      </div>
    </div>
  </div>

  {{-- ================= VIDEO SOURCE ================= --}}
  <div class="form-section">
    <h3 class="form-section__title">Video Source</h3>
    <p class="form-section__hint">
      Choose how you want to attach the video — paste a link (YouTube, Vimeo, external MP4) or upload a file.
      Uploaded files are saved to <code>public/videos/</code>.
    </p>

    {{-- Source type toggle --}}
    <div class="source-toggle" id="sourceToggle">
      <label class="source-toggle__option">
        <input type="radio" name="video_source_type" value="link"
               {{ $currentType === 'link' ? 'checked' : '' }}>
        <span class="source-toggle__box">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/>
            <path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/>
          </svg>
          <strong>Paste a Link</strong>
          <em>YouTube · Vimeo · external MP4</em>
        </span>
      </label>

      <label class="source-toggle__option">
        <input type="radio" name="video_source_type" value="upload"
               {{ $currentType === 'upload' ? 'checked' : '' }}>
        <span class="source-toggle__box">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
            <path d="M17 8l-5-5-5 5M12 3v12"/>
          </svg>
          <strong>Upload a File</strong>
          <em>MP4 · WebM · MOV · up to 100 MB</em>
        </span>
      </label>
    </div>

    {{-- Link mode --}}
    <div class="source-panel {{ $currentType !== 'link' ? 'is-hidden' : '' }}" data-source-panel="link">
      <div class="field">
        <label for="video_source">Video URL <span class="req">*</span></label>
        <input type="text" id="video_source" name="video_source"
               value="{{ old('video_source', $isUrl ? $currentSource : '') }}"
               placeholder="https://www.youtube.com/watch?v=... or https://cdn.example.com/video.mp4"
               maxlength="500"
               style="font-family:'SFMono-Regular',Consolas,monospace;font-size:.86rem;"
               class="{{ $errors->has('video_source') ? 'is-invalid' : '' }}">
        <span class="field-hint">
          <strong>Supported:</strong>
          YouTube (watch, youtu.be, shorts),
          Vimeo,
          or a direct link to an <code>.mp4</code> / <code>.webm</code> file.
        </span>
        @error('video_source') <span class="field-error">{{ $message }}</span> @enderror
      </div>

      <div class="field">
        <label for="video_type">Video Type</label>
        <select id="video_type" name="video_type">
          @foreach ([
            'youtube' => 'YouTube',
            'vimeo'   => 'Vimeo',
            'mp4'     => 'MP4 (direct link)',
            'webm'    => 'WebM (direct link)',
            'other'   => 'Other / External Embed',
          ] as $value => $label)
            <option value="{{ $value }}" {{ $val('video_type', 'youtube') === $value ? 'selected' : '' }}>
              {{ $label }}
            </option>
          @endforeach
        </select>
        <span class="field-hint">Auto-detected when you paste a YouTube or Vimeo URL.</span>
      </div>
    </div>

    {{-- Upload mode --}}
    <div class="source-panel {{ $currentType !== 'upload' ? 'is-hidden' : '' }}" data-source-panel="upload">
      @if ($isEdit && ! $isUrl && $currentSource)
        <div class="existing-video">
          <div class="existing-video__icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
              <rect x="2" y="4" width="20" height="16" rx="3"/>
              <path d="M10 9l5 3-5 3z" fill="currentColor"/>
            </svg>
          </div>
          <div class="existing-video__meta">
            <strong>Current uploaded file</strong>
            <span>{{ $currentSource }}</span>
            <a href="{{ asset($currentSource) }}" target="_blank" rel="noopener" class="existing-video__link">
              Open in new tab ↗
            </a>
          </div>
        </div>
      @endif

      <div class="field">
        <label for="video_file">
          {{ ($isEdit && ! $isUrl && $currentSource) ? 'Replace with a new file' : 'Upload Video File' }}
          <span class="req">{{ ($isEdit && ! $isUrl && $currentSource) ? '' : '*' }}</span>
        </label>
        <input type="file" id="video_file" name="video_file"
               accept="video/mp4,video/webm,video/quicktime,video/ogg"
               class="image-file {{ $errors->has('video_file') ? 'is-invalid' : '' }}">
        <span class="field-hint">
          MP4, WebM, MOV, or OGG · max 100 MB. Saved to <code>public/videos/</code>.
        </span>
        @error('video_file') <span class="field-error">{{ $message }}</span> @enderror
      </div>

      {{-- Live preview --}}
      <div class="video-preview" id="videoPreview" hidden>
        <span class="video-preview__label">Preview</span>
        <video id="videoPreviewPlayer" controls playsinline preload="metadata" style="max-width:100%;border-radius:10px;"></video>
        <div class="video-preview__name" id="videoPreviewName"></div>
      </div>
    </div>
  </div>

  {{-- ================= POSTER IMAGE ================= --}}
  <div class="form-section">
    <h3 class="form-section__title">Poster Image</h3>
    <p class="form-section__hint">
      Optional. Used as the card's background. If no poster is set, the stage gradient from CSS is used.
    </p>

    @if ($isEdit && $tour->poster_image)
      <div class="existing-poster">
        <div class="existing-poster__thumb" style="background-image:url('{{ asset($tour->poster_image) }}')"></div>
        <div class="existing-poster__meta">
          <strong>Current poster</strong>
          <span>{{ $tour->poster_image }}</span>
          <label class="existing-poster__remove">
            <input type="checkbox" name="remove_poster" value="1">
            <span>Remove this poster</span>
          </label>
        </div>
      </div>
    @endif

    <div class="field">
      <label for="poster_file">{{ $isEdit && $tour->poster_image ? 'Replace poster' : 'Upload poster' }}</label>
      <input type="file" id="poster_file" name="poster_file" accept="image/jpeg,image/png,image/webp"
             class="image-file">
      <span class="field-hint">JPG, PNG, or WebP · max 4 MB · landscape (16:10) recommended</span>
      @error('poster_file') <span class="field-error">{{ $message }}</span> @enderror
    </div>

    <div class="poster-preview" id="posterPreview" hidden>
      <span class="poster-preview__label">New poster preview</span>
      <div class="poster-preview__img" id="posterPreviewImg"></div>
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
          <span>Show this stage on the homepage infrastructure section</span>
        </div>
      </label>
    </div>
  </div>

  {{-- ================= ACTIONS ================= --}}
  <div class="form-actions">
    <a href="{{ route('admin.video-tours.index') }}" class="btn-toolbar btn-toolbar--ghost">Cancel</a>
    <button type="submit" class="btn-navy-md">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M20 6L9 17l-5-5"/></svg>
      {{ $isEdit ? 'Save Changes' : 'Create Stage' }}
    </button>
  </div>

</div>

@push('scripts')
<script>
(function () {
  /* ============================================================
     Source type toggle (link ↔ upload)
     ============================================================ */
  const sourceRadios = document.querySelectorAll('input[name="video_source_type"]');
  const sourcePanels = document.querySelectorAll('[data-source-panel]');
  const linkInput    = document.getElementById('video_source');
  const fileInput    = document.getElementById('video_file');
  const typeSelect   = document.getElementById('video_type');

  function updateSourcePanels() {
    const selected = document.querySelector('input[name="video_source_type"]:checked')?.value || 'link';

    sourcePanels.forEach(panel => {
      const isActive = panel.dataset.sourcePanel === selected;
      panel.classList.toggle('is-hidden', ! isActive);
    });

    // Toggle required attributes so the browser validates the right field
    if (selected === 'link') {
      if (linkInput) linkInput.setAttribute('required', 'required');
      if (fileInput) fileInput.removeAttribute('required');
    } else {
      if (linkInput) linkInput.removeAttribute('required');
      // Only require the file input if there isn't already an uploaded file
      const hasExisting = document.querySelector('.existing-video');
      if (fileInput && ! hasExisting) fileInput.setAttribute('required', 'required');
    }
  }

  sourceRadios.forEach(r => r.addEventListener('change', updateSourcePanels));
  updateSourcePanels(); // run on load

  /* ============================================================
     Auto-detect video type from URL
     ============================================================ */
  if (linkInput && typeSelect) {
    linkInput.addEventListener('input', () => {
      const val = linkInput.value.trim();
      if (! val) return;

      let detected = null;

      if (/youtube\.com|youtu\.be/i.test(val))               detected = 'youtube';
      else if (/vimeo\.com/i.test(val))                       detected = 'vimeo';
      else if (/\.mp4(\?|$)/i.test(val))                       detected = 'mp4';
      else if (/\.webm(\?|$)/i.test(val))                      detected = 'webm';
      else if (/\.(mov|m4v)(\?|$)/i.test(val))                 detected = 'mp4';
      else if (/^https?:\/\//i.test(val))                      detected = 'other';

      if (detected) {
        typeSelect.value = detected;
      }
    });
  }

  /* ============================================================
     Video file preview
     ============================================================ */
  const videoPreview = document.getElementById('videoPreview');
  const videoPlayer  = document.getElementById('videoPreviewPlayer');
  const videoName    = document.getElementById('videoPreviewName');

  if (fileInput && videoPreview && videoPlayer) {
    fileInput.addEventListener('change', (e) => {
      const file = e.target.files[0];
      if (! file) {
        videoPreview.hidden = true;
        videoPlayer.removeAttribute('src');
        return;
      }

      const url = URL.createObjectURL(file);
      videoPlayer.src = url;
      videoName.textContent = `${file.name} · ${(file.size / 1024 / 1024).toFixed(1)} MB`;
      videoPreview.hidden = false;
    });
  }

  /* ============================================================
     Poster preview
     ============================================================ */
  const posterInput = document.getElementById('poster_file');
  const preview     = document.getElementById('posterPreview');
  const previewImg  = document.getElementById('posterPreviewImg');

  if (posterInput && preview && previewImg) {
    posterInput.addEventListener('change', (e) => {
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