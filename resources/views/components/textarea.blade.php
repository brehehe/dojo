@props(['disabled' => false])

<textarea @disabled($disabled) {{ $attributes->merge(['class' => 'w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-md text-black placeholder:text-slate-800 focus:bg-white focus:border-orange-500/40 focus:ring-4 focus:ring-orange-500/10 transition-all font-medium text-[15px] min-h-[80px]']) }}>{{ $slot }}</textarea>
