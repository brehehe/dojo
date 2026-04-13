@props(['disabled' => false, 'variant' => null])

@php
    $classes = 'w-full px-3 bg-slate-50 border border-slate-200 rounded-md text-slate-700 placeholder:text-slate-400 focus:bg-white focus:border-orange-500/40 focus:ring-4 focus:ring-orange-500/10 transition-all font-medium text-[11px] h-[34px]';

    if ($variant === 'filter') {
        $classes .= ' font-bold italic';
    }
@endphp

<input @disabled($disabled) {{ $attributes->merge(['class' => $classes]) }}>