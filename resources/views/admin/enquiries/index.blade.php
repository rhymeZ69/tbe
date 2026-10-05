@extends('admin.layouts.app')

@section('title', 'Quote Enquiries')
@section('page_title', 'Quote Enquiries')
@section('page_subtitle', 'Leads from the website contact form')

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
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16v6H4zM4 14h16v6H4z"/></svg>
    </div>
    <div><span class="nl-stat__label">Total</span><strong class="nl-stat__value">{{ $stats['total'] }}</strong></div>
  </div>
  <div class="nl-stat">
    <div class="nl-stat__icon nl-stat__icon--gold">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16h.01"/></svg>
    </div>
    <div><span class="nl-stat__label">New</span><strong class="nl-stat__value">{{ $stats['new'] }}</strong></div>
  </div>
  <div class="nl-stat">
    <div class="nl-stat__icon nl-stat__icon--green">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
    </div>
    <div><span class="nl-stat__label">Won</span><strong class="nl-stat__value">{{ $stats['won'] }}</strong></div>
  </div>
  <div class="nl-stat">
    <div class="nl-stat__icon nl-stat__icon--muted">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
    </div>
    <div><span class="nl-stat__label">This Month</span><strong class="nl-stat__value">+{{ $stats['this_month'] }}</strong></div>
  </div>
</div>

<section class="panel">

  {{-- Toolbar --}}
  <div class="nl-toolbar">
    <form method="GET" action="{{ route('admin.enquiries.index') }}" class="nl-toolbar__form">
      <div class="nl-search">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
        <input type="search" name="q" value="{{ $search }}" placeholder="Search reference, name, email…">
      </div>

      <select name="status" class="nl-select" onchange="this.form.submit()">
        <option value="all"         {{ $status === 'all'         ? 'selected' : '' }}>All Statuses</option>
        <option value="new"         {{ $status === 'new'         ? 'selected' : '' }}>New</option>
        <option value="in_progress" {{ $status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
        <option value="quoted"      {{ $status === 'quoted'      ? 'selected' : '' }}>Quoted</option>
        <option value="won"         {{ $status === 'won'         ? 'selected' : '' }}>Won</option>
        <option value="lost"        {{ $status === 'lost'        ? 'selected' : '' }}>Lost</option>
        <option value="spam"        {{ $status === 'spam'        ? 'selected' : '' }}>Spam</option>
      </select>

      <select name="country" class="nl-select" onchange="this.form.submit()">
        <option value="">All Countries</option>
        @foreach ($countries as $country)
          <option value="{{ $country->id }}" {{ request('country') == $country->id ? 'selected' : '' }}>
            {{ $country->name }}
          </option>
        @endforeach
      </select>

      <select name="sort" class="nl-select" onchange="this.form.submit()">
        <option value="newest"    {{ $sort === 'newest'    ? 'selected' : '' }}>Newest First</option>
        <option value="oldest"    {{ $sort === 'oldest'    ? 'selected' : '' }}>Oldest First</option>
        <option value="reference" {{ $sort === 'reference' ? 'selected' : '' }}>By Reference</option>
      </select>

      <button type="submit" class="btn-toolbar">Apply</button>

      @if ($search || ($status !== 'all') || request('country') || $sort !== 'newest')
        <a href="{{ route('admin.enquiries.index') }}" class="btn-toolbar btn-toolbar--ghost">Reset</a>
      @endif
    </form>

    <div class="nl-toolbar__actions">
      <a href="{{ route('admin.enquiries.export', request()->only(['q', 'status', 'country'])) }}"
         class="btn-toolbar btn-toolbar--outline">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><path d="M7 10l5 5 5-5M12 15V3"/></svg>
        Export CSV
      </a>
    </div>
  </div>

  {{-- Empty --}}
  @if ($enquiries->isEmpty())
    <div class="nl-empty">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 4h16v6H4zM4 14h16v6H4z"/></svg>
      @if ($search || $status !== 'all' || request('country'))
        <h3>No enquiries match your filters</h3>
        <p>Try a different search term or clear the filters.</p>
        <a href="{{ route('admin.enquiries.index') }}" class="btn-navy-md">Clear Filters</a>
      @else
        <h3>No enquiries yet</h3>
        <p>When visitors submit the quote form on your website, they'll appear here.</p>
      @endif
    </div>
  @else

    <form method="POST" action="{{ route('admin.enquiries.bulk') }}" class="js-bulk-form" data-label="enquiry">
      @csrf

      {{-- Bulk action bar --}}
      <div class="nl-bulkbar" id="bulkBar">
        <span class="nl-bulkbar__count"><b id="bulkCount">0</b> selected</span>
        <select name="action" class="nl-select nl-select--compact" required>
          <option value="">Choose action…</option>
          <option value="mark_new">Mark as New</option>
          <option value="mark_in_progress">Mark as In Progress</option>
          <option value="mark_quoted">Mark as Quoted</option>
          <option value="mark_won">Mark as Won</option>
          <option value="mark_lost">Mark as Lost</option>
          <option value="mark_spam">Mark as Spam</option>
          <option value="delete">Delete</option>
        </select>
        <button type="submit" class="btn-toolbar">Apply</button>
        <button type="button" class="btn-toolbar btn-toolbar--ghost" onclick="clearSelection()">
          Cancel
        </button>
      </div>

      <div class="table-wrap">
        <table class="data-table enquiry-table">
          <thead>
            <tr>
              <th style="width: 40px;">
                <label class="cb">
                  <input type="checkbox" id="checkAll">
                  <span></span>
                </label>
              </th>
              <th style="width: 150px;">Reference</th>
              <th>Customer</th>
              <th style="width: 120px;">Country</th>
              <th style="width: 130px;">Interest</th>
              <th style="width: 140px;">Status</th>
              <th style="width: 130px;">Received</th>
              <th style="width: 80px; text-align: right;">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($enquiries as $enquiry)
              <tr class="{{ $enquiry->status === 'new' ? 'row--new' : '' }}">
                <td>
                  <label class="cb">
                    <input type="checkbox" name="ids[]" value="{{ $enquiry->id }}" class="rowCheckbox">
                    <span></span>
                  </label>
                </td>

                <td>
                  <a href="{{ route('admin.enquiries.show', $enquiry) }}" class="ref-link">
                    <code class="ref">{{ $enquiry->reference }}</code>
                  </a>
                </td>

                <td>
                  <div class="cat-name">
                    <strong>{{ $enquiry->name }}</strong>
                    @if ($enquiry->company)
                      <span class="cat-tagline">{{ $enquiry->company }}</span>
                    @endif
                  </div>
                  <p class="cat-desc">
                    <a href="mailto:{{ $enquiry->email }}" class="muted-link">{{ $enquiry->email }}</a>
                    @if ($enquiry->phone)
                      · <a href="tel:{{ $enquiry->phone }}" class="muted-link">{{ $enquiry->phone }}</a>
                    @endif
                  </p>
                </td>

                <td>
                  @if ($enquiry->country)
                    <span class="flag-emoji">{{ $enquiry->country->flag_emoji }}</span>
                    {{ $enquiry->country->short_label ?? $enquiry->country->name }}
                  @else
                    <span class="muted">—</span>
                  @endif
                </td>

                <td>
                  <span class="tag">{{ ucfirst(str_replace('_', ' ', $enquiry->product_interest)) }}</span>
                  @if ($enquiry->quantity_mt)
                    <div class="cell-secondary" style="margin-top: 3px;">{{ $enquiry->quantity_mt }} MT</div>
                  @endif
                </td>

                {{-- Inline status change --}}
                <td>
                  <form method="POST"
                        action="{{ route('admin.enquiries.status', $enquiry) }}"
                        class="inline-form"
                        onchange="this.submit()">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="status-select status-select--{{ $enquiry->status }}">
                      <option value="new"         {{ $enquiry->status === 'new'         ? 'selected' : '' }}>New</option>
                      <option value="in_progress" {{ $enquiry->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                      <option value="quoted"      {{ $enquiry->status === 'quoted'      ? 'selected' : '' }}>Quoted</option>
                      <option value="won"         {{ $enquiry->status === 'won'         ? 'selected' : '' }}>Won</option>
                      <option value="lost"        {{ $enquiry->status === 'lost'        ? 'selected' : '' }}>Lost</option>
                      <option value="spam"        {{ $enquiry->status === 'spam'        ? 'selected' : '' }}>Spam</option>
                    </select>
                  </form>
                </td>

                <td class="muted">
                  {{ $enquiry->created_at->format('M j, Y') }}
                  <br>
                  <span style="font-size: .72rem;">{{ $enquiry->created_at->diffForHumans() }}</span>
                </td>

                <td style="text-align: right;">
                  <div class="row-actions">
                    <a href="{{ route('admin.enquiries.show', $enquiry) }}" class="row-btn" title="View details">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </a>

                    <form method="POST"
                          action="{{ route('admin.enquiries.destroy', $enquiry) }}"
                          class="inline-form js-delete-form">
                      @csrf @method('DELETE')
                      <button type="button"
                              class="row-btn row-btn--danger js-delete-btn"
                              data-item-name="{{ $enquiry->reference }} — {{ $enquiry->name }}"
                              data-item-type="enquiry">
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
    </form>

    @if ($enquiries->hasPages())
      <div class="nl-pagination">
        <div class="nl-pagination__info">
          Showing <b>{{ $enquiries->firstItem() }}</b>–<b>{{ $enquiries->lastItem() }}</b>
          of <b>{{ $enquiries->total() }}</b> enquiries
        </div>
        <div class="nl-pagination__links">
          {{ $enquiries->links('pagination::simple-default') }}
        </div>
      </div>
    @endif

  @endif

</section>

@endsection

@push('scripts')
<script>
/* ---------- Select all + bulk bar ---------- */
const checkAll  = document.getElementById('checkAll');
const rowChecks = document.querySelectorAll('.rowCheckbox');
const bulkBar   = document.getElementById('bulkBar');
const bulkCount = document.getElementById('bulkCount');

function updateBulkBar() {
  const selected = document.querySelectorAll('.rowCheckbox:checked').length;
  bulkCount.textContent = selected;

  if (selected > 0) bulkBar.classList.add('active');
  else              bulkBar.classList.remove('active');

  if (checkAll) {
    checkAll.checked = selected === rowChecks.length && rowChecks.length > 0;
    checkAll.indeterminate = selected > 0 && selected < rowChecks.length;
  }
}

function clearSelection() {
  rowChecks.forEach(cb => cb.checked = false);
  if (checkAll) checkAll.checked = false;
  updateBulkBar();
}

if (checkAll) {
  checkAll.addEventListener('change', () => {
    rowChecks.forEach(cb => cb.checked = checkAll.checked);
    updateBulkBar();
  });
}
rowChecks.forEach(cb => cb.addEventListener('change', updateBulkBar));


/* ---------- Confirm modal for bulk actions ---------- */
document.querySelectorAll('.js-bulk-form').forEach(form => {
  form.addEventListener('submit', async (e) => {
    if (form.dataset.confirmed === '1') return;
    e.preventDefault();

    const action  = form.querySelector('select[name="action"]').value;
    const count   = form.querySelectorAll('input[name="ids[]"]:checked').length;
    const label   = form.dataset.label || 'item';
    const plural  = count === 1 ? label : label + 's';

    if (! action) {
      await window.tbe.confirm({
        title:   'Choose an action',
        message: 'Please select a bulk action from the dropdown first.',
        okText:  'OK',
      });
      return;
    }

    const copy = {
      delete:           { title: 'Delete ' + plural + '?', msg: `You're about to permanently delete <strong>${count} ${plural}</strong>. This cannot be undone.`, okText: 'Yes, delete', variant: 'danger' },
      mark_new:         { title: 'Mark as New?',           msg: `${count} ${plural} will be reset to <strong>New</strong>.`, okText: 'Confirm' },
      mark_in_progress: { title: 'Mark as In Progress?',   msg: `${count} ${plural} will be marked as <strong>In Progress</strong>.`, okText: 'Confirm' },
      mark_quoted:      { title: 'Mark as Quoted?',        msg: `${count} ${plural} will be marked as <strong>Quoted</strong>.`, okText: 'Confirm' },
      mark_won:         { title: 'Mark as Won?',           msg: `${count} ${plural} will be marked as <strong>Won</strong>.`, okText: 'Confirm' },
      mark_lost:        { title: 'Mark as Lost?',          msg: `${count} ${plural} will be marked as <strong>Lost</strong>.`, okText: 'Confirm' },
      mark_spam:        { title: 'Mark as Spam?',          msg: `${count} ${plural} will be marked as <strong>Spam</strong>.`, okText: 'Confirm' },
    };

    const opts = copy[action] || {
      title:  'Confirm action',
      msg:    `Apply this action to <strong>${count} ${plural}</strong>?`,
      okText: 'Confirm',
    };

    const ok = await window.tbe.confirm({
      title:   opts.title,
      message: opts.msg,
      okText:  opts.okText,
      variant: opts.variant || 'primary',
    });

    if (ok) {
      form.dataset.confirmed = '1';
      form.submit();
    }
  });
});


</script>
@endpush

