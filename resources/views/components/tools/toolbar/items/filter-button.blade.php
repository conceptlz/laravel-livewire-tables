@aware([ 'tableName','isTailwind','isBootstrap','isBootstrap4','isBootstrap5', 'localisationPath'])
@props([])
<flux:tooltip content="{{ __($localisationPath.'Filters') }}" position="top">
    <flux:modal.trigger name="{{ $tableName }}-filter-slideout">
        <flux:button square variant="filled" class="relative">
            <x-phosphor-faders-bold class="size-5" />
             @if ($count = $this->getFilterBadgeCount())
                <span class="absolute flex size-2 -top-1 -right-1">
                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-bco-400 opacity-75"></span>
                    <span class="relative inline-flex size-2 rounded-full bg-bco-500"></span>
                </span>
             @endif
        </flux:button>
    </flux:modal.trigger>
</flux:tooltip>
<flux:modal name="{{ $tableName }}-filter-slideout">
</flux:modal> 
