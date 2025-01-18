<?php

namespace App\Filament\Resources\CheckSheetResource\Pages;

use App\Filament\Resources\CheckSheetResource;
use App\Models\CheckSheet;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Symfony\Component\HttpFoundation\Response;

class HistoryCheckSheet extends Page
{
    protected static string $resource = CheckSheetResource::class;

    protected static string $view = 'filament.resources.check-sheet-resource.pages.history-check-sheet';

    public CheckSheet $record;

    public ?string $startDate = null;
    public ?string $endDate   = null;

    protected array $queryString = ['startDate', 'endDate'];

    public function getTitle(): string|Htmlable {
        return 'Historial de ' . $this->record->name;
    }

    public function mount(): void {
        $this->startDate = $this->startDate ?? now()->subWeek()->format('Y-m-d');
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
    public function dateRange(): array {
        return collect(CarbonPeriod::create(Carbon::parse($this->startDate), Carbon::parse($this->endDate)))
            ->map(fn($date) => $date->format('Y-m-d'))
            ->toArray();
    }


    private function normalizeArrayKeys(array $items, int $limit): array {
        if (empty($items)) {
            return [];
        }

        $allKeys = [];
        foreach ($items as $item) {
            $allKeys = array_unique(array_merge($allKeys, array_keys($item)));
        }

        $normalizedItems = array_map(function ($item) use ($allKeys) {
            return Arr::only(array_merge(array_fill_keys($allKeys, ''), $item), $allKeys);
        }, $items);

        if (count($normalizedItems) > $limit) {
            $normalizedItems = array_slice($normalizedItems, count($normalizedItems) - $limit, $limit);
        }

        return $normalizedItems;
    }

    #[Computed]
    public function tableData(): array {
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

        foreach ($items as $item) {
            $dayOfCheck = Carbon::parse($item->checked_at)->format('Y-m-d');
            $data['checks'][$dayOfCheck] = [];
            $data['operatorSignatures'][$dayOfCheck] = $item->operator_signature;
            $data['supervisorSignatures'][$dayOfCheck] = $item->supervisor_signature;
            $data['operatorNames'][$dayOfCheck] = $item->operator_name;

            foreach ($item->dailyCheckItems as $checkItem) {
                $data['items'][] = $checkItem->item;

                $value = [
                    'icon'  => $checkItem->checkStatus?->icon,
                    'color' => $checkItem->checkStatus?->color,
                    'text'  => $checkItem->notes
                ];

                $data['checks'][$dayOfCheck][] = $value;
            }
        }

        $data['items'] = $this->normalizeArrayKeys($data['items'], count(collect($data['checks'])->first()));

        return $data;
    }

    #[Computed]
    public function headers(): array {
        return array_keys($this->tableData['items'][0] ?? []);
    }

    #[Computed]
    public function availableDates(): array {
        return array_filter($this->dateRange, fn($date) => isset($this->tableData['checks'][$date]) ||
            isset($this->tableData['operatorSignatures'][$date])
        );
    }

    protected function getActions(): array {
        return [
            Action::make('exportPdf')
                  ->label('Exportar a PDF')
                  ->icon('heroicon-o-document-arrow-down')
                  ->action(fn() => $this->exportPdf()),
        ];
    }

    private function exportPdf(): Response {
        $headers = $this->headers;
        $tableData = $this->tableData;
        $availableDates = $this->availableDates;
        $record = $this->record;

        $dateChunks = array_chunk($availableDates, 15);
        $chunks = array_map(function ($dates) {
            return ['dates' => $dates];
        }, $dateChunks);

        $view = 'filament.resources.check-sheet-resource.pages.pdf-check-sheet';
        $pdf = Pdf::loadView($view, compact('chunks', 'headers', 'tableData', 'record'))
                  ->setPaper('a4', 'landscape')
                  ->setOption('isHtml5ParserEnabled', true)
                  ->setOption('isPhpEnabled', true)
                  ->setOption('isRemoteEnabled', true);


        // Create a unique file name
        $fileName = 'checksheet-history-' . Str::random(10) . '.pdf';

        // Download the pdf
        return response()->streamDownload(
            fn() => print($pdf->output()),
            $fileName
        );
    }
}
