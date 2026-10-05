<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="theme-color" content="#050F26">
<title>@yield('title', 'Dashboard') — Three Brothers Enterprises</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet">

<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/logo.png') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/logo.png') }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/logo.png') }}">
<link rel="shortcut icon" href="{{ asset('img/logo.png') }}">

<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@stack('styles')
</head>
<body>

@php
    // Compute the sidebar badge counts once per request
    $sidebarStats = admin_stats();
@endphp

<div class="admin-shell" id="adminShell">

  {{-- ================= SIDEBAR ================= --}}
  <aside class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-brand">
      <img src="{{ asset('img/logo.png') }}"
           alt="Three Brothers Enterprises logo"
           class="logo-img"
           width="46" height="46"
           loading="eager">
      <div class="brand-text">
        <strong>Three Brothers</strong>
        <span>Admin Panel</span>
      </div>
    </div>

    <nav class="sidebar-nav">
      {{-- ---------- Overview ---------- --}}
      <p class="nav-heading">Overview</p>
      <a href="{{ route('admin.dashboard') }}"
         class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/></svg>
        <span>Dashboard</span>
      </a>

      {{-- ---------- Catalogue ---------- --}}
      <p class="nav-heading">Catalogue</p>

      <a href="{{ route('admin.categories.index') }}"
         class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16v6H4zM4 14h16v6H4z"/></svg>
        <span>Categories</span>
        <span class="nav-badge">{{ $sidebarStats['categories'] }}</span>
      </a>

      @if (Route::has('admin.products.index'))
        <a href="{{ route('admin.products.index') }}"
           class="nav-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 00-1-1.7l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.7l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/><path d="M3.3 7L12 12l8.7-5"/></svg>
          <span>Products</span>
          <span class="nav-badge">{{ $sidebarStats['products'] }}</span>
        </a>
      @else
        <a href="#" class="nav-item nav-item--disabled" title="Coming soon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 00-1-1.7l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.7l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/><path d="M3.3 7L12 12l8.7-5"/></svg>
          <span>Products</span>
          <span class="nav-badge">{{ $sidebarStats['products'] }}</span>
        </a>
      @endif

      {{-- ---------- Content ---------- --}}
      <p class="nav-heading">Content</p>

      @if (Route::has('admin.video-tours.index'))
        <a href="{{ route('admin.video-tours.index') }}"
           class="nav-item {{ request()->routeIs('admin.video-tours.*') ? 'active' : '' }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M10 8l6 4-6 4V8z"/></svg>
          <span>Video Tours</span>
          <span class="nav-badge">{{ $sidebarStats['video_tours'] }}</span>
        </a>
      @else
        <a href="#" class="nav-item nav-item--disabled">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M10 8l6 4-6 4V8z"/></svg>
          <span>Video Tours</span>
          <span class="nav-badge">{{ $sidebarStats['video_tours'] }}</span>
        </a>
      @endif

      @if (Route::has('admin.certifications.index'))
        <a href="{{ route('admin.certifications.index') }}"
           class="nav-item {{ request()->routeIs('admin.certifications.*') ? 'active' : '' }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l8 4v6c0 5-3.4 8.8-8 10-4.6-1.2-8-5-8-10V6z"/></svg>
          <span>Certifications</span>
          <span class="nav-badge">{{ $sidebarStats['certifications'] }}</span>
        </a>
      @else
        <a href="#" class="nav-item nav-item--disabled">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l8 4v6c0 5-3.4 8.8-8 10-4.6-1.2-8-5-8-10V6z"/></svg>
          <span>Certifications</span>
          <span class="nav-badge">{{ $sidebarStats['certifications'] }}</span>
        </a>
      @endif

      @if (Route::has('admin.testimonials.index'))
        <a href="{{ route('admin.testimonials.index') }}"
           class="nav-item {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
          <span>Testimonials</span>
          <span class="nav-badge">{{ $sidebarStats['testimonials'] }}</span>
        </a>
      @else
        <a href="#" class="nav-item nav-item--disabled">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
          <span>Testimonials</span>
          <span class="nav-badge">{{ $sidebarStats['testimonials'] }}</span>
        </a>
      @endif

      @if (Route::has('admin.pages.index'))
        <a href="{{ route('admin.pages.index') }}"
           class="nav-item {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>
          <span>Pages</span>
        </a>
      @else
        <a href="#" class="nav-item nav-item--disabled">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>
          <span>Pages</span>
        </a>
      @endif

      @if (Route::has('admin.banners.index'))
        <a href="{{ route('admin.banners.index') }}"
           class="nav-item {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2"/><circle cx="8.5" cy="10" r="1.5"/><path d="M21 15l-5-5L5 19"/></svg>
          <span>Banners</span>
        </a>
      @else
        <a href="#" class="nav-item nav-item--disabled">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2"/><circle cx="8.5" cy="10" r="1.5"/><path d="M21 15l-5-5L5 19"/></svg>
          <span>Banners</span>
        </a>
      @endif

      {{-- ---------- Leads ---------- --}}
      <p class="nav-heading">Leads</p>

      @if (Route::has('admin.enquiries.index'))
        <a href="{{ route('admin.enquiries.index') }}"
           class="nav-item {{ request()->routeIs('admin.enquiries.*') ? 'active' : '' }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16v6H4zM4 14h16v6H4z"/><path d="M8 7h.01M8 17h.01"/></svg>
          <span>Quote Enquiries</span>
          @if ($sidebarStats['new_enquiries'] > 0)
            <span class="nav-badge nav-badge--gold">{{ $sidebarStats['new_enquiries'] }}</span>
          @endif
        </a>
      @else
        <a href="#" class="nav-item nav-item--disabled">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16v6H4zM4 14h16v6H4z"/><path d="M8 7h.01M8 17h.01"/></svg>
          <span>Quote Enquiries</span>
          @if ($sidebarStats['new_enquiries'] > 0)
            <span class="nav-badge nav-badge--gold">{{ $sidebarStats['new_enquiries'] }}</span>
          @endif
        </a>
      @endif

      @if (Route::has('admin.messages.index'))
        <a href="{{ route('admin.messages.index') }}"
           class="nav-item {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M2 7l10 6 10-6"/></svg>
          <span>Messages</span>
          @if ($sidebarStats['unread_messages'] > 0)
            <span class="nav-badge nav-badge--gold">{{ $sidebarStats['unread_messages'] }}</span>
          @endif
        </a>
      @else
        <a href="#" class="nav-item nav-item--disabled">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M2 7l10 6 10-6"/></svg>
          <span>Messages</span>
          @if ($sidebarStats['unread_messages'] > 0)
            <span class="nav-badge nav-badge--gold">{{ $sidebarStats['unread_messages'] }}</span>
          @endif
        </a>
      @endif

      <a href="{{ route('admin.newsletter.index') }}"
         class="nav-item {{ request()->routeIs('admin.newsletter.*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 6l-10 7L2 6"/><rect x="2" y="4" width="20" height="16" rx="2"/></svg>
        <span>Newsletter</span>
        @if ($sidebarStats['subscribers'] > 0)
          <span class="nav-badge">{{ $sidebarStats['subscribers'] }}</span>
        @endif
      </a>

      {{-- ---------- System ---------- --}}
      <p class="nav-heading">System</p>

      <a href="{{ route('admin.countries.index') }}"
         class="nav-item {{ request()->routeIs('admin.countries.*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a15 15 0 010 18"/></svg>
        <span>Countries</span>
        <span class="nav-badge">{{ $sidebarStats['gcc_markets'] }}</span>
      </a>

      <a href="{{ route('admin.settings.index') }}"
         class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.6 1.6 0 00.3 1.8l.1.1a2 2 0 11-2.8 2.8l-.1-.1a1.6 1.6 0 00-1.8-.3 1.6 1.6 0 00-1 1.5V21a2 2 0 11-4 0v-.1a1.6 1.6 0 00-1-1.5 1.6 1.6 0 00-1.8.3l-.1.1a2 2 0 11-2.8-2.8l.1-.1a1.6 1.6 0 00.3-1.8 1.6 1.6 0 00-1.5-1H3a2 2 0 110-4h.1a1.6 1.6 0 001.5-1 1.6 1.6 0 00-.3-1.8l-.1-.1a2 2 0 112.8-2.8l.1.1a1.6 1.6 0 001.8.3h.1a1.6 1.6 0 001-1.5V3a2 2 0 114 0v.1a1.6 1.6 0 001 1.5 1.6 1.6 0 001.8-.3l.1-.1a2 2 0 112.8 2.8l-.1.1a1.6 1.6 0 00-.3 1.8v.1a1.6 1.6 0 001.5 1H21a2 2 0 110 4h-.1a1.6 1.6 0 00-1.5 1z"/></svg>
        <span>Site Settings</span>
      </a>
    </nav>

    <div class="sidebar-footer">
      <a href="{{ route('home') }}" target="_blank" rel="noopener" class="nav-item nav-item--muted">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><path d="M15 3h6v6M10 14L21 3"/></svg>
        <span>View Website</span>
      </a>
    </div>
  </aside>

  {{-- ================= MAIN ================= --}}
  <div class="admin-main">

    <header class="admin-topbar">
      <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar" aria-expanded="false">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
      </button>

      <div class="topbar-title">
        <h1>@yield('page_title', 'Dashboard')</h1>
        <p>@yield('page_subtitle', 'Welcome back to your control centre')</p>
      </div>

      <div class="topbar-actions">
        @if (($sidebarStats['unread_messages'] ?? 0) > 0)
          <a href="#" class="topbar-icon" title="{{ $sidebarStats['unread_messages'] }} unread messages">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.7 21a2 2 0 01-3.4 0"/></svg>
            <span class="dot"></span>
          </a>
        @endif

        <div class="topbar-user" id="userMenuWrap">
          <button type="button" class="user-chip" id="userMenuBtn" aria-haspopup="true" aria-expanded="false">

            <span class="user-avatar">
              <span class="user-avatar__initial">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
              </span>
              @if (! empty(auth()->user()->avatar))
                <span class="user-avatar__img"
                      style="background-image:url('{{ asset(auth()->user()->avatar) }}')"></span>
              @endif
            </span>

            <span class="user-info">
              <strong>{{ auth()->user()->name ?? 'Admin' }}</strong>
              
            </span>

            <svg class="user-caret" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
              <path d="M6 9l6 6 6-6"/>
            </svg>

          </button>

          <div class="user-dropdown" id="userDropdown">
            <div class="user-dropdown__head">
              <strong>{{ auth()->user()->name ?? 'Admin' }}</strong>
              <span>{{ auth()->user()->role === 'admin' ? 'Administrator' : ucfirst(auth()->user()->role ?? 'User') }}</span>
              <span>{{ auth()->user()->email ?? '' }}</span>
            </div>
            <a href="{{ route('admin.account.index') }}" class="user-dropdown__item">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.6 1.6 0 00.3 1.8l.1.1a2 2 0 11-2.8 2.8l-.1-.1a1.6 1.6 0 00-1.8-.3 1.6 1.6 0 00-1 1.5V21a2 2 0 11-4 0v-.1a1.6 1.6 0 00-1-1.5 1.6 1.6 0 00-1.8.3l-.1.1a2 2 0 11-2.8-2.8l.1-.1a1.6 1.6 0 00.3-1.8 1.6 1.6 0 00-1.5-1H3a2 2 0 110-4h.1a1.6 1.6 0 001.5-1 1.6 1.6 0 00-.3-1.8l-.1-.1a2 2 0 112.8-2.8l.1.1a1.6 1.6 0 001.8.3h.1a1.6 1.6 0 001-1.5V3a2 2 0 114 0v.1a1.6 1.6 0 001 1.5 1.6 1.6 0 001.8-.3l.1-.1a2 2 0 112.8 2.8l-.1.1a1.6 1.6 0 00-.3 1.8v.1a1.6 1.6 0 001.5 1H21a2 2 0 110 4h-.1a1.6 1.6 0 00-1.5 1z"/></svg>
              Account Settings
            </a>
           <a href="{{ route('admin.users.index') }}" class="user-dropdown__item">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 00-3-3.87"/>
                <path d="M16 3.13a4 4 0 010 7.75"/>
              </svg>
              Manage Users
            </a>
            <a href="{{ route('home') }}" target="_blank" rel="noopener" class="user-dropdown__item">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><path d="M15 3h6v6M10 14L21 3"/></svg>
              View Website
            </a>

            <form method="POST" action="{{ route('admin.logout') }}" class="user-dropdown__form">
              @csrf
              <button type="submit" class="user-dropdown__item user-dropdown__item--danger">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><path d="M16 17l5-5-5-5M21 12H9"/></svg>
                Sign Out
              </button>
            </form>
          </div>
        </div>
      </div>
    </header>

    <main class="admin-content">
      @yield('content')
    </main>

    <footer class="admin-footer">
      <span>© {{ date('Y') }} Three Brothers Enterprises. Admin Panel v1.0</span>
      <span class="made">Built with <b>Laravel {{ app()->version() }}</b></span>
    </footer>
  </div>

  <div class="sidebar-overlay" id="sidebarOverlay"></div>
</div>

  {{-- ============================================================
       GLOBAL DELETE CONFIRMATION MODAL
       Any form with class "js-delete-form" will use this.
       The delete button needs: class="js-delete-btn"
       and can pass: data-item-name, data-item-type
       ============================================================ --}}
  <div class="modal" id="deleteModal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="deleteModalTitle">
    <div class="modal__backdrop" data-close-modal></div>

    <div class="modal__box">
      <div class="modal__icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M3 6h18M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
          <path d="M10 11v6M14 11v6"/>
        </svg>
      </div>

      <h3 class="modal__title" id="deleteModalTitle">Delete item?</h3>

      <p class="modal__message" id="deleteModalMessage">
        This action cannot be undone.
      </p>

      <div class="modal__actions">
        <button type="button" class="modal__btn modal__btn--cancel" data-close-modal>
          Cancel
        </button>
        <button type="button" class="modal__btn modal__btn--danger" id="deleteModalConfirm">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4">
            <path d="M3 6h18M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
          </svg>
          Yes, delete
        </button>
      </div>
    </div>
  </div>

    {{-- ============================================================
       GENERIC CONFIRMATION MODAL
       ============================================================ --}}
  <div class="modal" id="confirmModal" aria-hidden="true" role="dialog" aria-modal="true">
    <div class="modal__backdrop" data-close-confirm></div>
    <div class="modal__box">
      <div class="modal__icon" id="confirmModalIcon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="12" r="10"/>
          <path d="M12 8v5M12 16h.01"/>
        </svg>
      </div>
      <h3 class="modal__title" id="confirmModalTitle">Are you sure?</h3>
      <p class="modal__message" id="confirmModalMessage">This action cannot be undone.</p>
      <div class="modal__actions">
        <button type="button" class="modal__btn modal__btn--cancel" data-close-confirm>Cancel</button>
        <button type="button" class="modal__btn modal__btn--primary" id="confirmModalOk">Confirm</button>
      </div>
    </div>
  </div>

  {{-- ============================================================
       GENERIC PROMPT MODAL (single-line text input)
       ============================================================ --}}
  <div class="modal" id="promptModal" aria-hidden="true" role="dialog" aria-modal="true">
    <div class="modal__backdrop" data-close-prompt></div>
    <div class="modal__box">
      <div class="modal__icon modal__icon--info">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M12 20h9"/>
          <path d="M16.5 3.5a2.1 2.1 0 013 3L7 19l-4 1 1-4z"/>
        </svg>
      </div>
      <h3 class="modal__title" id="promptModalTitle">Enter a value</h3>
      <p class="modal__message" id="promptModalMessage" style="margin-bottom:16px;display:none;"></p>
      <div class="field" style="text-align:left;margin-bottom:22px;">
        <input type="text" id="promptModalInput" class="setting-input" style="width:100%;">
      </div>
      <div class="modal__actions">
        <button type="button" class="modal__btn modal__btn--cancel" data-close-prompt>Cancel</button>
        <button type="button" class="modal__btn modal__btn--primary" id="promptModalOk">OK</button>
      </div>
    </div>
  </div>

<!-- Scripts -->
<script src="{{ asset('js/deleteModal.js') }}"></script>

<script>
/* ============================================================
   ADMIN SHELL — sidebar toggle + user dropdown
   ============================================================ */
(function () {
  const shell     = document.getElementById('adminShell');
  const sidebar   = document.getElementById('adminSidebar');
  const overlay   = document.getElementById('sidebarOverlay');
  const toggleBtn = document.getElementById('sidebarToggle');

  /* ---------- Sidebar toggle (mobile) ---------- */
  function openSidebar() {
    shell.classList.add('sidebar-open');
    if (toggleBtn) toggleBtn.setAttribute('aria-expanded', 'true');
  }
  function closeSidebar() {
    shell.classList.remove('sidebar-open');
    if (toggleBtn) toggleBtn.setAttribute('aria-expanded', 'false');
  }

  if (toggleBtn) {
    toggleBtn.addEventListener('click', () => {
      shell.classList.contains('sidebar-open') ? closeSidebar() : openSidebar();
    });
  }
  if (overlay) overlay.addEventListener('click', closeSidebar);

  /* Auto-close sidebar when a nav link is clicked on mobile */
  document.querySelectorAll('.sidebar-nav a').forEach(a => {
    a.addEventListener('click', () => {
      if (window.innerWidth <= 1024) closeSidebar();
    });
  });

  /* Escape key closes sidebar */
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeSidebar();
  });

  /* ---------- User dropdown ---------- */
  const userWrap = document.getElementById('userMenuWrap');
  const userBtn  = document.getElementById('userMenuBtn');

  if (userBtn && userWrap) {
    userBtn.addEventListener('click', e => {
      e.stopPropagation();
      const open = userWrap.classList.toggle('open');
      userBtn.setAttribute('aria-expanded', open);
    });

    document.addEventListener('click', e => {
      if (! userWrap.contains(e.target)) {
        userWrap.classList.remove('open');
        userBtn.setAttribute('aria-expanded', 'false');
      }
    });

    document.addEventListener('keydown', e => {
      if (e.key === 'Escape') {
        userWrap.classList.remove('open');
        userBtn.setAttribute('aria-expanded', 'false');
      }
    });
  }
})();


  /* ============================================================
     window.tbe — Promise-based confirm() + prompt()
     Both replace the native browser dialogs with styled modals.
     ============================================================ */
  const confirmModal    = document.getElementById('confirmModal');
  const confirmTitle    = document.getElementById('confirmModalTitle');
  const confirmMessage  = document.getElementById('confirmModalMessage');
  const confirmOk       = document.getElementById('confirmModalOk');
  const confirmIcon     = document.getElementById('confirmModalIcon');

  const promptModal     = document.getElementById('promptModal');
  const promptTitle     = document.getElementById('promptModalTitle');
  const promptMessage   = document.getElementById('promptModalMessage');
  const promptInput     = document.getElementById('promptModalInput');
  const promptOk        = document.getElementById('promptModalOk');

  let confirmResolve = null;
  let promptResolve  = null;

  /* ---------- Confirm ---------- */
  function showConfirm(opts = {}) {
    return new Promise(resolve => {
      confirmResolve = resolve;

      confirmTitle.textContent   = opts.title || 'Are you sure?';
      confirmMessage.innerHTML   = opts.message || 'This action cannot be undone.';
      confirmOk.textContent      = opts.okText || 'Confirm';

      const isDanger = opts.variant === 'danger';
      confirmOk.className = 'modal__btn ' + (isDanger ? 'modal__btn--danger' : 'modal__btn--primary');

      // Swap icon colour scheme
      if (confirmIcon) {
        confirmIcon.style.background = isDanger
          ? 'rgba(185,28,28,.08)'
          : 'rgba(201,162,39,.12)';
        confirmIcon.style.borderColor = isDanger
          ? 'rgba(185,28,28,.18)'
          : 'rgba(201,162,39,.28)';
        confirmIcon.style.color = isDanger ? '#DC2626' : '#C9A227';
      }

      confirmModal.classList.add('open');
      confirmModal.setAttribute('aria-hidden', 'false');
      document.body.style.overflow = 'hidden';
      setTimeout(() => confirmOk.focus(), 60);
    });
  }

  function closeConfirm(result) {
    confirmModal.classList.remove('open');
    confirmModal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
    if (confirmResolve) { confirmResolve(result); confirmResolve = null; }
  }

  if (confirmOk) {
    confirmOk.addEventListener('click', () => closeConfirm(true));
    confirmModal.querySelectorAll('[data-close-confirm]').forEach(el => {
      el.addEventListener('click', () => closeConfirm(false));
    });
  }

  /* ---------- Prompt ---------- */
  function showPrompt(opts = {}) {
    return new Promise(resolve => {
      promptResolve = resolve;

      promptTitle.textContent = opts.title || 'Enter a value';
      promptInput.value       = opts.value || '';
      promptInput.placeholder = opts.placeholder || '';
      promptOk.textContent    = opts.okText || 'OK';

      if (opts.message) {
        promptMessage.textContent = opts.message;
        promptMessage.style.display = 'block';
      } else {
        promptMessage.style.display = 'none';
      }

      promptModal.classList.add('open');
      promptModal.setAttribute('aria-hidden', 'false');
      document.body.style.overflow = 'hidden';
      setTimeout(() => { promptInput.focus(); promptInput.select(); }, 60);
    });
  }

  function closePrompt(result) {
    promptModal.classList.remove('open');
    promptModal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
    if (promptResolve) { promptResolve(result); promptResolve = null; }
  }

  if (promptOk) {
    promptOk.addEventListener('click', () => closePrompt(promptInput.value));
    promptModal.querySelectorAll('[data-close-prompt]').forEach(el => {
      el.addEventListener('click', () => closePrompt(null));
    });

    promptInput.addEventListener('keydown', (e) => {
      if (e.key === 'Enter') {
        e.preventDefault();
        closePrompt(promptInput.value);
      }
    });
  }

  /* ---------- Global ESC handling ---------- */
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      if (confirmModal?.classList.contains('open')) closeConfirm(false);
      if (promptModal?.classList.contains('open'))  closePrompt(null);
    }
  });

  /* ---------- Expose the API ---------- */
  window.tbe = {
    confirm: showConfirm,
    prompt:  showPrompt,
  };

</script>

@stack('scripts')
</body>
</html>