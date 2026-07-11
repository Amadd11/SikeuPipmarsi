@props(['label', 'value', 'sub' => '', 'icon', 'variant' => 'teal'])

@php
    $variants = [
        'teal' => [
            'bg' => 'bg-[#e1f5ee]',
            'text' => 'text-[#0f6e56]',
            'ring' => 'ring-[#0f6e56]/10',
        ],
        'green' => [
            'bg' => 'bg-[#eaf3de]',
            'text' => 'text-[#3b6d11]',
            'ring' => 'ring-[#3b6d11]/10',
        ],
        'blue' => [
            'bg' => 'bg-[#e6f1fb]',
            'text' => 'text-[#185fa5]',
            'ring' => 'ring-[#185fa5]/10',
        ],
        'amber' => [
            'bg' => 'bg-[#faeeda]',
            'text' => 'text-[#854f0b]',
            'ring' => 'ring-[#854f0b]/10',
        ],
        'red' => [
            'bg' => 'bg-[#fde8e8]',
            'text' => 'text-[#c0392b]',
            'ring' => 'ring-[#c0392b]/10',
        ],
        'navy' => [
            'bg' => 'bg-[#e7ecf3]',
            'text' => 'text-[#0a2419]',
            'ring' => 'ring-[#0a2419]/10',
        ],
    ];

    $v = $variants[$variant] ?? $variants['teal'];
@endphp

<div
    class="group bg-white rounded-2xl border border-gray-100 p-4 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-0.5">

    {{-- Header --}}
    <div class="flex items-start justify-between mb-2">
        <p class="text-xs font-bold text-black/80">
            {{ $label }}
        </p>

        <div
            class="w-8 h-8 rounded-lg {{ $v['bg'] }} {{ $v['text'] }}
                   flex items-center justify-center ring-2 {{ $v['ring'] }}">

            {{-- Material Symbols --}}
            <span class="material-symbols-outlined text-[16px]">
                {{ $icon }}
            </span>
        </div>
    </div>

    {{-- Value --}}
    <p class="text-lg font-bold text-[#0a2419]">
        {{ $value }}
    </p>

    {{-- Sub --}}
    @if ($sub)
        <p class="text-[10px] text-gray-400 mt-0.5">
            {{ $sub }}
        </p>
    @endif
</div>
