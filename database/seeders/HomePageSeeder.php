<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;

class HomePageSeeder extends Seeder
{
    public function run(): void
    {
        Page::firstOrCreate(
            ['slug' => 'home'],
            [
                'title'        => 'Home',
                'slug'         => 'home',
                'full_path'    => '/',
                'content'      => null,
                'is_published' => true,
                'show_in_nav'  => false, // Home tidak perlu muncul di navbar
                'sort_order'   => 0,
                'depth'        => 0,
            ]
        );
    }
}