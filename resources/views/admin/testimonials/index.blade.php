@extends('admin.layouts.app')

@section('title', 'Testimonials')
@section('page_title', 'Testimonials')
@section('page_subtitle', 'Client reviews shown on your website')

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
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
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
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l3 7h7l-5.5 4.5L18 21l-6-4-6 4 1.5-7.5L2 9h7z"/></svg>
    </div>
    <div><span class="nl-stat__label">Featured</span><strong class="nl-stat__value">{{ $stats['featured'] }}</strong></div>
  </div>
  <div class="nl-stat">
    <div class="nl-stat__icon nl-stat__icon--muted">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l3 7h7l-5.5 4.5L18 21l-6-4-6 4 1.5-7.5L2 9h7z"/></svg>
    </div>
    <div><span class="nl-stat__label">Avg Rating</span><strong class="nl-stat__value">{{ $stats['avg'] }}</strong></div>
  </div>
</div>

<section class="panel">

  {{-- Toolbar --}}
  <div class="nl-toolbar">
    <form method="GET" action="{{ route('admin.testimonials.index') }}" class="nl-toolbar__form">
      <div class="nl-search">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
        <input type="search" name="q" value="{{ $search }}" placeholder="Search testimonials…">
      </div>

      <select name="filter" class="nl-select" onchange="this.form.submit()">
        <option value="all"      {{ request('filter') === 'all'      ? 'selected' : '' }}>All Testimonials</option>
        <option value="active"   {{ request('filter') === 'active'   ? 'selected' : '' }}>Active</option>
        <option value="inactive" {{ request('filter') === 'inactive' ? 'selected' : '' }}>Hidden</option>
        <option value="featured" {{ request('filter') === 'featured' ? 'selected' : '' }}>Featured</option>
      </select>

      <button type="submit" class="btn-toolbar">Apply</button>

      @if ($search || (request('filter') && request('filter') !== 'all'))
        <a href="{{ route('admin.testimonials.index') }}" class="btn-toolbar btn-toolbar--ghost">Reset</a>
      @endif
    </form>

    <div class="nl-toolbar__actions">
      <a href="{{ route('admin.testimonials.create') }}" class="btn-toolbar btn-toolbar--gold">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M12 5v14M5 12h14"/></svg>
        Add Testimonial
      </a>
    </div>
  </div>

  {{-- Empty --}}
  @if ($testimonials->isEmpty())
    <div class="nl-empty">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
      @if ($search || request('filter'))
        <h3>No testimonials match your filters</h3>
        <p>Try a different search or clear the filters.</p>
        <a href="{{ route('admin.testimonials.index') }}" class="btn-navy-md">Clear Filters</a>
      @else
        <h3>No testimonials yet</h3>
        <p>Add your first client testimonial to build trust with visitors.</p>
        <a href="{{ route('admin.testimonials.create') }}" class="btn-navy-md">Add Testimonial</a>
      @endif
    </div>
  @else

    <div class="table-wrap">
      <table class="data-table testimonial-table">
        <thead>
          <tr>
            <th style="width: 70px;">Avatar</th>
            <th>Client</th>
            <th style="width: 200px;">Rating</th>
            <th style="width: 140px;">Country</th>
            <th style="width: 100px;" class="center">Featured</th>
            <th style="width: 100px;" class="center">Status</th>
            <th style="width: 70px;" class="center">Sort</th>
            <th style="width: 90px; text-align: right;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($testimonials as $testimonial)
            <tr class="{{ ! $testimonial->is_active ? 'row--muted' : '' }}">

              <td>
                @if ($testimonial->avatar)
                  <div class="test-avatar" style="background-image:url('{{ asset($testimonial->avatar) }}')"></div>
                @else
                  <div class="test-avatar test-avatar--initials">
                    {{ strtoupper(substr($testimonial->client_name, 0, 1)) }}
                  </div>
                @endif
              </td>

              <td>
                <div class="cat-name">
                  <strong>{{ $testimonial->client_name }}</strong>
                  @if ($testimonial->position || $testimonial->company)
                    <span class="cat-tagline">
                      {{ $testimonial->position }}
                      @if ($testimonial->position && $testimonial->company) · @endif
                      {{ $testimonial->company }}
                    </span>
                  @endif
                </div>
                <p class="cat-desc">"{{ Str::limit($testimonial->quote, 100) }}"</p>
              </td>

              <td>
                <div class="star-display" title="{{ $testimonial->rating }} out of 5">
                  @for ($i = 1; $i <= 5; $i++)
                    <svg viewBox="0 0 24 24" class="{{ $i <= $testimonial->rating ? 'is-on' : 'is-off' }}">
                      <path d="M12 2l3 7h7l-5.5 4.5L18 21l-6-4-6 4 1.5-7.5L2 9h7z"/>
                    </svg>
                  @endfor
                </div>
              </td>

              <td>
                @if ($testimonial->country)
                  <span class="flag-emoji">{{ $testimonial->country->flag_emoji }}</span>
                  {{ $testimonial->country->short_label ?? $testimonial->country->name }}
                @else
                  <span class="muted">—</span>
                @endif
              </td>

              <td class="center">
                <form method="POST" action="{{ route('admin.testimonials.toggle', $testimonial) }}" class="inline-form">
                  @csrf @method('PATCH')
                  <input type="hidden" name="field" value="is_featured">
                  <button type="submit" class="pill-toggle {{ $testimonial->is_featured ? 'on-gold' : 'off' }}"
                          title="{{ $testimonial->is_featured ? 'Unfeature' : 'Mark as featured' }}">
                    {{ $testimonial->is_featured ? '★' : '☆' }}
                  </button>
                </form>
              </td>

              <td class="center">
                <form method="POST" action="{{ route('admin.testimonials.toggle', $testimonial) }}" class="inline-form">
                  @csrf @method('PATCH')
                  <input type="hidden" name="field" value="is_active">
                  <button type="submit" class="pill-toggle {{ $testimonial->is_active ? 'on-green' : 'off' }}"
                          title="{{ $testimonial->is_active ? 'Hide' : 'Show on website' }}">
                    {{ $testimonial->is_active ? 'Active' : 'Hidden' }}
                  </button>
                </form>
              </td>

              <td class="center">
                <span class="muted" style="font-weight: 700;">{{ $testimonial->sort_order ?? 0 }}</span>
              </td>

              <td style="text-align: right;">
                <div class="row-actions">
                  <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="row-btn" title="Edit">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.1 2.1 0 013 3L12 15l-4 1 1-4z"/></svg>
                  </a>

                  <form method="POST"
                        action="{{ route('admin.testimonials.destroy', $testimonial) }}"
                        class="inline-form js-delete-form">
                    @csrf @method('DELETE')
                    <button type="button"
                            class="row-btn row-btn--danger js-delete-btn"
                            data-item-name="{{ $testimonial->client_name }}"
                            data-item-type="testimonial">
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

    @if ($testimonials->hasPages())
      <div class="nl-pagination">
        <div class="nl-pagination__info">
          Showing <b>{{ $testimonials->firstItem() }}</b>–<b>{{ $testimonials->lastItem() }}</b>
          of <b>{{ $testimonials->total() }}</b> testimonials
        </div>
        <div class="nl-pagination__links">
          {{ $testimonials->links('pagination::simple-default') }}
        </div>
      </div>
    @endif

  @endif

</section>

@endsection