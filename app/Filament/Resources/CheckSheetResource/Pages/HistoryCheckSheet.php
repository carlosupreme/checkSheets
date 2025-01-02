<?php

namespace App\Filament\Resources\CheckSheetResource\Pages;

use App\Filament\Resources\CheckSheetResource;
use App\Models\CheckSheet;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Livewire\Attributes\Computed;

class HistoryCheckSheet extends Page
{
    protected static string $resource = CheckSheetResource::class;

    protected static string $view = 'filament.resources.check-sheet-resource.pages.history-check-sheet';

    public CheckSheet $record;

    public ?string $startDate = null;
    public ?string $endDate   = null;

    protected $queryString = ['startDate', 'endDate'];

    public function getTitle(): string|Htmlable {
        return 'Historial de ' . $this->record->name;
    }

    public function mount() {
        $this->startDate = $this->startDate ?? now()->subDay()->format('Y-m-d');
        $this->endDate = $this->endDate ?? now()->format('Y-m-d');
    }

    public function form(Form $form): Form {
        return $form
            ->schema([
                Grid::make()->schema([
                    DatePicker::make('startDate')
                              ->label('Fecha de inicio')
                              ->live()
                              ->native(false)
                              ->displayFormat('D d/m/Y')
                              ->maxDate(now())
                              ->required(),
                    DatePicker::make('endDate')
                              ->live()
                              ->label('Fecha de fin')
                              ->native(false)
                              ->displayFormat('D d/m/Y')
                              ->afterOrEqual('startDate')
                              ->maxDate(now())
                              ->required(),
                ])
            ]);
    }


    #[Computed]
    public function dateRange() {
        return collect(CarbonPeriod::create(Carbon::parse($this->startDate), Carbon::parse($this->endDate)))
            ->map(fn($date) => $date->format('Y-m-d'))
            ->toArray();
    }

    #[Computed]
    public function tableData() {
        $data = [
            'items'                => [],
            'operatorSignatures'   => [],
            'supervisorSignatures' => [],
            'checks'               => [],
            'operatorNames'        => []
        ];

        $endDate = Carbon::parse($this->endDate)->addDay()->format('Y-m-d');
        $items = $this->record->dailyChecks()
                              ->whereBetween('checked_at', [$this->startDate, $endDate])
                              ->with('dailyCheckItems.checkStatus')
                              ->get();

        debug($items);

        foreach ($items as $item) {
            $dayOfCheck = Carbon::parse($item->checked_at)->format('Y-m-d');
            $data['checks'][$dayOfCheck] = [];
            $data['operatorSignatures'][$dayOfCheck] = $item->operator_signature;
            $data['supervisorSignatures'][$dayOfCheck] = $item->supervisor_signature;
            $data['operatorNames'][$dayOfCheck] = $item->operator_name;

            foreach ($item->dailyCheckItems as $checkItem) {
                if (!in_array($checkItem->item, $data['items'])) {
                    $data['items'][] = $checkItem->item;
                }

                $icon = [
                    'icon'  => $checkItem->checkStatus?->icon ?? $checkItem->notes,
                    'color' => $checkItem->checkStatus->color
                ];
                $data['checks'][$dayOfCheck][] = $icon;
            }
        }

        return $data;
    }

    #[Computed]
    public function headers() {
        return array_keys($this->tableData['items'][0] ?? []);
    }

    #[Computed]
    public function availableDates() {
        return array_filter($this->dateRange, fn($date) => isset($this->tableData['checks'][$date]) ||
            isset($this->tableData['operatorSignatures'][$date])
        );
    }
}
