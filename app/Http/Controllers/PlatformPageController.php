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

        return view('landing.pages.static-page', [
            'title' => ($setting->about_title ?: 'Tentang '.$home['site_name']).' · '.$home['site_name'],
            'pageTitle' => $setting->about_title ?: 'Tentang '.$home['site_name'],
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

        return view('landing.pages.static-page', [
            'title' => ($setting->terms_title ?: 'Syarat & Ketentuan').' · '.$home['site_name'],
            'pageTitle' => $setting->terms_title ?: 'Syarat & Ketentuan',
            'breadcrumb' => 'Syarat & Ketentuan',
            'content' => $setting->terms_content ?: '<p>Syarat dan ketentuan belum diatur.</p>',
            'home' => $home,
            'theme' => $theme,
            'updatedAt' => $setting->updated_at,
        ]);
    }
}
