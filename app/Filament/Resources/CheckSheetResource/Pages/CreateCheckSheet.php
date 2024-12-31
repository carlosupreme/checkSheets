<?php

namespace App\Filament\Resources\CheckSheetResource\Pages;

use App\Filament\Resources\CheckSheetResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Livewire\Attributes\On;

class CreateCheckSheet extends Page
{
    protected static string $resource = CheckSheetResource::class;

    protected static string $view = 'filament.resources.check-sheet-resource.pages.create-check-sheet';

    public function getTitle(): string|Htmlable {
        return __('actions.named.create', ['name' => CheckSheetResource::getModelLabel()]);
    }

    public function create(): void {
        $this->dispatch('createCheckSheetFormSubmitted');
    }

    #[On("checkSheetItemsCreated")]
    public function redirectToTable() {
        Notification::make()
                    ->success()
                    ->title(__('Created'))
                    ->send();
        $this->redirect($this->getResource()::getUrl('index'));
    }
}
