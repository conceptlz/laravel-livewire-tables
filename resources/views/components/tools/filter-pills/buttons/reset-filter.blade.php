@aware(['tableName','isTailwind','isBootstrap','isBootstrap4','isBootstrap5', 'localisationPath'])
@props(['filterKey', 'filterPillData'])

@php
    
    $filterButtonAttributes = $filterPillData->getCalculatedCustomResetButtonAttributes($filterKey,$this->getFilterPillsResetFilterButtonAttributes);

@endphp
@if ($isTailwind)
    <flux:badge.close {{  $attributes->merge($filterButtonAttributes) }}>
        <span class="sr-only">{{ __($localisationPath.'Remove filter option') }}</span>
    </flux:badge.close>
    
@else
    <a
        href="#"
        x-on:click.prevent="resetSpecificFilter('{{ $filterKey }}')"
        {{
            $attributes->merge($filterButtonAttributes)
            ->class([
                'text-white ml-2' => $isBootstrap && $filterButtonAttributes['default-styling']
            ])
            ->except(['default', 'default-colors', 'default-styling', 'default-text'])
        }}
    >
        <span @class([
            'sr-only' => $isBootstrap4,
            'visually-hidden' => $isBootstrap5,
            ])>{{ __($localisationPath.'Remove filter option') }}
            </span>
        <x-heroicon-m-x-mark class="laravel-livewire-tables-btn-tiny"  />
    </a>
@endif
