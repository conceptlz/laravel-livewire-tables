<div x-data="{
    edit: false,
    edited: false,
    init() {
        $wire.on('fieldEdited', (id) => {
            if (id === '{{ $rowId }}') {
                this.edited = true
                setTimeout(() => {
                    this.edited = false
                }, 5000)
            }
        })
    }
}" x-init="init()" :key="{{ $rowId }}">
    <button class="min-h-[28px] w-full text-left hover:bg-red-100 px-2 py-1 -mx-2 -my-1 rounded focus:outline-none" x-bind:class="{ 'text-green-600': edited }" x-show="!edit"
        x-on:click="edit = true; $nextTick(() => { $refs.input.focus() })">{!! htmlspecialchars($value) !!}</button>
      
    <span x-cloak x-show="edit">
        <input class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 text-sm" x-ref="input" value="{!! htmlspecialchars($value) !!}"
            wire:change="edited($event.target.value, '{{ $key }}', '{{ $column }}', '{{ $rowId }}')"
            x-on:click.away="edit = false" x-on:blur="edit = false" x-on:keydown.enter="edit = false" />
    </span>
    <x-livewire-tables::error for="{{ $column.$rowId }}" class="mt-2" />
</div>
