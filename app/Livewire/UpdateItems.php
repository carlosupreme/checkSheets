<?php

namespace App\Livewire;

use App\Models\CheckSheet;
use App\Models\CheckSheetItem;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class UpdateItems extends Component
{
    public $columns = [];
    public $rows    = [];

    public $editingCell   = null;
    public $newColumnName = '';

    public $items = [];

    public function mount(CheckSheet $record) {
        $this->items = $record->items;

        $firstItem = $this->items->first();

        if (!$firstItem || empty($firstItem->properties)) {
            $this->init();
            return;
        }

        $keys = array_keys($firstItem->properties);

        if (count($keys) == 0) {
            $this->init();
        } else {
            $this->columns = $keys;
        }

        $this->items->each(function ($item) use ($keys) {
            $row = [];
            array_map(function ($value) use ($item, $keys, &$row) {
                if (is_null($item->properties))
                    $row[] = '';
                else
                    $row[] = $item->properties[$value];
            }, $keys);
            $this->rows[] = $row;
        });
    }

    public function init(): void {
        $defaultColumns = ['Item de chequeo', 'Frecuencia', 'Metodo de chequeo', 'Criterio de determinacion', 'Observaciones'];
        $this->columns = $defaultColumns;
        $this->addRow();
    }

    public function addColumn(): void {
        $this->newColumnName = 'Column ' . (count($this->columns) + 1);
        $this->columns[] = $this->newColumnName;
        $this->updateRows();
    }

    public function addRow(): void {
        if (count($this->columns) == 0)
            $this->addError('row', 'Para agregar una fila debe haber al menos una columna');
        else
            $this->rows[] = array_fill(0, count($this->columns), '');
    }

    public function removeColumn($index): void {
        unset($this->columns[$index]);
        $this->columns = array_values($this->columns);
        foreach ($this->rows as &$row) {
            unset($row[$index]);
            $row = array_values($row);
        }
    }

    public function removeRow($index): void {
        unset($this->rows[$index]);
        $this->rows = array_values($this->rows);
    }

    private function updateRows(): void {
        $this->rows = array_map(function ($row) {
            return array_pad($row, count($this->columns), '');
        }, $this->rows);
    }

    public function updateCell($rowIndex, $colIndex, $value = null): void {
        if ($value === null) {
            // Toggle editing state
            $this->editingCell = $this->editingCell && $this->editingCell['rowIndex'] === $rowIndex && $this->editingCell['colIndex'] === $colIndex
                ? null
                : ['rowIndex' => $rowIndex, 'colIndex' => $colIndex];
        } else {
            // Update cell value and exit editing state
            $this->rows[$rowIndex][$colIndex] = $value;
            $this->editingCell = null;
        }
    }

    public function startEditing($rowIndex, $colIndex): void {
        $this->editingCell = ['rowIndex' => $rowIndex, 'colIndex' => $colIndex];
    }

    public function cancelEditing(): void {
        $this->editingCell = null;
    }

    public function updateColumnName($index, $name): void {
        $this->columns[$index] = $name;
    }


    #[On('checkSheetUpdated')]
    public function updateOrCreateItems(int $id): void {
        if (empty($this->columns)) {
            return;
        }

        if (empty($this->items->first())) {
            $this->createItems($id);
        } else {
            $this->updateItems($id);
        }

        $this->dispatch('checkSheetItemsUpdated', $id);
    }

    public function createItems(int $id): void {
        collect($this->convertRowsToJsons())->map(function ($properties, $i) {
            return [
                'order'      => $i + 1,
                'properties' => $properties
            ];
        })->each(function ($itemData) use ($id) {
            CheckSheetItem::create([
                'check_sheet_id' => $id,
                'order'          => $itemData['order'],
                'properties'     => $itemData['properties']
            ]);
        });
    }

    public function updateItems(int $recordId): void {
        $order = 1;
        $items = collect($this->convertRowsToJsons())->map(function ($properties) use ($order) {
            return [
                'order'      => $order++,
                'properties' => $properties
            ];
        });

        $this->items->each(function ($item, $i) use ($order, &$items) {
            $item->update([
                'order'      => $order,
                'properties' => $items->get($i) ? $items[$i]['properties'] : null
            ]);

            if ($items->get($i) !== null) {
                $items = $items->forget($i);
            }
        });

        $items->each(function ($itemData) use ($order, $recordId) {
            CheckSheetItem::create([
                'check_sheet_id' => $recordId,
                'order'          => $order++,
                'properties'     => $itemData['properties']
            ]);
        });
    }

    private function convertRowsToJsons(): array {
        return array_map(function ($row) {
            if (count($row) !== count($this->columns)) {
                throw new \InvalidArgumentException('Number of columns does not match row length.');
            }
            return array_combine($this->columns, $row);
        }, $this->rows);
    }


    public function render(): View {
        return view('livewire.update-items');
    }
}
