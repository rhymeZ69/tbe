@extends('admin.layouts.app')

@section('title', 'Message from ' . $message->name)
@section('page_title', 'Message Details')
@section('page_subtitle', 'Received ' . $message->created_at->format('M j, Y \a\t g:i A'))

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
    @if ($message->is_read)
      <span class="status status--won">
        <span class="dot dot--green"></span> Read
      </span>
    @else
      <span class="status status--new">
        <span class="dot"></span> Unread
      </span>
    @endif

    <span class="muted" style="margin-left: 12px; font-size: .82rem;">
      {{ $message->created_at->diffForHumans() }}
    </span>
  </div>

  <div class="enquiry-header__actions">
    <a href="mailto:{{ $message->email }}?subject=Re: {{ $message->subject ?: 'Your enquiry' }}" class="btn-toolbar btn-toolbar--gold">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M2 7l10 6 10-6"/></svg>
      Reply by Email
    </a>

    @if ($message->phone)
      <a href="tel:{{ $message->phone }}" class="btn-toolbar btn-toolbar--outline">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.9v3a2 2 0 01-2.2 2 19.8 19.8 0 01-8.6-3.1 19.5 19.5 0 01-6-6A19.8 19.8 0 012.1 4.2 2 2 0 014.1 2h3a2 2 0 012 1.7c.1 1 .4 1.9.7 2.8a2 2 0 01-.5 2.1L8.1 9.9a16 16 0 006 6l1.3-1.2a2 2 0 012.1-.5c.9.3 1.8.6 2.8.7a2 2 0 011.7 2z"/></svg>
        Call
      </a>
    @endif

    <form method="POST" action="{{ route('admin.messages.toggle', $message) }}" class="inline-form">
      @csrf @method('PATCH')
      <button type="submit" class="btn-toolbar btn-toolbar--outline">
        @if ($message->is_read)
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16h.01"/></svg>
          Mark Unread
        @else
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
          Mark Read
        @endif
      </button>
    </form>

    <a href="{{ route('admin.messages.index') }}" class="btn-toolbar btn-toolbar--ghost">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
      Back
    </a>
  </div>
</div>

<div class="enquiry-grid">

  {{-- Left: message body --}}
  <div class="enquiry-col">

    <section class="panel">
      <header class="panel__head">
        <div>
          <h2>{{ $message->subject ?: 'Message' }}</h2>
          <p>{{ $message->name }} &lt;{{ $message->email }}&gt;</p>
        </div>
      </header>

      <div class="message-body">
        {!! nl2br(e($message->message)) !!}
      </div>
    </section>

  </div>

  {{-- Right: sender info --}}
  <div class="enquiry-col">

    <section class="panel">
      <header class="panel__head">
        <div><h2>Sender</h2></div>
      </header>

      <div class="info-grid info-grid--single">
        <div class="info-item">
          <span class="info-item__label">Name</span>
          <span class="info-item__value">{{ $message->name }}</span>
        </div>

        <div class="info-item">
          <span class="info-item__label">Email</span>
          <a href="mailto:{{ $message->email }}" class="info-item__value info-item__value--link">
            {{ $message->email }}
          </a>
        </div>

        @if ($message->phone)
          <div class="info-item">
            <span class="info-item__label">Phone</span>
            <a href="tel:{{ $message->phone }}" class="info-item__value info-item__value--link">
              {{ $message->phone }}
            </a>
          </div>
        @endif

        @if ($message->subject)
          <div class="info-item">
            <span class="info-item__label">Subject</span>
            <span class="info-item__value">{{ $message->subject }}</span>
          </div>
        @endif
      </div>
    </section>

    <section class="panel">
      <header class="panel__head">
        <div><h2>Metadata</h2></div>
      </header>

      <div class="info-grid info-grid--single">
        <div class="info-item">
          <span class="info-item__label">Received</span>
          <span class="info-item__value">{{ $message->created_at->format('M j, Y \a\t g:i A') }}</span>
        </div>

        @if ($message->ip_address)
          <div class="info-item">
            <span class="info-item__label">IP Address</span>
            <span class="info-item__value" style="font-family: monospace; font-size: .82rem;">{{ $message->ip_address }}</span>
          </div>
        @endif

        <div class="info-item">
          <span class="info-item__label">Status</span>
          <span class="info-item__value">
            <span class="status status--{{ $message->is_read ? 'won' : 'new' }}">
              {{ $message->is_read ? 'Read' : 'Unread' }}
            </span>
          </span>
        </div>
      </div>
    </section>

  </div>

</div>

@endsection