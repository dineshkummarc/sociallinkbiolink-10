<?php

namespace Modules\Sociallink\App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    protected string $name = 'Sociallink';

    protected $moduleNamespace = 'Modules\Sociallink\App\Http\Controllers';
    public function boot(): void
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     */
    public function map(): void
    {
        $this->mapApiRoutes();
        $this->mapWebRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     */
    protected function mapWebRoutes(): void
    {
        Route::middleware(['web', 'auth', 'user'])
            ->prefix('user/sociallink')
            ->name('user.sociallink.')
            ->namespace($this->moduleNamespace)
            ->group(module_path($this->name, '/routes/user.php'));

        Route::middleware(['web', 'auth', 'admin'])
            ->prefix('admin/sociallink')
            ->as('admin.sociallink.')
            ->group(module_path($this->name, '/routes/web.php'));
        Route::prefix('bio')
            ->group(module_path($this->name, '/routes/social_link.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     */
    protected function mapApiRoutes(): void
    {
        Route::middleware('api')
            ->prefix('api/sociallink')
            ->name('sociallink.api.')
            ->namespace($this->moduleNamespace)
            ->group(module_path($this->name, '/routes/api.php'));
    }
}
