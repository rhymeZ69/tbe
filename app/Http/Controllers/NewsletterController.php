<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'name'  => ['nullable', 'string', 'max:120'],
        ], [
            'email.required' => 'Please enter your email address.',
            'email.email'    => 'Please enter a valid email address.',
        ]);

        $existing = NewsletterSubscriber::where('email', $validated['email'])->first();

        if ($existing) {
            if ($existing->is_active) {
                return back()->with('newsletter_info', 'You are already subscribed — thank you!');
            }

            // Reactivate a previously unsubscribed email
            $existing->update([
                'is_active'       => true,
                'unsubscribed_at' => null,
            ]);

            return back()->with('newsletter_success', 'Welcome back! Your subscription has been reactivated.');
        }

        NewsletterSubscriber::create([
            'email'      => $validated['email'],
            'name'       => $validated['name'] ?? null,
            'is_active'  => true,
            'token'      => Str::random(64),
            'ip_address' => $request->ip(),
        ]);

        return back()->with('newsletter_success', 'Thank you for subscribing! We\'ll be in touch.');
    }
}