<?php

namespace App\Providers;

use App\Models\ServiceTier;
use App\Support\Site;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Navigation data is shared with every view, so it is resolved once per
     * request instead of once per Blade view.
     *
     * @var Collection<int, ServiceTier>|null
     */
    protected static ?Collection $navServices = null;

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Model::preventLazyLoading(! $this->app->isProduction());

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Keep the shared navigation in step with admin edits.
        ServiceTier::saved(fn () => static::flushNavigation());
        ServiceTier::deleted(fn () => static::flushNavigation());

        // Navigation and footer data are needed on every page; sharing them
        // here keeps the layouts and controllers free of boilerplate.
        View::composer('*', function ($view) {
            $view->with('navServices', static::navigation());
            $view->with('site', static::siteData());
        });
    }

    public static function flushNavigation(): void
    {
        static::$navServices = null;
    }

    /** @return Collection<int, ServiceTier> */
    protected static function navigation(): Collection
    {
        return static::$navServices ??= ServiceTier::query()
            ->active()
            ->ordered()
            ->get(['id', 'name', 'slug', 'includes', 'model', 'display_order']);
    }

    /** @return array<string, mixed> */
    protected static function siteData(): array
    {
        return [
            'name' => Site::name(),
            'tagline' => Site::get('tagline'),
            'positioning' => config('site.positioning'),
            'email' => Site::email(),
            'phone' => Site::phone(),
            'city' => Site::get('city'),
            'country' => Site::get('country') ?: 'Philippines',
            'address' => Site::get('address'),
            'social' => Site::socialLinks(),
        ];
    }
}
