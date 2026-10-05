<?php

if (! function_exists('setting')) {
    function setting(string $key, $default = null)
    {
        return $GLOBALS['__tbe_settings'][$key] ?? $default;
    }
}

if (! function_exists('admin_stats')) {
    /**
     * Sidebar badge counts for the admin panel.
     * Cached per request — no repeated DB hits.
     */
    function admin_stats(): array
    {
        static $cache = null;

        if ($cache !== null) {
            return $cache;
        }

        $cache = [
            'categories'      => \App\Models\ProductCategory::count(),
            'products'        => \App\Models\Product::count(),
            'video_tours'     => \App\Models\VideoTour::count(),
            'certifications'  => \App\Models\Certification::count(),
            'testimonials'    => \App\Models\Testimonial::count(),
            'gcc_markets'     => \App\Models\Country::where('is_gcc', true)->count(),
            'subscribers'     => \App\Models\NewsletterSubscriber::where('is_active', true)->count(),
            'new_enquiries'   => \App\Models\QuoteEnquiry::where('status', 'new')->count(),
            'unread_messages' => \App\Models\ContactMessage::where('is_read', false)->count(),
            'banners'         => \App\Models\Banner::count(),
        ];

        return $cache;
    }
}