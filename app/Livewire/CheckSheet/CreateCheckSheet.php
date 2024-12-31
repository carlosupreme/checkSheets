<?php

namespace App\Livewire\CheckSheet;

use App\Models\CheckSheet;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Contracts\View\View;

class CreateCheckSheet extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public function mount(): void {
        $this->form->fill();
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
                                        ->unique(),
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
                ]),
            ])
            ->statePath('data')
            ->model(CheckSheet::class);
    }

    #[On("createCheckSheetFormSubmitted")]
    public function create(): void {
        $userId = auth()->id();
        $data = [... $this->form->getState(), 'created_by' => $userId, 'updated_by' => $userId];

        $record = CheckSheet::create($data);
        $this->form->model($record)->saveRelationships();

        $this->dispatch("checkSheetCreated", $record->id);
    }

    public function render(): View {
        return view('livewire.check-sheet.create-check-sheet');
    }
}
