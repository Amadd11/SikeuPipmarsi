<x-app-layout title="Dashboard Keuangan">
    
    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-stat-card 
            label="Rencana Pendapatan" 
            :value="'Rp ' . number_format($stats['rencana_pendapatan'], 0, ',', '.')" 
            variant="teal"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
        />
        <x-stat-card 
            label="Realisasi Pendapatan" 
            :value="'Rp ' . number_format($stats['realisasi_pendapatan'], 0, ',', '.')" 
            :sub="$stats['pendapatan_percentage'] . '% Target'"
            variant="green"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z"></path></svg>'
        />
        <x-stat-card 
            label="Anggaran Belanja" 
            :value="'Rp ' . number_format($stats['total_anggaran'], 0, ',', '.')" 
            variant="navy"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>'
        />
        <x-stat-card 
            label="Realisasi Pengeluaran" 
            :value="'Rp ' . number_format($stats['realisasi_pengeluaran'], 0, ',', '.')" 
            :sub="$stats['pengeluaran_percentage'] . '% Serapan'"
            variant="red"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13l-3 3m0 0l-3-3m3 3V8m0 13a9 9 0 110-18 9 9 0 010 18z"></path></svg>'
        />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Saldo & Serapan -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white p-8 rounded-2xl border border-border-light shadow-sm">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-xl font-bold font-serif">Posisi Kas & Serapan Anggaran</h3>
                        <p class="text-sm text-muted-text">Ringkasan efisiensi penggunaan anggaran tahun ini.</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-bold text-muted-text uppercase tracking-widest mb-1">Saldo Kas Saat Ini</p>
                        <h4 class="text-2xl font-bold text-teal">Rp {{ number_format($stats['saldo_kas'], 0, ',', '.') }}</h4>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <div class="flex justify-between mb-2">
                            <span class="text-sm font-bold">Total Serapan Anggaran</span>
                            <span class="text-sm font-bold text-teal">{{ $stats['serapan_percentage'] }}%</span>
                        </div>
                        <div class="w-full h-4 bg-page-bg rounded-full overflow-hidden">
                            <div class="h-full bg-teal transition-all duration-1000" style="width: {{ $stats['serapan_percentage'] }}%"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($serapanPerBidang as $bidang)
                        <div class="p-4 rounded-xl border border-border-light bg-page-bg/30">
                            <div class="flex justify-between mb-1">
                                <span class="text-xs font-bold text-navy">{{ $bidang['kode'] }} — {{ $bidang['nama'] }}</span>
                                <span class="text-xs font-bold" style="color: {{ $bidang['warna'] }}">{{ $bidang['persen'] }}%</span>
                            </div>
                            <div class="w-full h-1.5 bg-page-bg rounded-full overflow-hidden">
                                <div class="h-full transition-all duration-1000" style="width: {{ $bidang['persen'] }}%; background-color: {{ $bidang['warna'] }}"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Aktivitas Terbaru -->
            <div class="bg-white rounded-2xl border border-border-light shadow-sm overflow-hidden">
                <div class="p-6 border-b border-border-light flex justify-between items-center">
                    <h3 class="text-xl font-bold font-serif">Aktivitas Terbaru</h3>
                    <a href="/aktivitas" class="text-xs font-bold text-teal hover:underline uppercase tracking-widest">Lihat Semua</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-page-bg/50 text-[10px] font-bold text-muted-text uppercase tracking-widest">
                                <th class="px-6 py-4">Kode / Tanggal</th>
                                <th class="px-6 py-4">Uraian</th>
                                <th class="px-6 py-4">Jumlah</th>
                                <th class="px-6 py-4">Jenis</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-light">
                            @foreach($aktivitasTerbaru as $trx)
                            <tr class="hover:bg-page-bg/20 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold">{{ $trx['kode'] }}</div>
                                    <div class="text-[10px] text-muted-text">{{ date('d/m/Y', strtotime($trx['tanggal'])) }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm">{{ $trx['uraian'] }}</td>
                                <td class="px-6 py-4 font-bold text-sm">Rp {{ number_format($trx['jumlah'], 0, ',', '.') }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $trx['jenis'] == 'masuk' ? 'bg-green/10 text-green' : 'bg-red/10 text-red' }}">
                                        {{ $trx['jenis'] == 'masuk' ? 'Pemasukan' : 'Pengeluaran' }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar Dashboard -->
        <div class="space-y-8">
            <!-- Capaian Mutu -->
            <div class="bg-white p-6 rounded-2xl border border-border-light shadow-sm">
                <h3 class="text-lg font-bold font-serif mb-4 text-center">Capaian Indikator Mutu</h3>
                <div id="chart-mutu" class="mb-6"></div>
                
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 rounded-xl bg-green/5 border border-green/10">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 bg-green rounded-full"></span>
                            <span class="text-sm font-medium">Tercapai</span>
                        </div>
                        <span class="font-bold">{{ $capaianMutu['tercapai'] }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-xl bg-gold/5 border border-gold/10">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 bg-gold rounded-full"></span>
                            <span class="text-sm font-medium">Dalam Proses</span>
                        </div>
                        <span class="font-bold">{{ $capaianMutu['proses'] }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-xl bg-red/5 border border-red/10">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 bg-red rounded-full"></span>
                            <span class="text-sm font-medium">Belum</span>
                        </div>
                        <span class="font-bold">{{ $capaianMutu['belum'] }}</span>
                    </div>
                </div>
                
                <div class="mt-6 pt-6 border-t border-border-light text-center">
                    <div class="text-3xl font-bold text-teal">{{ $capaianMutu['percentage_tercapai'] }}%</div>
                    <div class="text-[10px] font-bold text-muted-text uppercase tracking-widest mt-1">Total Capaian Indikator</div>
                </div>
            </div>

            <!-- Peringatan -->
            <div class="bg-navy p-6 rounded-2xl text-white shadow-lg relative overflow-hidden">
                <div class="absolute -right-8 -top-8 w-32 h-32 bg-white/10 rounded-full blur-3xl"></div>
                <h3 class="text-lg font-bold font-serif mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    Pusat Informasi
                </h3>
                <div class="space-y-4 relative z-10">
                    @foreach($peringatan as $p)
                    <div class="flex gap-3 text-sm">
                        <div class="mt-1 w-1.5 h-1.5 rounded-full {{ $p['type'] == 'warning' ? 'bg-gold' : 'bg-teal' }} shrink-0"></div>
                        <p class="text-white/80 leading-relaxed">{{ $p['message'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Capaian Mutu Chart
            const optionsMutu = {
                series: [{{ $capaianMutu['tercapai'] }}, {{ $capaianMutu['proses'] }}, {{ $capaianMutu['belum'] }}],
                chart: {
                    type: 'donut',
                    height: 250,
                },
                labels: ['Tercapai', 'Dalam Proses', 'Belum'],
                colors: ['#1a7a4e', '#c8960c', '#c0392b'],
                legend: { show: false },
                dataLabels: { enabled: false },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '75%',
                            labels: {
                                show: true,
                                name: { show: false },
                                value: {
                                    show: true,
                                    fontSize: '24px',
                                    fontWeight: 'bold',
                                    fontFamily: 'Outfit',
                                    color: '#0b1f38'
                                },
                                total: {
                                    show: true,
                                    label: 'Total',
                                    formatter: function (w) {
                                        return w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                                    }
                                }
                            }
                        }
                    }
                }
            };

            const chartMutu = new ApexCharts(document.querySelector("#chart-mutu"), optionsMutu);
            chartMutu.render();
        });
    </script>
    @endpush

</x-app-layout>
