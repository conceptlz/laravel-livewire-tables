@aware(['isTailwind', 'isBootstrap'])
@props(['displayMinimisedOnReorder' => false, 'hideUntilReorder' => false, 'customAttributes' => ['default' => true]])

<th x-cloak scope="col" @if($hideUntilReorder) :class="!reorderDisplayColumn && 'w-0 p-0 hidden'" @endif {{ 
        $attributes->merge($customAttributes)->class([
            'sm:px-6' => $isTailwind && (($customAttributes['default-styling'] ?? true) || ($customAttributes['default'] ?? true)),
            'laravel-livewire-tables-reorderingMinimised' => $isBootstrap && (($customAttributes['default-colors'] ?? true) || ($customAttributes['default'] ?? true)),
        ])->except(['default','default-styling','default-colors'])
}}>
    {{ $slot }}
</th>
