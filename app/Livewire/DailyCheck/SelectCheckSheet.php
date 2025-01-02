<?php

namespace App\Livewire\DailyCheck;

use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;

class SelectCheckSheet extends Component implements HasForms
{
    use InteractsWithForms;

    public $checkSheet;

    public ?array $data = [];

    public function mount(): void {
        $this->form->fill();
    }

    public function form(Form $form): Form {
        return $form
            ->schema([
                Select::make('check_sheet_id')
                      ->label('Hoja de chequeo')
                      ->relationship('checkSheet', 'name')
                      ->required(),
            ])
            ->statePath('data')
            ->model(\App\Models\DailyCheck::class);
    }

    public function nextPage(): void {
        $data = $this->form->getState();
        $this->dispatch("checkSheetSelected", $data['check_sheet_id']);
    }

    public function render() {
        return view('livewire.daily-check.select-check-sheet');
    }
}
