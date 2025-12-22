@aware([ 'tableName','isTailwind','isBootstrap','isBootstrap4','isBootstrap5', 'localisationPath'])
@if ($this->sortingPillsAreEnabled() && $this->hasSorts())
    <th colspan="{{ $this->getColspanCount }}" class="space-x-2 text-left" x-cloak x-show="!currentlyReorderingStatus">
        <small @class([
            'text-gray-700 dark:text-white' => $isTailwind,
            '' =>  $isBootstrap,
        ])>
            {{ __($localisationPath.'Applied Sorting') }}:
        </small>
        @foreach($this->getSorts() as $columnSelectName => $direction)
                    @php($column = $this->getColumnBySelectName($columnSelectName) ?? $this->getColumnBySlug($columnSelectName))

                    @continue(is_null($column))
                    @continue($column->isHidden())
                    @continue($this->columnSelectIsEnabled && ! $this->columnSelectIsEnabledForColumn($column))
                    <flux:badge size="sm" color="cyan" wire:key="{{ $tableName }}-sorting-pill-{{ $columnSelectName }}" {{ $attributes->merge($this->getSortingPillsItemAttributes()) }}>
                        {{ $column->getSortingPillTitle() }} <span class="font-bold ps-1">: {{ $column->getSortingPillDirectionLabel($direction, $this->getDefaultSortingLabelAsc, $this->getDefaultSortingLabelDesc) }}</span> 
                        <flux:badge.close wire:click="clearSort('{{ $columnSelectName }}')" {{  $attributes->merge($this->getSortingPillsClearSortButtonAttributes()) }}/>
                    </flux:badge>
        @endforeach
    </th>
@endif