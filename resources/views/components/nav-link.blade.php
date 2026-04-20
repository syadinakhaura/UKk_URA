@props(['route', 'icon', 'label'])

@php
    $isActive = request()->routeIs($route);
    $classes = $isActive 
        ? 'flex items-center px-4 py-3 text-sm font-bold text-white bg-indigo-600 rounded-lg shadow-md transition-all duration-200'
        : 'flex items-center px-4 py-3 text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800 rounded-lg transition-all duration-200';
@endphp

<a href="{{ route($route) }}" {{ $attributes->merge(['class' => $classes]) }}>
    <i class="{{ $icon }} mr-3 text-lg {{ $isActive ? 'text-white' : 'text-slate-500' }}"></i>
    <span>{{ $label }}</span>
    @if($isActive)
        <span class="ml-auto w-2 h-2 rounded-full bg-white shadow-sm"></span>
    @endif
</a>
