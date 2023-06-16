<?php

namespace App\Filament\Pages;

use Filament\Facades\Filament;
use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-view-grid';

    protected static ?int $navigationSort = -2;

    protected static string $view = 'filament::pages.dashboard';

    protected static function getNavigationLabel(): string
    {
        return static::$navigationLabel ?? static::$title ?? __('filament::pages/dashboard.title');
    }

    protected function getWidgets(): array
    {
        return Filament::getWidgets();
    }

    protected function getColumns(): int|string|array
    {
        return 2;
    }

    protected function getTitle(): string
    {
        return static::$title ?? __('filament::pages/dashboard.title');
    }
}
