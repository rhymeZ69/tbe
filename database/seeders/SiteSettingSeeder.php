<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['site_name', 'Three Brothers Enterprises', 'general', 'text'],
            ['site_tagline', 'Premium Halal Meat, Rice, Garments & Vegetables Exporter', 'general', 'text'],
            ['site_description', 'Multi-product export house from Pakistan — meat, garments, rice and vegetables shipped worldwide.', 'general', 'textarea'],

            // Contact
            ['contact_email', 'exports@threebrothers.com', 'contact', 'email'],
            ['contact_phone', '+92 300 000 0000', 'contact', 'phone'],
            ['contact_whatsapp', '923000000000', 'contact', 'text'],
            ['contact_address', 'Karachi, Sindh, Pakistan', 'contact', 'text'],
            ['business_hours', 'Mon – Sat · 9:00 AM – 7:00 PM (PKT)', 'contact', 'text'],

            // Social
            ['social_facebook',  '', 'social', 'url'],
            ['social_instagram', '', 'social', 'url'],
            ['social_linkedin',  '', 'social', 'url'],
            ['social_youtube',   '', 'social', 'url'],
            ['social_twitter',   '', 'social', 'url'],

            // SEO
            ['meta_title', 'Three Brothers Enterprises | Multi-Product Exporter — Pakistan', 'seo', 'text'],
            ['meta_description', 'Premium halal meat, garments, rice and vegetables exporter from Pakistan to the GCC and worldwide.', 'seo', 'textarea'],
            ['google_analytics_id', '', 'seo', 'text'],

            // Feature flags
            ['enable_newsletter', '1', 'general', 'boolean'],
            ['enable_quote_form', '1', 'general', 'boolean'],
        ];

        foreach ($settings as $row) {
            SiteSetting::updateOrCreate(
                ['key' => $row[0]],
                ['value' => $row[1], 'group' => $row[2], 'type' => $row[3]]
            );
        }
    }
}