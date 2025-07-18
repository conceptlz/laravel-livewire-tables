@aware(['tableName','isTailwind','isBootstrap4','isBootstrap5'])

<flux:badge size="sm" color="cyan"   x-data="filterPillsHandler({{ json_encode($setupData) }})" x-bind="trigger" 
    wire:key="{{ $tableName }}-filter-pill-{{ $filterKey }}" {{ $attributes->merge($filterPillsItemAttributes) }}>
    <span {{ $attributes->merge($pillTitleDisplayDataArray) }}></span>:&nbsp;
    <span {{ $attributes->merge($pillDisplayDataArray) }}></span>
   <x-livewire-tables::tools.filter-pills.buttons.reset-filter :$filterKey :$filterPillData/>
</flux:badge>
