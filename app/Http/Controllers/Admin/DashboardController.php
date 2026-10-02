<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Certification;
use App\Models\ContactMessage;
use App\Models\Country;
use App\Models\NewsletterSubscriber;
use App\Models\Page;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\QuoteEnquiry;
use App\Models\Testimonial;
use App\Models\VideoTour;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        /* ---------- Stat cards ---------- */
        $stats = [
            'new_enquiries'        => QuoteEnquiry::where('status', 'new')->count(),
            'total_enquiries'      => QuoteEnquiry::count(),
            'enquiries_this_month' => QuoteEnquiry::whereMonth('created_at', now()->month)
                                        ->whereYear('created_at', now()->year)
                                        ->count(),
            'unread_messages'      => ContactMessage::where('is_read', false)->count(),
            'total_messages'       => ContactMessage::count(),
            'subscribers'          => NewsletterSubscriber::where('is_active', true)->count(),
            'products'             => Product::where('is_active', true)->count(),
            'categories'           => ProductCategory::where('is_active', true)->count(),
            'video_tours'          => VideoTour::where('is_active', true)->count(),
            'gcc_markets'          => Country::where('is_gcc', true)->where('is_active', true)->count(),
            'certifications'       => Certification::where('is_active', true)->count(),
            'testimonials'         => Testimonial::where('is_active', true)->count(),
            'published_pages'      => Page::where('is_published', true)->count(),
            'active_banners'       => Banner::where('is_active', true)->count(),
        ];

        /* ---------- Recent activity ---------- */
        $recentEnquiries = QuoteEnquiry::with('country')
            ->latest()
            ->limit(8)
            ->get();

        $recentMessages = ContactMessage::latest()
            ->limit(5)
            ->get();

        $recentSubscribers = NewsletterSubscriber::where('is_active', true)
            ->latest()
            ->limit(5)
            ->get();

        /* ---------- Enquiries by status (for donut/bars) ---------- */
        $enquiriesByStatus = QuoteEnquiry::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $statusLabels = [
            'new'         => 'New',
            'in_progress' => 'In Progress',
            'quoted'      => 'Quoted',
            'won'         => 'Won',
            'lost'        => 'Lost',
            'spam'        => 'Spam',
        ];

        /* ---------- Enquiries for the last 6 months (line chart) ---------- */
        $enquiriesByMonth = [];
        for ($i = 5; $i >= 0; $i--) {
            $date  = now()->subMonths($i);
            $count = QuoteEnquiry::whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->count();
            $enquiriesByMonth[$date->format('M')] = $count;
        }

        /* ---------- Top destination countries ---------- */
        $topCountries = Country::withCount('enquiries')
            ->has('enquiries')
            ->orderByDesc('enquiries_count')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentEnquiries',
            'recentMessages',
            'recentSubscribers',
            'enquiriesByStatus',
            'statusLabels',
            'enquiriesByMonth',
            'topCountries'
        ));
    }
}