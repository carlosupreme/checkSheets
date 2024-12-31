<x-filament-panels::page>
    <livewire:check-sheet.edit-check-sheet></livewire:check-sheet.edit-check-sheet>

    <livewire:update-items></livewire:update-items>

    <div class="w-1/3">
        <x-filament::button wire:click="update">
            {{ __('Update') }}
        </x-filament::button>
    </div>
</x-filament-panels::page>
