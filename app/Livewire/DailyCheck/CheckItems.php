<?php

namespace App\Livewire\DailyCheck;

use App\Models\DailyCheckItem;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class CheckItems extends Component
{
    public $items        = [];
    public $headers      = [];
    public $checks       = [];
    public $customInputs = [];

    public function mount(Collection $items) {
        if (!$items->isEmpty() && !is_null($items->first()->properties)) {
            $this->headers = array_keys($items->first()->properties);
            $this->items = $items->map(function ($item) {
                $arr = [];
                $arr['id'] = $item->id;

                foreach ($item->properties as $property => $value) {
                    $arr[$property] = $value;
                }

                return $arr;
            });

            foreach ($this->items as $item) {
                $this->checks[$item['id']] = null;
                $this->customInputs[$item['id']] = null;
            }
        }
    }

    #[On('dailyCheckCreated')]
    public function save(int $id): void {
        try {
            foreach ($this->items as $item) {
                $notes = is_null($this->customInputs[$item['id']]) ? null : $this->customInputs[$item['id']];
                $checkStatus = is_null($this->checks[$item['id']]) ? null : $this->checks[$item['id']];
                unset($item['id']);

                DailyCheckItem::create([
                    'daily_check_id'  => $id,
                    'item'            => $item,
                    'notes'           => $notes,
                    'check_status_id' => $checkStatus
                ]);
            }

            $this->dispatch('dailyCheckItemsSaved');
        } catch (\Exception $e) {
            debug($e);
            $this->dispatch('dailyCheckItemsFailed', $id);
        }
    }

    #[On('dailyCheckItemsStatusChanged')]
    public function onStatusChange(array $data): void {
        if (is_null($data['statusId'])) {
            $this->checks[$data['itemId']] = null;
            $this->customInputs[$data['itemId']] = $data["customText"];
        } else {
            $this->customInputs[$data['itemId']] = null;
            $this->checks[$data['itemId']] = $data["statusId"];
        }
    }

    public function validateItems(): bool {
        foreach ($this->items as $item) {
            $itemId = $item['id'];

            $hasCheck = isset($this->checks[$itemId]) && !is_null($this->checks[$itemId]);
            $hasCustomInput = isset($this->customInputs[$itemId]) && !is_null($this->customInputs[$itemId]);

            if (!$hasCheck && !$hasCustomInput) {
                return false;
            }
        }

        return true;
    }

    #[On('requestForValidItems')]
    public function checkValidItems(): void {
        if ($this->validateItems()) {
            $this->dispatch('validItems');
        } else {
            $this->dispatch('invalidItems');
        }
    }

    public function render() {
        return view('livewire.daily-check.check-items');
    }
}
