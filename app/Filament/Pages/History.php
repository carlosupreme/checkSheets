<?php

namespace App\Filament\Pages;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;

class History extends Page
{
    use HasPageShield;
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static string $view = 'filament.pages.history';

    protected static ?string $title = 'Historial';

    public static function getNavigationGroup(): ?string {
        return 'Hojas de chequeo';
    }
}
