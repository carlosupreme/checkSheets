<x-filament-panels::page>
    <livewire:check-sheet.create-check-sheet></livewire:check-sheet.create-check-sheet>

    <livewire:create-items></livewire:create-items>

    <div class="w-1/3">
        <x-filament::button
            wire:click="create">
            {{ __('Create') }}
        </x-filament::button>
    </div>

</x-filament-panels::page>
