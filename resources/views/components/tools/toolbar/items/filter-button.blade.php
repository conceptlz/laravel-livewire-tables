@aware([ 'tableName','isTailwind','isBootstrap','isBootstrap4','isBootstrap5', 'localisationPath','appliedFilters'])
@props([''])
@php
$applied_filter_keys = array_keys($appliedFilters);
@endphp
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
<flux:modal name="{{ $tableName }}-filter-slideout" variant="flyout" :dismissible=true class="w-124" x-data="{ 
        selectedFilters: $wire.entangle('appliedFilters'),
        reactiveKey: 0,

        selectFilter(key) {
            console.log(this.selectedFilters);
            if (!this.selectedFilters.hasOwnProperty(key)) {
                this.selectedFilters = { ...this.selectedFilters, [key]: {} };
                this.reactiveKey++; // force reactivity
            }
            console.log(this.selectedFilters);
            console.log(this.selectedFilters.hasOwnProperty(key));
        },
        removeFilter(key) {
            delete this.selectedFilters[key];
        },
        isSelected(key) {
        console.log('selet' + this.selectedFilters);
        console.log(key);
            return this.selectedFilters.hasOwnProperty(key);
        }
    }">
    <flux:heading size="xl">{{ __("Filter your view") }}</flux:heading>
    <div class="overflow-y-auto divide-y-2 divide-solid divide-zinc-200 ">
           
                
                    <!-- Selected Filters -->
                    <template x-if="selectedFilters.length === 0">
                         <section class="py-6 flex items-center justify-between gap-6 w-full">
                            <p class="text-gray-500 ">{{ __("No filters selected") }}</p>
                        </section>
                    </template>
                   @foreach($this->getFilters() as $filterKey => $filter)
                        <div x-show="isSelected('{{ $filter->getKey() }}')"
                            x-transition
                            class="py-6 flex flex-col gap-4" wire:key="filter-{{ $filterKey }}"
                        >
                           {{ $filter->setGenericDisplayData($this->getFilterGenericData)->render() }}
                        </div>
                    @endforeach

            <section class="py-6 flex items-center justify-between gap-6 w-full">
                <flux:button variant="primary" x-on:click="$wire.dispatch('refreshDatatable');$flux.modal('{{ $tableName }}-filter-slideout').close()">
                    <x-phosphor-faders class="size-5" />
                    {{ __("Apply filters")}}
                </flux:button>
                <flux:dropdown position="bottom" align="center">
                <flux:button variant="filled">
                    <x-phosphor-funnel class="size-5" />
                    {{ __("Add a filter")}}
                </flux:button>
                <flux:menu>
                        @foreach ($this->getVisibleFilters() as $filter)
                            @if(!in_array($filter->getKey(), $applied_filter_keys))
                            <flux:menu.item wire:key="{{ $tableName }}-filter-{{ $filter->getKey() }}-menuitem" @click="selectFilter('{{ $filter->getKey() }}');"> {{ $filter->getName() }}</flux:menu.item>
                            @endif
                        @endforeach
                </flux:menu>
                </flux:dropdown>
            </section>
    </div>
</flux:modal>
