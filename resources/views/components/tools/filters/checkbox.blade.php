<x-livewire-tables::tools.filter-label :$filter :$filterLayout :$tableName :$isTailwind :$isBootstrap4 :$isBootstrap5 :$isBootstrap />
<flux:checkbox.group variant="pills" :attributes="$filterInputAttributes->merge()" class="w-60">
      @foreach($filter->getOptions() as $key => $value)
        @if (is_iterable($value))
        @else
            <flux:checkbox value="{{ $key }}" label="{{ $value }}" />
        @endif
    @endforeach
</flux:checkbox.group>