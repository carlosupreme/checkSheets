<?php

namespace App\Filament\Resources\CheckSheetResource\Pages;

use App\Filament\Resources\CheckSheetResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCheckSheets extends ListRecords
{
    protected static string $resource = CheckSheetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
