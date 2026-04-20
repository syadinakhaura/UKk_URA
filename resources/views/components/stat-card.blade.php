@props(['label', 'value', 'icon', 'color'])

@php
    $colors = [
        'indigo' => 'bg-indigo-50 text-indigo-600 border-indigo-100 ring-indigo-500',
        'amber' => 'bg-amber-50 text-amber-600 border-amber-100 ring-amber-500',
        'purple' => 'bg-purple-50 text-purple-600 border-purple-100 ring-purple-500',
        'emerald' => 'bg-emerald-50 text-emerald-600 border-emerald-100 ring-emerald-500',
        'rose' => 'bg-rose-50 text-rose-600 border-rose-100 ring-rose-500',
        'blue' => 'bg-blue-50 text-blue-600 border-blue-100 ring-blue-500',
        'slate' => 'bg-slate-50 text-slate-600 border-slate-100 ring-slate-500',
    ];
    $style = $colors[$color] ?? $colors['indigo'];
@endphp

<div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 hover:shadow-md transition-all duration-300 group">
    <div class="flex items-center justify-between mb-3">
        <div class="p-2.5 rounded-xl {{ $style }} border transition-all duration-300 group-hover:scale-110">
            <i class="{{ $icon }} text-lg"></i>
        </div>
    </div>
    <div>
        <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">{{ $label }}</p>
        <p class="text-2xl font-black text-slate-800 mt-1">{{ $value }}</p>
    </div>
</div>
