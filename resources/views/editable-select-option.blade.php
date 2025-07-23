<div x-data="{
    edit: false,
    edited: false,
    init() {
       
        $wire.on('fieldEdited', (id) => {
            this.edit = false;
            if (id === '{{ $rowId }}') {
                this.edited = true
                setTimeout(() => {
                    this.edited = false
                }, 1000)
            }
        })
    },
    valueId : '{{$value}}'
}" x-init="init()"  wire:key="edit-{{ $rowId }}_{{ $column }}-{{ rand(0,1000) }}">
    
    <button class="min-h-[28px] w-full text-left hover:bg-red-100 px-2 py-1 -mx-2 -my-1 rounded focus:outline-none" x-bind:class="{ 'text-green-600': edited }" x-show="!edit"
        x-on:click="edit = true; $nextTick(() => { $refs.select.focus();})">{{ (isset($options[$value]) ? $options[$value] : $value)}}</button>
       
    <span x-cloak x-show="edit">
        <select  class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 text-sm"
            x-ref="select"
            id="{{$column . $rowId }}"
            value="{{$value}}"
            name="{{$column . $rowId }}"
            wire:change="edited($event.target.value, '{{ $key }}', '{{ $column }}', '{{ $rowId }}');"
            x-on:click.away="edit = false" x-on:change="edit = false" x-on:blur="edit = false" x-on:keydown.enter="edit = false" required />
            <option value=""></option>
            @foreach($options as $key => $option)
            @php 
                $selected = ($key == $value) ? 'selected' : '';
            @endphp
            <option data-id="{{$option}}" value="{{ $key }}" {{ $selected }} >{{$option }}</option>
            @endforeach
        </select>
    </span>
    <x-livewire-tables::error for="{{ $column.$rowId }}" class="mt-2" />
</div>
