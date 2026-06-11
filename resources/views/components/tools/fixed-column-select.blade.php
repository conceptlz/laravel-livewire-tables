@aware(['tableName'])

@if($this->fixedColumnSelectIsEnabled())

<flux:tooltip content="{{ __('Pin Columns') }}" position="top">
    <flux:dropdown position="bottom" align="center" wire:key="{{ $tableName }}-column-fixed-button" class="fixed-column-select" >
        <flux:button square variant="filled" icon="phosphor-push-pin-simple-bold">
        </flux:button>
        <flux:popover class="min-w-60 flex flex-col gap-4 shadow-xl">
            <flux:heading class="!font-bold text-bco-600">
                <span class="text-sm font-medium text-zinc-700">Pin Columns (Max {{ $this->maxFixedColumns }})</span>
                @if(count($this->selectedFixedColumns) > 0)
                    <flux:button 
                        variant="ghost" 
                        size="xs" 
                        wire:click="$set('selectedFixedColumns', [])"
                    >
                        Clear All
                    </flux:button>
                @endif
            </flux:heading>
            <div class="w-60 h-72 overflow-y-auto bg-zinc-100 p-4 rounded-">
                <flux:checkbox.group class="text-sm space-y-4" >
                    
                    @foreach ($this->getColumnsForColumnSelect() as $columnSlug => $columnTitle)
                    @php
                        $column = collect($this->selectedVisibleColumns)->firstWhere('slug', $columnSlug);
                        $isSelected = in_array($columnSlug, $this->selectedFixedColumns);
                        $position = array_search($columnSlug, $this->selectedFixedColumns);
                        $canSelect = count($this->selectedFixedColumns) < $this->maxFixedColumns || $isSelected;
                    @endphp
                    <flux:checkbox  wire:key="{{ $tableName }}-fixedcolumnSelect-{{ $loop->index }}" wire:model.live="selectedFixedColumns" wire:target="selectedFixedColumns" wire:loading.attr="disabled" value="{{ $columnSlug }}" label="{{ $columnTitle }}" :disabled="!$canSelect && !$isSelected" />
                     @endforeach
                </flux:checkbox.group>
            </div>
             @if(count($this->selectedFixedColumns) > 0)
                <div class="mt-2 pt-2 border-t border-zinc-200">
                    <div class="text-xs text-zinc-600">
                        <strong>{{ count($this->selectedFixedColumns) }}</strong> of <strong>{{ $this->maxFixedColumns }}</strong> columns pinned
                    </div>
                </div>
            @endif
        </flux:popover>
    </flux:dropdown>
</flux:tooltip>
@endif
