<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\QuoteEnquiry;
use Illuminate\Http\Request;

class QuoteEnquiryController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'company'  => 'nullable|string|max:255',
            'email'    => 'required|email|max:255',
            'phone'    => 'nullable|string|max:50',
            'country'  => 'required|string',
            'product'  => 'nullable|string',
            'message'  => 'nullable|string|max:5000',
        ]);

        // Map the country name to its DB record (optional but useful)
        $country = Country::where('name', $validated['country'])->first();

        // Map the frontend select value to the enum used in the DB
        $productMap = [
            'Meat — Beef'                  => 'beef',
            'Meat — Mutton'                => 'mutton',
            'Meat — Beef & Mutton (Mixed)' => 'meat_mixed',
            'Ready Made Garments'          => 'garments',
            'Premium Rice'                 => 'rice',
            'Fresh Vegetables'             => 'vegetables',
            'Multiple Products'            => 'multiple',
        ];

        QuoteEnquiry::create([
            'name'             => $validated['name'],
            'company'          => $validated['company']  ?? null,
            'email'            => $validated['email'],
            'phone'            => $validated['phone']    ?? null,
            'country_id'       => $country?->id,
            'product_interest' => $productMap[$validated['product'] ?? ''] ?? 'other',
            'message'          => $validated['message']  ?? null,
            'source'           => 'website',
            'ip_address'       => $request->ip(),
            'user_agent'       => $request->userAgent(),
        ]);

        return back()->with('success', 'Thank you! Your enquiry has been received. Our export team will contact you within one business day.');
    }
}