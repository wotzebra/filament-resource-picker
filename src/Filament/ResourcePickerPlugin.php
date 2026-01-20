<?php

namespace Wotz\FilamentResourcePicker\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;

class ResourcePickerPlugin implements Plugin
{
    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return 'filament-resource-picker';
    }

    public function register(Panel $panel): void {}

    public function boot(Panel $panel): void
    {
        //
    }
}
