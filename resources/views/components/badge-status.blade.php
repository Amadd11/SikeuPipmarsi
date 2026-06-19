@props(['realisasi', 'target'])

@php
    $percentage = $target > 0 ? ($realisasi / $target) * 100 : 0;
    
    if ($percentage <= 0) {
        $status = 'Belum';
        $classes = 'bg-gray-100 text-gray-600 border-gray-200';
    } elseif ($percentage < 50) {
        $status = 'Rendah';
        $classes = 'bg-red/10 text-red border-red/20';
    } elseif ($percentage < 100) {
        $status = 'Proses';
        $classes = 'bg-gold/10 text-gold border-gold/20';
    } else {
        $status = 'Selesai';
        $classes = 'bg-green/10 text-green border-green/20';
    }
@endphp

<span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $classes }}">
    {{ $status }}
</span>
