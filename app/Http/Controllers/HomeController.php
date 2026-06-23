<?php

namespace App\Http\Controllers;

use App\Models\HeroSlide;
use App\Models\BranchCard;
use App\Models\GalleryImage;
use App\Models\SiteSetting;

class HomeController extends Controller
{
    public function index()
    {
        $heroSlides  = HeroSlide::where('is_active', true)->orderBy('sort_order')->get();
        $branchCards = BranchCard::where('is_active', true)->orderBy('sort_order')->get();
        $galleryImages = GalleryImage::orderBy('sort_order')->paginate(SiteSetting::get('gallery_per_page', 12));

        return view('home', compact('heroSlides', 'branchCards', 'galleryImages'));
    }
}