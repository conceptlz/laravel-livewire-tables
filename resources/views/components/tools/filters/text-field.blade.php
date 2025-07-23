<x-livewire-tables::tools.filter-label :$filter :$filterLayout :$tableName :$isTailwind :$isBootstrap4 :$isBootstrap5 :$isBootstrap />
<div class="mt-4 w-full px-2 space-y-4">
<flux:input :attributes="$filterInputAttributes->merge()" />
</div>