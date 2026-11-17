<?php

namespace App\Providers;

use App\Models\Accommodation;
use App\Models\CmsPage;
use App\Services\PriceCalculator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PriceCalculator::class, fn () => new PriceCalculator(config('site.rates')));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer([
            'pages.home',
            'pages.property',
            'pages.booking',
            'pages.rates',
            'pages.privacy',
        ], fn ($view) => $view->with('accommodation', Accommodation::current()));

        View::composer(['pages.rates', 'pages.availability'], fn ($view) => $view->with(
            'seasonRates',
            Accommodation::current()?->upcomingRates() ?? collect(),
        ));

        View::composer('pages.home', fn ($view) => $view->with('cms', CmsPage::byKey('home')));
        View::composer('pages.property', fn ($view) => $view->with('cms', CmsPage::byKey('property')));
    }
}
