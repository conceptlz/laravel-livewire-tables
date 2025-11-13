@aware([ 'tableName','isTailwind','isBootstrap','isBootstrap4','isBootstrap5', 'localisationPath','appliedFilters'])
@props([''])
@php
$visiable_filters = $this->getVisibleFilters();
$applied_filter_keys = array_keys($appliedFilters);
@endphp
<flux:tooltip>
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
<flux:modal name="{{ $tableName }}-filter-slideout" variant="flyout" :dismissible=true class="w-138" x-data="{ 
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
        
           const { [key]: removed, ...rest } = this.selectedFilters;
            this.selectedFilters = rest;
            this.reactiveKey++; // Optional: helps force rerender
        },
        isSelected(key) {
            return this.selectedFilters.hasOwnProperty(key);
        }
    }">
    <div class="h-[calc(100vh-72px)] w-auto flex flex-col justify-start gap-6 inset-y-0 left-0">
        <div class="shrink-0">
                <div class="border-b border-zinc-200 flex items-center justify-start gap-2 pt-2 pb-4">
                    <x-phosphor-faders class="size-8" />
                    <flux:heading size="xl" >{{ __("Apply filters for view") }}</flux:heading>
                </div>
        </div>
        <div class="grow overflow-y-auto mb-6 pe-4">
            
                    
                        <!-- Selected Filters -->
                        <template x-if="selectedFilters.length === 0">
                            <section class="py-6 flex items-center justify-between gap-6 w-full">
                                <p class="text-gray-500 ">{{ __("No filters selected") }}</p>
                            </section>
                        </template>
                    <flux:accordion variant="reverse">
                        @foreach($this->getFilters() as $filterKey => $filter)
                             <flux:accordion.item expanded x-show="isSelected('{{ $filter->getKey() }}')" wire:key="filter-{{ $filterKey }}">
                                <flux:accordion.heading>    
                                    <div class="flex items-start justify-between space-x-10 py-2">
                                        <flux:heading class="!font-extrabold uppercase">{{ $filter->getName() }} </flux:heading>
                                        
                                    </div>
                                </flux:accordion.heading>
                                <flux:accordion.content>
                                    <div class="p-6 bg-zinc-50 rounded-lg space-y-4">
                                         {{ $filter->setGenericDisplayData($this->getFilterGenericData)->render() }}
                                         <flux:link href="javascript:void(0)" class="text-bco-500" x-on:click="resetSpecificFilter('{{ $filter->getKey() }}');$flux.modal('{{ $tableName }}-filter-slideout').close()">{{ __("remove") }}</flux:link>
                                    </div>
                                </flux:accordion.content>
                            </flux:accordion.item>
                            
                        @endforeach
                    </flux:accordion>
        </div>
        <div class="flex justify-end items-center space-x-4 p-4 bg-zinc-100 rounded-lg">
                <flux:button variant="primary" x-on:click="$wire.dispatch('refreshDatatable');$flux.modal('{{ $tableName }}-filter-slideout').close()">
                        <x-phosphor-faders class="size-5" />
                        {{ __("Apply filters")}}
                    </flux:button>
                <flux:dropdown position="top" align="end" gap="12" offset="12">
                    <flux:button x-ref="{{ $tableName }}-add-filter">
                        <x-phosphor-funnel class="size-5" />
                       {{ __("Add a filter")}}
                    </flux:button>

                    <flux:popover class="shadow-xl !bg-slate-50">
                        <div class="{{(count($visiable_filters) > 10) ? 'w-84 grid grid-cols-2' : 'w-42 grid grid-cols-1' }} gap-4 p-3 capitalize text-sm">
                             @foreach ($visiable_filters as $filter)
                                @if(!in_array($filter->getKey(), $applied_filter_keys))
                                <flux:link href="javascript:void(0)" variant="ghost" class="text-slate-800" wire:key="{{ $tableName }}-filter-{{ $filter->getKey() }}-menuitem" @click="selectFilter('{{ $filter->getKey() }}');$refs['{{ $tableName }}-add-filter'].click();"> {{ $filter->getName() }}</flux:link>
                                @endif
                            @endforeach
                        </div>
                    </flux:popover>
                </flux:dropdown>
            </div>
    </div>  
</flux:modal>
