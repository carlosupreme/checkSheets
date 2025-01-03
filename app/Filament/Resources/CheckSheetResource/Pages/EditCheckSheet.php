<?php

namespace App\Filament\Resources\CheckSheetResource\Pages;

use App\Filament\Resources\CheckSheetResource;
use App\Models\CheckSheet;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Livewire\Attributes\On;

class EditCheckSheet extends Page
{
    protected static string $resource = CheckSheetResource::class;

    protected static string $view = 'filament.resources.check-sheet-resource.pages.edit-check-sheet';

    public CheckSheet $record;

    public function getTitle(): string|Htmlable {
        return __('actions.named.edit', ['name' => CheckSheetResource::getModelLabel()]);
    }

    public function update(): void {
        $this->dispatch('updateCheckSheetFormSubmitted');
    }

    #[On('checkSheetItemsUpdated')]
    public function redirectToTable(int $id) {
        Notification::make()
                    ->success()
                    ->title(__('filament-actions::edit.single.notifications.saved.title'));

            Notification::make()
                        ->success()
                        ->title("La hoja de chequeo {$this->record->name} se ha actualizado")
                        ->broadcast(User::whereHas('roles', fn($q) => $q->where("name", "operador"))->get());
        $this->redirect($this->getResource()::getUrl('index'));
    }
}
