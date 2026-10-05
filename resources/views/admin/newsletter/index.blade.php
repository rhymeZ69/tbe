@extends('admin.layouts.app')

@section('title', 'Newsletter Subscribers')
@section('page_title', 'Newsletter Subscribers')
@section('page_subtitle', 'Manage your email list — activate, deactivate or export')

@section('content')

{{-- ================= FLASH ================= --}}
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
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 6l-10 7L2 6"/><rect x="2" y="4" width="20" height="16" rx="2"/></svg>
    </div>
    <div>
      <span class="nl-stat__label">Total</span>
      <strong class="nl-stat__value">{{ number_format($stats['total']) }}</strong>
    </div>
  </div>

  <div class="nl-stat">
    <div class="nl-stat__icon nl-stat__icon--green">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
    </div>
    <div>
      <span class="nl-stat__label">Active</span>
      <strong class="nl-stat__value">{{ number_format($stats['active']) }}</strong>
    </div>
  </div>

  <div class="nl-stat">
    <div class="nl-stat__icon nl-stat__icon--muted">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
    </div>
    <div>
      <span class="nl-stat__label">Unsubscribed</span>
      <strong class="nl-stat__value">{{ number_format($stats['inactive']) }}</strong>
    </div>
  </div>

  <div class="nl-stat">
    <div class="nl-stat__icon nl-stat__icon--gold">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3.5 2"/></svg>
    </div>
    <div>
      <span class="nl-stat__label">This Month</span>
      <strong class="nl-stat__value">+{{ number_format($stats['this_month']) }}</strong>
    </div>
  </div>
</div>

{{-- ================= MAIN PANEL ================= --}}
<section class="panel">

  {{-- Toolbar --}}
  <div class="nl-toolbar">
    <form method="GET" action="{{ route('admin.newsletter.index') }}" class="nl-toolbar__form">

      {{-- Search --}}
      <div class="nl-search">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
        <input type="search" name="q" value="{{ $search }}" placeholder="Search by email or name…">
      </div>

      {{-- Status --}}
      <select name="status" class="nl-select" onchange="this.form.submit()">
        <option value="all"      {{ $status === 'all'      ? 'selected' : '' }}>All Statuses</option>
        <option value="active"   {{ $status === 'active'   ? 'selected' : '' }}>Active</option>
        <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Unsubscribed</option>
      </select>

      {{-- Sort --}}
      <select name="sort" class="nl-select" onchange="this.form.submit()">
        <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Newest First</option>
        <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>Oldest First</option>
        <option value="email"  {{ $sort === 'email'  ? 'selected' : '' }}>By Email</option>
      </select>

      <button type="submit" class="btn-toolbar">Apply</button>

      @if ($search || $status !== 'all' || $sort !== 'newest')
        <a href="{{ route('admin.newsletter.index') }}" class="btn-toolbar btn-toolbar--ghost">Reset</a>
      @endif
    </form>

    <div class="nl-toolbar__actions">
      <a href="{{ route('admin.newsletter.export', ['status' => $status]) }}"
         class="btn-toolbar btn-toolbar--outline">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><path d="M7 10l5 5 5-5M12 15V3"/></svg>
        Export CSV
      </a>
    </div>
  </div>

  {{-- Empty --}}
  @if ($subscribers->isEmpty())
    <div class="nl-empty">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
        <path d="M22 6l-10 7L2 6"/><rect x="2" y="4" width="20" height="16" rx="2"/>
      </svg>
      @if ($search || $status !== 'all')
        <h3>No subscribers match your filters</h3>
        <p>Try a different search term or clear the filters.</p>
        <a href="{{ route('admin.newsletter.index') }}" class="btn-navy-md">Clear Filters</a>
      @else
        <h3>No subscribers yet</h3>
        <p>Once visitors sign up through the newsletter form, they'll appear here.</p>
      @endif
    </div>
  @else

    {{-- Bulk action form wraps the table --}}
    <form method="POST" action="{{ route('admin.newsletter.bulk') }}" class="js-bulk-form" data-label="subscriber">
      @csrf

      <div class="nl-bulkbar" id="bulkBar">
        <span class="nl-bulkbar__count"><b id="bulkCount">0</b> selected</span>
        <select name="action" class="nl-select nl-select--compact" required>
          <option value="">Choose action…</option>
          <option value="activate">Activate</option>
          <option value="deactivate">Unsubscribe</option>
          <option value="delete">Delete</option>
        </select>
        <button type="submit" class="btn-toolbar btn-toolbar--danger">Apply</button>
        <button type="button" class="btn-toolbar btn-toolbar--ghost" onclick="clearSelection()">
          Cancel
        </button>
      </div>

      <div class="table-wrap">
        <table class="data-table nl-table">
          <thead>
            <tr>
              <th style="width: 40px;">
                <label class="cb">
                  <input type="checkbox" id="checkAll">
                  <span></span>
                </label>
              </th>
              <th>Subscriber</th>
              <th>Status</th>
              <th>Subscribed</th>
              <th>IP Address</th>
              <th style="width: 90px; text-align: right;">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($subscribers as $subscriber)
              <tr>
                <td>
                  <label class="cb">
                    <input type="checkbox" name="ids[]" value="{{ $subscriber->id }}" class="rowCheckbox">
                    <span></span>
                  </label>
                </td>

                <td>
                  <div class="nl-user">
                    <div class="nl-user__avatar">{{ strtoupper(substr($subscriber->email, 0, 1)) }}</div>
                    <div class="nl-user__meta">
                      <strong>{{ $subscriber->name ?: 'Anonymous' }}</strong>
                      <a href="mailto:{{ $subscriber->email }}" class="nl-user__email">{{ $subscriber->email }}</a>
                    </div>
                  </div>
                </td>

                <td>
                  @if ($subscriber->is_active)
                    <span class="status status--won">
                      <span class="dot dot--green"></span> Active
                    </span>
                  @else
                    <span class="status status--spam">
                      <span class="dot dot--muted"></span> Unsubscribed
                    </span>
                  @endif
                </td>

                <td class="muted">
                  {{ optional($subscriber->subscribed_at ?? $subscriber->created_at)->format('M j, Y') }}
                  <br>
                  <span style="font-size: .72rem;">
                    {{ optional($subscriber->subscribed_at ?? $subscriber->created_at)->diffForHumans() }}
                  </span>
                </td>

                <td class="muted" style="font-family: 'SFMono-Regular', Consolas, monospace; font-size: .76rem;">
                  {{ $subscriber->ip_address ?: '—' }}
                </td>

                <td style="text-align: right;">
                  <div class="row-actions">
                    <form method="POST"
                          action="{{ route('admin.newsletter.toggle', $subscriber) }}"
                          style="display: inline;">
                      @csrf
                      @method('PATCH')
                      <button type="submit" class="row-btn"
                              title="{{ $subscriber->is_active ? 'Unsubscribe' : 'Reactivate' }}">
                        @if ($subscriber->is_active)
                          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
                        @else
                          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
                        @endif
                      </button>
                    </form>

                    <form method="POST"
                          action="{{ route('admin.newsletter.destroy', $subscriber) }}"
                          class="inline-form js-delete-form">
                      @csrf
                      @method('DELETE')
                      <button type="button"
                              class="row-btn row-btn--danger js-delete-btn"
                              title="Delete"
                              data-item-name="{{ $subscriber->email }}"
                              data-item-type="subscriber">
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

    {{-- Pagination --}}
    @if ($subscribers->hasPages())
      <div class="nl-pagination">
        <div class="nl-pagination__info">
          Showing <b>{{ $subscribers->firstItem() }}</b>–<b>{{ $subscribers->lastItem() }}</b>
          of <b>{{ number_format($subscribers->total()) }}</b> subscribers
        </div>
        <div class="nl-pagination__links">
          {{ $subscribers->links('pagination::simple-default') }}
        </div>
      </div>
    @endif

  @endif

</section>

@endsection

@push('scripts')
<script>
/* ---------- Select all ---------- */
const checkAll   = document.getElementById('checkAll');
const rowChecks  = document.querySelectorAll('.rowCheckbox');
const bulkBar    = document.getElementById('bulkBar');
const bulkCount  = document.getElementById('bulkCount');

function updateBulkBar() {
  const selected = document.querySelectorAll('.rowCheckbox:checked').length;
  bulkCount.textContent = selected;

  if (selected > 0) {
    bulkBar.classList.add('active');
  } else {
    bulkBar.classList.remove('active');
  }

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
      delete:     { title: 'Delete subscribers?',    msg: `You're about to permanently delete <strong>${count} subscribers</strong>. This cannot be undone.`, okText: 'Yes, delete', variant: 'danger' },
      activate:   { title: 'Activate subscribers?',  msg: `${count} subscribers will be marked as <strong>Active</strong>.`, okText: 'Activate' },
      deactivate: { title: 'Unsubscribe?',           msg: `${count} subscribers will be moved to <strong>Unsubscribed</strong>.`, okText: 'Unsubscribe' },
    };

    const opts = copy[action] || {
      title:  'Confirm action',
      message: `Apply this action to <strong>${count} ${plural}</strong>?`,
      okText: 'Confirm',
    };

    const ok = await window.tbe.confirm({
      title:   opts.title,
      message: opts.message,
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

