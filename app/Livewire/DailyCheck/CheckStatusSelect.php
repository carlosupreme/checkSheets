<?php

namespace App\Livewire\DailyCheck;

use App\Models\CheckStatus;
use Livewire\Component;

class CheckStatusSelect extends Component
{
    public $itemId;
    public $selectedStatus;
    public $customText      = '';
    public $showCustomInput = false;
    public $open            = false;
    public $selectedName    = '';

    public function mount($itemId, $initialStatus) {
        $this->itemId = $itemId;
        $this->selectedStatus = $initialStatus;
        $this->selectedName = CheckStatus::find($initialStatus)?->name ?? '';
    }

    public function updatedCustomText(): void {
        if (!empty($this->customText)) {
            $this->selectedName = 'Personalizado';
            $this->dispatch('dailyCheckItemsStatusChanged', [
                'itemId'     => $this->itemId,
                'customText' => $this->customText,
                'statusId'   => null
            ]);
        }
    }

    public function close(): void {
        $this->open = false;
    }

    public function choose($value): void {
        $this->open = false;
        if ($value === 'custom') {
            $this->showCustomInput = true;
            $this->selectedName = 'Personalizado';
        } else {
            $this->showCustomInput = false;
            $this->selectedName = CheckStatus::find($value)?->name ?? '';
            $this->dispatch('dailyCheckItemsStatusChanged', [
                'itemId'     => $this->itemId,
                'statusId'   => $value,
                'customText' => null
            ]);
        }
    }

    public function render() {
        $statuses = CheckStatus::select('id', 'name', 'icon', 'color')->get();
        return view('livewire.daily-check.check-status-select', [
            'statuses' => $statuses
        ]);
    }
}
