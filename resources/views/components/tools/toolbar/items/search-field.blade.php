@aware(['isTailwind', 'isBootstrap'])
@aware(['isTailwind', 'isBootstrap'])
@php
    $modelDirective['wire:model' . $this->getSearchOptions()] = "search";
    $modelDirective = array_merge($this->getSearchFieldAttributes(),$modelDirective);
@endphp
<flux:input icon="magnifying-glass" 
    placeholder="{{ $this->getSearchPlaceholder() }}"
    type="text" 
    {{ $attributes->merge($modelDirective) }}>
    @if ($this->hasSearch)
        <x-slot name="iconTrailing">
            <flux:button size="sm" variant="subtle" icon="x-mark" class="-mr-1" wire:click="clearSearch"/>
        </x-slot>
     @endif
</flux:input>

