<?php

namespace App\Providers;

use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use DB;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->isLocal()) {
            $this->app->register(IdeHelperServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();

        DB::connection()
            ->getDoctrineSchemaManager()
            ->getDatabasePlatform()
            ->registerDoctrineTypeMapping('enum', 'string');

        Filament::serving(static function () {
            Filament::registerNavigationGroups([
                NavigationGroup::make()
                    ->label(trans('admin.nav_groups.users')),
                NavigationGroup::make()
                    ->label(trans('admin.nav_groups.settings')),
            ]);
        });
    }
}
