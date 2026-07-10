document.addEventListener('alpine:init', () => {
    Alpine.data('rincianForm', (config) => ({
        details: config.details || [],
        
        get grandTotal() {
            return this.details.reduce((sum, item) => {
                let h = parseInt(item.hargaRaw) || 0;
                let k = parseInt(item.kuantitas) || 0;
                return sum + (h * k);
            }, 0);
        },
        
        get grandTotalDisplay() {
            return this.grandTotal > 0 ? new Intl.NumberFormat('id-ID').format(this.grandTotal) : '';
        },
        
        addDetail() {
            this.details.push({
                id: Date.now(),
                uraian: '',
                satuan: '',
                hargaRaw: '',
                hargaDisplay: '',
                kuantitas: 1
            });
        },
        
        removeDetail(id) {
            if (this.details.length > 1) {
                this.details = this.details.filter(d => d.id !== id);
            }
        },
        
        formatHarga(item, e) {
            let v = e.target.value.replace(/\D/g, '');
            item.hargaRaw = v;
            item.hargaDisplay = v ? new Intl.NumberFormat('id-ID').format(v) : '';
            e.target.value = item.hargaDisplay;
        }
    }));
});
