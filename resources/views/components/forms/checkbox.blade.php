@aware(['tableName','primaryKey', 'isTailwind', 'isBootstrap', 'isBootstrap4', 'isBootstrap5'])
@props(['checkboxAttributes'])
<flux:checkbox x-cloak  {{ $attributes->merge($checkboxAttributes) }}/>