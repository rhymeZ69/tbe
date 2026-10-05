<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>{{ setting('meta_title', 'Three Brothers Enterprises') }}</title>
<meta name="description" content="{{ setting('meta_description') }}">
<meta name="theme-color" content="#0A1F44">

{{-- Favicons --}}
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/Three Brothers Global Export Emblem.png') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/Three Brothers Global Export Emblem.png') }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/Three Brothers Global Export Emblem.png') }}">
<link rel="shortcut icon" href="{{ asset('img/Three Brothers Global Export Emblem.png') }}">

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

@if ($topBanners->isNotEmpty())
  @foreach ($topBanners as $banner)
    @include('partials.banner', ['banner' => $banner])
  @endforeach
@endif

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

        <div class="logo-text">
            <strong>Three Brothers</strong>
            <span>Enterprises</span>
        </div>
        </a>

      <ul class="nav-links" id="navLinks">
        <li><a href="#home" class="active">Home</a></li>
        <li><a href="#about">About</a></li>

        @if ($videoTours->isNotEmpty())
          <li><a href="#infrastructure">Infrastructure</a></li>
        @endif

        @if ($categories->isNotEmpty())
          <li><a href="#products">Products</a></li>
        @endif

        @if ($gccMarkets->isNotEmpty())
          <li><a href="#markets">Markets</a></li>
        @endif

        @if ($pages->isNotEmpty())
          <li class="has-dropdown">
            <button type="button" class="nav-dropdown-trigger" aria-haspopup="true" aria-expanded="false">
              Company
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M6 9l6 6 6-6"/>
              </svg>
            </button>

            <div class="nav-dropdown">
              @foreach ($pages as $p)
                <a href="{{ route('page.show', $p->slug) }}" class="nav-dropdown__item">
                  {{ $p->title }}
                </a>
              @endforeach
            </div>
          </li>
        @endif

        <li>
          <button type="button" class="nav-link-button" data-open-contact>
            Contact
          </button>
        </li>
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

@if ($heroBanners->isNotEmpty())
  @foreach ($heroBanners as $banner)
    @include('partials.banner', ['banner' => $banner])
  @endforeach
@else

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

      @if ($certifications->isNotEmpty())
        <ul class="hero-trust">
          @foreach ($certifications->take(4) as $cert)
            <li>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                <path d="M20 6L9 17l-5-5"/>
              </svg>
              {{ $cert->name }}
            </li>
          @endforeach
        </ul>
      @endif
    </div>

    <aside class="hero-card">
      <div class="seal">
        <div>
          <b>100%</b>
          <small>Halal</small>
        </div>
      </div>
      <h3>Our Export Portfolio</h3>
      <p class="sub">
        {{ $stats['categories'] }}
        {{ Str::plural('product line', $stats['categories']) }}
        · One trusted export partner
      </p>
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
@endif

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

@if ($videoTours->isNotEmpty())
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
@endif

@if ($categories->isNotEmpty())
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
            $product = $category->activeFeaturedProducts->first();
            if (!$product) continue;

            // Reveal delay class
            $revealClass = $index === 0 ? '' : ' d' . min($index, 3);

            // Icon SVG per category slug
            $iconSlug = $category->slug;

            // Collect images for the carousel (already sorted: primary first, then sort_order)
            $productImages = $product->images;

            if ($productImages->isEmpty()) {
                // Fallback: single slide with no-image.png
                $slides = [[
                    'url' => asset('img/no-image.png'),
                    'alt' => $product->name,
                ]];
            } else {
                $slides = $productImages->map(fn ($img) => [
                    'url' => $img->url,
                    'alt' => $img->alt ?: $product->name,
                ])->all();
            }

            $slideCount  = count($slides);
            $hasMultiple = $slideCount > 1;
        @endphp

        <article class="prod reveal{{ $revealClass }}">
            <div class="prod-top has-image">

                <div class="prod-carousel"
                    data-carousel
                    data-slide-count="{{ $slideCount }}">

                    {{-- All slides stacked; only the active one is visible --}}
                    @foreach ($slides as $i => $slide)
                        <img src="{{ $slide['url'] }}"
                            alt="{{ $slide['alt'] }}"
                            class="prod-carousel__slide {{ $i === 0 ? 'is-active' : '' }}"
                            loading="{{ $i === 0 ? 'eager' : 'lazy' }}"
                            decoding="async"
                            width="800" height="500"
                            data-index="{{ $i }}">
                    @endforeach

                    {{-- Navigation arrows (only when 2+ images) --}}
                    @if ($hasMultiple)
                        <button type="button"
                                class="prod-carousel__nav prod-carousel__nav--prev"
                                aria-label="Previous image"
                                data-carousel-prev>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M15 18l-6-6 6-6"/>
                            </svg>
                        </button>

                        <button type="button"
                                class="prod-carousel__nav prod-carousel__nav--next"
                                aria-label="Next image"
                                data-carousel-next>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M9 6l6 6-6 6"/>
                            </svg>
                        </button>

                        {{-- Dot indicators --}}
                        <div class="prod-carousel__dots" data-carousel-dots role="tablist">
                            @foreach ($slides as $i => $slide)
                                <button type="button"
                                        class="prod-carousel__dot {{ $i === 0 ? 'is-active' : '' }}"
                                        aria-label="Go to image {{ $i + 1 }}"
                                        data-carousel-dot="{{ $i }}"
                                        role="tab"></button>
                            @endforeach
                        </div>
                    @endif

                </div>

                <span class="prod-top__overlay" aria-hidden="true"></span>

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
@endif

@if ($middleBanners->isNotEmpty())
  @foreach ($middleBanners as $banner)
    @include('partials.banner', ['banner' => $banner])
  @endforeach
@endif

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

@if ($testimonials->isNotEmpty())
<!-- ================= TESTIMONIALS ================= -->
<section class="testimonials" id="testimonials">
  <div class="container">
    <div class="sec-head center reveal">
      <span class="eyebrow">Client Voices</span>
      <h2 class="sec-title">Trusted by Importers Worldwide</h2>
      <p class="sec-sub">
        Real feedback from buyers across the GCC and beyond.
      </p>
    </div>

    <div class="test-grid">
      @foreach ($testimonials as $index => $t)
        <article class="test-card reveal{{ $index === 0 ? '' : ' d' . min($index, 4) }}">

          {{-- Star row --}}
          <div class="test-card__stars">
            @for ($i = 1; $i <= 5; $i++)
              <svg viewBox="0 0 24 24" class="{{ $i <= $t->rating ? 'is-on' : 'is-off' }}">
                <path d="M12 2l3 7h7l-5.5 4.5L18 21l-6-4-6 4 1.5-7.5L2 9h7z"/>
              </svg>
            @endfor
          </div>

          {{-- Decorative quote mark --}}
          <svg class="test-card__quote-mark" viewBox="0 0 32 32" fill="currentColor" aria-hidden="true">
            <path d="M7.5 8C4.5 8 2 10.5 2 13.5S4.5 19 7.5 19c.4 0 .8 0 1.2-.1-.6 1.9-2.2 3.3-4.2 3.6l.4 2.5c4.5-.6 8-4.4 8-9v-1.5C12.9 10.5 10.5 8 7.5 8zm17 0C21.5 8 19 10.5 19 13.5S21.5 19 24.5 19c.4 0 .8 0 1.2-.1-.6 1.9-2.2 3.3-4.2 3.6l.4 2.5c4.5-.6 8-4.4 8-9v-1.5C29.9 10.5 27.5 8 24.5 8z"/>
          </svg>

          {{-- Quote --}}
          <p class="test-card__quote">"{{ $t->quote }}"</p>

          {{-- Author --}}
          <div class="test-card__author">
            @if ($t->avatar)
              <div class="test-card__avatar" style="background-image:url('{{ asset($t->avatar) }}')"></div>
            @else
              <div class="test-card__avatar test-card__avatar--initials">
                {{ strtoupper(substr($t->client_name, 0, 1)) }}
              </div>
            @endif

            <div class="test-card__author-meta">
              <strong>{{ $t->client_name }}</strong>

              @if ($t->position || $t->company)
                <span class="test-card__role">
                  {{ $t->position }}@if ($t->position && $t->company), @endif{{ $t->company }}
                </span>
              @endif

              @if ($t->country)
                <span class="test-card__country">
                  {{ $t->country->flag_emoji }} {{ $t->country->name }}
                </span>
              @endif
            </div>
          </div>

        </article>
      @endforeach
    </div>
  </div>
</section>
@endif

@if ($certifications->isNotEmpty())
<!-- ================= CERTIFICATIONS ================= -->
<section class="certifications" id="certifications">
  <div class="container">
    <div class="sec-head center reveal">
      <span class="eyebrow">Certified Quality</span>
      <h2 class="sec-title">Certifications You Can Trust</h2>
      <p class="sec-sub">
        Our operations are independently audited and certified. Every consignment ships with the
        documentation that proves it.
      </p>
    </div>

    <div class="cert-grid">
      @foreach ($certifications as $index => $cert)
        @php
          $revealClass = $index === 0 ? '' : ' d' . min($index, 4);
          $isExpired   = $cert->expires_on && $cert->expires_on->isPast();
        @endphp

        <article class="cert-card reveal{{ $revealClass }}">
          <div class="cert-card__logo">
            @if ($cert->logo)
              <img src="{{ asset($cert->logo) }}"
                   alt="{{ $cert->name }} logo"
                   loading="lazy"
                   decoding="async">
            @else
              <div class="cert-card__logo-fallback">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                  <path d="M12 2l8 4v6c0 5-3.4 8.8-8 10-4.6-1.2-8-5-8-10V6z"/>
                  <path d="M9 12l2 2 4-4"/>
                </svg>
              </div>
            @endif
          </div>

          <h3 class="cert-card__name">{{ $cert->name }}</h3>

          @if ($cert->issued_by)
            <span class="cert-card__issuer">Issued by {{ $cert->issued_by }}</span>
          @endif

          @if ($cert->description)
            <p class="cert-card__description">{{ $cert->description }}</p>
          @endif

          @if ($cert->certificate_number || $cert->expires_on)
            <div class="cert-card__meta">
              @if ($cert->certificate_number)
                <span class="cert-card__number">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="16" rx="2"/>
                    <path d="M3 10h18M8 4v16"/>
                  </svg>
                  {{ $cert->certificate_number }}
                </span>
              @endif

              @if ($cert->expires_on)
                <span class="cert-card__expiry {{ $isExpired ? 'is-expired' : '' }}">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="9"/>
                    <path d="M12 7v5l3 2"/>
                  </svg>
                  {{ $isExpired ? 'Expired' : 'Valid until' }} {{ $cert->expires_on->format('M Y') }}
                </span>
              @endif
            </div>
          @endif
        </article>
      @endforeach
    </div>
  </div>
</section>
@endif

@if ($gccMarkets->isNotEmpty())
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
@endif

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
      @if (setting('enable_quote_form', '1') == '1')
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
      @else
        {{-- Fallback when the quote form is disabled --}}
        <div class="form-card form-card--disabled reveal d1">
          <div class="form-disabled">
            <div class="form-disabled__icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <circle cx="12" cy="12" r="9"/>
                <path d="M12 8v5M12 16h.01"/>
              </svg>
            </div>
            <h3>Enquiries Temporarily Paused</h3>
            <p>
              We're not accepting online enquiries right now. Please reach us directly at
              <a href="mailto:{{ setting('contact_email', 'exports@threebrothers.com') }}">
                {{ setting('contact_email', 'exports@threebrothers.com') }}
              </a>
              or call <a href="tel:{{ setting('contact_phone') }}">{{ setting('contact_phone') }}</a>.
            </p>
          </div>
        </div>
      @endif
    </div>
  </div>
</section>

@if ($footerBanners->isNotEmpty())
  @foreach ($footerBanners as $banner)
    @include('partials.banner', ['banner' => $banner])
  @endforeach
@endif


@if (setting('enable_newsletter', '1') == '1')
<!-- ================= NEWSLETTER ================= -->
<section class="newsletter" id="newsletter">
  <div class="container">
    <div class="newsletter__inner">

      <div class="newsletter__copy">
        <span class="newsletter__eyebrow">Stay Updated</span>
        <h2 class="newsletter__title">Get export updates in your inbox</h2>
        <p class="newsletter__subtitle">
          Seasonal offerings, new product lines and shipping announcements — sent occasionally, never spam.
        </p>
      </div>

      <div class="newsletter__form-wrap">
        @if (session('newsletter_success'))
          <div class="newsletter__flash newsletter__flash--success">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4">
              <path d="M20 6L9 17l-5-5"/>
            </svg>
            <span>{{ session('newsletter_success') }}</span>
          </div>
        @endif

        @if (session('newsletter_info'))
          <div class="newsletter__flash newsletter__flash--info">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
              <circle cx="12" cy="12" r="9"/>
              <path d="M12 8h.01M11 12h1v4h1"/>
            </svg>
            <span>{{ session('newsletter_info') }}</span>
          </div>
        @endif

        @if ($errors->has('email'))
          <div class="newsletter__flash newsletter__flash--error">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
              <circle cx="12" cy="12" r="10"/>
              <path d="M12 8v5M12 16h.01"/>
            </svg>
            <span>{{ $errors->first('email') }}</span>
          </div>
        @endif

        <form method="POST"
              action="{{ route('newsletter.subscribe') }}"
              class="newsletter__form"
              novalidate>
          @csrf

          <div class="newsletter__field">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="newsletter__field-icon">
              <rect x="2" y="4" width="20" height="16" rx="2"/>
              <path d="M2 7l10 6 10-6"/>
            </svg>
            <input type="email"
                   name="email"
                   placeholder="your@email.com"
                   required
                   autocomplete="email"
                   value="{{ old('email') }}"
                   class="{{ $errors->has('email') ? 'is-invalid' : '' }}">
          </div>

          <button type="submit" class="newsletter__submit">
            Subscribe
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4">
              <path d="M5 12h14M13 6l6 6-6 6"/>
            </svg>
          </button>
        </form>

        <p class="newsletter__note">
          By subscribing you agree to receive occasional emails. Unsubscribe anytime.
        </p>
      </div>

    </div>
  </div>
</section>
@endif

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

        <div class="logo-text">
            <strong>Three Brothers</strong>
            <span>Enterprises</span>
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
          <a href="#" data-open-contact>Contact</a>
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

{{-- ================= CONTACT MODAL ================= --}}
<div class="contact-modal" id="contactModal" aria-hidden="true" role="dialog" aria-modal="true">
  <div class="contact-modal__backdrop" data-close-contact></div>

  <div class="contact-modal__box">

    <button type="button" class="contact-modal__close" data-close-contact aria-label="Close">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4">
        <path d="M18 6L6 18M6 6l12 12"/>
      </svg>
    </button>

    <div class="contact-modal__grid">

      {{-- LEFT: info --}}
      <div class="contact-modal__info">
        <span class="contact-modal__eyebrow">Get in Touch</span>
        <h2 class="contact-modal__title">Contact Our Export Desk</h2>
        <p class="contact-modal__subtitle">
          We're available six days a week to discuss your requirements, samples and shipping schedules.
        </p>

        <ul class="contact-modal__list">
          <li>
            <div class="contact-modal__icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.9v3a2 2 0 01-2.2 2 19.8 19.8 0 01-8.6-3.1 19.5 19.5 0 01-6-6A19.8 19.8 0 012.1 4.2 2 2 0 014.1 2h3a2 2 0 012 1.7c.1 1 .4 1.9.7 2.8a2 2 0 01-.5 2.1L8.1 9.9a16 16 0 006 6l1.3-1.2a2 2 0 012.1-.5c.9.3 1.8.6 2.8.7a2 2 0 011.7 2z"/></svg>
            </div>
            <div>
              <span>Phone / WhatsApp</span>
              <a href="tel:{{ setting('contact_phone', '+923000000000') }}">
                {{ setting('contact_phone', '+92 300 000 0000') }}
              </a>
            </div>
          </li>

          <li>
            <div class="contact-modal__icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M2 7l10 6 10-6"/></svg>
            </div>
            <div>
              <span>Email</span>
              <a href="mailto:{{ setting('contact_email', 'exports@threebrothers.com') }}">
                {{ setting('contact_email', 'exports@threebrothers.com') }}
              </a>
            </div>
          </li>

          <li>
            <div class="contact-modal__icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 6-9 12-9 12s-9-6-9-12a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
            </div>
            <div>
              <span>Head Office</span>
              <b>Karachi, Sindh, Pakistan</b>
            </div>
          </li>

          <li>
            <div class="contact-modal__icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3.5 2"/></svg>
            </div>
            <div>
              <span>Business Hours</span>
              <b>Mon – Sat · 9:00 AM – 7:00 PM (PKT)</b>
            </div>
          </li>
        </ul>
      </div>

      {{-- RIGHT: form --}}
      <div class="contact-modal__form-wrap">
        <h3 class="contact-modal__form-title">Send an Enquiry</h3>
        <p class="contact-modal__form-sub">Fill in the details and we'll get back within one business day.</p>

        @if (setting('enable_quote_form', '1') == '1')
          <form action="{{ route('contact.store') }}" method="POST" class="contact-modal__form" novalidate>
            @csrf

            <div class="contact-modal__field">
              <label for="modal_name">Full Name *</label>
              <input type="text" id="modal_name" name="name" placeholder="Your name" required>
            </div>

            <div class="contact-modal__field">
              <label for="modal_email">Email *</label>
              <input type="email" id="modal_email" name="email" placeholder="you@company.com" required>
            </div>

            <div class="contact-modal__field">
              <label for="modal_phone">Phone / WhatsApp</label>
              <input type="tel" id="modal_phone" name="phone" placeholder="+966 5X XXX XXXX">
            </div>

            <div class="contact-modal__field">
              <label for="modal_subject">Subject</label>
              <input type="text" id="modal_subject" name="subject"
                    placeholder="e.g. Halal beef enquiry — 20 MT"
                    maxlength="160">
            </div>

            <div class="contact-modal__field">
              <label for="modal_message">Your Requirement</label>
              <textarea id="modal_message" name="message" rows="3"
                        placeholder="Tell us about products, quantity, packaging and delivery schedule…"></textarea>
            </div>

            @if (session('contact_success'))
              <div class="contact-modal__flash contact-modal__flash--success">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4">
                  <path d="M20 6L9 17l-5-5"/>
                </svg>
                <span>{{ session('contact_success') }}</span>
              </div>
            @endif

            @if ($errors->any())
              <div class="contact-modal__flash contact-modal__flash--error">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                  <circle cx="12" cy="12" r="10"/>
                  <path d="M12 8v5M12 16h.01"/>
                </svg>
                <span>{{ $errors->first() }}</span>
              </div>
            @endif

            <button type="submit" class="contact-modal__submit">
              Submit Enquiry
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                <path d="M22 2L11 13M22 2l-7 20-4-9-9-4z"/>
              </svg>
            </button>

            <p class="contact-modal__note">* Required fields. Your information is kept confidential.</p>
          </form>
        @else
          <div class="contact-modal__paused">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
              <circle cx="12" cy="12" r="9"/>
              <path d="M12 8v5M12 16h.01"/>
            </svg>
            <h3>Enquiries Temporarily Paused</h3>
            <p>Please contact us directly at
              <a href="mailto:{{ setting('contact_email') }}">{{ setting('contact_email') }}</a>
              or <a href="tel:{{ setting('contact_phone') }}">{{ setting('contact_phone') }}</a>.
            </p>
          </div>
        @endif
      </div>

    </div>
  </div>
</div>

<script src="{{ asset('js/contactModal.js') }}" defer></script>
<script src="{{ asset('js/home.js') }}" defer></script>

</body>
</html>