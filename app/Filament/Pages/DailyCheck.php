<?php

namespace App\Filament\Pages;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;

class DailyCheck extends Page
{
    use HasPageShield;
    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    protected static string $view = 'filament.pages.daily-check';

    protected static ?string $title = 'Chequeo diario';

    public static function getNavigationGroup(): ?string {
        return 'Hojas de chequeo';
    }
}
