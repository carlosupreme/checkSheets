<div class="bg-white dark:bg-gray-900 sm:px-4 py-5 rounded-lg">
    @if($page === 1)
        <livewire:daily-check.select-check-sheet/>
    @else
        <div class="flex sm:flex-row flex-col gap-4 items-center w-full my-5 place-content-center">
            <div class="flex gap-4 items-center place-content-center">
                <h2 class="font-bold">{{ $this->checkSheet->name }}</h2>
                <x-filament::badge color="warning">{{$this->checkSheet->equipment_tag}}</x-filament::badge>
            </div>
            <x-filament::badge>{{\Carbon\Carbon::now()->format('d/m/Y')}}</x-filament::badge>
        </div>
        @if($this->hasItems())
            <livewire:daily-check.check-items :items=" $checkSheet->items"/>

            <div class="my-5">
                {!! $this->checkSheet->notes !!}
            </div>

            <form>
                {{ $this->form }}
            </form>

            <div class="mt-4">
                @error('items')
                <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex w-full gap-2 my-5 items-center place-content-end">
                <x-filament::button wire:click="saveAndReport"
                                    tooltip="Ir a la pagina de reportes"
                                    color="danger"
                                    class="mt-4"
                >
                    Guardar y reportar
                </x-filament::button>
                <x-filament::button wire:click="save" class="mt-4">Guardar</x-filament::button>
            </div>
            <x-filament-actions::modals/>
        @else
            <div class="bg-gray-50 gap-4 w-full grid place-items-center p-4 mb-4">
                <h2 class="font-bold text-slate-600">No hay items en esta hoja de chequeo</h2>
                <x-filament::button wire:click="resetState" color="gray" class="mt-4">Elegir otra</x-filament::button>
            </div>
        @endif
    @endif
</div>
