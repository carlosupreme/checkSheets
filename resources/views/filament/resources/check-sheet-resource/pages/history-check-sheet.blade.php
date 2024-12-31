<x-filament-panels::page>
    <div class="flex items-center gap-4">
        <x-filament::badge class="max-w-fit"> Area: {{$record->equipment_area}}</x-filament::badge>
        <x-filament::badge class="max-w-fit"> Tag: {{$record->equipment_tag}}</x-filament::badge>
        <x-filament::badge class="max-w-fit"> Nombre: {{$record->equipment_name}}</x-filament::badge>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-lg p-4">

        <div class="flex items-center gap-4 mb-5">

            <form>
                {{$this->form}}
            </form>

            <x-filament::button wire:click.prevent="query">Buscar</x-filament::button>

        </div>

        @if(count($tableData) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse border border-gray-200 dark:border-gray-700">
                    <!-- Header Row with Dates -->
                    <thead>
                    <tr>
                        <!-- Left side headers -->
                        @foreach($headers as $header)
                            <th class="border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 p-2 text-left whitespace-nowrap text-xs font-medium text-gray-500 dark:text-gray-400">
                                {{ $header }}
                            </th>
                        @endforeach

                        <!-- Date columns -->
                        @foreach($availableDates as $day)
                            <th class="border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 p-2 text-center w-16 text-xs font-medium text-gray-500 dark:text-gray-400">
                                {{ Carbon\Carbon::parse($day)->format('d/m/Y') }}
                            </th>
                        @endforeach
                    </tr>
                    </thead>

                    <!-- Table Body -->
                    <tbody>
                    @foreach($tableData['items'] as $index => $item)
                        <tr>
                            <!-- Item data columns -->
                            @foreach($headers as $header)
                                <td class="border border-gray-200 dark:border-gray-700 p-2 text-xs dark:text-gray-300">
                                    {{ $item[$header] }}
                                </td>
                            @endforeach

                            <!-- Check status columns for each date -->
                            @foreach($availableDates as $day)
                                <td class="border border-gray-200 dark:border-gray-700 p-2 text-center">
                                    <div class="flex justify-center items-center">
                                        @if(isset($tableData['checks'][$day][$index]))
                                            <x-dynamic-component
                                                :component="$tableData['checks'][$day][$index]"
                                                class="w-6 h-6 text-green-500"
                                            />
                                        @else
                                            <span
                                                class="inline-block w-6 h-6 rounded-full border-2 border-gray-300 dark:border-gray-600 flex items-center justify-center text-xs">
                                        ○
                                    </span>
                                        @endif
                                    </div>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach

                    <!-- Operator Name Row -->
                    <tr>
                        <td colspan="{{ count($headers) }}"
                            class="border border-gray-200 dark:border-gray-700 p-2 font-medium text-xs">
                            Nombre del Operador
                        </td>
                        @foreach($availableDates as $day)
                            <td class="border border-gray-200 dark:border-gray-700 p-2 text-center">
                                <span class="text-xs">{{ $tableData['operatorNames'][$day] }}</span>
                            </td>
                        @endforeach
                    </tr>

                    <!-- Operator Signature Row -->
                    <tr>
                        <td colspan="{{ count($headers) }}"
                            class="border border-gray-200 dark:border-gray-700 p-2 font-medium text-xs">
                            Firma del Operador
                        </td>
                        @foreach($availableDates as $day)
                            <td class="border border-gray-200 dark:border-gray-700 p-2 text-center">
                                @if(isset($tableData['operatorSignatures'][$day]))
                                    <img src="{{ $tableData['operatorSignatures'][$day] }}" alt="Firma del Operador"
                                         class="w-16 h-16 mx-auto">
                                @else
                                    <div class="flex justify-center items-center">
                                <span
                                    class="inline-block w-6 h-6 rounded-full border-2 border-gray-300 dark:border-gray-600 flex items-center justify-center text-xs">
                                    ○
                                </span>
                                    </div>
                                @endif
                            </td>
                        @endforeach
                    </tr>

                    <!-- Supervisor Signature Row -->
                    <tr>
                        <td colspan="{{ count($headers) }}"
                            class="border border-gray-200 dark:border-gray-700 p-2 font-medium text-xs">
                            Firma del Supervisor
                        </td>
                        @foreach($availableDates as $day)
                            <td class="border border-gray-200 dark:border-gray-700 p-2 text-center">
                                @if(isset($tableData['supervisor_signature'][$day]))
                                    <img src="{{ $tableData['supervisor_signature'][$day] }}" alt="Firma del Operador"
                                         class="w-16 h-8 mx-auto">
                                @else
                                    <div class="flex justify-center items-center">
                                <span
                                    class="inline-block w-6 h-6 rounded-full border-2 border-gray-300 dark:border-gray-600 flex items-center justify-center text-xs">
                                    /
                                </span>
                                    </div>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    <x-filament-actions::modals/>
</x-filament-panels::page>
