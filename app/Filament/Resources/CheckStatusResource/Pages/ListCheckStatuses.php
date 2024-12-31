<?php

namespace App\Filament\Resources\CheckStatusResource\Pages;

use App\Filament\Resources\CheckStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCheckStatuses extends ListRecords
{
    protected static string $resource = CheckStatusResource::class;

    protected function getHeaderActions(): array {
        return [
            Actions\CreateAction::make()->slideOver()->mutateFormDataUsing(function (array $data): array {
                $data['created_by'] = auth()->id();
                $data['updated_by'] = auth()->id();
                return $data;
            })->createAnother(false),
        ];
    }
}
