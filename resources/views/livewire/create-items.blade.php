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
                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                        <x-filament::icon-button
                            wire:click.prevent="removeRow({{ $rowIndex }})"
                            tooltip="Eliminar fila"
                            color="danger"
                            icon="heroicon-o-trash"
                            wire:loading.attr="disabled"
                        />
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

