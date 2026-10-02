@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Overview of your business at a glance')

@section('content')

{{-- ================= STAT CARDS ================= --}}
<div class="stat-grid">
  <div class="stat-card stat-card--gold">
    <div class="stat-card__icon">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16v6H4zM4 14h16v6H4z"/></svg>
    </div>
    <div class="stat-card__body">
      <span class="stat-card__label">New Enquiries</span>
      <div class="stat-card__value">{{ $stats['new_enquiries'] }}</div>
      <span class="stat-card__hint">{{ $stats['enquiries_this_month'] }} this month</span>
    </div>
  </div>

  <div class="stat-card stat-card--blue">
    <div class="stat-card__icon">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M2 7l10 6 10-6"/></svg>
    </div>
    <div class="stat-card__body">
      <span class="stat-card__label">Unread Messages</span>
      <div class="stat-card__value">{{ $stats['unread_messages'] }}</div>
      <span class="stat-card__hint">{{ $stats['total_messages'] }} total received</span>
    </div>
  </div>

  <div class="stat-card stat-card--green">
    <div class="stat-card__icon">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 6l-10 7L2 6"/><rect x="2" y="4" width="20" height="16" rx="2"/></svg>
    </div>
    <div class="stat-card__body">
      <span class="stat-card__label">Subscribers</span>
      <div class="stat-card__value">{{ $stats['subscribers'] }}</div>
      <span class="stat-card__hint">Active newsletter list</span>
    </div>
  </div>

  <div class="stat-card stat-card--purple">
    <div class="stat-card__icon">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 00-1-1.7l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.7l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
    </div>
    <div class="stat-card__body">
      <span class="stat-card__label">Active Products</span>
      <div class="stat-card__value">{{ $stats['products'] }}</div>
      <span class="stat-card__hint">Across {{ $stats['categories'] }} categories</span>
    </div>
  </div>
</div>

{{-- ================= SECONDARY STATS ================= --}}
<div class="mini-stat-grid">
  <a href="#" class="mini-stat">
    <span class="mini-stat__value">{{ $stats['video_tours'] }}</span>
    <span class="mini-stat__label">Video Tours</span>
  </a>
  <a href="#" class="mini-stat">
    <span class="mini-stat__value">{{ $stats['gcc_markets'] }}</span>
    <span class="mini-stat__label">GCC Markets</span>
  </a>
  <a href="#" class="mini-stat">
    <span class="mini-stat__value">{{ $stats['certifications'] }}</span>
    <span class="mini-stat__label">Certifications</span>
  </a>
  <a href="#" class="mini-stat">
    <span class="mini-stat__value">{{ $stats['testimonials'] }}</span>
    <span class="mini-stat__label">Testimonials</span>
  </a>
  <a href="#" class="mini-stat">
    <span class="mini-stat__value">{{ $stats['published_pages'] }}</span>
    <span class="mini-stat__label">Published Pages</span>
  </a>
  <a href="#" class="mini-stat">
    <span class="mini-stat__value">{{ $stats['active_banners'] }}</span>
    <span class="mini-stat__label">Active Banners</span>
  </a>
</div>

{{-- ================= MAIN GRID ================= --}}
<div class="dash-grid">

  {{-- Recent Enquiries --}}
  <section class="panel panel--wide">
    <header class="panel__head">
      <div>
        <h2>Recent Quote Enquiries</h2>
        <p>Latest {{ $recentEnquiries->count() }} submissions from your website</p>
      </div>
      <a href="#" class="btn-link">View all
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
      </a>
    </header>

    @if($recentEnquiries->isEmpty())
      <div class="empty-state">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 4h16v6H4zM4 14h16v6H4z"/></svg>
        <p>No enquiries yet. They'll appear here when customers submit the quote form.</p>
      </div>
    @else
      <div class="table-wrap">
        <table class="data-table">
          <thead>
            <tr>
              <th>Reference</th>
              <th>Customer</th>
              <th>Country</th>
              <th>Interest</th>
              <th>Status</th>
              <th>Received</th>
            </tr>
          </thead>
          <tbody>
            @foreach($recentEnquiries as $enquiry)
              <tr>
                <td><span class="ref">{{ $enquiry->reference }}</span></td>
                <td>
                  <div class="cell-primary">{{ $enquiry->name }}</div>
                  @if($enquiry->company)
                    <div class="cell-secondary">{{ $enquiry->company }}</div>
                  @endif
                </td>
                <td>
                  @if($enquiry->country)
                    <span class="flag-emoji">{{ $enquiry->country->flag_emoji }}</span>
                    {{ $enquiry->country->short_label ?? $enquiry->country->name }}
                  @else
                    <span class="muted">—</span>
                  @endif
                </td>
                <td>
                  <span class="tag">{{ ucfirst(str_replace('_', ' ', $enquiry->product_interest)) }}</span>
                </td>
                <td>
                  <span class="status status--{{ $enquiry->status }}">
                    {{ ucfirst(str_replace('_', ' ', $enquiry->status)) }}
                  </span>
                </td>
                <td class="muted">{{ $enquiry->created_at->diffForHumans() }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </section>

  {{-- Enquiries chart --}}
  <section class="panel">
    <header class="panel__head">
      <div>
        <h2>Enquiries Trend</h2>
        <p>Last 6 months</p>
      </div>
    </header>

    @php $maxCount = max(1, max($enquiriesByMonth ?: [0])); @endphp
    <div class="bar-chart">
      @foreach($enquiriesByMonth as $month => $count)
        <div class="bar-chart__col">
          <div class="bar-chart__bar-wrap">
            <div class="bar-chart__bar" style="height: {{ ($count / $maxCount) * 100 }}%">
              <span class="bar-chart__value">{{ $count }}</span>
            </div>
          </div>
          <span class="bar-chart__label">{{ $month }}</span>
        </div>
      @endforeach
    </div>
  </section>

  {{-- Recent Messages --}}
  <section class="panel">
    <header class="panel__head">
      <div>
        <h2>Recent Messages</h2>
        <p>From the contact form</p>
      </div>
      <a href="#" class="btn-link">Inbox</a>
    </header>

    @if($recentMessages->isEmpty())
      <div class="empty-state">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M2 7l10 6 10-6"/></svg>
        <p>No messages yet.</p>
      </div>
    @else
      <ul class="message-list">
        @foreach($recentMessages as $message)
          <li class="message-list__item {{ $message->is_read ? '' : 'unread' }}">
            <div class="message-list__avatar">{{ strtoupper(substr($message->name, 0, 1)) }}</div>
            <div class="message-list__body">
              <div class="message-list__head">
                <strong>{{ $message->name }}</strong>
                <span class="muted">{{ $message->created_at->diffForHumans() }}</span>
              </div>
              <span class="message-list__email">{{ $message->email }}</span>
              <p class="message-list__preview">{{ Str::limit($message->message, 70) }}</p>
            </div>
          </li>
        @endforeach
      </ul>
    @endif
  </section>

  {{-- Enquiries by Status --}}
  <section class="panel">
    <header class="panel__head">
      <div>
        <h2>Pipeline</h2>
        <p>Enquiries by status</p>
      </div>
    </header>

    @if(empty($enquiriesByStatus))
      <div class="empty-state">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="9"/></svg>
        <p>No data yet.</p>
      </div>
    @else
      <ul class="pipeline">
        @foreach($statusLabels as $key => $label)
          @php $count = $enquiriesByStatus[$key] ?? 0; @endphp
          <li class="pipeline__row">
            <span class="status status--{{ $key }}">{{ $label }}</span>
            <div class="pipeline__bar">
              <div class="pipeline__bar-fill pipeline__bar-fill--{{ $key }}"
                   style="width: {{ $stats['total_enquiries'] ? ($count / $stats['total_enquiries']) * 100 : 0 }}%">
              </div>
            </div>
            <span class="pipeline__count">{{ $count }}</span>
          </li>
        @endforeach
      </ul>
    @endif
  </section>

  {{-- Top Countries --}}
  <section class="panel">
    <header class="panel__head">
      <div>
        <h2>Top Destinations</h2>
        <p>Where your enquiries come from</p>
      </div>
    </header>

    @if($topCountries->isEmpty())
      <div class="empty-state">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a15 15 0 010 18"/></svg>
        <p>No enquiries yet.</p>
      </div>
    @else
      <ul class="country-list">
        @foreach($topCountries as $country)
          <li>
            <span class="flag-emoji">{{ $country->flag_emoji }}</span>
            <span class="country-list__name">{{ $country->name }}</span>
            <span class="country-list__count">{{ $country->enquiries_count }}</span>
          </li>
        @endforeach
      </ul>
    @endif
  </section>

  {{-- Latest Subscribers --}}
  <section class="panel">
    <header class="panel__head">
      <div>
        <h2>Latest Subscribers</h2>
        <p>Newsletter sign-ups</p>
      </div>
    </header>

    @if($recentSubscribers->isEmpty())
      <div class="empty-state">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="4" width="20" height="16" rx="2"/></svg>
        <p>No subscribers yet.</p>
      </div>
    @else
      <ul class="subscriber-list">
        @foreach($recentSubscribers as $subscriber)
          <li>
            <div class="subscriber-list__avatar">{{ strtoupper(substr($subscriber->email, 0, 1)) }}</div>
            <div class="subscriber-list__body">
              <strong>{{ $subscriber->name ?? 'Anonymous' }}</strong>
              <span>{{ $subscriber->email }}</span>
            </div>
            <span class="subscriber-list__time">{{ $subscriber->created_at->diffForHumans() }}</span>
          </li>
        @endforeach
      </ul>
    @endif
  </section>

  {{-- Quick actions --}}
  <section class="panel">
    <header class="panel__head">
      <div>
        <h2>Quick Actions</h2>
        <p>Shortcuts to common tasks</p>
      </div>
    </header>

    <div class="quick-actions">
      <a href="#" class="quick-action">
        <div class="quick-action__icon quick-action__icon--gold">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
        </div>
        <span>Add Product</span>
      </a>
      <a href="#" class="quick-action">
        <div class="quick-action__icon quick-action__icon--blue">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M10 8l6 4-6 4V8z"/></svg>
        </div>
        <span>Add Video</span>
      </a>
      <a href="#" class="quick-action">
        <div class="quick-action__icon quick-action__icon--green">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
        </div>
        <span>New Page</span>
      </a>
      <a href="#" class="quick-action">
        <div class="quick-action__icon quick-action__icon--purple">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16v6H4zM4 14h16v6H4z"/></svg>
        </div>
        <span>Site Settings</span>
      </a>
    </div>
  </section>

</div>

@endsection