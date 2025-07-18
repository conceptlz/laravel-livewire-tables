@aware([ 'tableName','isTailwind','isBootstrap','isBootstrap4','isBootstrap5', 'localisationPath'])
<flux:dropdown id="{{ $tableName }}-bulkActionsDropdown" >
    <flux:button icon:trailing="chevron-down">{{ __($localisationPath.'Bulk Actions') }}</flux:button>
    <flux:menu>
         @foreach ($this->getBulkActions() as $action => $title)
                @php
                    $confirmAttribute = [];
                    if($this->hasConfirmationMessage($action))
                    {
                        $confirmAttribute['wire:confirm'] = $this->getBulkActionConfirmMessage($action);
                    }
                    $confirmAttribute = array_merge($this->getBulkActionsMenuItemAttributes,$confirmAttribute);
                @endphp
                    <flux:menu.item icon="arrow-down-tray"  
                                    wire:click="{{ $action }}"
                                    {{ $attributes->merge($confirmAttribute) }}
                                    wire:key="{{ $tableName }}-bulk-action-{{ $action }}">{{ $title }}</flux:menu.item>
         @endforeach
    </flux:menu>
</flux:dropdown>