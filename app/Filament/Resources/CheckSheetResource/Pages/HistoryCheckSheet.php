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

class HistoryCheckSheet extends Page
{
    protected static string $resource = CheckSheetResource::class;

    protected static string $view = 'filament.resources.check-sheet-resource.pages.history-check-sheet';

    public CheckSheet $record;

    public function getTitle(): string|Htmlable {
        return "Historial de " . $this->record->name;
    }

    public ?string $startDate;
    public ?string $endDate;

    public array $dateRange = [];
    public array $tableData = [];

    public array $headers        = [];
    public array $availableDates = [];

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

    public function query() {
        $this->form->getState();
        $this->dateRange = [];
        $this->availableDates = [];
        $this->tableData = $this->getTableData();
        $this->headers = array_keys($this->tableData['items'][0]);
        $period = CarbonPeriod::create(Carbon::parse($this->startDate), Carbon::parse($this->endDate));
        foreach ($period as $date) {
            $dateString = $date->format('Y-m-d');
            $this->dateRange[] = $dateString;
            if (isset($this->tableData['checks'][$dateString]) ||
                isset($this->tableData['operatorSignatures'][$dateString])) {
                $this->availableDates[] = $dateString;
            }
        }
    }

    public function getTableData(): array {
        $data = [
            'items'                => [],
            'operatorSignatures'   => [],
            'supervisorSignatures' => [],
            'checks'               => [],
            'operatorNames'        => []
        ];

        $items = $this->record->dailyChecks;

        foreach ($items as $item) {
            $dayOfCheck = Carbon::parse($item->checked_at)->format('Y-m-d');
            $data['checks'][$dayOfCheck] = [];
            $data['operatorSignatures'][$dayOfCheck] = $item->operator_signature;
            $data['supervisorSignatures'][$dayOfCheck] = $item->supervisor_signature;
            $data['operatorNames'][$dayOfCheck] = $item->operator_name;
            foreach ($item->dailyCheckItems as $checkItem) {
                $data['items'][] = $checkItem->item;
                $data['checks'][$dayOfCheck][] = $checkItem->checkStatus?->icon ?? $checkItem->notes;
            }
        }

        debug($data);

        return $data;
    }
}
