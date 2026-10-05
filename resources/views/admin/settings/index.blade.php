@extends('admin.layouts.app')

@section('title', 'Site Settings')
@section('page_title', 'Site Settings')
@section('page_subtitle', 'Manage the content that powers every page of your website')

@section('content')

@if (session('status'))
  <div class="flash flash--success">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M20 6L9 17l-5-5"/></svg>
    <span>{{ session('status') }}</span>
  </div>
@endif

@if ($errors->any())
  <div class="flash flash--error">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><path d="M12 8v5M12 16h.01"/></svg>
    <span>{{ $errors->first() }}</span>
  </div>
@endif

<form method="POST" action="{{ route('admin.settings.update') }}">
  @csrf
  @method('PUT')

  <div class="settings-layout">

    {{-- ================= SIDEBAR NAV (sticky) ================= --}}
    <aside class="settings-nav">
      <div class="settings-nav__sticky">
        <p class="settings-nav__heading">Sections</p>
        <ul class="settings-nav__list">
          @foreach ($groupMeta as $groupKey => $meta)
            @php
              // The feature toggles live in the "general" group but we show them separately
              $isFlags = $groupKey === 'general_flags';
              $targetId = $isFlags ? 'section-flags' : 'section-' . $groupKey;
              $hasItems = $isFlags
                ? ($settings->get('general', collect())->where('type', 'boolean')->count() > 0)
                : ($settings->get($groupKey, collect())->count() > 0);
            @endphp

            @if ($hasItems)
              <li>
                <a href="#{{ $targetId }}" class="settings-nav__link">
                  {{ $meta['label'] }}
                </a>
              </li>
            @endif
          @endforeach
        </ul>

        <button type="submit" class="settings-save-sticky">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M20 6L9 17l-5-5"/></svg>
          Save Changes
        </button>
      </div>
    </aside>

    {{-- ================= FIELDS ================= --}}
    <div class="settings-fields">

      @foreach ($groupMeta as $groupKey => $meta)
        @php
          $isFlags = $groupKey === 'general_flags';

          if ($isFlags) {
            $items = $settings->get('general', collect())->where('type', 'boolean');
          } else {
            $items = $settings->get($groupKey, collect())->where('type', '!=', 'boolean');
          }
        @endphp

        @if ($items->count())
          <section class="settings-section" id="section-{{ $isFlags ? 'flags' : $groupKey }}">
            <header class="settings-section__head">
              <div class="settings-section__icon">
                @switch($meta['icon'])
                  @case('settings')
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.6 1.6 0 00.3 1.8l.1.1a2 2 0 11-2.8 2.8l-.1-.1a1.6 1.6 0 00-1.8-.3 1.6 1.6 0 00-1 1.5V21a2 2 0 11-4 0v-.1a1.6 1.6 0 00-1-1.5 1.6 1.6 0 00-1.8.3l-.1.1a2 2 0 11-2.8-2.8l.1-.1a1.6 1.6 0 00.3-1.8 1.6 1.6 0 00-1.5-1H3a2 2 0 110-4h.1a1.6 1.6 0 001.5-1 1.6 1.6 0 00-.3-1.8l-.1-.1a2 2 0 112.8-2.8l.1.1a1.6 1.6 0 001.8.3h.1a1.6 1.6 0 001-1.5V3a2 2 0 114 0v.1a1.6 1.6 0 001 1.5 1.6 1.6 0 001.8-.3l.1-.1a2 2 0 112.8 2.8l-.1.1a1.6 1.6 0 00-.3 1.8v.1a1.6 1.6 0 001.5 1H21a2 2 0 110 4h-.1a1.6 1.6 0 00-1.5 1z"/></svg>
                    @break
                  @case('phone')
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.9v3a2 2 0 01-2.2 2 19.8 19.8 0 01-8.6-3.1 19.5 19.5 0 01-6-6A19.8 19.8 0 012.1 4.2 2 2 0 014.1 2h3a2 2 0 012 1.7c.1 1 .4 1.9.7 2.8a2 2 0 01-.5 2.1L8.1 9.9a16 16 0 006 6l1.3-1.2a2 2 0 012.1-.5c.9.3 1.8.6 2.8.7a2 2 0 011.7 2z"/></svg>
                    @break
                  @case('share')
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><path d="M8.6 13.5l6.8 4M15.4 6.5l-6.8 4"/></svg>
                    @break
                  @case('search')
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
                    @break
                  @case('toggle')
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="10" rx="5"/><circle cx="17" cy="12" r="3" fill="currentColor"/></svg>
                    @break
                @endswitch
              </div>

              <div>
                <h2>{{ $meta['label'] }}</h2>
                <p>{{ $meta['description'] }}</p>
              </div>
            </header>

            <div class="settings-section__body">
              @foreach ($items as $setting)
                <div class="setting-row">
                  <label class="setting-label" for="setting-{{ $setting->key }}">
                    <span class="setting-label__name">
                      {{ $setting->label ?: ucwords(str_replace('_', ' ', $setting->key)) }}
                    </span>
                    <code class="setting-label__key">{{ $setting->key }}</code>
                  </label>

                  <div class="setting-control">
                    @switch($setting->type)

                      @case('textarea')
                        <textarea
                          id="setting-{{ $setting->key }}"
                          name="settings[{{ $setting->key }}]"
                          rows="3"
                          class="setting-input setting-input--textarea"
                          placeholder="{{ $setting->description }}">{{ old('settings.'.$setting->key, $setting->value) }}</textarea>
                        @break

                      @case('boolean')
                        <label class="toggle-switch">
                          <input
                            type="checkbox"
                            name="settings[{{ $setting->key }}]"
                            value="1"
                            {{ old('settings.'.$setting->key, $setting->value) == '1' ? 'checked' : '' }}>
                          <span class="toggle-switch__track">
                            <span class="toggle-switch__thumb"></span>
                          </span>
                          <span class="toggle-switch__label" data-on="Enabled" data-off="Disabled">
                            {{ old('settings.'.$setting->key, $setting->value) == '1' ? 'Enabled' : 'Disabled' }}
                          </span>
                        </label>
                        @break

                      @case('email')
                        <div class="setting-input-wrap">
                          <input
                            type="email"
                            id="setting-{{ $setting->key }}"
                            name="settings[{{ $setting->key }}]"
                            value="{{ old('settings.'.$setting->key, $setting->value) }}"
                            class="setting-input"
                            placeholder="name@example.com">
                          <svg class="setting-input__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M2 7l10 6 10-6"/></svg>
                        </div>
                        @break

                      @case('phone')
                        <div class="setting-input-wrap">
                          <input
                            type="tel"
                            id="setting-{{ $setting->key }}"
                            name="settings[{{ $setting->key }}]"
                            value="{{ old('settings.'.$setting->key, $setting->value) }}"
                            class="setting-input"
                            placeholder="+92 300 0000000">
                          <svg class="setting-input__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.9v3a2 2 0 01-2.2 2 19.8 19.8 0 01-8.6-3.1 19.5 19.5 0 01-6-6A19.8 19.8 0 012.1 4.2 2 2 0 014.1 2h3a2 2 0 012 1.7c.1 1 .4 1.9.7 2.8a2 2 0 01-.5 2.1L8.1 9.9a16 16 0 006 6l1.3-1.2a2 2 0 012.1-.5c.9.3 1.8.6 2.8.7a2 2 0 011.7 2z"/></svg>
                        </div>
                        @break

                      @case('url')
                        <div class="setting-input-wrap">
                          <input
                            type="url"
                            id="setting-{{ $setting->key }}"
                            name="settings[{{ $setting->key }}]"
                            value="{{ old('settings.'.$setting->key, $setting->value) }}"
                            class="setting-input"
                            placeholder="https://...">
                          <svg class="setting-input__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/></svg>
                        </div>
                        @break

                      @default
                        <input
                          type="text"
                          id="setting-{{ $setting->key }}"
                          name="settings[{{ $setting->key }}]"
                          value="{{ old('settings.'.$setting->key, $setting->value) }}"
                          class="setting-input"
                          placeholder="{{ $setting->description }}">
                    @endswitch

                    @if ($setting->description && in_array($setting->type, ['text', 'textarea', 'url']))
                      <p class="setting-hint">{{ $setting->description }}</p>
                    @endif
                  </div>
                </div>
              @endforeach
            </div>
          </section>
        @endif
      @endforeach

      {{-- Save bar --}}
      <div class="settings-savebar">
        <div class="settings-savebar__info">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v5M12 16h.01"/></svg>
          Changes are cached for 1 hour and cleared automatically when you save.
        </div>
        <button type="submit" class="btn-navy-lg">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M20 6L9 17l-5-5"/></svg>
          Save Changes
        </button>
      </div>

    </div>
  </div>
</form>

@endsection