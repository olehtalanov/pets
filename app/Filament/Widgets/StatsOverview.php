<?php

namespace App\Filament\Widgets;

use App\Models\Animal;
use App\Models\User;
use Cache;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Flowframe\Trend\Trend;

class StatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        [$thisWeekRegistrations, $totalRegistrations] = $this->weeklyRegistrations();
        [$thisWeekAnimals, $totalAnimals] = $this->weeklyAnimals();

        return [
            Card::make(trans('admin.placeholders.registrations.weekly'), $thisWeekRegistrations->sum->aggregate)
                ->description(abs($totalRegistrations) . ($totalRegistrations >= 0 ? ' increase' : ' decrease'))
                ->descriptionIcon($totalRegistrations >= 0 ? 'heroicon-s-trending-up' : 'heroicon-s-trending-down')
                ->chart($thisWeekRegistrations->map->aggregate->all())
                ->color($totalRegistrations > 0 ? 'success' : 'warning'),

            Card::make(trans('admin.placeholders.animals.weekly'), $thisWeekAnimals->sum->aggregate)
                ->description(abs($totalAnimals) . ($totalAnimals >= 0 ? ' increase' : ' decrease'))
                ->descriptionIcon($totalAnimals >= 0 ? 'heroicon-s-trending-up' : 'heroicon-s-trending-down')
                ->chart($thisWeekAnimals->map->aggregate->all())
                ->color($totalRegistrations > 0 ? 'success' : 'warning'),
        ];
    }

    private function weeklyRegistrations(): array
    {
        $lastWeekRegistrations = Cache::remember('registrations:weekly:last', now()->endOfWeek(), static function () {
            return Trend::model(User::class)
                ->between(
                    start: now()->subWeek()->startOfWeek(),
                    end: now()->subWeek()->endOfWeek(),
                )
                ->perMonth()
                ->count();
        });

        $thisWeekRegistrations = Trend::model(User::class)
            ->between(
                start: now()->startOfWeek(),
                end: now()->endOfWeek(),
            )
            ->perDay()
            ->count();

        $total = $thisWeekRegistrations->sum->aggregate - $lastWeekRegistrations->first()->aggregate;

        return [$thisWeekRegistrations, $total];
    }

    private function weeklyAnimals(): array
    {
        $lastWeekAnimals = Cache::remember('animals:weekly:last', now()->endOfWeek(), static function () {
            return Trend::model(Animal::class)
                ->between(
                    start: now()->subWeek()->startOfWeek(),
                    end: now()->subWeek()->endOfWeek(),
                )
                ->perMonth()
                ->count();
        });

        $thisWeekAnimals = Trend::model(Animal::class)
            ->between(
                start: now()->startOfWeek(),
                end: now()->endOfWeek(),
            )
            ->perDay()
            ->count();

        $total = $thisWeekAnimals->sum->aggregate - $lastWeekAnimals->first()->aggregate;

        return [$thisWeekAnimals, $total];
    }
}
