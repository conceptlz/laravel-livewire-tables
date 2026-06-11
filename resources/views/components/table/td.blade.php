@aware([ 'row', 'rowIndex', 'tableName', 'primaryKey','isTailwind','isBootstrap','columnCount'])
@props(['column', 'colIndex'])

@php
    $customAttributes = $this->getTdAttributes($column, $row, $colIndex, $rowIndex);
    $td_class = ($columnCount == $colIndex) ? ' ps-4 pe-3 text-right text-sm flex items-center justify-end space-x-4' : 'whitespace-nowrap min-w-min';
    $is_column_fixed = ($column->isFixed() || $this->isColumnFixed($column));
    $isLastFixed = $this->isLastFixedColumn($column, $colIndex);
    $td_class .= "   data-[last-fixed]:after:w-8 data-[last-fixed]:after:absolute data-[last-fixed]:after:inset-y-0 data-[last-fixed]:after:right-0 data-[last-fixed]:after:translate-x-full data-[last-fixed]:after:pointer-events-none in-data-scrolled-right:data-[last-fixed]:after:inset-shadow-[8px_0px_8px_-8px_rgba(0,0,0,0.05)]";
@endphp

<td wire:key="{{ $tableName . '-table-td-'.$row->{$primaryKey}.'-'.$column->getSlug() }}"
    @if ($column->isClickable())
        @if($this->getTableRowUrlTarget($row) === 'navigate') wire:navigate href="{{ $this->getTableRowUrl($row) }}"
        @else onclick="window.open('{{ $this->getTableRowUrl($row) }}', '{{ $this->getTableRowUrlTarget($row) ?? '_self' }}')"
        @endif
    @endif
    @if($is_column_fixed) data-sticky="true" @endif
    @if($isLastFixed) data-last-fixed="true" @endif
    data-column-slug="{{ $column->getSlug() }}"
        {{
            $attributes->merge($customAttributes)
                ->class([
                    $td_class => $isTailwind && ($customAttributes['default'] ?? true),
                    'hidden' =>  $isTailwind && $column && $column->shouldCollapseAlways(),
                    'hidden md:table-cell' => $isTailwind && $column && $column->shouldCollapseOnMobile(),
                    'hidden lg:table-cell' => $isTailwind && $column && $column->shouldCollapseOnTablet(),
                    '' => $isBootstrap && ($customAttributes['default'] ?? true),
                    'd-none' => $isBootstrap && $column && $column->shouldCollapseAlways(),
                    'd-none d-md-table-cell' => $isBootstrap && $column && $column->shouldCollapseOnMobile(),
                    'd-none d-lg-table-cell' => $isBootstrap && $column && $column->shouldCollapseOnTablet(),
                    'laravel-livewire-tables-cursor' => $isBootstrap && $column && $column->isClickable(),
                    'data-[sticky]:sticky data-[sticky]:bg-white' => $is_column_fixed
                ])
                ->except(['default','default-styling','default-colors'])
        }}
    >
        {{ $slot }}
</td>
