<x-livewire-tables::tools.filter-label :$filter :$filterLayout :$tableName :$isTailwind :$isBootstrap4 :$isBootstrap5 :$isBootstrap />
<flux:date-picker  :attributes="$filterInputAttributes->merge()" locale="{{ $filter->getConfig('locale') }}" 
/>