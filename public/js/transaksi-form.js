document.addEventListener('alpine:init', () => {
    Alpine.data('transaksiForm', (config) => ({
        jenis: config.jenis || 'pemasukan',
        bidangKerjaId: config.bidangKerjaId || '',
        tahunAnggaranId: config.tahunAnggaranId || '',
        pendapatanList: config.pendapatanList || [],
        pengeluaranList: config.pengeluaranList || [],
        typeModel: config.typeModel || '',
        oldTransaksableId: config.oldTransaksableId || '',
        rawJumlah: config.rawJumlah || '',
        formattedJumlah: '',
        tomSelectInstance: null,
        
        init() {
            this.$watch('jenis', value => {
                this.typeModel = value === 'pemasukan' 
                    ? 'App\\Models\\RencanaPendapatan' 
                    : 'App\\Models\\RencanaPengeluaran';
            });
            
            if (this.rawJumlah) {
                this.formattedJumlah = this.formatNumber(this.rawJumlah);
            }
            
            // Gunakan $nextTick untuk memastikan elemen DOM (tomSelectEl) sudah ada
            this.$nextTick(() => {
                if (this.$refs.tomSelectEl) {
                    this.tomSelectInstance = new TomSelect(this.$refs.tomSelectEl, {
                        valueField: 'value',
                        labelField: 'text',
                        searchField: 'text',
                        maxOptions: null,
                        onItemAdd: function() {
                            this.blur();
                        }
                    });
                    
                    this.updateTomSelect();
                    
                    if (this.oldTransaksableId) {
                        this.tomSelectInstance.setValue(this.oldTransaksableId);
                    }
                }
            });

            this.$watch('jenis', () => {
                if (this.tomSelectInstance) {
                    this.tomSelectInstance.clear();
                    this.updateTomSelect();
                }
            });
            this.$watch('tahunAnggaranId', () => {
                if (this.tomSelectInstance) {
                    this.tomSelectInstance.clear();
                    this.updateTomSelect();
                }
            });
            this.$watch('bidangKerjaId', () => {
                if (this.tomSelectInstance) {
                    this.tomSelectInstance.clear();
                    this.updateTomSelect();
                }
            });
        },
        
        formatNumber(value) {
            if (!value) return '';
            return parseInt(value.toString().replace(/[^0-9]/g, '') || 0).toLocaleString('id-ID');
        },
        
        updateJumlah(value) {
            let cleanValue = value.toString().replace(/[^0-9]/g, '');
            this.rawJumlah = cleanValue;
            this.formattedJumlah = this.formatNumber(cleanValue);
        },
        
        updateTomSelect() {
            if (!this.tomSelectInstance) return;
            
            this.tomSelectInstance.clearOptions();
            
            const list = this.jenis === 'pemasukan' ? this.pendapatanList : this.pengeluaranList;
            const filtered = list.filter(item => {
                let matchTahun = item.tahun_anggaran_id == this.tahunAnggaranId;
                let matchBidang = true;
                if (this.jenis === 'pengeluaran' && this.bidangKerjaId) {
                    matchBidang = item.bidang_kerja_id == this.bidangKerjaId;
                }
                return matchTahun && matchBidang;
            });
            
            const options = filtered.map(item => {
                return {
                    value: item.id,
                    text: this.jenis === 'pemasukan' 
                        ? item.nama_sumber + ' - Rp ' + parseInt(item.jumlah_rencana || 0).toLocaleString('id-ID')
                        : item.nama_kegiatan + ' - Rp ' + parseInt(item.jumlah_anggaran || 0).toLocaleString('id-ID')
                };
            });
            
            this.tomSelectInstance.addOption(options);
            this.tomSelectInstance.refreshOptions(false);
        }
    }));
});
