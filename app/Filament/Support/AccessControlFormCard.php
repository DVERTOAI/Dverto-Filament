<?php

namespace App\Filament\Support;

use Filament\Schemas\Components\Section;
use Filament\Support\Enums\IconSize;

class AccessControlFormCard
{
    public static function make(string $heading, string $description, array $schema, string | \BackedEnum $icon, string $variant = 'default'): Section
    {
        return Section::make($heading)
            ->description($description)
            ->icon($icon)
            ->iconSize(IconSize::Large)
            ->schema($schema)
            ->compact()
            ->columnSpanFull()
            ->extraAttributes([
                'class' => "ac-form-shell ac-form-shell--{$variant}",
            ]);
    }
}
