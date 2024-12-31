<?php

namespace App\Livewire;

use App\Models\CheckSheetItem;
use Livewire\Attributes\On;
use Livewire\Component;

class CreateItems extends Component
{
    public $columns = [];
    public $rows    = [];

    public $editingCell   = null;
    public $newColumnName = '';

    public function mount() {
        $this->init();
    }

    private function init() {
        $defaultColumns = ['Item de chequeo', 'Frecuencia', 'Metodo de chequeo', 'Criterio de determinacion', 'Observaciones'];
        $this->columns = $defaultColumns;
        $this->addRow();
    }


    public function addColumn() {
        $this->newColumnName = 'Column ' . (count($this->columns) + 1);
        $this->columns[] = $this->newColumnName;
        $this->updateRows();
    }

    public function addRow() {
        $this->rows[] = array_fill(0, count($this->columns), '');
    }

    public function removeColumn($index) {
        unset($this->columns[$index]);
        $this->columns = array_values($this->columns);
        foreach ($this->rows as &$row) {
            unset($row[$index]);
            $row = array_values($row);
        }
    }

    public function removeRow($index) {
        unset($this->rows[$index]);
        $this->rows = array_values($this->rows);
    }

    private function updateRows() {
        $this->rows = array_map(function ($row) {
            return array_pad($row, count($this->columns), '');
        }, $this->rows);
    }

    public function updateCell($rowIndex, $colIndex, $value = null) {
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

    public function startEditing($rowIndex, $colIndex) {
        $this->editingCell = ['rowIndex' => $rowIndex, 'colIndex' => $colIndex];
    }

    public function cancelEditing() {
        $this->editingCell = null;
    }

    public function updateColumnName($index, $name) {
        $this->columns[$index] = $name;
    }

    /**
     * @throws \Exception
     */
    #[On('checkSheetCreated')]
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

        $this->dispatch("checkSheetItemsCreated");
    }

    private function convertRowsToJsons(): array {
        return array_map(function ($row) {
            if (count($row) !== count($this->columns)) {
                throw new \InvalidArgumentException('Number of columns does not match row length.');
            }
            return array_combine($this->columns, $row);
        }, $this->rows);
    }


    public function render() {
        return view('livewire.create-items');
    }
}
