@aware(['isTailwind','isBootstrap','isBootstrap4','isBootstrap5', 'localisationPath'])
@if ($isTailwind)
    <flux:badge as="button" size="sm" {{  $attributes->merge($this->getFilterPillsResetAllButtonAttributes) }} x-on:click.prevent="resetAllFilters">
        {{ __($localisationPath.'Clear') }}
    </flux:badge>
    
@else
    <a
        href="#"
        x-on:click.prevent="resetAllFilters"
        {{
            $attributes->merge($this->getFilterPillsResetAllButtonAttributes)
            ->class([
                'badge badge-pill badge-light' => $isBootstrap4 && ($this->getFilterPillsResetAllButtonAttributes['default-styling'] ?? true),
                'badge rounded-pill bg-light text-dark text-decoration-none' => $isBootstrap5 && ($this->getFilterPillsResetAllButtonAttribute['default-styling'] ?? true),
            ])
            ->except(['default-styling', 'default-colors'])
        }}
    >
        {{ __($localisationPath.'Clear') }}
    </a>
@endif
