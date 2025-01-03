<x-filament-panels::page>
    <livewire:check-sheet.edit-check-sheet :record="$record"></livewire:check-sheet.edit-check-sheet>

    <livewire:update-items :record="$record"></livewire:update-items>

    <div class="w-1/3">
        <x-filament::button wire:click="update">
            {{ __('Update') }}
        </x-filament::button>
    </div>
</x-filament-panels::page>
