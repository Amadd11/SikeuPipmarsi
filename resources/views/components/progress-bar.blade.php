@props(['value', 'max' => 100, 'color' => 'bg-teal'])

@php
    $percentage = $max > 0 ? min(100, max(0, ($value / $max) * 100)) : 0;
@endphp

<div class="w-full">
    <div class="flex justify-between items-end mb-1">
        <span class="text-[10px] font-bold text-muted-text uppercase tracking-widest">{{ number_format($percentage, 1, ',', '') }}%</span>
    </div>
    <div class="w-full h-1.5 bg-page-bg rounded-full overflow-hidden">
        <div class="h-full {{ $color }} transition-all duration-1000" style="width: {{ $percentage }}%"></div>
    </div>
</div>
