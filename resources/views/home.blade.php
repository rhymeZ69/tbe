<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>{{ setting('meta_title', 'Three Brothers Enterprises') }}</title>
<meta name="description" content="{{ setting('meta_description') }}">
<meta name="theme-color" content="#0A1F44">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>
<body>

<!-- ================= TOP BAR ================= -->
<div class="topbar">
  <div class="container">
    <div class="topbar-left">
      <span style="display:inline-flex;align-items:center;gap:7px;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a15 15 0 010 18 15 15 0 010-18"/></svg>
        Meat · Rice · Garments · Vegetables — Exported from Pakistan to the World
      </span>
    </div>
    <div class="topbar-right">
      <a href="tel:{{ setting('contact_phone', 'No phone number available') }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.9v3a2 2 0 01-2.2 2 19.8 19.8 0 01-8.6-3.1 19.5 19.5 0 01-6-6A19.8 19.8 0 012.1 4.2 2 2 0 014.1 2h3a2 2 0 012 1.7c.1 1 .4 1.9.7 2.8a2 2 0 01-.5 2.1L8.1 9.9a16 16 0 006 6l1.3-1.2a2 2 0 012.1-.5c.9.3 1.8.6 2.8.7a2 2 0 011.7 2z"/></svg>
        {{ setting('contact_phone', 'No phone number available') }}
      </a>
      <a href="mailto:{{ setting('contact_email', 'No email address available') }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M2 7l10 6 10-6"/></svg>
        {{ setting('contact_email', 'No email address available') }}
      </a>
    </div>
  </div>
</div>

<!-- ================= HEADER ================= -->
<header class="site-header" id="header">
  <div class="container">
    <nav class="nav">
        <a href="#home" class="logo" aria-label="Three Brothers Enterprises home">
        <img src="{{ asset('img/logo.png') }}"
            alt="Three Brothers Enterprises logo"
            class="logo-img"
            width="46" height="46"
            loading="eager">

        @php
            $fullName = setting('site_name', 'Three Brothers Enterprises');
            $parts = explode(' ', $fullName, 2);
            $first = $parts[0] ?? 'Three Brothers';
            $second = $parts[1] ?? 'Enterprises';
        @endphp

        <div class="logo-text">
            <strong>{{ $first }}</strong>
            <span>{{ $second }}</span>
        </div>
        </a>

      <ul class="nav-links" id="navLinks">
        <li><a href="#home" class="active">Home</a></li>
        <li><a href="#about">About</a></li>
        <li><a href="#infrastructure">Infrastructure</a></li>
        <li><a href="#products">Products</a></li>
        <li><a href="#markets">Markets</a></li>
        <li><a href="#contact">Contact</a></li>
      </ul>

      <div class="nav-cta">
        <a href="#contact" class="btn btn-gold">
          Request a Quote
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </a>
        <button class="burger" id="burger" aria-label="Toggle navigation" aria-expanded="false">
          <span></span><span></span><span></span>
        </button>
      </div>
    </nav>
  </div>
</header>

<!-- ================= HERO ================= -->
<section class="hero" id="home">
  <div class="hero-bg"></div>
  <div class="container hero-inner">
    <div class="hero-copy">
      <span class="pill"><span class="dot"></span> Pakistan → GCC &amp; Worldwide</span>
      <h1>{!! setting('site_tagline', 'Premium Halal Meat, Rice, Garments & Fresh Vegetables') !!}</h1>
        <p>{{ setting('site_description') }}</p>

      <div class="hero-actions">
        <a href="#contact" class="btn btn-gold">
          Get Export Pricing
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </a>
        <a href="#infrastructure" class="btn btn-ghost">See Our Infrastructure</a>
      </div>

      <ul class="hero-trust">
        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M20 6L9 17l-5-5"/></svg> 100% Halal Certified</li>
        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M20 6L9 17l-5-5"/></svg> HACCP Compliant</li>
        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M20 6L9 17l-5-5"/></svg> Air &amp; Sea Freight</li>
      </ul>
    </div>

    <aside class="hero-card">
      <div class="seal">
        <div>
          <b>100%</b>
          <small>Halal</small>
        </div>
      </div>
      <h3>Our Export Portfolio</h3>
      <p class="sub">Four product lines · One trusted export partner</p>
      <ul class="spec">
        <li>
          <span class="k">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="8"/><circle cx="12" cy="12" r="3"/></svg>
            Fresh &amp; Chilled Meat
          </span>
          <span class="v">Beef · Mutton</span>
        </li>
        <li>
          <span class="k">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 4l4 2 4-2 4 3-2 3v10H6V10L4 7z"/></svg>
            Ready Made Garments
          </span>
          <span class="v">Apparel</span>
        </li>
        <li>
          <span class="k">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v18M5 9c2 0 3.5 1.5 3.5 3.5S7 16 5 16M19 9c-2 0-3.5 1.5-3.5 3.5S17 16 19 16"/></svg>
            Premium Rice
          </span>
          <span class="v">Basmati · IRRI</span>
        </li>
        <li>
          <span class="k">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 20A7 7 0 019.8 6.1C15.5 5 17 4.5 19 2c1 2 2 4.2 2 8 0 5.5-4.8 10-10 10z"/><path d="M2 21c0-3 1.9-6 5-7"/></svg>
            Fresh Vegetables
          </span>
          <span class="v">Onion · Potato</span>
        </li>
      </ul>
    </aside>
  </div>
</section>

<!-- ================= STATS ================= -->
<div class="stats">
  <div class="container">
    <div class="stat reveal">
      <div class="num" data-count="{{ $stats['categories'] }}" data-suffix="">0</div>
      <div class="lbl">Export Categories</div>
    </div>
    <div class="stat reveal d1">
      <div class="num" data-count="{{ $stats['markets'] }}" data-suffix="">0</div>
      <div class="lbl">GCC Markets</div>
    </div>
    <div class="stat reveal d2">
      <div class="num" data-count="{{ $stats['capacity'] }}" data-suffix="+">0</div>
      <div class="lbl">MT Monthly Capacity</div>
    </div>
    <div class="stat reveal d3">
      <div class="num" data-count="{{ $stats['halal'] }}" data-suffix="%">0</div>
      <div class="lbl">Halal Certified</div>
    </div>
  </div>
</div>

<!-- ================= ABOUT ================= -->
<section class="about" id="about">
  <div class="container about-grid">
    <div class="about-visual reveal">
      <div class="about-panel">
        <h3>Rooted in Pakistan. Trusted Worldwide.</h3>
        <p>
          Three Brothers Enterprises was founded on a simple promise: to deliver Pakistan's finest
          products to the world with uncompromising quality and honesty at every step of the supply
          chain — from livestock selection and farming to final hygienic delivery.
        </p>
        <div class="mini-grid">
          <div class="mini"><b>4</b><span>Product Lines</span></div>
          <div class="mini"><b>6</b><span>GCC Destinations</span></div>
          <div class="mini"><b>0–4°C</b><span>Chilled Chain</span></div>
          <div class="mini"><b>A+</b><span>Quality Grade</span></div>
        </div>
      </div>
      <div class="badge-float">
        <div class="icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l2.6 6.6L21 11l-6.4 2.4L12 20l-2.6-6.6L3 11l6.4-2.4z"/></svg>
        </div>
        <div>
          <b>Full Infrastructure</b>
          <span>Livestock to Delivery</span>
        </div>
      </div>
    </div>

    <div class="reveal d1">
      <div class="sec-head" style="margin-bottom:0">
        <span class="eyebrow">Who We Are</span>
        <h2 class="sec-title">A Complete Export House, Built on Trust</h2>
        <p class="sec-sub">
          We are a Pakistan-based multi-product export house supplying fresh and chilled halal beef and
          mutton, ready made garments, premium rice and fresh vegetables to importers, distributors,
          hotels and hypermarkets across the GCC and beyond. Every product line is backed by our own
          infrastructure, certified processes and a strictly monitored supply chain — so it arrives at
          your door exactly as it left ours.
        </p>
      </div>

      <ul class="about-list">
        <li>
          <span class="tick"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M20 6L9 17l-5-5"/></svg></span>
          <div>
            <b>Own Infrastructure, End to End</b>
            <p>From livestock rearing and farming to processing, packing and cold-chain logistics — we control every stage ourselves.</p>
          </div>
        </li>
        <li>
          <span class="tick"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M20 6L9 17l-5-5"/></svg></span>
          <div>
            <b>Certified Halal &amp; HACCP</b>
            <p>All meat processing follows Islamic Shariah requirements with full veterinary inspection and HACCP compliance.</p>
          </div>
        </li>
        <li>
          <span class="tick"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M20 6L9 17l-5-5"/></svg></span>
          <div>
            <b>Four Product Lines, One Partner</b>
            <p>Meat, garments, rice and vegetables shipped together — simplifying your sourcing and consolidating your logistics.</p>
          </div>
        </li>
        <li>
          <span class="tick"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M20 6L9 17l-5-5"/></svg></span>
          <div>
            <b>Complete Export Documentation</b>
            <p>Health certificates, Halal certificates, invoices, packing lists and customs paperwork handled end-to-end.</p>
          </div>
        </li>
      </ul>
    </div>
  </div>
</section>

<!-- ================= INFRASTRUCTURE / VIDEO TOUR ================= -->
<section class="infra" id="infrastructure">
  <div class="container">
    <div class="sec-head center reveal">
      <span class="eyebrow">Our Infrastructure</span>
      <h2 class="sec-title">From Livestock to Hygienic Delivery — See It Yourself</h2>
      <p class="sec-sub">
        We believe in full transparency. Watch the journey of our meat — starting at the farm with
        healthy livestock, through certified halal slaughtering, and finishing with hygienic packing
        and cold-chain delivery to destinations worldwide.
      </p>
    </div>

    <div class="v-grid">
        @foreach ($videoTours as $index => $tour)
            @php
            // Pick a gradient class based on the stage number (falls back to stage-1)
            $posterClass = 'stage-' . ($tour->stage_number > 3 ? 3 : $tour->stage_number);

            // Reveal delay class for staggered animation (only first 3 stages get one)
            $revealClass = $index === 0 ? '' : ' d' . min($index, 4);
            @endphp

            <button class="v-card reveal{{ $revealClass }}" type="button"
                    data-video="{{ $tour->video_source }}"
                    data-title="Stage {{ str_pad($tour->stage_number, 2, '0', STR_PAD_LEFT) }} — {{ $tour->title }}"
                    data-desc="{{ $tour->description }}">

            <div class="v-poster {{ $posterClass }}">
                <span class="v-num">{{ str_pad($tour->stage_number, 2, '0', STR_PAD_LEFT) }}</span>
                <span class="v-stage-tag">{{ $tour->stage_tag }}</span>

                <div class="v-scene">
                {{-- Pick an icon per stage number --}}
                @if ($tour->stage_number === 1)
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                    <path d="M4 15c0-3 1.5-5 4-5h8c2.5 0 4 2 4 5v3H4z"/>
                    <path d="M4 15V9l2-3 2 2h8l2-2 2 3v6"/>
                    <circle cx="8.5" cy="17.5" r="1.2" fill="currentColor" stroke="none"/>
                    <circle cx="15.5" cy="17.5" r="1.2" fill="currentColor" stroke="none"/>
                    </svg>
                @elseif ($tour->stage_number === 2)
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                    <path d="M12 2l8 4v6c0 5-3.4 8.8-8 10-4.6-1.2-8-5-8-10V6z"/>
                    <path d="M9 12l2 2 4-4"/>
                    </svg>
                @else
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                    <path d="M21 16V8a2 2 0 00-1-1.7l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.7l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/>
                    <path d="M3.3 7L12 12l8.7-5M12 22V12"/>
                    </svg>
                @endif
                </div>

                <span class="v-play" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                </span>
            </div>

            <div class="v-body">
                <h4>{{ $tour->title }}</h4>
                <p>{{ Str::limit($tour->description, 130) }}</p>
            </div>
            </button>
        @endforeach
        </div>

    <p class="v-note reveal">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 8h.01M11 12h1v4h1"/></svg>
      Click any stage to watch the video tour. Full infrastructure walkthroughs available on request.
    </p>
  </div>
</section>

<!-- ================= PRODUCTS ================= -->
<section class="products" id="products">
  <div class="container">
    <div class="sec-head center reveal">
      <span class="eyebrow">Our Products</span>
      <h2 class="sec-title">Four Export Lines, One Trusted Source</h2>
      <p class="sec-sub">
        Premium quality, carefully sourced and professionally processed. Available in the cuts, grades
        and pack formats your market demands.
      </p>
    </div>

    <div class="prod-grid">
    @foreach ($categories as $index => $category)
        @php
        // Category has one or more products; we show the first (primary) one
        $product = $category->activeProducts->first();
        if (!$product) continue;

        // Reveal delay class
        $revealClass = $index === 0 ? '' : ' d' . min($index, 3);

        // Icon SVG per category slug
        $iconSlug = $category->slug;

          // Pick the primary image (or first available)
        $image = $product->images->firstWhere('is_primary', true) 
                ?? $product->images->first();
        $imageUrl = $image?->url;

        // If an image exists, use it as the background, otherwise fall back to the
        // category's gradient class (e.g. "prod-top garments")
        $topClass = $imageUrl ? 'prod-top has-image' : 'prod-top ' . $category->gradient_class;

        // Inline background-image only when we have a URL
        $topStyle = $imageUrl ? 'style="background-image:url(\'' . e($imageUrl) . '\')"' : '';
        @endphp

        <article class="prod reveal{{ $revealClass }}">
        <div class="{{ $topClass }}" {!! $topStyle !!}>
            <div class="prod-head">
            <span class="prod-tag">{{ $product->tagline ?? $category->tagline }}</span>
            <span class="prod-ico">
                @switch($iconSlug)
                @case('meat')
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="8"/><circle cx="12" cy="12" r="3"/></svg>
                    @break
                @case('garments')
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M8 4l4 2 4-2 4 3-2 3v10H6V10L4 7z"/></svg>
                    @break
                @case('rice')
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3v18M5 9c2 0 3.5 1.5 3.5 3.5S7 16 5 16M19 9c-2 0-3.5 1.5-3.5 3.5S17 16 19 16"/></svg>
                    @break
                @case('vegetables')
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M11 20A7 7 0 019.8 6.1C15.5 5 17 4.5 19 2c1 2 2 4.2 2 8 0 5.5-4.8 10-10 10z"/><path d="M2 21c0-3 1.9-6 5-7"/></svg>
                    @break
                @default
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="8"/></svg>
                @endswitch
            </span>
            </div>
            <h3>{{ $product->name }}</h3>
            <p>{{ $product->short_description }}</p>
        </div>

        <div class="prod-body">
            <h4>Available {{ $category->slug === 'meat' ? 'Cuts' : ($category->slug === 'rice' ? 'Varieties' : 'Range') }}</h4>
            <div class="cuts">
            @foreach ($product->varieties as $variety)
                <span>{{ $variety->name }}</span>
            @endforeach
            </div>

            <div class="prod-spec">
            @foreach ($product->specs as $spec)
                <div><strong>{{ $spec->value }}</strong>{{ $spec->label }}</div>
            @endforeach
            </div>
        </div>
        </article>
    @endforeach
    </div>
  </div>
</section>

<!-- ================= PROCESS ================= -->
<section class="process" id="process">
  <div class="container">
    <div class="sec-head center reveal">
      <span class="eyebrow">Our Process</span>
      <h2 class="sec-title">Controlled at Every Step, Delivered Worldwide</h2>
      <p class="sec-sub">
        Six disciplined stages guarantee that every product you receive is safe, fresh and exactly
        to specification.
      </p>
    </div>

    <div class="steps">
      <div class="step reveal">
        <span class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18M5 21V9l7-5 7 5v12M9 21v-6h6v6"/></svg></span>
        <div class="n">01</div>
        <h4>Sourcing &amp; Farming</h4>
        <p>Healthy livestock, quality crops and trusted garment units selected and vetted against strict criteria.</p>
      </div>
      <div class="step reveal d1">
        <span class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l2.6 6.6L21 11l-6.4 2.4L12 20l-2.6-6.6L3 11l6.4-2.4z"/></svg></span>
        <div class="n">02</div>
        <h4>Halal Slaughter &amp; Harvest</h4>
        <p>Certified halal slaughtering under Shariah supervision; fresh produce harvested and graded at peak quality.</p>
      </div>
      <div class="step reveal d2">
        <span class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M4 6h16M4 18h16"/></svg></span>
        <div class="n">03</div>
        <h4>Rapid Chilling &amp; Sorting</h4>
        <p>Meat chilled immediately to 0°C – 4°C; rice milled and sortex-cleaned; garments quality checked.</p>
      </div>
      <div class="step reveal">
        <span class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16v6H4zM4 14h16v6H4z"/></svg></span>
        <div class="n">04</div>
        <h4>Processing &amp; Customisation</h4>
        <p>Cuts, packing sizes, garment styles and rice grades prepared exactly to your market's specification.</p>
      </div>
      <div class="step reveal d1">
        <span class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M8 7V5a2 2 0 012-2h4a2 2 0 012 2v2"/></svg></span>
        <div class="n">05</div>
        <h4>Hygienic Packing &amp; Labelling</h4>
        <p>Vacuum-packed, bulk-packed or carton-packed with full traceability and market-ready labelling.</p>
      </div>
      <div class="step reveal d2">
        <span class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg></span>
        <div class="n">06</div>
        <h4>Worldwide Delivery</h4>
        <p>Refrigerated transport to airport or port, then air or sea freight to your destination — anywhere in the world.</p>
      </div>
    </div>
  </div>
</section>

<!-- ================= WHY US ================= -->
<section class="why">
  <div class="container">
    <div class="sec-head center reveal">
      <span class="eyebrow">Why Three Brothers</span>
      <h2 class="sec-title">Built for Serious Importers</h2>
      <p class="sec-sub">
        We understand international trade — the standards, the timelines and the paperwork. Here's what
        sets us apart.
      </p>
    </div>

    <div class="why-grid">
      <div class="feature reveal">
        <div class="f-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M12 2l8 4v6c0 5-3.4 8.8-8 10-4.6-1.2-8-5-8-10V6z"/><path d="M9 12l2 2 4-4"/></svg>
        </div>
        <h4>Certified Quality</h4>
        <p>Halal and HACCP-compliant processing with veterinary inspection and full traceability on every consignment.</p>
      </div>

      <div class="feature reveal d1">
        <div class="f-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M3 21h18M5 21V9l7-5 7 5v12M9 21v-6h6v6"/></svg>
        </div>
        <h4>Full Infrastructure</h4>
        <p>From livestock rearing to processing, packing and cold-chain logistics — we own and manage every stage.</p>
      </div>

      <div class="feature reveal d2">
        <div class="f-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M21 16V8a2 2 0 00-1-1.7l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.7l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/><path d="M3.3 7L12 12l8.7-5M12 22V12"/></svg>
        </div>
        <h4>Multi-Product Export</h4>
        <p>Meat, garments, rice and vegetables from one trusted partner — consolidating your sourcing and logistics.</p>
      </div>

      <div class="feature reveal d3">
        <div class="f-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3.5 2"/></svg>
        </div>
        <h4>On-Time Worldwide Delivery</h4>
        <p>Reliable scheduling with trusted freight partners ensures your shipments arrive fresh and on schedule.</p>
      </div>
    </div>
  </div>
</section>

<!-- ================= MARKETS ================= -->
<section class="markets" id="markets">
  <div class="container">
    <div class="sec-head center reveal">
      <span class="eyebrow">Export Destinations</span>
      <h2 class="sec-title">Serving the GCC &amp; Beyond</h2>
      <p class="sec-sub">
        We ship premium halal meat, rice, garments and fresh vegetables across the Gulf Cooperation
        Council — and to markets worldwide.
      </p>
    </div>

    <div class="market-grid">
    @foreach ($gccMarkets as $index => $market)
        <div class="market reveal{{ $index === 0 ? '' : ' d' . min($index, 4) }}">
        <span class="flag">{{ $market->flag_emoji }}</span>
        <b>{{ $market->name }}</b>
        <span>{{ $market->short_label }}</span>
        </div>
    @endforeach
    </div>

    <div style="margin-top:64px" class="reveal">
      <div class="cta-band">
        <div>
          <h3>Ready to import premium Pakistani products?</h3>
          <p>Send us your product specification and destination port — we'll come back with pricing, packing details and lead time.</p>
        </div>
        <div class="actions">
          <a href="#contact" class="btn btn-gold">
            Request a Quote
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
          </a>
          <a href="https://wa.me/{{ setting('contact_whatsapp', '923000000000') }}" class="btn btn-ghost" target="_blank" rel="noopener">WhatsApp Us</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ================= CONTACT ================= -->
<section class="contact" id="contact">
  <div class="container">
    <div class="sec-head center reveal">
      <span class="eyebrow">Get In Touch</span>
      <h2 class="sec-title">Request a Quotation</h2>
      <p class="sec-sub">
        Tell us what you need and where it's going. Our export team responds to all enquiries within
        one business day.
      </p>
    </div>

    <div class="contact-grid">
      <!-- INFO -->
      <div class="info-card reveal">
        <h3>Contact Our Export Desk</h3>
        <p>We're available six days a week to discuss your requirements, samples and shipping schedules.</p>

        <div class="info-row">
          <div class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.9v3a2 2 0 01-2.2 2 19.8 19.8 0 01-8.6-3.1 19.5 19.5 0 01-6-6A19.8 19.8 0 012.1 4.2 2 2 0 014.1 2h3a2 2 0 012 1.7c.1 1 .4 1.9.7 2.8a2 2 0 01-.5 2.1L8.1 9.9a16 16 0 006 6l1.3-1.2a2 2 0 012.1-.5c.9.3 1.8.6 2.8.7a2 2 0 011.7 2z"/></svg></div>
          <div class="txt">
            <span>Phone / WhatsApp</span>
            <b><a href="tel:{{ setting('contact_phone', 'No phone number available') }}">{{ setting('contact_phone', 'No phone number available') }}</a></b>
          </div>
        </div>

        <div class="info-row">
          <div class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M2 7l10 6 10-6"/></svg></div>
          <div class="txt">
            <span>Email</span>
            <b><a href="mailto:{{ setting('contact_email', 'No email address available') }}">{{ setting('contact_email', 'No email address available') }}</a></b>
          </div>
        </div>

        <div class="info-row">
          <div class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 6-9 12-9 12s-9-6-9-12a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg></div>
          <div class="txt">
            <span>Head Office</span>
            <b>Karachi, Sindh, Pakistan</b>
          </div>
        </div>

        <div class="info-row">
          <div class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3.5 2"/></svg></div>
          <div class="txt">
            <span>Business Hours</span>
            <b>Mon – Sat · 9:00 AM – 7:00 PM (PKT)</b>
          </div>
        </div>
      </div>

      <!-- FORM -->
      <div class="form-card reveal d1">
        <h3>Send an Enquiry</h3>
        <p>Fill in the details below and our team will get back to you shortly.</p>

        <form id="quoteForm" action="{{ route('quote.store') }}" method="POST" novalidate>
        @csrf
          <div class="form-grid">
            <div class="field">
              <label for="name">Full Name *</label>
              <input type="text" id="name" name="name" placeholder="Your name" required>
            </div>
            <div class="field">
              <label for="company">Company</label>
              <input type="text" id="company" name="company" placeholder="Company name">
            </div>
            <div class="field">
              <label for="email">Email *</label>
              <input type="email" id="email" name="email" placeholder="you@company.com" required>
            </div>
            <div class="field">
              <label for="phone">Phone / WhatsApp</label>
              <input type="tel" id="phone" name="phone" placeholder="+966 5X XXX XXXX">
            </div>
            <div class="field">
              <label for="country">Destination Country *</label>
              <select id="country" name="country" required>
                <option value="">Select country</option>
                <option>Saudi Arabia</option>
                <option>United Arab Emirates</option>
                <option>Kuwait</option>
                <option>Qatar</option>
                <option>Bahrain</option>
                <option>Oman</option>
                <option>Other</option>
              </select>
            </div>
            <div class="field">
              <label for="product">Product Interest</label>
              <select id="product" name="product">
                <option value="">Select product</option>
                <option>Meat — Beef</option>
                <option>Meat — Mutton</option>
                <option>Meat — Beef &amp; Mutton (Mixed)</option>
                <option>Ready Made Garments</option>
                <option>Premium Rice</option>
                <option>Fresh Vegetables</option>
                <option>Multiple Products</option>
              </select>
            </div>
            <div class="field full">
              <label for="message">Your Requirement</label>
              <textarea id="message" name="message" placeholder="Tell us about the products, quantity (MT / units), packaging and delivery schedule you need..."></textarea>
            </div>
          </div>

          <button type="submit" class="btn btn-navy" style="width:100%;margin-top:24px">
            Submit Enquiry
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M22 2L11 13M22 2l-7 20-4-9-9-4z"/></svg>
          </button>

          <p class="form-note">* Required fields. Your information is kept strictly confidential.</p>
            @if (session('success'))
                <div class="form-success show" id="formSuccess">
                    ✓ {{ session('success') }}
                </div>
            @endif
        </form>
      </div>
    </div>
  </div>
</section>

<!-- ================= FOOTER ================= -->
<footer>
  <div class="container">
    <div class="foot-grid">
      <div class="foot-brand">
        <div class="logo">
        <img src="{{ asset('img/logo.png') }}"
            alt="Three Brothers Enterprises logo"
            class="logo-img"
            width="46" height="46"
            loading="lazy">

        @php
            $fullName = setting('site_name', 'Three Brothers Enterprises');
            $parts = explode(' ', $fullName, 2);
            $first = $parts[0] ?? 'Three Brothers';
            $second = $parts[1] ?? 'Enterprises';
        @endphp

        <div class="logo-text">
            <strong style="color:#fff">{{ $first }}</strong>
            <span>{{ $second }}</span>
        </div>
        </div>
        <p>
          Premium halal meat, ready made garments, rice and fresh vegetables exporter from Pakistan —
          serving importers, distributors and retailers across the GCC and worldwide with certified
          quality and complete infrastructure.
        </p>
        <div class="foot-social">
        @if (setting('social_facebook'))
            <a href="{{ setting('social_facebook') }}" target="_blank" rel="noopener" aria-label="Facebook">
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M13.5 21v-8h2.7l.4-3.1h-3.1V7.9c0-.9.25-1.5 1.55-1.5h1.65V3.6c-.29-.04-1.27-.12-2.4-.12-2.37 0-4 1.45-4 4.1v2.3H7.6V13h2.7v8z"/></svg>
            </a>
        @endif
        @if (setting('social_instagram'))
            <a href="{{ setting('social_instagram') }}" target="_blank" rel="noopener" aria-label="Instagram">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.2" cy="6.8" r="1.1" fill="currentColor" stroke="none"/></svg>
            </a>
        @endif
        @if (setting('contact_whatsapp'))
            <a href="https://wa.me/{{ setting('contact_whatsapp') }}" target="_blank" rel="noopener" aria-label="WhatsApp">
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 00-8.6 15L2 22l5.2-1.4A10 10 0 1012 2zm0 18a8 8 0 01-4.1-1.1l-.3-.2-3 .8.8-2.9-.2-.3A8 8 0 1112 20zm4.4-5.8c-.24-.12-1.4-.7-1.62-.78-.22-.08-.38-.12-.54.12-.16.24-.62.78-.76.94-.14.16-.28.18-.52.06-.24-.12-1-.37-1.92-1.18-.7-.63-1.18-1.4-1.32-1.64-.14-.24-.02-.37.1-.49.1-.1.24-.28.36-.42.12-.14.16-.24.24-.4.08-.16.04-.3-.02-.42-.06-.12-.54-1.3-.74-1.78-.2-.46-.4-.4-.54-.4h-.46c-.16 0-.42.06-.64.3-.22.24-.84.82-.84 2s.86 2.32.98 2.48c.12.16 1.7 2.6 4.12 3.64.58.25 1.02.4 1.37.51.58.18 1.1.16 1.52.1.46-.07 1.4-.57 1.6-1.12.2-.55.2-1.02.14-1.12-.06-.1-.22-.16-.46-.28z"/></svg>
            </a>
        @endif
        @if (setting('contact_email'))
            <a href="mailto:{{ setting('contact_email') }}" aria-label="Email">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M2 7l10 6 10-6"/></svg>
            </a>
        @endif
        </div>
      </div>

      <div>
        <h5>Company</h5>
        <div class="foot-links">
          <a href="#about">About Us</a>
          <a href="#infrastructure">Infrastructure</a>
          <a href="#products">Products</a>
          <a href="#process">Our Process</a>
          <a href="#markets">Export Markets</a>
          <a href="#contact">Contact</a>
        </div>
      </div>

      <div>
        <h5>Products</h5>
        <div class="foot-links">
          <a href="#products">Halal Beef &amp; Mutton</a>
          <a href="#products">Ready Made Garments</a>
          <a href="#products">Premium Rice</a>
          <a href="#products">Fresh Vegetables</a>
          <a href="#products">Custom Packing</a>
        </div>
      </div>

      <div>
        <h5>Get In Touch</h5>
        <ul class="foot-contact">
          <li>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 6-9 12-9 12s-9-6-9-12a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
            Karachi, Sindh, Pakistan
          </li>
          <li>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.9v3a2 2 0 01-2.2 2 19.8 19.8 0 01-8.6-3.1 19.5 19.5 0 01-6-6A19.8 19.8 0 012.1 4.2 2 2 0 014.1 2h3a2 2 0 012 1.7c.1 1 .4 1.9.7 2.8a2 2 0 01-.5 2.1L8.1 9.9a16 16 0 006 6l1.3-1.2a2 2 0 012.1-.5c.9.3 1.8.6 2.8.7a2 2 0 011.7 2z"/></svg>
            <a href="tel:{{ setting('contact_phone', 'No phone number available') }}">{{ setting('contact_phone', 'No phone number available') }}</a>
          </li>
          <li>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M2 7l10 6 10-6"/></svg>
            <a href="mailto:{{ setting('contact_email', 'No email address available') }}">{{ setting('contact_email', 'No email address available') }}</a>
          </li>
        </ul>
      </div>
    </div>

    <div class="foot-bottom">
      <span>© <span id="year"></span> Three Brothers Enterprises. All rights reserved.</span>
      <span class="made">Multi-Product Exporter · <b>Pakistan → GCC &amp; Worldwide</b></span>
    </div>
  </div>
</footer>

<!-- ================= VIDEO MODAL ================= -->
<div class="video-modal" id="videoModal" aria-hidden="true" role="dialog" aria-modal="true">
  <div class="video-modal-backdrop" data-close></div>
  <div class="video-modal-box">
    <button class="video-modal-close" type="button" data-close aria-label="Close video">×</button>
    <div class="video-frame" id="videoFrame"></div>
    <div class="video-meta">
      <h4 id="videoTitle">Video</h4>
      <p id="videoDesc"></p>
    </div>
  </div>
</div>

<!-- ================= FLOATING WHATSAPP ================= -->
<a href="https://wa.me/{{ setting('contact_whatsapp', '923000000000') }}" class="wa-float" target="_blank" rel="noopener" aria-label="Chat on WhatsApp">
  <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 00-8.6 15L2 22l5.2-1.4A10 10 0 1012 2zm0 18a8 8 0 01-4.1-1.1l-.3-.2-3 .8.8-2.9-.2-.3A8 8 0 1112 20zm4.4-5.8c-.24-.12-1.4-.7-1.62-.78-.22-.08-.38-.12-.54.12-.16.24-.62.78-.76.94-.14.16-.28.18-.52.06-.24-.12-1-.37-1.92-1.18-.7-.63-1.18-1.4-1.32-1.64-.14-.24-.02-.37.1-.49.1-.1.24-.28.36-.42.12-.14.16-.24.24-.4.08-.16.04-.3-.02-.42-.06-.12-.54-1.3-.74-1.78-.2-.46-.4-.4-.54-.4h-.46c-.16 0-.42.06-.64.3-.22.24-.84.82-.84 2s.86 2.32.98 2.48c.12.16 1.7 2.6 4.12 3.64.58.25 1.02.4 1.37.51.58.18 1.1.16 1.52.1.46-.07 1.4-.57 1.6-1.12.2-.55.2-1.02.14-1.12-.06-.1-.22-.16-.46-.28z"/></svg>
</a>

<script src="{{ asset('js/home.js') }}" defer></script>

</body>
</html>