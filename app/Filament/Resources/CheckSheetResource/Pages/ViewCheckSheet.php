<?php

namespace App\Filament\Resources\CheckSheetResource\Pages;

use App\Filament\Resources\CheckSheetResource;
use Filament\Actions;
use Filament\Infolists\Components\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewCheckSheet extends ViewRecord
{
    protected static string $resource = CheckSheetResource::class;

    protected function getHeaderActions(): array {
        return [
            Actions\EditAction::make()
        ];
    }
}
