@aware(['tableName','isTailwind','isBootstrap'])
@props(['colCount' => 1])

@php
    $loaderRow = $this->getLoadingPlaceHolderRowAttributes();
    $loaderCell = $this->getLoadingPlaceHolderCellAttributes();
    $loaderIcon = $this->getLoadingPlaceHolderIconAttributes();
@endphp

<tr wire:key="{{ $tableName }}-loader" wire:loading.class.remove="hidden d-none " {{
    $attributes->merge($loaderRow)
        ->class([
            'hidden w-full text-center place-items-center align-middle border-0' => $isTailwind && ($loaderRow['default'] ?? true),
            'd-none w-100 text-center align-items-center' => $isBootstrap && ($loaderRow['default'] ?? true),
        ])
        ->except(['default','default-styling','default-colors'])
}}>
    <td colspan="{{ $colCount }}" wire:key="{{ $tableName }}-loader-column" {{
        $attributes->merge($loaderCell)
            ->class([
                'py-4' => $isTailwind && ($loaderCell['default'] ?? true),
                'py-4' => $isBootstrap && ($loaderCell['default'] ?? true),
            ])
            ->except(['default','default-styling','default-colors', 'colspan','wire:key'])
    }}>
        @if($this->hasLoadingPlaceholderBlade())
            @include($this->getLoadingPlaceHolderBlade(), ['colCount' => $colCount])
        @else
            <div class="h-min self-center align-middle text-center">
                <div class="!absolute lds-hourglass top-2/4 -translate-y-2/4 -translate-x-2/4 left-2/4"{{
                        $attributes->merge($loaderIcon)
                            ->class([
                                'lds-hourglass' => $isTailwind && ($loaderIcon['default'] ?? true),
                                'lds-hourglass' => $isBootstrap && ($loaderIcon['default'] ?? true),
                            ])
                            ->except(['default','default-styling','default-colors'])
                }}></div>
                <div>{!! $this->getLoadingPlaceholderContent() !!}</div>
            </div>
        @endif
    </td>
</tr>
