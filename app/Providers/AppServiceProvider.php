<?php

namespace App\Providers;

use App\Models\Accommodation;
use App\Models\CmsPage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer([
            'pages.home',
            'pages.het-huis',
            'pages.reserveren',
            'pages.tarieven',
            'pages.privacy-voorwaarden',
        ], fn ($view) => $view->with('woning', Accommodation::current()));

        View::composer('pages.home', fn ($view) => $view->with('cms', CmsPage::byKey('home')));
        View::composer('pages.het-huis', fn ($view) => $view->with('cms', CmsPage::byKey('property')));
    }
}
