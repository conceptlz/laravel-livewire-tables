@aware([ 'tableName'])
@props(['filter', 'filterLayout' => 'popover', 'tableName' => 'table', 'isTailwind' => false, 'isBootstrap' => false, 'isBootstrap4' => false, 'isBootstrap5' => false, 'for' => null])

@php
    $filterLabelAttributes = $filter->getFilterLabelAttributes();
    $customLabelAttributes = $filter->getLabelAttributes();
@endphp


@if($filter->hasCustomFilterLabel() && !$filter->hasCustomPosition())
    @include($filter->getCustomFilterLabel(),['filter' => $filter, 'filterLayout' => $filterLayout, 'tableName' => $tableName, 'isTailwind' => $isTailwind, 'isBootstrap' => $isBootstrap, 'isBootstrap4' => $isBootstrap4, 'isBootstrap5' => $isBootstrap5, 'customLabelAttributes' => $customLabelAttributes])
@elseif(!$filter->hasCustomPosition())
    <flux:heading   for="{{ $for ?? $tableName.'-filter-'.$filter->getKey() }}"
                    {{
                    $attributes->merge($customLabelAttributes)->merge($filterLabelAttributes)
                    ->class(['!font-bold text-bco-600 uppercase' => $isTailwind && ($filterLabelAttributes['default'] ?? true)]) }}>
        {{ __("Filter by")}} {{ $filter->getName() }}
    </flux:heading>
@else
    <flux:heading   for="{{ $for ?? $tableName.'-filter-'.$filter->getKey() }}"
                    {{
                    $attributes->merge($customLabelAttributes)->merge($filterLabelAttributes)
                    ->class(['!font-bold text-bco-600' => $isTailwind && ($filterLabelAttributes['default'] ?? true)]) }}>
        {{ __("Filter by")}} {{ $filter->getName() }}
    </flux:heading>
@endif
                                                
