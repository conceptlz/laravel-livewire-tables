<x-livewire-tables::tools.filter-label :$filter :$filterLayout :$tableName :$isTailwind :$isBootstrap4 :$isBootstrap5 :$isBootstrap />
 <flux:select
        wire:model.defer="filterComponents.{{ $filter->getKey() }}.condition"
        :placeholder="__('Condition')"
        wire:key="{{ $tableName }}-{{ $filter->getKey() }}-condition"
        class="w-full"
    >
    @foreach($filter->getConditions() as $value => $label)
        <flux:select.option value="{{$value}}">{{ $label }}</flux:select.option>
    @endforeach
</flux:select>
<flux:input :attributes="$filterInputAttributes->merge()"/>