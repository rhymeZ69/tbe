@extends('admin.layouts.app')

@section('title', 'Countries')
@section('page_title', 'Countries &amp; Markets')
@section('page_subtitle', 'Manage export destinations shown on the website')

@section('content')

@if (session('status'))
  <div class="flash flash--success">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M20 6L9 17l-5-5"/></svg>
    <span>{{ session('status') }}</span>
  </div>
@endif

{{-- ================= STATS ================= --}}
<div class="nl-stat-grid">
  <div class="nl-stat">
    <div class="nl-stat__icon nl-stat__icon--blue">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a15 15 0 010 18"/></svg>
    </div>
    <div>
      <span class="nl-stat__label">Total</span>
      <strong class="nl-stat__value">{{ $stats['total'] }}</strong>
    </div>
  </div>
  <div class="nl-stat">
    <div class="nl-stat__icon nl-stat__icon--gold">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l3 7h7l-5.5 4.5L18 21l-6-4-6 4 1.5-7.5L2 9h7z"/></svg>
    </div>
    <div>
      <span class="nl-stat__label">GCC Markets</span>
      <strong class="nl-stat__value">{{ $stats['gcc'] }}</strong>
    </div>
  </div>
  <div class="nl-stat">
    <div class="nl-stat__icon nl-stat__icon--green">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
    </div>
    <div>
      <span class="nl-stat__label">Active</span>
      <strong class="nl-stat__value">{{ $stats['active'] }}</strong>
    </div>
  </div>
  <div class="nl-stat">
    <div class="nl-stat__icon nl-stat__icon--muted">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3.5 2"/></svg>
    </div>
    <div>
      <span class="nl-stat__label">Featured</span>
      <strong class="nl-stat__value">{{ $stats['featured'] }}</strong>
    </div>
  </div>
</div>

{{-- ================= PANEL ================= --}}
<section class="panel">

  {{-- Toolbar --}}
  <div class="nl-toolbar">
    <form method="GET" action="{{ route('admin.countries.index') }}" class="nl-toolbar__form">
      <div class="nl-search">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
        <input type="search" name="q" value="{{ $search }}" placeholder="Search by name or code…">
      </div>

      <select name="filter" class="nl-select" onchange="this.form.submit()">
        <option value="all"      {{ $filter === 'all'      ? 'selected' : '' }}>All Countries</option>
        <option value="gcc"      {{ $filter === 'gcc'      ? 'selected' : '' }}>GCC Only</option>
        <option value="featured" {{ $filter === 'featured' ? 'selected' : '' }}>Featured</option>
        <option value="active"   {{ $filter === 'active'   ? 'selected' : '' }}>Active</option>
        <option value="inactive" {{ $filter === 'inactive' ? 'selected' : '' }}>Inactive</option>
      </select>

      <button type="submit" class="btn-toolbar">Apply</button>

      @if ($search || $filter !== 'all')
        <a href="{{ route('admin.countries.index') }}" class="btn-toolbar btn-toolbar--ghost">Reset</a>
      @endif
    </form>

    <div class="nl-toolbar__actions">
      <a href="{{ route('admin.countries.create') }}" class="btn-toolbar btn-toolbar--gold">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M12 5v14M5 12h14"/></svg>
        Add Country
      </a>
    </div>
  </div>

  {{-- Empty state --}}
  @if ($countries->isEmpty())
    <div class="nl-empty">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a15 15 0 010 18"/></svg>
      @if ($search || $filter !== 'all')
        <h3>No countries match your filters</h3>
        <p>Try a different search term or clear the filters.</p>
        <a href="{{ route('admin.countries.index') }}" class="btn-navy-md">Clear Filters</a>
      @else
        <h3>No countries yet</h3>
        <p>Add your first export destination to get started.</p>
        <a href="{{ route('admin.countries.create') }}" class="btn-navy-md">Add Country</a>
      @endif
    </div>
  @else

    {{-- Table --}}
    <div class="table-wrap">
      <table class="data-table country-table">
        <thead>
          <tr>
            <th style="width: 60px;">Flag</th>
            <th>Country</th>
            <th style="width: 90px;">Code</th>
            <th style="width: 80px;">Short</th>
            <th style="width: 80px;" class="center">GCC</th>
            <th style="width: 100px;" class="center">Featured</th>
            <th style="width: 100px;" class="center">Active</th>
            <th style="width: 70px;" class="center">Sort</th>
            <th style="width: 90px; text-align: right;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($countries as $country)
            <tr class="{{ ! $country->is_active ? 'row--muted' : '' }}">

              <td>
                <span class="country-flag">{{ $country->flag_emoji ?: '🏳️' }}</span>
              </td>

              <td>
                <div class="country-name">
                  <strong>{{ $country->name }}</strong>
                  @if ($country->code3)
                    <span class="country-code3">{{ $country->code3 }}</span>
                  @endif
                </div>
              </td>

              <td>
                <code class="code-chip">{{ $country->code }}</code>
              </td>

              <td>
                <span class="muted">{{ $country->short_label ?: '—' }}</span>
              </td>

              {{-- Toggle: is_gcc --}}
              <td class="center">
                <form method="POST" action="{{ route('admin.countries.toggle', $country) }}" class="inline-form">
                  @csrf
                  @method('PATCH')
                  <input type="hidden" name="field" value="is_gcc">
                  <button type="submit" class="pill-toggle {{ $country->is_gcc ? 'on' : 'off' }}"
                          title="{{ $country->is_gcc ? 'Remove from GCC' : 'Mark as GCC' }}">
                    {{ $country->is_gcc ? 'Yes' : 'No' }}
                  </button>
                </form>
              </td>

              {{-- Toggle: is_featured --}}
              <td class="center">
                <form method="POST" action="{{ route('admin.countries.toggle', $country) }}" class="inline-form">
                  @csrf
                  @method('PATCH')
                  <input type="hidden" name="field" value="is_featured">
                  <button type="submit" class="pill-toggle {{ $country->is_featured ? 'on-gold' : 'off' }}"
                          title="{{ $country->is_featured ? 'Unfeature' : 'Mark as featured' }}">
                    {{ $country->is_featured ? '★' : '☆' }}
                  </button>
                </form>
              </td>

              {{-- Toggle: is_active --}}
              <td class="center">
                <form method="POST" action="{{ route('admin.countries.toggle', $country) }}" class="inline-form">
                  @csrf
                  @method('PATCH')
                  <input type="hidden" name="field" value="is_active">
                  <button type="submit" class="pill-toggle {{ $country->is_active ? 'on-green' : 'off' }}"
                          title="{{ $country->is_active ? 'Deactivate' : 'Activate' }}">
                    {{ $country->is_active ? 'Active' : 'Hidden' }}
                  </button>
                </form>
              </td>

              <td class="center">
                <span class="muted" style="font-weight: 700;">{{ $country->sort_order ?? 0 }}</span>
              </td>

              <td style="text-align: right;">
                <div class="row-actions">
                  <a href="{{ route('admin.countries.edit', $country) }}"
                     class="row-btn" title="Edit">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.1 2.1 0 013 3L12 15l-4 1 1-4z"/></svg>
                  </a>

                  <form method="POST"
                        action="{{ route('admin.countries.destroy', $country) }}"
                        class="inline-form js-delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="button"
                            class="row-btn row-btn--danger js-delete-btn"
                            title="Delete"
                            data-item-name="{{ $country->name }}"
                            data-item-type="country">
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

    {{-- Pagination --}}
    @if ($countries->hasPages())
      <div class="nl-pagination">
        <div class="nl-pagination__info">
          Showing <b>{{ $countries->firstItem() }}</b>–<b>{{ $countries->lastItem() }}</b>
          of <b>{{ $countries->total() }}</b> countries
        </div>
        <div class="nl-pagination__links">
          {{ $countries->links('pagination::simple-default') }}
        </div>
      </div>
    @endif

  @endif

</section>

@endsection