@extends('admin.layouts.app')

@section('title', 'Certifications')
@section('page_title', 'Certifications')
@section('page_subtitle', 'Manage the quality certifications your business holds')

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
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l8 4v6c0 5-3.4 8.8-8 10-4.6-1.2-8-5-8-10V6z"/></svg>
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
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
    </div>
    <div><span class="nl-stat__label">Expiring Soon</span><strong class="nl-stat__value">{{ $stats['expiring'] }}</strong></div>
  </div>
  <div class="nl-stat">
    <div class="nl-stat__icon nl-stat__icon--muted" style="background: rgba(185,28,28,.10); color: #B91C1C;">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v5M12 16h.01"/></svg>
    </div>
    <div><span class="nl-stat__label">Expired</span><strong class="nl-stat__value">{{ $stats['expired'] }}</strong></div>
  </div>
</div>

<section class="panel">

  {{-- Toolbar --}}
  <div class="nl-toolbar">
    <form method="GET" action="{{ route('admin.certifications.index') }}" class="nl-toolbar__form">
      <div class="nl-search">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
        <input type="search" name="q" value="{{ $search }}" placeholder="Search certifications…">
      </div>

      <select name="filter" class="nl-select" onchange="this.form.submit()">
        <option value="all"      {{ request('filter') === 'all'      ? 'selected' : '' }}>All</option>
        <option value="active"   {{ request('filter') === 'active'   ? 'selected' : '' }}>Active</option>
        <option value="inactive" {{ request('filter') === 'inactive' ? 'selected' : '' }}>Hidden</option>
        <option value="expiring" {{ request('filter') === 'expiring' ? 'selected' : '' }}>Expiring in 60 days</option>
        <option value="expired"  {{ request('filter') === 'expired'  ? 'selected' : '' }}>Expired</option>
      </select>

      <button type="submit" class="btn-toolbar">Apply</button>

      @if ($search || (request('filter') && request('filter') !== 'all'))
        <a href="{{ route('admin.certifications.index') }}" class="btn-toolbar btn-toolbar--ghost">Reset</a>
      @endif
    </form>

    <div class="nl-toolbar__actions">
      <a href="{{ route('admin.certifications.create') }}" class="btn-toolbar btn-toolbar--gold">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M12 5v14M5 12h14"/></svg>
        Add Certification
      </a>
    </div>
  </div>

  {{-- Empty --}}
  @if ($certifications->isEmpty())
    <div class="nl-empty">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2l8 4v6c0 5-3.4 8.8-8 10-4.6-1.2-8-5-8-10V6z"/></svg>
      @if ($search || request('filter'))
        <h3>No certifications match your filters</h3>
        <p>Try a different search or clear the filters.</p>
        <a href="{{ route('admin.certifications.index') }}" class="btn-navy-md">Clear Filters</a>
      @else
        <h3>No certifications yet</h3>
        <p>Add your first certification — e.g. Halal Certified or HACCP.</p>
        <a href="{{ route('admin.certifications.create') }}" class="btn-navy-md">Add Certification</a>
      @endif
    </div>
  @else

    <div class="table-wrap">
      <table class="data-table certification-table">
        <thead>
          <tr>
            <th style="width: 80px;">Logo</th>
            <th>Certification</th>
            <th style="width: 140px;">Issued By</th>
            <th style="width: 120px;">Number</th>
            <th style="width: 130px;">Valid Period</th>
            <th style="width: 100px;" class="center">Status</th>
            <th style="width: 70px;" class="center">Sort</th>
            <th style="width: 90px; text-align: right;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($certifications as $cert)
            @php
              $isExpired  = $cert->expires_on && $cert->expires_on->isPast();
              $isExpiring = ! $isExpired && $cert->expires_on && $cert->expires_on->diffInDays(now()) <= 60;
            @endphp
            <tr class="{{ ! $cert->is_active ? 'row--muted' : '' }}">

              <td>
                @if ($cert->logo)
                  <div class="cert-logo" style="background-image:url('{{ asset($cert->logo) }}')"></div>
                @else
                  <div class="cert-logo cert-logo--empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 2l8 4v6c0 5-3.4 8.8-8 10-4.6-1.2-8-5-8-10V6z"/><path d="M9 12l2 2 4-4"/></svg>
                  </div>
                @endif
              </td>

              <td>
                <div class="cat-name">
                  <strong>{{ $cert->name }}</strong>
                </div>
                @if ($cert->description)
                  <p class="cat-desc">{{ Str::limit($cert->description, 90) }}</p>
                @endif
              </td>

              <td>
                @if ($cert->issued_by)
                  <span class="muted">{{ $cert->issued_by }}</span>
                @else
                  <span class="muted">—</span>
                @endif
              </td>

              <td>
                @if ($cert->certificate_number)
                  <code class="code-chip">{{ $cert->certificate_number }}</code>
                @else
                  <span class="muted">—</span>
                @endif
              </td>

              <td>
                @if ($cert->issued_on || $cert->expires_on)
                  <div class="cert-dates">
                    @if ($cert->issued_on)
                      <span>{{ $cert->issued_on->format('M j, Y') }}</span>
                    @endif
                    @if ($cert->expires_on)
                      <span class="cert-dates__arrow">→</span>
                      <span class="{{ $isExpired ? 'is-expired' : ($isExpiring ? 'is-expiring' : '') }}">
                        {{ $cert->expires_on->format('M j, Y') }}
                      </span>
                    @endif
                  </div>
                  @if ($isExpired)
                    <span class="expiry-badge expiry-badge--danger">Expired {{ $cert->expires_on->diffForHumans(null, true) }} ago</span>
                  @elseif ($isExpiring)
                    <span class="expiry-badge expiry-badge--warn">Expires in {{ $cert->expires_on->diffInDays(now()) }} days</span>
                  @endif
                @else
                  <span class="muted">—</span>
                @endif
              </td>

              <td class="center">
                <form method="POST" action="{{ route('admin.certifications.toggle', $cert) }}" class="inline-form">
                  @csrf @method('PATCH')
                  <input type="hidden" name="field" value="is_active">
                  <button type="submit" class="pill-toggle {{ $cert->is_active ? 'on-green' : 'off' }}">
                    {{ $cert->is_active ? 'Active' : 'Hidden' }}
                  </button>
                </form>
              </td>

              <td class="center">
                <span class="muted" style="font-weight: 700;">{{ $cert->sort_order ?? 0 }}</span>
              </td>

              <td style="text-align: right;">
                <div class="row-actions">
                  <a href="{{ route('admin.certifications.edit', $cert) }}" class="row-btn" title="Edit">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.1 2.1 0 013 3L12 15l-4 1 1-4z"/></svg>
                  </a>

                  <form method="POST"
                        action="{{ route('admin.certifications.destroy', $cert) }}"
                        class="inline-form js-delete-form">
                    @csrf @method('DELETE')
                    <button type="button"
                            class="row-btn row-btn--danger js-delete-btn"
                            data-item-name="{{ $cert->name }}"
                            data-item-type="certification">
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

    @if ($certifications->hasPages())
      <div class="nl-pagination">
        <div class="nl-pagination__info">
          Showing <b>{{ $certifications->firstItem() }}</b>–<b>{{ $certifications->lastItem() }}</b>
          of <b>{{ $certifications->total() }}</b> certifications
        </div>
        <div class="nl-pagination__links">
          {{ $certifications->links('pagination::simple-default') }}
        </div>
      </div>
    @endif

  @endif

</section>

@endsection