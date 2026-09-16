<?php

namespace App\Http\Controllers;

use App\Models\PlatformSetting;
use App\Support\RestaurantTheme;
use Illuminate\View\View;

class PlatformPageController extends Controller
{
    public function about(): View
    {
        $setting = PlatformSetting::current();
        $home = PlatformSetting::homeViewData();
        $theme = RestaurantTheme::for(null);
        $cleanContent = trim(strip_tags($setting->about_content ?: ''));
        $description = filled($cleanContent)
            ? \Illuminate\Support\Str::limit($cleanContent, 160)
            : 'Tentang '.$home['site_name'].' - Platform direktori kuliner dan rekomendasi restoran terdekat.';

        return view('landing.pages.static-page', [
            'title' => ($setting->about_title ?: 'Tentang '.$home['site_name']).' · '.$home['site_name'],
            'pageTitle' => $setting->about_title ?: 'Tentang '.$home['site_name'],
            'description' => $description,
            'canonical' => route('page.about'),
            'breadcrumb' => 'Tentang Kami',
            'content' => $setting->about_content ?: '<p>Informasi belum diatur.</p>',
            'home' => $home,
            'theme' => $theme,
            'updatedAt' => $setting->updated_at,
        ]);
    }

    public function terms(): View
    {
        $setting = PlatformSetting::current();
        $home = PlatformSetting::homeViewData();
        $theme = RestaurantTheme::for(null);
        $cleanContent = trim(strip_tags($setting->terms_content ?: ''));
        $description = filled($cleanContent)
            ? \Illuminate\Support\Str::limit($cleanContent, 160)
            : 'Syarat dan Ketentuan layanan '.$home['site_name'].' - Ketentuan penggunaan platform direktori kuliner dan pemesanan walk-in.';

        return view('landing.pages.static-page', [
            'title' => ($setting->terms_title ?: 'Syarat & Ketentuan').' · '.$home['site_name'],
            'pageTitle' => $setting->terms_title ?: 'Syarat & Ketentuan',
            'description' => $description,
            'canonical' => route('page.terms'),
            'breadcrumb' => 'Syarat & Ketentuan',
            'content' => $setting->terms_content ?: '<p>Syarat dan ketentuan belum diatur.</p>',
            'home' => $home,
            'theme' => $theme,
            'updatedAt' => $setting->updated_at,
        ]);
    }
}
