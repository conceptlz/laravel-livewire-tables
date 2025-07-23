<x-livewire-tables::tools.filter-label :$filter :$filterLayout :$tableName :$isTailwind :$isBootstrap4 :$isBootstrap5 :$isBootstrap />
<flux:select :attributes="$filterInputAttributes->merge()" variant="listbox" multiple searchable indicator="checkbox" clearable>
   
    @foreach($filter->getOptions() as $key => $value)
        @if (is_iterable($value))
            <optgroup label="{{ $key }}">
                @foreach ($value as $optionKey => $optionValue)
                    <option value="{{ $optionKey }}">{{ $optionValue }}</option>
                @endforeach
            </optgroup>
        @else
            <flux:select.option value="{{ $key }}" wire:key="{{ $key }}">{{ $value }}</flux:select.option>
        @endif
    @endforeach
</flux:select>
