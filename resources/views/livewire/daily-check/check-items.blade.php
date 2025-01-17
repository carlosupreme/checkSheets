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
                                wire:key="desktop-check-status-select-{{ $item['id'] }}"
                                :item-id="$item['id']"
                                :initial-status="$checks[$item['id']]"/>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile View -->
        <div class="block lg:hidden">
            <div class="border-t dark:border-gray-700">
                @foreach($items as $item)
                    <div class="p-4 border-b dark:border-gray-700 last:border-b-0" x-data="{ open: false }" wire:key="item={{$item['id']}}}">
                        <div class="flex items-center justify-between gap-5">
                            <button
                                @click="open = !open"
                                class="text-left focus:outline-none"
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
                            <div class="flex-1">
                                <livewire:daily-check.check-status-select
                                    wire:key="mobile-check-status-select-{{ $item['id'] }}"
                                    :item-id="$item['id']"
                                    :initial-status="$checks[$item['id']]"/>
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
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
