<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Services\LandingPageDataService;
use App\Support\RestaurantTheme;
use Illuminate\View\View;

class RestaurantLandingController extends Controller
{
    public function home(): View
    {
        $restaurants = Restaurant::query()
            ->listedInDirectory()
            ->with(['cmsProfile', 'defaultOutlet.operatingHours', 'defaultOutlet.closedDates', 'defaultOutlet.restaurant'])
            ->orderBy('name')
            ->get();

        return view('landing.home', [
            'restaurants' => $restaurants,
            'theme' => RestaurantTheme::for(null),
        ]);
    }

    public function show(Restaurant $restaurant, LandingPageDataService $landingDataService): View
    {
        abort_unless($restaurant->isLandingPublic(), 404);

        $payload = $landingDataService->getPayload($restaurant);
        $template = $payload['template'];

        $viewName = filled($template?->view_path) && view()->exists($template->view_path)
            ? $template->view_path
            : 'landing.templates.classic.show';

        if (! view()->exists($viewName)) {
            $viewName = 'landing.show';
        }

        return view($viewName, $payload);
    }
}
