{{-- Reusable header partial for all PDF reports --}}

<div style="text-align: center; margin-bottom: 20px;">
    <div style="font-size: 14px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px;">
        {{ $judul ?? 'Laporan' }}
    </div>
    @if (!empty($periode))
        <div style="font-size: 11px; color: #555; margin-top: 4px;">
            Periode: {{ $periode }}
        </div>
    @endif
</div>

<div style="font-size: 9px; color: #888; margin-bottom: 15px; display: flex; justify-content: space-between;">
    <span>Dicetak oleh: {{ auth()->user()->name ?? '-' }}</span>
    <span style="float: right;">Tanggal cetak: {{ now()->translatedFormat('d F Y, H:i') }} WIB</span>
</div>
