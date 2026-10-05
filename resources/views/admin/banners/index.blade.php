@extends('admin.layouts.app')

@section('title', 'Banners')
@section('page_title', 'Banners')
@section('page_subtitle', 'Manage promotional and hero banners')

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
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2"/></svg>
    </div>
    <div><span class="nl-stat__label">Total</span><strong class="nl-stat__value">{{ $stats['total'] }}</strong></div>
  </div>
  <div class="nl-stat">
    <div class="nl-stat__icon nl-stat__icon--green">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
    </div>
    <div><span class="nl-stat__label">Live Now</span><strong class="nl-stat__value">{{ $stats['active'] }}</strong></div>
  </div>
  <div class="nl-stat">
    <div class="nl-stat__icon nl-stat__icon--gold">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
    </div>
    <div><span class="nl-stat__label">Scheduled</span><strong class="nl-stat__value">{{ $stats['scheduled'] }}</strong></div>
  </div>
  <div class="nl-stat">
    <div class="nl-stat__icon nl-stat__icon--muted" style="background: rgba(185,28,28,.10); color:#B91C1C;">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v5M12 16h.01"/></svg>
    </div>
    <div><span class="nl-stat__label">Expired</span><strong class="nl-stat__value">{{ $stats['expired'] }}</strong></div>
  </div>
</div>

<section class="panel">

  {{-- Toolbar --}}
  <div class="nl-toolbar">
    <form method="GET" action="{{ route('admin.banners.index') }}" class="nl-toolbar__form">
      <div class="nl-search">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
        <input type="search" name="q" value="{{ $search }}" placeholder="Search banners…">
      </div>

      <select name="position" class="nl-select" onchange="this.form.submit()">
        <option value="">All Positions</option>
        <option value="hero"   {{ request('position') === 'hero'   ? 'selected' : '' }}>Hero</option>
        <option value="top"    {{ request('position') === 'top'    ? 'selected' : '' }}>Top</option>
        <option value="middle" {{ request('position') === 'middle' ? 'selected' : '' }}>Middle</option>
        <option value="footer" {{ request('position') === 'footer' ? 'selected' : '' }}>Footer</option>
      </select>

      <select name="filter" class="nl-select" onchange="this.form.submit()">
        <option value="all"       {{ request('filter') === 'all'       ? 'selected' : '' }}>All Statuses</option>
        <option value="active"    {{ request('filter') === 'active'    ? 'selected' : '' }}>Active</option>
        <option value="inactive"  {{ request('filter') === 'inactive'  ? 'selected' : '' }}>Hidden</option>
        <option value="scheduled" {{ request('filter') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
        <option value="expired"   {{ request('filter') === 'expired'   ? 'selected' : '' }}>Expired</option>
      </select>

      <button type="submit" class="btn-toolbar">Apply</button>

      @if ($search || request('filter') || request('position'))
        <a href="{{ route('admin.banners.index') }}" class="btn-toolbar btn-toolbar--ghost">Reset</a>
      @endif
    </form>

    <div class="nl-toolbar__actions">
      <a href="{{ route('admin.banners.create') }}" class="btn-toolbar btn-toolbar--gold">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M12 5v14M5 12h14"/></svg>
        Add Banner
      </a>
    </div>
  </div>

  {{-- Empty --}}
  @if ($banners->isEmpty())
    <div class="nl-empty">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="5" width="18" height="14" rx="2"/><circle cx="8.5" cy="10" r="1.5"/><path d="M21 15l-5-5L5 19"/></svg>
      @if ($search || request('filter') || request('position'))
        <h3>No banners match your filters</h3>
        <p>Try a different search or clear the filters.</p>
        <a href="{{ route('admin.banners.index') }}" class="btn-navy-md">Clear Filters</a>
      @else
        <h3>No banners yet</h3>
        <p>Add a hero banner for the top of your homepage, or a promotional banner for other sections.</p>
        <a href="{{ route('admin.banners.create') }}" class="btn-navy-md">Add Banner</a>
      @endif
    </div>
  @else

    <div class="table-wrap">
      <table class="data-table banner-table">
        <thead>
          <tr>
            <th style="width: 140px;">Preview</th>
            <th>Banner</th>
            <th style="width: 100px;">Position</th>
            <th style="width: 180px;">Schedule</th>
            <th style="width: 100px;" class="center">Status</th>
            <th style="width: 70px;" class="center">Sort</th>
            <th style="width: 90px; text-align: right;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($banners as $banner)
            @php
              $isScheduled = $banner->starts_at && $banner->starts_at->isFuture();
              $isExpired   = $banner->ends_at && $banner->ends_at->isPast();
            @endphp
            <tr class="{{ ! $banner->is_active || $isExpired ? 'row--muted' : '' }}">

              <td>
                @if ($banner->image)
                  <div class="banner-thumb" style="background-image:url('{{ asset($banner->image) }}')"></div>
                @else
                  <div class="banner-thumb banner-thumb--empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3" y="5" width="18" height="14" rx="2"/><circle cx="8.5" cy="10" r="1.5"/><path d="M21 15l-5-5L5 19"/></svg>
                  </div>
                @endif
              </td>

              <td>
                <div class="cat-name">
                  <strong>{{ $banner->title ?: 'Untitled banner' }}</strong>
                </div>
                @if ($banner->subtitle)
                  <p class="cat-desc">{{ Str::limit($banner->subtitle, 90) }}</p>
                @endif
                @if ($banner->cta_text)
                  <div class="cell-secondary" style="margin-top: 4px;">
                    CTA: <strong>{{ $banner->cta_text }}</strong>
                    @if ($banner->cta_url)
                      <span style="opacity: .7;">→ {{ Str::limit($banner->cta_url, 30) }}</span>
                    @endif
                  </div>
                @endif
              </td>

              <td>
                <span class="position-chip position-chip--{{ $banner->position }}">
                  {{ ucfirst($banner->position) }}
                </span>
              </td>

              <td>
                @if ($banner->starts_at || $banner->ends_at)
                  <div class="cert-dates">
                    @if ($banner->starts_at)
                      <span class="{{ $isScheduled ? 'is-expiring' : '' }}">
                        {{ $banner->starts_at->format('M j, Y') }}
                      </span>
                    @else
                      <span class="muted">Always</span>
                    @endif
                    @if ($banner->ends_at)
                      <span class="cert-dates__arrow">→</span>
                      <span class="{{ $isExpired ? 'is-expired' : '' }}">
                        {{ $banner->ends_at->format('M j, Y') }}
                      </span>
                    @endif
                  </div>
                  @if ($isExpired)
                    <span class="expiry-badge expiry-badge--danger">Expired {{ $banner->ends_at->diffForHumans(null, true) }} ago</span>
                  @elseif ($isScheduled)
                    <span class="expiry-badge expiry-badge--warn">Starts {{ $banner->starts_at->diffForHumans() }}</span>
                  @endif
                @else
                  <span class="muted">No schedule</span>
                @endif
              </td>

              <td class="center">
                <form method="POST" action="{{ route('admin.banners.toggle', $banner) }}" class="inline-form">
                  @csrf @method('PATCH')
                  <input type="hidden" name="field" value="is_active">
                  <button type="submit" class="pill-toggle {{ $banner->is_active ? 'on-green' : 'off' }}">
                    {{ $banner->is_active ? 'Live' : 'Hidden' }}
                  </button>
                </form>
              </td>

              <td class="center">
                <span class="muted" style="font-weight: 700;">{{ $banner->sort_order ?? 0 }}</span>
              </td>

              <td style="text-align: right;">
                <div class="row-actions">
                  <a href="{{ route('admin.banners.edit', $banner) }}" class="row-btn" title="Edit">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.1 2.1 0 013 3L12 15l-4 1 1-4z"/></svg>
                  </a>

                  <form method="POST"
                        action="{{ route('admin.banners.destroy', $banner) }}"
                        class="inline-form js-delete-form">
                    @csrf @method('DELETE')
                    <button type="button"
                            class="row-btn row-btn--danger js-delete-btn"
                            data-item-name="{{ $banner->title ?: 'Untitled banner' }}"
                            data-item-type="banner">
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

    @if ($banners->hasPages())
      <div class="nl-pagination">
        <div class="nl-pagination__info">
          Showing <b>{{ $banners->firstItem() }}</b>–<b>{{ $banners->lastItem() }}</b>
          of <b>{{ $banners->total() }}</b> banners
        </div>
        <div class="nl-pagination__links">
          {{ $banners->links('pagination::simple-default') }}
        </div>
      </div>
    @endif

  @endif

</section>

@endsection