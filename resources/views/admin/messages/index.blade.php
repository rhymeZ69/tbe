@extends('admin.layouts.app')

@section('title', 'Messages')
@section('page_title', 'Contact Messages')
@section('page_subtitle', 'Enquiries from the contact form')

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
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M2 7l10 6 10-6"/></svg>
    </div>
    <div><span class="nl-stat__label">Total</span><strong class="nl-stat__value">{{ $stats['total'] }}</strong></div>
  </div>
  <div class="nl-stat">
    <div class="nl-stat__icon nl-stat__icon--gold">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16h.01"/></svg>
    </div>
    <div><span class="nl-stat__label">Unread</span><strong class="nl-stat__value">{{ $stats['unread'] }}</strong></div>
  </div>
  <div class="nl-stat">
    <div class="nl-stat__icon nl-stat__icon--green">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
    </div>
    <div><span class="nl-stat__label">Read</span><strong class="nl-stat__value">{{ $stats['read'] }}</strong></div>
  </div>
  <div class="nl-stat">
    <div class="nl-stat__icon nl-stat__icon--muted">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
    </div>
    <div><span class="nl-stat__label">This Week</span><strong class="nl-stat__value">{{ $stats['this_week'] }}</strong></div>
  </div>
</div>

<section class="panel">

  <div class="nl-toolbar">
    <form method="GET" action="{{ route('admin.messages.index') }}" class="nl-toolbar__form">
      <div class="nl-search">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
        <input type="search" name="q" value="{{ $search }}" placeholder="Search by name, email, subject…">
      </div>

      <select name="filter" class="nl-select" onchange="this.form.submit()">
        <option value="all"    {{ request('filter') === 'all'    ? 'selected' : '' }}>All Messages</option>
        <option value="unread" {{ request('filter') === 'unread' ? 'selected' : '' }}>Unread</option>
        <option value="read"   {{ request('filter') === 'read'   ? 'selected' : '' }}>Read</option>
      </select>

      <button type="submit" class="btn-toolbar">Apply</button>

      @if ($search || (request('filter') && request('filter') !== 'all'))
        <a href="{{ route('admin.messages.index') }}" class="btn-toolbar btn-toolbar--ghost">Reset</a>
      @endif
    </form>

    <div class="nl-toolbar__actions">
      <a href="{{ route('admin.messages.export', request()->only(['q', 'filter'])) }}"
         class="btn-toolbar btn-toolbar--outline">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><path d="M7 10l5 5 5-5M12 15V3"/></svg>
        Export CSV
      </a>
    </div>
  </div>

  @if ($messages->isEmpty())
    <div class="nl-empty">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M2 7l10 6 10-6"/></svg>
      @if ($search || request('filter'))
        <h3>No messages match your filters</h3>
        <p>Try a different search or clear the filters.</p>
        <a href="{{ route('admin.messages.index') }}" class="btn-navy-md">Clear Filters</a>
      @else
        <h3>No messages yet</h3>
        <p>When visitors submit the contact form, their messages will appear here.</p>
      @endif
    </div>
  @else

    <form method="POST" action="{{ route('admin.messages.bulk') }}" class="js-bulk-form" data-label="message">
      @csrf

      <div class="nl-bulkbar" id="bulkBar">
        <span class="nl-bulkbar__count"><b id="bulkCount">0</b> selected</span>
        <select name="action" class="nl-select nl-select--compact" required>
          <option value="">Choose action…</option>
          <option value="mark_read">Mark as Read</option>
          <option value="mark_unread">Mark as Unread</option>
          <option value="delete">Delete</option>
        </select>
        <button type="submit" class="btn-toolbar">Apply</button>
        <button type="button" class="btn-toolbar btn-toolbar--ghost" onclick="clearSelection()">Cancel</button>
      </div>

      <div class="table-wrap">
        <table class="data-table message-table">
          <thead>
            <tr>
              <th style="width: 40px;">
                <label class="cb">
                  <input type="checkbox" id="checkAll">
                  <span></span>
                </label>
              </th>
              <th style="width: 50px;"></th>
              <th>From</th>
              <th>Subject</th>
              <th style="width: 130px;">Received</th>
              <th style="width: 90px; text-align: right;">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($messages as $message)
              <tr class="{{ ! $message->is_read ? 'row--unread' : '' }}">
                <td>
                  <label class="cb">
                    <input type="checkbox" name="ids[]" value="{{ $message->id }}" class="rowCheckbox">
                    <span></span>
                  </label>
                </td>

                <td>
                  @if (! $message->is_read)
                    <span class="unread-dot" title="Unread"></span>
                  @endif
                </td>

                <td>
                  <div class="cat-name">
                    <strong>{{ $message->name }}</strong>
                  </div>
                  <p class="cat-desc">
                    <a href="mailto:{{ $message->email }}" class="muted-link">{{ $message->email }}</a>
                    @if ($message->phone)
                      · <a href="tel:{{ $message->phone }}" class="muted-link">{{ $message->phone }}</a>
                    @endif
                  </p>
                </td>

                <td>
                  <div class="cat-name">
                    <strong>{{ $message->subject ?: '(no subject)' }}</strong>
                  </div>
                  <p class="cat-desc">{{ Str::limit($message->message, 90) }}</p>
                </td>

                <td class="muted">
                  {{ $message->created_at->format('M j, Y') }}
                  <br>
                  <span style="font-size: .72rem;">{{ $message->created_at->diffForHumans() }}</span>
                </td>

                <td style="text-align: right;">
                  <div class="row-actions">
                    <a href="{{ route('admin.messages.show', $message) }}" class="row-btn" title="View">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </a>

                    <form method="POST"
                          action="{{ route('admin.messages.toggle', $message) }}"
                          class="inline-form">
                      @csrf @method('PATCH')
                      <button type="submit"
                              class="row-btn"
                              title="{{ $message->is_read ? 'Mark unread' : 'Mark read' }}">
                        @if ($message->is_read)
                          {{-- Mark as unread: envelope with a slash --}}
                          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 11V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h9"/>
                            <path d="M2 8l10 6 10-6"/>
                            <circle cx="18" cy="17" r="4" fill="white" stroke="currentColor"/>
                            <path d="M16 15l4 4M20 15l-4 4" stroke-width="1.8"/>
                          </svg>
                        @else
                          {{-- Mark as read: envelope with a check --}}
                          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 11V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h9"/>
                            <path d="M2 8l10 6 10-6"/>
                            <path d="M15 17l2 2 4-4"/>
                          </svg>
                        @endif
                      </button>
                    </form>

                    <form method="POST"
                          action="{{ route('admin.messages.destroy', $message) }}"
                          class="inline-form js-delete-form">
                      @csrf @method('DELETE')
                      <button type="button"
                              class="row-btn row-btn--danger js-delete-btn"
                              data-item-name="message from {{ $message->name }}"
                              data-item-type="message">
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

    @if ($messages->hasPages())
      <div class="nl-pagination">
        <div class="nl-pagination__info">
          Showing <b>{{ $messages->firstItem() }}</b>–<b>{{ $messages->lastItem() }}</b>
          of <b>{{ $messages->total() }}</b> messages
        </div>
        <div class="nl-pagination__links">
          {{ $messages->links('pagination::simple-default') }}
        </div>
      </div>
    @endif

  @endif

</section>

@endsection

@push('scripts')
<script>
const checkAll  = document.getElementById('checkAll');
const rowChecks = document.querySelectorAll('.rowCheckbox');
const bulkBar   = document.getElementById('bulkBar');
const bulkCount = document.getElementById('bulkCount');

function updateBulkBar() {
  const selected = document.querySelectorAll('.rowCheckbox:checked').length;
  bulkCount.textContent = selected;
  if (selected > 0) bulkBar.classList.add('active');
  else bulkBar.classList.remove('active');

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

/* Confirm modal for bulk actions */
document.querySelectorAll('.js-bulk-form').forEach(form => {
  form.addEventListener('submit', async (e) => {
    if (form.dataset.confirmed === '1') return;
    e.preventDefault();

    const action = form.querySelector('select[name="action"]').value;
    const count  = form.querySelectorAll('input[name="ids[]"]:checked').length;
    const label  = form.dataset.label || 'item';
    const plural = count === 1 ? label : label + 's';

    if (! action) {
      await window.tbe.confirm({
        title: 'Choose an action',
        message: 'Please select a bulk action from the dropdown first.',
        okText: 'OK',
      });
      return;
    }

    const copy = {
      delete:       { title: 'Delete ' + plural + '?',      msg: `You're about to permanently delete <strong>${count} ${plural}</strong>. This cannot be undone.`, okText: 'Yes, delete', variant: 'danger' },
      mark_read:    { title: 'Mark as read?',                msg: `${count} ${plural} will be marked as <strong>Read</strong>.`, okText: 'Confirm' },
      mark_unread:  { title: 'Mark as unread?',              msg: `${count} ${plural} will be marked as <strong>Unread</strong>.`, okText: 'Confirm' },
    };

    const opts = copy[action] || {
      title: 'Confirm action',
      msg: `Apply this action to <strong>${count} ${plural}</strong>?`,
      okText: 'Confirm',
    };

    const ok = await window.tbe.confirm({
      title: opts.title,
      message: opts.msg,
      okText: opts.okText,
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