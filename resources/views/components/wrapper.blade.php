@props(['component', 'tableName', 'primaryKey', 'isTailwind', 'isBootstrap','isBootstrap4', 'isBootstrap5','columnCount','appliedFilters'])
<div wire:key="{{ $tableName }}-wrapper" {{ $this->getTopLevelAttributes() }} {{ $attributes->merge($this->getComponentWrapperAttributes()) }}
     @if ($this->hasRefresh()) wire:poll{{ $this->getRefreshOptions() }} @endif
    @if ($this->isFilterLayoutSlideDown()) wire:ignore.self @endif>
    
        @if ($this->debugIsEnabled())
                @include('livewire-tables::includes.debug')
        @endif
        @if ($this->offlineIndicatorIsEnabled())
            @include('livewire-tables::includes.offline')
        @endif

    {{ $slot }}
</div>
