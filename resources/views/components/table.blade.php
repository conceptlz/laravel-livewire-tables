@aware([ 'tableName','isTailwind','isBootstrap','appliedFilters'])

@php
    $customAttributes = [
        'wrapper' => $this->getTableWrapperAttributes(),
        'table' => $this->getTableAttributes(),
        'thead' => $this->getTheadAttributes(),
        'tbody' => $this->getTbodyAttributes(),
    ];
@endphp

@if ($isTailwind)
    <div
        wire:key="{{ $tableName }}-twrap"
        {{ $attributes->merge($customAttributes['wrapper'])
            ->class([
                'mt-6 flow-root relative' => $customAttributes['wrapper']['default'] ?? true
            ])
            ->except(['default','default-styling','default-colors']) }}
    >
    <div class="overflow-x-auto shadow-md ring-1 ring-zinc-200 ring-opacity-5 sm:rounded-lg">
            <div class="inline-block overflow-hidden min-w-full align-middle ">
                <table
                    wire:key="{{ $tableName }}-table"
                    {{ $attributes->merge($customAttributes['table'])
                        ->class(['min-w-full block overflow-auto h-[calc(100vh-300px)]' => $customAttributes['table']['default'] ?? true])
                        ->except(['default','default-styling','default-colors']) }}

                >
                    <thead wire:key="{{ $tableName }}-thead"
                        {{ $attributes->merge($customAttributes['thead'])
                            ->class([
                                'text-sm' => $customAttributes['thead']['default'] ?? true
                            ])
                            ->except(['default','default-styling','default-colors']) }}
                    >
                        <tr class="[&>th]:sticky [&>th]:top-0 [&>th]:z-20 [&>th]:bg-zinc-100 [&>th]:ps-4 [&>th]:pe-2 [&>th]:shadow-[inset_1px_-1px_rgba(0,0,0,0.9)] [&>th]:shadow-zinc-200 [&>th]:text-zinc-950 [&>th]:py-2">
                            {{ $thead }}
                        </tr>
                        @if($this->showSortPillsSection)
                        <tr class="[&>th]:bg-zinc-100 [&>th]:ps-4 [&>th]:pe-2 [&>th]:shadow-[inset_1px_-1px_rgba(0,0,0,0.9)] [&>th]:shadow-zinc-200 [&>th]:text-zinc-950 [&>th]:py-2">
                            <x-livewire-tables::table.th.sorting-pills />
                        </tr>
                        @endif
                         @if($this->showFilterPillsSection)
                        <tr class="[&>th]:bg-zinc-100 [&>th]:ps-4 [&>th]:pe-2 [&>th]:shadow-[inset_1px_-1px_rgba(0,0,0,0.9)] [&>th]:shadow-zinc-200 [&>th]:text-zinc-950 [&>th]:py-2">
                            <x-livewire-tables::table.th.filter-pills />
                        </tr>
                        @endif
                    </thead>

                    <tbody
                        wire:key="{{ $tableName }}-tbody"
                        id="{{ $tableName }}-tbody"
                        {{ $attributes->merge($customAttributes['tbody'])
                                ->class([
                                    'divide-y divide-zinc-200 overflow-y-auto [&>tr>td]:py-3 [&>tr>td]:px-4 [&>tr>td]:text-zinc-800 [&>tr>td]:text-sm' => $customAttributes['tbody']['default'] ?? true
                                ])
                                ->except(['default','default-styling','default-colors']) }}
                    >
                        {{ $slot }}
                    </tbody>

                    @isset($tfoot)
                        <tfoot wire:key="{{ $tableName }}-tfoot">
                            {{ $tfoot }}
                        </tfoot>
                    @endisset
                </table>
            </div>
        </div>
    </div>
@elseif ($isBootstrap)
    <div wire:key="{{ $tableName }}-twrap"
        {{ $attributes->merge($customAttributes['wrapper'])
            ->class(['table-responsive' => $customAttributes['wrapper']['default'] ?? true])
            ->except(['default','default-styling','default-colors']) }}
    >
        <table
            wire:key="{{ $tableName }}-table"
            {{ $attributes->merge($customAttributes['table'])
                ->class(['laravel-livewire-table table' => $customAttributes['table']['default'] ?? true])
                ->except(['default','default-styling','default-colors'])
            }}
        >
            <thead
                wire:key="{{ $tableName }}-thead"
                {{ $attributes->merge($customAttributes['thead'])
                    ->class(['' => $customAttributes['thead']['default'] ?? true])
                    ->except(['default','default-styling','default-colors']) }}
            >
                <tr>
                    {{ $thead }}
                </tr>
            </thead>

            <tbody
                wire:key="{{ $tableName }}-tbody"
                id="{{ $tableName }}-tbody"
                {{ $attributes->merge($customAttributes['tbody'])
                        ->class(['' => $customAttributes['tbody']['default'] ?? true])
                        ->except(['default','default-styling','default-colors']) }}
            >
                {{ $slot }}
            </tbody>

            @isset($tfoot)
                <tfoot wire:key="{{ $tableName }}-tfoot">
                    {{ $tfoot }}
                </tfoot>
            @endisset
        </table>
    </div>
@endif
