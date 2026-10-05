@extends('admin.layouts.app')

@section('title', 'Video Tours')
@section('page_title', 'Video Tours')
@section('page_subtitle', 'Manage the infrastructure video stages shown on the homepage')

@section('content')

@if (session('status'))
  <div class="flash flash--success">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M20 6L9 17l-5-5"/></svg>
    <span>{{ session('status') }}</span>
  </div>
@endif

{{-- Stats --}}
<div class="nl-stat-grid">
  <div class="nl-stat">
    <div class="nl-stat__icon nl-stat__icon--blue">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M10 8l6 4-6 4V8z"/></svg>
    </div>
    <div><span class="nl-stat__label">Total</span><strong class="nl-stat__value">{{ $stats['total'] }}</strong></div>
  </div>
  <div class="nl-stat">
    <div class="nl-stat__icon nl-stat__icon--green">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
    </div>
    <div><span class="nl-stat__label">Active</span><strong class="nl-stat__value">{{ $stats['active'] }}</strong></div>
  </div>
  <div class="nl-stat">
    <div class="nl-stat__icon nl-stat__icon--gold">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="3"/><path d="M10 9l5 3-5 3z" fill="currentColor"/></svg>
    </div>
    <div><span class="nl-stat__label">YouTube</span><strong class="nl-stat__value">{{ $stats['youtube'] }}</strong></div>
  </div>
  <div class="nl-stat">
    <div class="nl-stat__icon nl-stat__icon--muted">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><path d="M17 8l-5-5-5 5M12 3v12"/></svg>
    </div>
    <div><span class="nl-stat__label">Local Files</span><strong class="nl-stat__value">{{ $stats['local'] }}</strong></div>
  </div>
</div>

<section class="panel">

  {{-- Toolbar --}}
  <div class="nl-toolbar">
    <form method="GET" action="{{ route('admin.video-tours.index') }}" class="nl-toolbar__form">
      <div class="nl-search">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
        <input type="search" name="q" value="{{ $search }}" placeholder="Search by title or tag…">
      </div>

      <select name="filter" class="nl-select" onchange="this.form.submit()">
        <option value="all"      {{ request('filter') === 'all'      ? 'selected' : '' }}>All Stages</option>
        <option value="active"   {{ request('filter') === 'active'   ? 'selected' : '' }}>Active</option>
        <option value="inactive" {{ request('filter') === 'inactive' ? 'selected' : '' }}>Hidden</option>
      </select>

      <button type="submit" class="btn-toolbar">Apply</button>

      @if ($search || (request('filter') && request('filter') !== 'all'))
        <a href="{{ route('admin.video-tours.index') }}" class="btn-toolbar btn-toolbar--ghost">Reset</a>
      @endif
    </form>

    <div class="nl-toolbar__actions">
      <a href="{{ route('admin.video-tours.create') }}" class="btn-toolbar btn-toolbar--gold">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M12 5v14M5 12h14"/></svg>
        Add Stage
      </a>
    </div>
  </div>

  {{-- Empty --}}
  @if ($tours->isEmpty())
    <div class="nl-empty">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="9"/><path d="M10 8l6 4-6 4V8z"/></svg>
      @if ($search || request('filter'))
        <h3>No stages match your filters</h3>
        <p>Try a different search or clear the filters.</p>
        <a href="{{ route('admin.video-tours.index') }}" class="btn-navy-md">Clear Filters</a>
      @else
        <h3>No video stages yet</h3>
        <p>Add your first infrastructure stage — e.g. "Livestock Sourcing".</p>
        <a href="{{ route('admin.video-tours.create') }}" class="btn-navy-md">Add Stage</a>
      @endif
    </div>
  @else

    <div class="table-wrap">
      <table class="data-table video-tour-table">
        <thead>
          <tr>
            <th style="width: 90px;">Stage</th>
            <th style="width: 110px;">Poster</th>
            <th>Title</th>
            <th style="width: 140px;">Source</th>
            <th style="width: 80px;" class="center">Sort</th>
            <th style="width: 100px;" class="center">Status</th>
            <th style="width: 90px; text-align: right;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($tours as $tour)
            <tr class="{{ ! $tour->is_active ? 'row--muted' : '' }}">

              <td>
                <span class="stage-num">{{ str_pad($tour->stage_number, 2, '0', STR_PAD_LEFT) }}</span>
              </td>

              <td>
                @if ($tour->poster_image)
                  <div class="poster-thumb" style="background-image:url('{{ asset($tour->poster_image) }}')"></div>
                @else
                  <div class="poster-thumb poster-thumb--empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                  </div>
                @endif
              </td>

              <td>
                <div class="cat-name">
                  <strong>{{ $tour->title }}</strong>
                  @if ($tour->stage_tag)
                    <span class="cat-tagline">{{ $tour->stage_tag }}</span>
                  @endif
                </div>
                @if ($tour->description)
                  <p class="cat-desc">{{ Str::limit($tour->description, 90) }}</p>
                @endif
              </td>

              <td>
                @php
                  $typeLabel = [
                    'youtube' => 'YouTube',
                    'vimeo'   => 'Vimeo',
                    'mp4'     => 'MP4',
                    'webm'    => 'WebM',
                    'other'   => 'Other',
                  ][$tour->video_type] ?? ucfirst($tour->video_type);
                @endphp
                <span class="source-chip source-chip--{{ $tour->video_type }}">
                  {{ $typeLabel }}
                </span>
                <div class="source-url" title="{{ $tour->video_source }}">
                  {{ Str::limit($tour->video_source, 28) }}
                </div>
              </td>

              <td class="center">
                <span class="muted" style="font-weight: 700;">{{ $tour->sort_order ?? 0 }}</span>
              </td>

              <td class="center">
                <form method="POST" action="{{ route('admin.video-tours.toggle', $tour) }}" class="inline-form">
                  @csrf @method('PATCH')
                  <button type="submit" class="pill-toggle {{ $tour->is_active ? 'on-green' : 'off' }}"
                          title="{{ $tour->is_active ? 'Hide from website' : 'Show on website' }}">
                    {{ $tour->is_active ? 'Active' : 'Hidden' }}
                  </button>
                </form>
              </td>

              <td style="text-align: right;">
                <div class="row-actions">
                  <a href="{{ route('admin.video-tours.edit', $tour) }}" class="row-btn" title="Edit">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.1 2.1 0 013 3L12 15l-4 1 1-4z"/></svg>
                  </a>

                  <form method="POST"
                        action="{{ route('admin.video-tours.destroy', $tour) }}"
                        class="inline-form js-delete-form">
                    @csrf @method('DELETE')
                    <button type="button"
                            class="row-btn row-btn--danger js-delete-btn"
                            data-item-name="Stage {{ $tour->stage_number }} — {{ $tour->title }}"
                            data-item-type="video stage">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/></svg>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    @if ($tours->hasPages())
      <div class="nl-pagination">
        <div class="nl-pagination__info">
          Showing <b>{{ $tours->firstItem() }}</b>–<b>{{ $tours->lastItem() }}</b>
          of <b>{{ $tours->total() }}</b> stages
        </div>
        <div class="nl-pagination__links">
          {{ $tours->links('pagination::simple-default') }}
        </div>
      </div>
    @endif

  @endif

</section>

@endsection