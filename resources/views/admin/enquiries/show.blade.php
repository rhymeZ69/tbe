@extends('admin.layouts.app')

@section('title', 'Enquiry ' . $enquiry->reference)
@section('page_title', 'Enquiry ' . $enquiry->reference)
@section('page_subtitle', 'Received ' . $enquiry->created_at->format('M j, Y \a\t g:i A'))

@section('content')

@if (session('status'))
  <div class="flash flash--success">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M20 6L9 17l-5-5"/></svg>
    <span>{{ session('status') }}</span>
  </div>
@endif

{{-- Header actions --}}
<div class="enquiry-header reveal">
  <div>
    <span class="status status--{{ $enquiry->status }}">
      {{ ucfirst(str_replace('_', ' ', $enquiry->status)) }}
    </span>

    @if ($enquiry->contacted_at)
      <span class="muted" style="margin-left: 12px; font-size: .82rem;">
        Contacted {{ $enquiry->contacted_at->diffForHumans() }}
      </span>
    @endif

    @if ($enquiry->quoted_at)
      <span class="muted" style="margin-left: 12px; font-size: .82rem;">
        · Quoted {{ $enquiry->quoted_at->diffForHumans() }}
      </span>
    @endif
  </div>

  <div class="enquiry-header__actions">
    <a href="mailto:{{ $enquiry->email }}" class="btn-toolbar btn-toolbar--outline">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M2 7l10 6 10-6"/></svg>
      Reply by Email
    </a>

    @if ($enquiry->phone)
      <a href="tel:{{ $enquiry->phone }}" class="btn-toolbar btn-toolbar--outline">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.9v3a2 2 0 01-2.2 2 19.8 19.8 0 01-8.6-3.1 19.5 19.5 0 01-6-6A19.8 19.8 0 012.1 4.2 2 2 0 014.1 2h3a2 2 0 012 1.7c.1 1 .4 1.9.7 2.8a2 2 0 01-.5 2.1L8.1 9.9a16 16 0 006 6l1.3-1.2a2 2 0 012.1-.5c.9.3 1.8.6 2.8.7a2 2 0 011.7 2z"/></svg>
        Call
      </a>
    @endif

    <a href="{{ route('admin.enquiries.index') }}" class="btn-toolbar btn-toolbar--ghost">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
      Back
    </a>
  </div>
</div>

{{-- Main grid --}}
<div class="enquiry-grid">

  {{-- Left column: customer + message --}}
  <div class="enquiry-col">

    {{-- Customer info --}}
    <section class="panel">
      <header class="panel__head">
        <div>
          <h2>Customer</h2>
        </div>
      </header>

      <div class="info-grid">
        <div class="info-item">
          <span class="info-item__label">Name</span>
          <span class="info-item__value">{{ $enquiry->name }}</span>
        </div>

        @if ($enquiry->company)
          <div class="info-item">
            <span class="info-item__label">Company</span>
            <span class="info-item__value">{{ $enquiry->company }}</span>
          </div>
        @endif

        <div class="info-item">
          <span class="info-item__label">Email</span>
          <a href="mailto:{{ $enquiry->email }}" class="info-item__value info-item__value--link">
            {{ $enquiry->email }}
          </a>
        </div>

        @if ($enquiry->phone)
          <div class="info-item">
            <span class="info-item__label">Phone</span>
            <a href="tel:{{ $enquiry->phone }}" class="info-item__value info-item__value--link">
              {{ $enquiry->phone }}
            </a>
          </div>
        @endif

        <div class="info-item">
          <span class="info-item__label">Country</span>
          <span class="info-item__value">
            @if ($enquiry->country)
              {{ $enquiry->country->flag_emoji }} {{ $enquiry->country->name }}
            @else
              —
            @endif
          </span>
        </div>

        @if ($enquiry->destination_port)
          <div class="info-item">
            <span class="info-item__label">Destination Port</span>
            <span class="info-item__value">{{ $enquiry->destination_port }}</span>
          </div>
        @endif
      </div>
    </section>

    {{-- Product interest --}}
    <section class="panel">
      <header class="panel__head">
        <div><h2>Requirement</h2></div>
      </header>

      <div class="info-grid">
        <div class="info-item">
          <span class="info-item__label">Product Interest</span>
          <span class="info-item__value">
            <span class="tag">{{ ucfirst(str_replace('_', ' ', $enquiry->product_interest)) }}</span>
          </span>
        </div>

        @if ($enquiry->quantity_mt)
          <div class="info-item">
            <span class="info-item__label">Quantity</span>
            <span class="info-item__value">{{ $enquiry->quantity_mt }} MT</span>
          </div>
        @endif

        @if ($enquiry->packaging_notes)
          <div class="info-item info-item--full">
            <span class="info-item__label">Packaging Notes</span>
            <span class="info-item__value">{{ $enquiry->packaging_notes }}</span>
          </div>
        @endif

        @if ($enquiry->message)
          <div class="info-item info-item--full">
            <span class="info-item__label">Message</span>
            <div class="info-item__message">{{ $enquiry->message }}</div>
          </div>
        @endif
      </div>
    </section>

    {{-- Line items (if any) --}}
    @if ($enquiry->items->isNotEmpty())
      <section class="panel">
        <header class="panel__head">
          <div>
            <h2>Line Items</h2>
            <p>{{ $enquiry->items->count() }} {{ Str::plural('item', $enquiry->items->count()) }} requested</p>
          </div>
        </header>

        <div class="table-wrap">
          <table class="data-table">
            <thead>
              <tr>
                <th>Product</th>
                <th>Description</th>
                <th style="width: 90px;">Qty</th>
                <th style="width: 80px;">Unit</th>
                <th style="width: 140px;">Packaging</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($enquiry->items as $item)
                <tr>
                  <td>{{ $item->product?->name ?? '—' }}</td>
                  <td>{{ $item->item_description ?? '—' }}</td>
                  <td>{{ $item->quantity ?? '—' }}</td>
                  <td>{{ $item->unit ?? '—' }}</td>
                  <td>{{ $item->packaging ?? '—' }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </section>
    @endif

  </div>

  {{-- Right column: status + admin --}}
  <div class="enquiry-col">

    {{-- Update status + notes --}}
    <section class="panel">
      <header class="panel__head">
        <div><h2>Manage</h2></div>
      </header>

      <form method="POST" action="{{ route('admin.enquiries.update', $enquiry) }}">
        @csrf @method('PUT')

        <div class="field">
          <label for="status">Status</label>
          <select id="status" name="status" class="nl-select" style="width: 100%;">
            <option value="new"         {{ $enquiry->status === 'new'         ? 'selected' : '' }}>New</option>
            <option value="in_progress" {{ $enquiry->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
            <option value="quoted"      {{ $enquiry->status === 'quoted'      ? 'selected' : '' }}>Quoted</option>
            <option value="won"         {{ $enquiry->status === 'won'         ? 'selected' : '' }}>Won</option>
            <option value="lost"        {{ $enquiry->status === 'lost'        ? 'selected' : '' }}>Lost</option>
            <option value="spam"        {{ $enquiry->status === 'spam'        ? 'selected' : '' }}>Spam</option>
          </select>
        </div>

        <div class="field" style="margin-top: 16px;">
          <label for="admin_notes">Admin Notes</label>
          <textarea id="admin_notes" name="admin_notes" rows="6"
                    placeholder="Internal notes — not visible to the customer"
                    class="setting-input setting-input--textarea">{{ old('admin_notes', $enquiry->admin_notes) }}</textarea>
        </div>

        <button type="submit" class="btn-navy-md" style="width: 100%; margin-top: 16px;">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M20 6L9 17l-5-5"/></svg>
          Save Changes
        </button>
      </form>
    </section>

    {{-- Meta --}}
    <section class="panel">
      <header class="panel__head">
        <div><h2>Metadata</h2></div>
      </header>

      <div class="info-grid info-grid--single">
        <div class="info-item">
          <span class="info-item__label">Reference</span>
          <span class="info-item__value"><code class="ref">{{ $enquiry->reference }}</code></span>
        </div>
        <div class="info-item">
          <span class="info-item__label">Submitted</span>
          <span class="info-item__value">{{ $enquiry->created_at->format('M j, Y \a\t g:i A') }}</span>
        </div>
        @if ($enquiry->source)
          <div class="info-item">
            <span class="info-item__label">Source</span>
            <span class="info-item__value">{{ $enquiry->source }}</span>
          </div>
        @endif
        @if ($enquiry->ip_address)
          <div class="info-item">
            <span class="info-item__label">IP Address</span>
            <span class="info-item__value" style="font-family: monospace; font-size: .82rem;">{{ $enquiry->ip_address }}</span>
          </div>
        @endif
        @if ($enquiry->contacted_at)
          <div class="info-item">
            <span class="info-item__label">First Contacted</span>
            <span class="info-item__value">{{ $enquiry->contacted_at->format('M j, Y \a\t g:i A') }}</span>
          </div>
        @endif
        @if ($enquiry->quoted_at)
          <div class="info-item">
            <span class="info-item__label">Quoted</span>
            <span class="info-item__value">{{ $enquiry->quoted_at->format('M j, Y \a\t g:i A') }}</span>
          </div>
        @endif
      </div>
    </section>

  </div>

</div>

@endsection