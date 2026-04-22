<div class="bg-white rounded-lg shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto custom-scrollbar">
        <table {{ $attributes->merge(['class' => 'w-full text-left border-collapse border border-slate-200 rounded-xl overflow-hidden']) }}>
            @if(isset($header))
                <thead class="bg-slate-800 text-white">
                    <tr class="bg-slate-800">
                        {{ $header }}
                    </tr>
                </thead>
            @endif
            
            <tbody class="divide-y divide-slate-200">
                {{ $slot }}
            </tbody>
        </table>
    </div>

    @if(isset($pagination))
        <div class="px-4 py-2 bg-slate-50/50 border-t border-slate-100">
            {{ $pagination }}
        </div>
    @endif
</div>
