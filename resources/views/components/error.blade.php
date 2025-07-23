@props(['for'])

@error($for)
    <p {{ $attributes->merge(['class' => 'text-sm text-red-600']) }} for="{{$for}}">{{ $message }}</p>
@enderror
 <p {{ $attributes->merge(['class' => 'text-sm text-red-600']) }} for="{{$for}}"></p>
