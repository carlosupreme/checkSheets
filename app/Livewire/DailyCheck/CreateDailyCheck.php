<?php

namespace App\Livewire\DailyCheck;

use App\Models\CheckSheet;
use App\Models\DailyCheck;
use App\Models\User;
use Coolsam\SignaturePad\Forms\Components\Fields\SignaturePad;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class CreateDailyCheck extends Component implements HasForms
{
    use InteractsWithForms;

    public bool $reported = false;

    public int         $page = 1;
    public ?CheckSheet $checkSheet;

    public ?array $data = [];

    public function mount(): void {
        $this->form->fill();
    }

    public function form(Form $form): Form {
        return $form
            ->schema([
                Grid::make()->schema([
                    SignaturePad::make('operator_signature')
                                ->label('Firma')
                                ->hideDownloadButtons()
                                ->required(),
                    TextInput::make('operator_name')
                             ->default(fn() => auth()->user()->name)
                             ->label('Nombre')
                             ->required(),
                ]),
                Textarea::make('notes')
                        ->translateLabel()
                        ->columnSpanFull(),
            ])
            ->statePath('data')
            ->model(DailyCheck::class);
    }


    #[On('checkSheetSelected')]
    public function nextPage(int $checkSheet): void {
        $this->page = 2;
        $this->checkSheet = CheckSheet::with('items')->find($checkSheet);
    }

    public function hasItems(): bool {
        if ($this->checkSheet) {
            return $this->checkSheet->items->count() > 0;
        }
        return false;
    }

    public function save(): void {
        $this->dispatch('requestForValidItems');
    }

    #[On('validItems')]
    public function creating(): void {
        $data = $this->form->getState();
        $created = DailyCheck::create([
            ...$data,
            'check_sheet_id' => $this->checkSheet->id,
            'operator_id'    => auth()->id(),
            'checked_at'     => now()
        ]);
        $this->dispatch('dailyCheckCreated', $created->id);
    }

    #[On('invalidItems')]
    public function invalidItems(): void {
        $this->addError('items', 'Algunos items no han sido completados');
    }

    public function saveAndReport(): void {
        $this->save();
        $this->reported = true;
    }

    #[On('dailyCheckItemsSaved')]
    public function allSaved(): void {
        Notification::make()
                    ->success()
                    ->icon('heroicon-o-document-text')
                    ->iconColor('success')
                    ->title('Chequeo diario guardado')
                    ->send();

        Notification::make()
                    ->title('Chequeo diario guardado')
                    ->success()
                    ->icon('heroicon-o-document-text')
                    ->iconColor('success')
                    ->body(auth()->user()->name . ' ha completado un chequeo diario para la hoja ' . $this->checkSheet->name)
                    ->actions([
                        Action::make('Ver')
                              ->button()
                              ->url("/admin/check-sheets/{$this->checkSheet->id}/history?startDate=" . now()->format('Y-m-d') . "&endDate=" . now()->format('Y-m-d'))
                    ])
                    ->sendToDatabase(User::whereHas('roles', fn($q) => $q
                        ->where('name', 'admin')
                        ->orWhere('name', 'super_admin')
                    )->get(), isEventDispatched: true);

        if ($this->reported) {
            $this->redirect('https://mantenimientotintoreriatacuba.netlify.app/');
        } else {
            $this->resetState();
        }
    }

    #[On('dailyCheckItemsFailed')]
    public function onError($dailyCheckId): void {
        DailyCheck::destroy($dailyCheckId);
    }

    public function resetState(): void {
        $this->checkSheet = null;
        $this->form->fill();
        $this->page = 1;
    }

    public function render(): View {
        return view('livewire.daily-check.create-daily-check');
    }
}
