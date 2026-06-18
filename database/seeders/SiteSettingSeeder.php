<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteSetting;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            // Identitas website
            'site_name'        => 'Nama Perusahaan',
            'site_tagline'     => '',
            'meta_description' => '',

            // Logo
            'logo_path' => '',
            'logo_mode' => 'text',

            // Footer
            'footer_about_text'      => '',
            'footer_contact_address' => '',
            'footer_contact_phone'   => '',
            'footer_contact_fax'     => '',
            'footer_contact_email'   => '',
            'footer_projects_title'  => 'Recent Projects',

            // Home sections
            'what_we_do_heading' => 'What We Do',
            'what_we_do_body'    => '',
            'gallery_per_page'   => '12',

            // Blog
            'blog_enabled'  => 'true',
            'blog_per_page' => '9',
            'blog_title'    => 'Blog',
        ];

        foreach ($defaults as $key => $value) {
            SiteSetting::firstOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }
}