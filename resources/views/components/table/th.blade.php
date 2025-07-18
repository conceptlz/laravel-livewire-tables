@aware(['isTailwind','isBootstrap','columnCount','tableName'])
@props(['column', 'index'])

@php
    $allThAttributes = $this->getAllThAttributes($column);
    $customThAttributes = $allThAttributes['customAttributes'];
    $customSortButtonAttributes = $allThAttributes['sortButtonAttributes'];
    $customLabelAttributes = $allThAttributes['labelAttributes'];
    $customIconAttributes = $this->getThSortIconAttributes($column);
    $direction = $column->hasField() ? $this->getSort($column->getColumnSelectName()) : $this->getSort($column->getSlug()) ?? null;
    $th_class = ($columnCount == $index) ? 'relative py-4 pl-3 pr-6 bg-zinc-100 shadow-[inset_1px_-1px_rgba(0,0,0,0.1)] shadow-zinc-200 min-w-min w-full' : 'text-left whitespace-nowrap';

@endphp

<th {{
    $attributes->merge($customThAttributes)
        ->class([
            $th_class => $isTailwind,
            'hidden' => $isTailwind && $column->shouldCollapseAlways(),
            'hidden md:table-cell' => $isTailwind && $column->shouldCollapseOnMobile(),
            'hidden lg:table-cell' => $isTailwind && $column->shouldCollapseOnTablet(),
            '' => $isBootstrap && ($customThAttributes['default'] ?? true),
            'd-none' => $isBootstrap && $column->shouldCollapseAlways(),
            'd-none d-md-table-cell' => $isBootstrap && $column->shouldCollapseOnMobile(),
            'd-none d-lg-table-cell' => $isBootstrap && $column->shouldCollapseOnTablet(),
        ])
        ->except(['default', 'default-colors', 'default-styling'])
}}>
    @if($column->getColumnLabelStatus())
        @unless ($this->sortingIsEnabled() && ($column->isSortable() || $column->getSortCallback()))
            <x-livewire-tables::table.th.label :$customLabelAttributes :columnTitle="$column->getTitle()" />
        @else
            @if ($isTailwind)
                <x-livewire-tables::table.th.label :$customLabelAttributes :columnTitle="$column->getTitle()" />
                <div class="ms-2 inline-flex items-center justify-end space-x-0.5" {{ $attributes->merge($customSortButtonAttributes) }}>
                    @if($this->sortingIsEnabled() && ($column->isSortable() || $column->getSortCallback()))
                        <flux:button variant="subtle" size="xs"  wire:click="sortBy('{{ $column->getColumnSortKey() }}')">
                            <x-phosphor-caret-up-down class="size-3" />
                        </flux:button>
                    @endif
                    @if($this->filtersAreEnabled() &&
                            $this->filtersVisibilityIsEnabled() &&
                            $this->hasVisibleFilters() && ($column->hasSecondaryHeader() && $column->hasSecondaryHeaderCallback()))
                        <flux:dropdown position="bottom" align="center" wire:key="{{ $tableName .'-'. $column->getSlug() .'dropdown-'. $index }}">
                            <flux:button variant="subtle" size="xs" wire:key="{{ $tableName .'-'. $column->getSlug() .'filterbutton-'. $index }}" >
                                <x-phosphor-funnel class="size-3" />
                            </flux:button>
                             <flux:popover class="min-w-60 flex flex-col gap-4 shadow-xl">
                                   @if( $column->secondaryHeaderCallbackIsFilter())
                                        {{ $column->getSecondaryHeaderFilter($column->getSecondaryHeaderCallback(), $this->getFilterGenericData) }}    
                                    @elseif($column->secondaryHeaderCallbackIsString())
                                        {{ $column->getSecondaryHeaderFilter($this->getFilterByKey($column->getSecondaryHeaderCallback()), $this->getFilterGenericData) }}
                                    @else
                                        {{ $column->getNewSecondaryHeaderContents($this->getRows) }}
                                    @endif
                             </flux:popover>
                        </flux:dropdown>
                    @endif
                </div>
                
            @elseif ($isBootstrap)
                <div wire:click="sortBy('{{ $column->getColumnSortKey() }}')" {{
                        $attributes->merge($customSortButtonAttributes)
                            ->class([
                                'd-flex align-items-center laravel-livewire-tables-cursor' => (($customSortButtonAttributes['default-styling'] ?? true) || ($customSortButtonAttributes['default'] ?? true))
                            ])
                            ->except(['default', 'default-colors', 'default-styling', 'wire:key'])
                }}>
                    <x-livewire-tables::table.th.label :$customLabelAttributes :columnTitle="$column->getTitle()" />
                    <x-livewire-tables::table.th.sort-icons :$direction :$customIconAttributes />

                </div>
            @endif

        @endunless
    @endif
</th>
