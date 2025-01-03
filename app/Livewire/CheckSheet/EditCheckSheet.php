<?php

namespace App\Livewire\CheckSheet;

use App\Models\CheckSheet;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class EditCheckSheet extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public CheckSheet $record;

    public function mount(CheckSheet $record): void {
        $this->record = $record;
        $this->form->fill($this->record->attributesToArray());
    }

    public function form(Form $form): Form {
        return $form
            ->schema([
                Grid::make(2)->schema([
                    Section::make(__('CheckSheet data'))
                           ->icon('heroicon-o-document')
                           ->schema([
                               TextInput::make('name')
                                        ->placeholder('Lavadora LAV-REN-01')
                                        ->translateLabel()
                                        ->required()
                                        ->unique(ignoreRecord: true),
                               Forms\Components\RichEditor::make('notes')
                                                          ->disableToolbarButtons(['attachFiles'])
                                                          ->translateLabel(),
                               Toggle::make('is_published')
                                     ->label('Publicar')
                                     ->default(true)
                                     ->markAsRequired(false)
                                     ->required(),
                           ])->columnSpan(1),
                    Section::make(__('Equipment data'))->columnSpan(1)
                           ->icon('heroicon-o-wrench-screwdriver')
                           ->schema([
                               TextInput::make('equipment_area')
                                        ->label('Area')
                                        ->translateLabel()
                                        ->required(),
                               Grid::make()->schema([
                                   TextInput::make('equipment_tag')
                                            ->label('Tag')
                                            ->required()
                                            ->unique(ignoreRecord: true)
                                            ->columnSpan(1),
                                   TextInput::make('equipment_name')
                                            ->label('Name')
                                            ->translateLabel()
                                            ->required()
                               ])
                           ]),

                ])
            ])
            ->statePath('data')
            ->model($this->record);
    }

    #[On('updateCheckSheetFormSubmitted')]
    public function update(): void {
        $this->record->update([... $this->form->getState(), 'updated_by' => auth()->id()]);
        $this->dispatch('checkSheetUpdated', $this->record->id);
    }

    public function render(): View {
        return view('livewire.check-sheet.edit-check-sheet');
    }
}
