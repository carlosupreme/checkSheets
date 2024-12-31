<div>
    <div class="mb-6 border rounded-lg dark:border-gray-700 overflow-auto">
        <!-- Desktop View -->
        <div class="hidden lg:block">
            <table class="table-fixed border-collapse w-full">
                <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    @foreach($headers as $header)
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            {{ $header }}
                        </th>
                    @endforeach
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Check
                    </th>
                </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($items as $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                        @foreach($headers as $header)
                            <td class="px-4 py-4 text-sm text-gray-900 dark:text-gray-300">
                                {{ $item[$header] ?? 'N/A' }}
                            </td>
                        @endforeach
                        <td class="px-1 py-4">
                            <livewire:daily-check.check-status-select
                                wire:key="check-status-select-{{ $item['id'] }}"
                                :item-id="$item['id']"
                                :initial-status="$checks[$item['id']]"/>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <!-- Tablet View -->
        <div class="hidden md:block lg:hidden">
            <div class="grid grid-cols-[1fr,auto] bg-gray-50 dark:bg-gray-800">
                <div class="p-4 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Items
                </div>
                <div class="p-4 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Check
                </div>
            </div>
            @foreach($items as $item)
                <div class="grid grid-cols-[1fr,auto] border-t dark:border-gray-700">
                    <div class="p-4">
                        <h3 class="font-medium text-gray-900 dark:text-gray-100">
                            {{ $item[$headers[0]] ?? 'N/A' }}
                        </h3>
                        <div class="mt-2 grid grid-cols-2 gap-x-4 gap-y-2">
                            @foreach(array_slice($headers, 1, 4) as $header)
                                <div class="text-sm">
                                    <span class="font-medium text-gray-700 dark:text-gray-300">{{ $header }}:</span>
                                    <span
                                        class="text-gray-600 dark:text-gray-400 ml-1">{{ $item[$header] ?? 'N/A' }}</span>
                                </div>
                            @endforeach
                        </div>
                        @if(count($headers) > 5)
                            <button
                                x-data="{ open: false }"
                                @click="open = !open"
                                class="mt-2 text-sm text-blue-600 dark:text-blue-400 hover:underline focus:outline-none"
                            >
                                <span x-text="open ? 'Ver menos' : 'Ver más'"></span>
                            </button>
                            <div
                                x-data="{ open: false }"
                                x-show="open"
                                x-collapse
                                class="mt-2 grid grid-cols-2 gap-x-4 gap-y-2"
                            >
                                @foreach(array_slice($headers, 5) as $header)
                                    <div class="text-sm">
                                            <span
                                                class="font-medium text-gray-700 dark:text-gray-300">{{ $header }}:</span>
                                        <span
                                            class="text-gray-600 dark:text-gray-400 ml-1">{{ $item[$header] ?? 'N/A' }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="p-4 flex items-start">
                        <select
                            wire:model="checks.{{ $item['id'] }}"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600
                                   shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200
                                   focus:ring-opacity-50 dark:bg-gray-700 dark:text-gray-300
                                   dark:focus:border-blue-500 dark:focus:ring-blue-400"
                        >
                            <option value="check">✓</option>
                            <option value="cross">✗</option>
                            <option value="wave">〰</option>
                            <option value="custom">Custom Text</option>
                        </select>
                    </div>
                </div>
                @if($checks[$item['id']] === 'custom')
                    <div class="col-span-2 px-4 pb-4">
                        <input
                            type="text"
                            wire:model="customInputs.{{ $item['id'] }}"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600
                                   shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200
                                   focus:ring-opacity-50 dark:bg-gray-700 dark:text-gray-300
                                   dark:focus:border-blue-500 dark:focus:ring-blue-400
                                   dark:placeholder-gray-500"
                            placeholder="Enter custom text"
                        >
                    </div>
                @endif
            @endforeach
        </div>

        <!-- Mobile View -->
        <div class="md:hidden">
            <div class="border-t dark:border-gray-700">
                @foreach($items as $item)
                    <div class="p-4 border-b dark:border-gray-700 last:border-b-0" x-data="{ open: false }">
                        <div class="flex items-center justify-between">
                            <button
                                @click="open = !open"
                                class="flex-1 text-left focus:outline-none"
                            >
                                <h3 class="font-medium text-gray-900 dark:text-gray-100">
                                    {{ $item[$headers[0]] ?? 'N/A' }}
                                </h3>
                                @if(isset($headers[1]))
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $item[$headers[1]] ?? 'N/A' }}
                                    </p>
                                @endif
                            </button>
                            <div class="ml-4">
                                <select
                                    wire:model="checks.{{ $item['id'] }}"
                                    class="rounded-md border-gray-300 dark:border-gray-600
                                       shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200
                                       focus:ring-opacity-50 dark:bg-gray-700 dark:text-gray-300
                                       dark:focus:border-blue-500 dark:focus:ring-blue-400"
                                >
                                    <option value="check">✓</option>
                                    <option value="cross">✗</option>
                                    <option value="wave">〰</option>
                                    <option value="custom">Custom Text</option>
                                </select>
                            </div>
                        </div>

                        <div x-show="open" x-collapse>
                            <div class="mt-4 space-y-2">
                                @foreach($headers as $index => $header)
                                    @if($index > 1)
                                        <div class="text-sm">
                                            <span
                                                class="font-medium text-gray-700 dark:text-gray-300">{{ $header }}:</span>
                                            <span
                                                class="text-gray-600 dark:text-gray-400 ml-2">{{ $item[$header] ?? 'N/A' }}</span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        @if($checks[$item['id']] === 'custom')
                            <div class="mt-4">
                                <input
                                    type="text"
                                    wire:model="customInputs.{{ $item['id'] }}"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600
                                       shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200
                                       focus:ring-opacity-50 dark:bg-gray-700 dark:text-gray-300
                                       dark:focus:border-blue-500 dark:focus:ring-blue-400
                                       dark:placeholder-gray-500"
                                    placeholder="Enter custom text"
                                >
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
