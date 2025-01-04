<div>
    <div class="overflow-x-auto">
        <table class="w-full border-collapse rounded-lg overflow-hidden">
            <thead>
            <tr>
                <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-left">
                    <span class="font-bold text-gray-800 dark:text-white">N°</span>
                </th>
                @foreach ($columns as $index => $column)
                    <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-left">
                        <div class="flex items-center justify-between">
                            <input type="text" wire:model.live="columns.{{ $index }}"
                                   class="bg-transparent border-none font-bold text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 rounded transition-colors duration-200"
                                   aria-label="Column name"
                            />
                            <x-filament::icon-button
                                tooltip="Eliminar columna"
                                wire:loading.attr="disabled"
                                wire:click.prevent="removeColumn({{ $index }})"
                                icon="heroicon-o-trash"
                                color="danger"
                                wire:confirm="Seguro de eliminar esta columna?"
                            />
                        </div>
                    </th>
                @endforeach
                <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 bg-gray-100 dark:bg-gray-700">
                    <x-filament::icon-button
                        wire:click.prevent="addColumn"
                        wire:loading.attr="disabled"
                        tooltip="Agregar columna"
                        color="custom"
                        icon="heroicon-o-plus"
                    />
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach ($rows as $rowIndex => $row)
                <tr class="{{ $loop->even ? 'bg-gray-50 dark:bg-gray-800' : '' }}">
                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">
                        {{ $rowIndex + 1 }}
                    </td>
                    @foreach ($row as $colIndex => $cell)
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2"
                            wire:key="cell-{{$rowIndex}}-{{$colIndex}}">
                            <div class="flex items-center justify-between">
                                @if($editingCell && $editingCell['rowIndex'] === $rowIndex && $editingCell['colIndex'] === $colIndex)
                                    <input type="text"
                                           wire:model.live="rows.{{$rowIndex}}.{{$colIndex}}"
                                           class="flex-grow bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded px-2 py-1 text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200"
                                           aria-label="Edit cell content"
                                    />
                                @else
                                    <span class="text-gray-800 dark:text-gray-200">{{ $cell }}</span>
                                    <x-filament::icon-button wire:click="startEditing({{ $rowIndex }}, {{ $colIndex }})"
                                                             tooltip="Editar"
                                                             icon="heroicon-o-pencil"
                                                             wire:loading.attr="disabled"
                                    >
                                    </x-filament::icon-button>
                                @endif
                            </div>
                        </td>
                    @endforeach
                    <td class="border-r border-r-gray-300 h-full dark:border-r-gray-600 px-4 py-2 flex items-center gap-4">
                        <x-filament::icon-button
                            wire:click.prevent="removeRow({{ $rowIndex }})"
                            tooltip="Eliminar fila"
                            color="danger"
                            icon="heroicon-o-trash"
                            wire:loading.attr="disabled"
                            class="h-full"
                        />
                        <x-filament::modal id="addAlertModal"
                                           icon="heroicon-o-information-circle"
                                           icon-color="primary"
                                           width="xl"
                                           :close-by-clicking-away="false"
                                           :close-by-escaping="false"

                        >
                            <x-slot name="trigger">
                                <x-filament::icon-button
                                    tooltip="Agregar alerta"
                                    color="warning"
                                    icon="heroicon-o-exclamation-circle"
                                    wire:loading.attr="disabled"
                                />
                            </x-slot>
                            <x-slot name="heading">Agregar un disparador de alerta para este item</x-slot>
                            <x-slot name="description">Disparar alerta cuando:</x-slot>

                            <div class="flex flex-col justify-center gap-4" x-data="{
                                                showCustomInput: false,
                                                selectedName: '',
                                                open: false,
                                                customText: '',
                                                selectedStatus: null,
                                                setCustom() {this.selectedName = 'Personalizado'; this.selectedStatus = null; this.open = false; this.showCustomInput = true;},
                                                choose(id, name) {this.selectedName = name; this.selectedStatus = id; this.open = false; this.customText = '', this.showCustomInput = false;}
                                           }">
                                <p>Este item sea marcado con:</p>

                                <div class="relative">
                                    <button
                                        @click="open = !open"
                                        type="button"
                                        class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-4 py-2 inline-flex justify-between items-center text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                    >
                                        <span x-text="selectedName"></span>
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                             viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                  d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </button>

                                    <div
                                        x-show="open"
                                        @click.away="open = !open"
                                        x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="transform opacity-0 scale-95"
                                        x-transition:enter-end="transform opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-75"
                                        x-transition:leave-start="transform opacity-100 scale-100"
                                        x-transition:leave-end="transform opacity-0 scale-95"
                                        class="absolute z-10 mt-1 w-full flex flex-col gap-2 bg-white dark:bg-gray-700 shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm"
                                    >
                                        @foreach ($statuses as $status)
                                            <button @click="choose({{ $status->id }}, '{{ $status->name }}')"
                                                    class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-gray-100 dark:hover:bg-gray-600">
                                                <div class="flex items-center">
                                                        <span class="mr-3" style="color: {{ $status->color }}">
                                                            <x-dynamic-component :component="$status->icon"
                                                                                 class="h-5 w-5"/>
                                                        </span>
                                                    <span
                                                        class="font-normal block truncate min-w-fit">{{ $status->name }}</span>
                                                </div>
                                            </button>
                                        @endforeach

                                        <div
                                            @click="setCustom()"
                                            class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-gray-100 dark:hover:bg-gray-600"
                                        >
                                            <div class="flex items-center">
                                                <span class="text-gray-600 dark:text-gray-400 mr-3">
                                                    <x-heroicon-o-pencil class="h-5 w-5"/>
                                                </span>
                                                <span class="font-normal block truncate">
                                                    Personalizado
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div x-show="showCustomInput" class="mt-2">
                                        <x-filament::input.wrapper>
                                            <x-filament::input
                                                x-model="customText"
                                                placeholder="Escribe el estado"
                                                class="w-full"
                                            />
                                        </x-filament::input.wrapper>
                                    </div>
                                </div>

                                <x-filament::button
                                    x-show="customText.length || selectedStatus != null"
                                    @click.prevent="$wire.addAlert({{ $rowIndex }}, selectedStatus, customText)">
                                    Establecer
                                </x-filament::button>
                            </div>
                        </x-filament::modal>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 bg-gray-50 dark:bg-gray-700"></td>
                @foreach ($columns as $index => $column)
                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 bg-gray-50 dark:bg-gray-700"></td>
                @endforeach
                <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 bg-gray-50 dark:bg-gray-700">
                    <x-filament::icon-button
                        wire:click.prevent="addRow"
                        wire:loading.attr="disabled"
                        tooltip="Agregar fila"
                        color="custom"
                        icon="heroicon-o-plus"
                    />
                </td>
            </tr>
            </tbody>
        </table>
        <br>
    </div>

    <div class="mt-6 text-sm text-gray-600 dark:text-gray-400 flex items-center">
        <x-heroicon-o-information-circle class="w-6 h-6"/>
        <span>Tip: {{__("Click the pencil icon to edit a cell's content. Use the '+' buttons to add rows or columns")}}.</span>
    </div>
</div>

