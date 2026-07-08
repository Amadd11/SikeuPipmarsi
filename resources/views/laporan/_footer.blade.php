{{-- Reusable footer partial for PDF reports --}}
<div style="position: fixed; bottom: 0; left: 0; right: 0; text-align: center; font-size: 8px; color: #aaa; border-top: 1px solid #ddd; padding-top: 5px;">
    {{ config('laporan.instansi.unit') }} — Dicetak {{ now()->translatedFormat('d/m/Y H:i') }}
</div>
