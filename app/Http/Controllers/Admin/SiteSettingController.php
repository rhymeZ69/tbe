<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SiteSettingController extends Controller
{
    /**
     * Show the settings form.
     */
    public function index()
    {
        $settings = SiteSetting::orderBy('group')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->groupBy('group');

        // Group display metadata
        $groupMeta = [
            'general' => [
                'label' => 'General',
                'description' => 'Site name, tagline and description used across the site.',
                'icon' => 'settings',
            ],
            'contact' => [
                'label' => 'Contact Information',
                'description' => 'Phone, email, address, hours and WhatsApp — shown in the topbar, contact section and footer.',
                'icon' => 'phone',
            ],
            'social' => [
                'label' => 'Social Media',
                'description' => 'Links to your social profiles. Leave a field blank to hide that icon from the footer.',
                'icon' => 'share',
            ],
            'seo' => [
                'label' => 'SEO & Analytics',
                'description' => 'Meta tags and analytics IDs used in the page head.',
                'icon' => 'search',
            ],
            'general_flags' => [
                'label' => 'Feature Toggles',
                'description' => 'Turn features of the website on or off.',
                'icon' => 'toggle',
            ],
        ];

        return view('admin.settings.index', compact('settings', 'groupMeta'));
    }

    /**
     * Update the settings.
     */
    public function update(Request $request)
    {
        $submitted = $request->input('settings', []);

        // Booleans: checkboxes that aren't submitted are off
        $booleanKeys = SiteSetting::where('type', 'boolean')->pluck('key')->all();
        foreach ($booleanKeys as $key) {
            if (! array_key_exists($key, $submitted)) {
                $submitted[$key] = '0';
            }
        }

        foreach ($submitted as $key => $value) {
            SiteSetting::where('key', $key)->update(['value' => $value]);
        }

        Cache::forget('site_settings');
        Cache::forget('tbe-cache-site_settings');

        return redirect()
            ->route('admin.settings.index')
            ->with('status', 'Site settings updated successfully.');
    }
}