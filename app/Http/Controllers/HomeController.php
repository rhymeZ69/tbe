<?php

namespace App\Http\Controllers;

use App\Models\VideoTour;
use App\Models\ProductCategory;
use App\Models\Country;

class HomeController extends Controller
{
    public function index()
    {
        $gccMarkets = Country::gcc()->active()->orderBy('sort_order')->get();
        $categories = ProductCategory::active()
                        ->with(['activeProducts.varieties', 'activeProducts.specs'])
                        ->orderBy('sort_order')
                        ->get();

        return view('home', [
            'videoTours'   => VideoTour::active()->get(),
            'categories'   => $categories,
            'gccMarkets'   => $gccMarkets,
            'stats' => [
                'categories' => $categories->count(),
                'markets'    => $gccMarkets->count(),
                'capacity'   => 500, // keep static or pull from a setting
                'halal'      => 100,
            ],
        ]);
    }
}