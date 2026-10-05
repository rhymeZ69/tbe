<?php

namespace App\Http\Controllers;

use App\Models\Certification;
use App\Models\VideoTour;
use App\Models\ProductCategory;
use App\Models\Country;
use App\Models\Page;
use App\Models\Testimonial;

class HomeController extends Controller
{
    public function index()
    {
        $gccMarkets = Country::active()
                        ->featured()
                        ->orderBy('sort_order')
                        ->get();

        $categories = ProductCategory::active()
                        ->featured()
                        ->with([
                            'activeFeaturedProducts.varieties',
                            'activeFeaturedProducts.specs',
                            'activeFeaturedProducts.images',
                        ])
                        ->orderBy('sort_order')
                        ->get();

        // Drop categories that ended up with no featured products after filtering
        $categories = $categories->filter(
            fn ($category) => $category->activeFeaturedProducts->isNotEmpty()
        )->values();

        $certifications = Certification::active()->get();

        $pages = Page::published()
            ->orderBy('title')
            ->get(['id', 'title', 'slug']);

        $testimonials = Testimonial::active()
            ->with('country')
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();

        // Banners by position
        $topBanners    = \App\Models\Banner::active()->where('position', 'top')->orderBy('sort_order')->get();
        $heroBanners   = \App\Models\Banner::active()->where('position', 'hero')->orderBy('sort_order')->get();
        $middleBanners = \App\Models\Banner::active()->where('position', 'middle')->orderBy('sort_order')->get();
        $footerBanners = \App\Models\Banner::active()->where('position', 'footer')->orderBy('sort_order')->get();

        return view('home', [
            'videoTours'     => VideoTour::active()->get(),
            'categories'     => $categories,
            'gccMarkets'     => $gccMarkets,
            'certifications' => $certifications,
            'stats' => [
                'categories' => $categories->count(),
                'markets'    => $gccMarkets->count(),
                'capacity'   => 500,
                'halal'      => 100,
            ],
            'pages' => $pages,
            'testimonials' => $testimonials,
            'topBanners'    => $topBanners,
            'heroBanners'   => $heroBanners,
            'middleBanners' => $middleBanners,
            'footerBanners' => $footerBanners,
        ]);
    }
}