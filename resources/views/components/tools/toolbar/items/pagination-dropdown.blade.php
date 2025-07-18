@aware([ 'tableName','isTailwind','isBootstrap','isBootstrap4','isBootstrap5', 'localisationPath'])
<flux:select variant="combobox" placeholder="Item per page ..." wire:model.live="perPage" id="{{ $tableName }}-perPage">
        @foreach ($this->getPerPageAccepted() as $item)
            <flux:select.option
                value="{{ $item }}"
                wire:key="{{ $tableName }}-per-page-{{ $item }}"
            >
                {{ $item === -1 ? __($localisationPath.'All') : $item }}
            </flux:select.option>
        @endforeach
</flux:select>