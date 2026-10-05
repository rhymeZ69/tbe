@php
    /** @var \App\Models\Banner $banner */
    $hasMobileImage = !empty($banner->image_mobile);

    $bannerHeight = (int) ($banner->height ?? 160);
@endphp

<section
    class="banner banner--{{ $banner->position }}"
    style="--banner-min-height: {{ $bannerHeight }}px;"
>
    {{-- FULL WIDTH IMAGE --}}
    @if ($banner->image)
        <picture class="banner__media">
            @if ($hasMobileImage)
                <source
                    media="(max-width: 680px)"
                    srcset="{{ asset($banner->image_mobile) }}"
                >
            @endif

            <img
                src="{{ asset($banner->image) }}"
                alt="{{ $banner->title ?: 'Promotional banner' }}"
                class="banner__image"
                loading="lazy"
                decoding="async"
            >
        </picture>
    @endif

    {{-- NO DARK OVERLAY --}}
    <span class="banner__overlay" aria-hidden="true"></span>

    {{-- CONTENT --}}
    @if ($banner->title || $banner->subtitle || $banner->cta_text)
        <div class="container banner__container">
            <div class="banner__content">

                @if ($banner->title)
                    <h3 class="banner__title">
                        {{ $banner->title }}
                    </h3>
                @endif

                @if ($banner->subtitle)
                    <p class="banner__subtitle">
                        {{ $banner->subtitle }}
                    </p>
                @endif

                @if ($banner->cta_text)
                    <a
                        href="{{ $banner->cta_url ?: '#' }}"
                        class="banner__cta"
                    >
                        {{ $banner->cta_text }}

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2.2"
                            aria-hidden="true"
                        >
                            <path d="M5 12h14M13 6l6 6-6 6"/>
                        </svg>
                    </a>
                @endif

            </div>
        </div>
    @endif
</section>