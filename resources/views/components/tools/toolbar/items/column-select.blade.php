@aware([ 'tableName','isTailwind','isBootstrap','isBootstrap4','isBootstrap5', 'localisationPath'])
@if ($isTailwind)
@php

    $columnmodelDirective['wire:click'] = ($this->getSelectableSelectedColumns()->count() === $this->getSelectableColumns()->count()) ? 'deselectAllColumns' : 'selectAllColumns';
    $columnmodelDirective = array_merge($this->getColumnSelectMenuOptionCheckboxAttributes(),$columnmodelDirective);
@endphp
<flux:tooltip content="{{ __('Show/Hide Columns') }}" position="top">
    <flux:dropdown position="bottom" align="center" wire:key="{{ $tableName }}-column-select-button">
        <flux:button square variant="filled" icon="phosphor-square-split-horizontal-bold">
        </flux:button>
        <flux:popover class="min-w-60 flex flex-col gap-4 shadow-xl">
            <flux:heading class="!font-bold text-bco-600">Show / Hide columns in this view</flux:heading>
            <div class="w-60 h-72 overflow-y-auto bg-zinc-100 p-4 rounded-">
                <flux:checkbox.group class="text-sm space-y-4" >
                    <flux:checkbox.all label="Select all" {{ $attributes->merge($columnmodelDirective) }} wire:key="{{ $tableName }}-columnSelect-selectAll-{{ rand(0,1000) }}"/>
                    <flux:separator class="my-2" />
                   
                    @foreach ($this->getColumnsForColumnSelect() as $columnSlug => $columnTitle)
                    <flux:checkbox  wire:key="{{ $tableName }}-columnSelect-{{ $loop->index }}" wire:model.live="selectedColumns" wire:target="selectedColumns" wire:loading.attr="disabled" value="{{ $columnSlug }}" label="{{ $columnTitle }}"  />
                     @endforeach
                </flux:checkbox.group>
            </div>
        </flux:popover>
    </flux:dropdown>
</flux:tooltip>
   
@elseif ($isBootstrap)
    <div
        @class([
            'd-none d-sm mb-3 mb-md-0 pl-0 pl-md-2' => $this->getColumnSelectIsHiddenOnMobile() && $isBootstrap4,
            'd-none d-md-block mb-3 mb-md-0 pl-0 pl-md-2' => $this->getColumnSelectIsHiddenOnTablet() && $isBootstrap4,
            'd-none d-sm-block mb-3 mb-md-0 md-0 ms-md-2' => $this->getColumnSelectIsHiddenOnMobile() && $isBootstrap5,
            'd-none d-md-block mb-3 mb-md-0 md-0 ms-md-2' => $this->getColumnSelectIsHiddenOnTablet() && $isBootstrap5,
        ])
    >
        <div
            x-data="{ open: false, childElementOpen: false }"
            x-on:keydown.escape.stop="if (!childElementOpen) { open = false }"
            x-on:mousedown.away="if (!childElementOpen) { open = false }"
            @class([
                'dropdown d-block d-md-inline' => $isBootstrap,
            ])
            wire:key="{{ $tableName }}-column-select-button"
        >
            <button
                x-on:click="open = !open"
                {{
                    $attributes->merge($this->getColumnSelectButtonAttributes())
                    ->class([
                        'btn dropdown-toggle d-block w-100 d-md-inline' => $this->getColumnSelectButtonAttributes()['default-styling'],
                    ])
                    ->except(['default-styling', 'default-colors'])
                }}
                type="button" id="{{ $tableName }}-columnSelect" aria-haspopup="true"
                x-bind:aria-expanded="open"
            >
                {{ __($localisationPath.'Columns') }}
            </button>

            <div
                x-bind:class="{ 'show': open }"
                @class([
                    'dropdown-menu dropdown-menu-right w-100 mt-0 mt-md-3' => $isBootstrap4,
                    'dropdown-menu dropdown-menu-end w-100' => $isBootstrap5,
                ])
                aria-labelledby="columnSelect-{{ $tableName }}"
            >
                @if($isBootstrap4)
                    <div wire:key="{{ $tableName }}-columnSelect-selectAll-{{ rand(0,1000) }}">
                        <label wire:loading.attr="disabled" class="px-2 mb-1">
                            <input
                                wire:loading.attr="disabled"
                                type="checkbox"
                                @if($this->getSelectableSelectedColumns()->count() == $this->getSelectableColumns()->count()) checked wire:click="deselectAllColumns" @else unchecked wire:click="selectAllColumns" @endif
                            />

                            <span class="ml-2">{{ __($localisationPath.'All Columns') }}</span>


                        </label>
                    </div>
                @elseif($isBootstrap5)
                    <div class="form-check ms-2" wire:key="{{ $tableName }}-columnSelect-selectAll-{{ rand(0,1000) }}">
                        <input
                            wire:loading.attr="disabled"
                            type="checkbox"
                            {{
                                $attributes->merge($this->getColumnSelectMenuOptionCheckboxAttributes())
                                ->class([
                                    'form-check-input' => $this->getColumnSelectMenuOptionCheckboxAttributes()['default-styling'],
                                ])
                                ->except(['default-styling', 'default-colors'])
                            }}
                            @if($this->getSelectableSelectedColumns()->count() == $this->getSelectableColumns()->count()) checked wire:click="deselectAllColumns" @else unchecked wire:click="selectAllColumns" @endif
                        />

                        <label wire:loading.attr="disabled" class="form-check-label">
                            {{ __($localisationPath.'All Columns') }}
                        </label>
                    </div>
                @endif

                @foreach ($this->getColumnsForColumnSelect() as $columnSlug => $columnTitle)
                    <div
                        wire:key="{{ $tableName }}-columnSelect-{{ $loop->index }}"
                        @class([
                            'form-check ms-2' => $isBootstrap5,
                        ])
                    >
                        @if ($isBootstrap4)
                            <label
                                wire:loading.attr="disabled"
                                wire:target="selectedColumns"
                                class="px-2 {{ $loop->last ? 'mb-0' : 'mb-1' }}"
                            >
                                <input
                                    wire:model.live="selectedColumns"
                                    wire:target="selectedColumns"
                                    wire:loading.attr="disabled" type="checkbox"
                                    value="{{ $columnSlug }}"
                                />
                                <span class="ml-2">
                                    {{ $columnTitle }}
                                </span>
                            </label>
                        @elseif($isBootstrap5)
                            <input
                                wire:model.live="selectedColumns"
                                wire:target="selectedColumns"
                                wire:loading.attr="disabled"
                                type="checkbox"
                                {{
                                    $attributes->merge($this->getColumnSelectMenuOptionCheckboxAttributes())
                                    ->class([
                                        'form-check-input' => $this->getColumnSelectMenuOptionCheckboxAttributes()['default-styling'],
                                    ])
                                    ->except(['default-styling', 'default-colors'])
                                }}
                                value="{{ $columnSlug }}"
                            />
                            <label
                                wire:loading.attr="disabled"
                                wire:target="selectedColumns"
                                class="{{ $loop->last ? 'mb-0' : 'mb-1' }} form-check-label"
                            >
                                {{ $columnTitle }}
                            </label>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
