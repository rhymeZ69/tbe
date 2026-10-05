<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="theme-color" content="#0A1F44">

<title>{{ $page->meta_title ?: $page->title }} — Three Brothers Enterprises</title>
<meta name="description" content="{{ $page->meta_description ?: $page->excerpt }}">

<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/logo.png') }}">
<link rel="apple-touch-icon" href="{{ asset('img/logo.png') }}">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>
<body class="page-body">

{{-- ================= HEADER (simplified) ================= --}}
<header class="site-header" id="header">
  <div class="container">
    <nav class="nav">
      <a href="{{ route('home') }}" class="logo">
        <img src="{{ asset('img/logo.png') }}" alt="Three Brothers Enterprises" class="logo-img" width="46" height="46" loading="eager">
        <div class="logo-text">
          <strong>Three Brothers</strong>
          <span>Enterprises</span>
        </div>
      </a>

      <ul class="nav-links">
        <li><a href="{{ route('home') }}">Home</a></li>
        <li><a href="{{ route('home') }}#about">About</a></li>
        <li><a href="{{ route('home') }}#infrastructure">Infrastructure</a></li>
        <li><a href="{{ route('home') }}#products">Products</a></li>
        <li><a href="{{ route('home') }}#contact">Contact</a></li>
      </ul>

      <div class="nav-cta">
        <a href="{{ route('home') }}#contact" class="btn btn-gold">
          Request a Quote
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </a>
      </div>
    </nav>
  </div>
</header>

{{-- ================= HERO / BANNER ================= --}}
<section class="page-hero">
  @if ($page->featured_image)
    <div class="page-hero__image" style="background-image: url('{{ asset($page->featured_image) }}')"></div>
    <div class="page-hero__overlay"></div>
  @endif

  <div class="container page-hero__inner">
    <nav class="page-breadcrumb" aria-label="Breadcrumb">
      <a href="{{ route('home') }}">Home</a>
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M9 6l6 6-6 6"/></svg>
      <span>{{ $page->title }}</span>
    </nav>

    <h1 class="page-hero__title">{{ $page->title }}</h1>

    @if ($page->excerpt)
      <p class="page-hero__excerpt">{{ $page->excerpt }}</p>
    @endif

    @if ($page->published_at)
      <span class="page-hero__date">
        Published {{ $page->published_at->format('F j, Y') }}
      </span>
    @endif
  </div>
</section>

{{-- ================= CONTENT ================= --}}
<article class="page-content">
  <div class="container page-content__inner">
    {!! $page->content ?: '<p><em>This page has no content yet.</em></p>' !!}
  </div>
</article>

{{-- ================= CTA BAND ================= --}}
<section class="page-cta">
  <div class="container">
    <div class="cta-band">
      <div>
        <h3>Ready to import premium Pakistani products?</h3>
        <p>Send us your product specification and destination port — we'll come back with pricing and lead time.</p>
      </div>
      <div class="actions">
        <a href="{{ route('home') }}#contact" class="btn btn-gold">
          Request a Quote
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </a>
        <a href="https://wa.me/{{ setting('contact_whatsapp', '923000000000') }}" class="btn btn-ghost" target="_blank" rel="noopener">WhatsApp Us</a>
      </div>
    </div>
  </div>
</section>

{{-- ================= FOOTER (simplified) ================= --}}
<footer>
  <div class="container">
    <div class="foot-bottom" style="border-top:0;padding:36px 0;">
      <span>© {{ date('Y') }} Three Brothers Enterprises. All rights reserved.</span>
      <span class="made">Multi-Product Exporter · <b>Pakistan → GCC &amp; Worldwide</b></span>
    </div>
  </div>
</footer>

<script>
  /* Sticky header */
  const header = document.getElementById('header');
  window.addEventListener('scroll', () => {
    header.classList.toggle('scrolled', window.scrollY > 30);
  }, { passive: true });
</script>

</body>
</html>