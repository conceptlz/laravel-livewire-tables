<div>
    @if ($paginator->hasPages())
        @php(isset($this->numberOfPaginatorsRendered[$paginator->getPageName()]) ? $this->numberOfPaginatorsRendered[$paginator->getPageName()]++ : $this->numberOfPaginatorsRendered[$paginator->getPageName()] = 1)

        <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
            <div class="flex justify-between flex-1 md:hidden">
                <span>
                    @if ($paginator->onFirstPage())
                        <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md dark:bg-gray-700 dark:text-white dark:border-gray-600">
                            {!! __('pagination.previous') !!}
                        </span>
                    @else
                        <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.before" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 dark:bg-gray-700 dark:text-white dark:ring-gray-600 dark:border-gray-600 dark:hover:bg-gray-600">
                            {!! __('pagination.previous') !!}
                        </button>
                    @endif
                </span>

                <span>
                    @if ($paginator->hasMorePages())
                        <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.before" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 dark:bg-gray-700 dark:text-white dark:ring-gray-600 dark:border-gray-600 dark:hover:bg-gray-600">
                            {!! __('pagination.next') !!}
                        </button>
                    @else
                        <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md dark:bg-gray-700 dark:text-white dark:border-gray-600">
                            {!! __('pagination.next') !!}
                        </span>
                    @endif
                </span>
            </div>

            <div class="hidden md:flex gap-1 items-center justify-end">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <flux:button variant="subtle" size="sm" aria-disabled="true" aria-label="{{ __('pagination.previous') }}" disabled>
                        <x-phosphor-caret-left class="size-4" />
                    </flux:button>
                @else
                    <flux:button variant="subtle" size="sm"  wire:click="previousPage('{{ $paginator->getPageName() }}')" dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after" rel="prev" aria-label="{{ __('pagination.previous') }}">
                        <x-phosphor-caret-left class="size-4" />
                    </flux:button>
                @endif
                {{-- Pagination Elements --}}

                @if ($elements ?? null)
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <flux:button variant="subtle" size="sm" aria-disabled="true">
                                {{ $element }}
                            </flux:button>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                <span wire:key="paginator-{{ $paginator->getPageName() }}-{{ $this->numberOfPaginatorsRendered[$paginator->getPageName()] }}-page{{ $page }}">
                                    @if ($page == $paginator->currentPage())
                                        
                                            <flux:button variant="secondary" size="sm" aria-current="page">
                                            {{ $page }}
                                        </flux:button>
                                    @else
                                            <flux:button variant="subtle" size="sm" wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                            {{ $page }}
                                        </flux:button>
                                    @endif
                                </span>
                            @endforeach
                        @endif
                    @endforeach
                @endif
                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <flux:button variant="subtle" size="sm" wire:click="nextPage('{{ $paginator->getPageName() }}')" dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after" rel="next" aria-label="{{ __('pagination.next') }}">
                        <x-phosphor-caret-right class="size-4" />
                    </flux:button>
                @else
                    <flux:button variant="subtle" size="sm" aria-disabled="true" aria-label="{{ __('pagination.next') }}" disabled>
                        <x-phosphor-caret-right class="size-4" />
                    </flux:button>
                    
                @endif
              
            </div>
        </nav>
    @endif
</div>
