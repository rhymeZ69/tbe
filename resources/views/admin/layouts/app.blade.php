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
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@stack('styles')
</head>
<body>

<div class="admin-shell" id="adminShell">

  {{-- ================= SIDEBAR ================= --}}
  <aside class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-brand">
      <div class="brand-mark">TBE</div>
      <div class="brand-text">
        <strong>Three Brothers</strong>
        <span>Admin Panel</span>
      </div>
    </div>

    <nav class="sidebar-nav">
      <p class="nav-heading">Overview</p>
      <a href="{{ route('admin.dashboard') }}"
         class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/></svg>
        <span>Dashboard</span>
      </a>

      <p class="nav-heading">Catalogue</p>
      <a href="#" class="nav-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16v6H4zM4 14h16v6H4z"/></svg>
        <span>Categories</span>
        <span class="nav-badge">{{ $stats['categories'] ?? 0 }}</span>
      </a>
      <a href="#" class="nav-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 00-1-1.7l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.7l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/><path d="M3.3 7L12 12l8.7-5"/></svg>
        <span>Products</span>
        <span class="nav-badge">{{ $stats['products'] ?? 0 }}</span>
      </a>

      <p class="nav-heading">Content</p>
      <a href="#" class="nav-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M10 8l6 4-6 4V8z"/></svg>
        <span>Video Tours</span>
        <span class="nav-badge">{{ $stats['video_tours'] ?? 0 }}</span>
      </a>
      <a href="#" class="nav-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l8 4v6c0 5-3.4 8.8-8 10-4.6-1.2-8-5-8-10V6z"/></svg>
        <span>Certifications</span>
        <span class="nav-badge">{{ $stats['certifications'] ?? 0 }}</span>
      </a>
      <a href="#" class="nav-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
        <span>Testimonials</span>
        <span class="nav-badge">{{ $stats['testimonials'] ?? 0 }}</span>
      </a>
      <a href="#" class="nav-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>
        <span>Pages</span>
      </a>
      <a href="#" class="nav-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2"/><circle cx="8.5" cy="10" r="1.5"/><path d="M21 15l-5-5L5 19"/></svg>
        <span>Banners</span>
      </a>

      <p class="nav-heading">Leads</p>
      <a href="#" class="nav-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16v6H4zM4 14h16v6H4z"/><path d="M8 7h.01M8 17h.01"/></svg>
        <span>Quote Enquiries</span>
        @if(($stats['new_enquiries'] ?? 0) > 0)
          <span class="nav-badge nav-badge--gold">{{ $stats['new_enquiries'] }}</span>
        @endif
      </a>
      <a href="#" class="nav-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M2 7l10 6 10-6"/></svg>
        <span>Messages</span>
        @if(($stats['unread_messages'] ?? 0) > 0)
          <span class="nav-badge nav-badge--gold">{{ $stats['unread_messages'] }}</span>
        @endif
      </a>
      <a href="#" class="nav-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 6l-10 7L2 6"/><rect x="2" y="4" width="20" height="16" rx="2"/></svg>
        <span>Newsletter</span>
        <span class="nav-badge">{{ $stats['subscribers'] ?? 0 }}</span>
      </a>

      <p class="nav-heading">System</p>
      <a href="#" class="nav-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 8v8M8 12h8"/></svg>
        <span>Countries</span>
        <span class="nav-badge">{{ $stats['gcc_markets'] ?? 0 }}</span>
      </a>
      <a href="#" class="nav-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.6 1.6 0 00.3 1.8l.1.1a2 2 0 11-2.8 2.8l-.1-.1a1.6 1.6 0 00-1.8-.3 1.6 1.6 0 00-1 1.5V21a2 2 0 11-4 0v-.1a1.6 1.6 0 00-1-1.5 1.6 1.6 0 00-1.8.3l-.1.1a2 2 0 11-2.8-2.8l.1-.1a1.6 1.6 0 00.3-1.8 1.6 1.6 0 00-1.5-1H3a2 2 0 110-4h.1a1.6 1.6 0 001.5-1 1.6 1.6 0 00-.3-1.8l-.1-.1a2 2 0 112.8-2.8l.1.1a1.6 1.6 0 001.8.3h.1a1.6 1.6 0 001-1.5V3a2 2 0 114 0v.1a1.6 1.6 0 001 1.5 1.6 1.6 0 001.8-.3l.1-.1a2 2 0 112.8 2.8l-.1.1a1.6 1.6 0 00-.3 1.8v.1a1.6 1.6 0 001.5 1H21a2 2 0 110 4h-.1a1.6 1.6 0 00-1.5 1z"/></svg>
        <span>Site Settings</span>
      </a>
    </nav>

    <div class="sidebar-footer">
      <a href="{{ route('home') }}" target="_blank" class="nav-item nav-item--muted">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><path d="M15 3h6v6M10 14L21 3"/></svg>
        <span>View Website</span>
      </a>
    </div>
  </aside>

  {{-- ================= MAIN ================= --}}
  <div class="admin-main">

    <header class="admin-topbar">
      <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
      </button>

      <div class="topbar-title">
        <h1>@yield('page_title', 'Dashboard')</h1>
        <p>@yield('page_subtitle', 'Welcome back to your control centre')</p>
      </div>

      <div class="topbar-actions">
        @if(($stats['unread_messages'] ?? 0) > 0)
          <a href="#" class="topbar-icon" title="{{ $stats['unread_messages'] }} unread messages">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.7 21a2 2 0 01-3.4 0"/></svg>
            <span class="dot"></span>
          </a>
        @endif

        <div class="topbar-user">
          <div class="user-avatar">A</div>
          <div class="user-info">
            <strong>Admin</strong>
            <span>Administrator</span>
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

<script>
/* Sidebar toggle for mobile */
const sidebar   = document.getElementById('adminSidebar');
const overlay   = document.getElementById('sidebarOverlay');
const toggleBtn = document.getElementById('sidebarToggle');
const shell     = document.getElementById('adminShell');

function openSidebar()  { shell.classList.add('sidebar-open'); }
function closeSidebar() { shell.classList.remove('sidebar-open'); }

toggleBtn.addEventListener('click', () => {
  shell.classList.toggle('sidebar-open');
});
overlay.addEventListener('click', closeSidebar);

/* Auto-close on nav click (mobile) */
document.querySelectorAll('.sidebar-nav a').forEach(a => {
  a.addEventListener('click', () => {
    if (window.innerWidth <= 1024) closeSidebar();
  });
});
</script>
@stack('scripts')
</body>
</html>