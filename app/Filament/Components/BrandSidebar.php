<?php

namespace App\Filament\Components;

use Filament\Components\Component;
use Filament\Support\Components\ViewComponent;

class BrandSidebar extends ViewComponent
{
    protected string $view = 'filament.components.brand-sidebar';

    public static function make(): static
    {
        return app(static::class);
    }
}