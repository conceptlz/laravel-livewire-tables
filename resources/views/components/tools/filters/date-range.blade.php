
<x-livewire-tables::tools.filter-label :$filter :$filterLayout :$tableName :$isTailwind :$isBootstrap4 :$isBootstrap5 :$isBootstrap />
<flux:date-picker mode="range"  :attributes="$filterInputAttributes->merge()" locale="{{ $filter->getConfig('locale') }}" 
min="{{ $filter->getConfig('earliestDate') }}" max="{{ $filter->getConfig('latestDate') }}" 
/>