document.addEventListener('alpine:init', () => {
    Alpine.data('auditForm', (config) => ({
        tahunId: config.tahunId,
        indikatorId: config.indikatorId,
        existingAudits: config.existingAudits || {},
        indikatorList: config.indikatorList || [],
        currentAuditIndikatorId: config.currentAuditIndikatorId || null,
        tomSelectInstance: null,
        
        init() {
            // Gunakan $nextTick untuk memastikan elemen DOM ($refs) sudah di-render oleh Alpine
            this.$nextTick(() => {
                if (this.$refs.indikatorSelect) {
                    this.tomSelectInstance = new TomSelect(this.$refs.indikatorSelect, {
                        valueField: 'id',
                        labelField: 'text',
                        searchField: 'text',
                        maxOptions: null,
                        placeholder: 'Pilih indikator mutu...',
                        onItemAdd: function() {
                            this.blur();
                        }
                    });
                    
                    this.updateTomSelect();
                    
                    if (this.indikatorId) {
                        this.tomSelectInstance.setValue(this.indikatorId);
                    }
                }
            });
            
            // Watch perubahan tahun anggaran
            this.$watch('tahunId', () => {
                if (this.tomSelectInstance) {
                    this.tomSelectInstance.clear();
                    this.updateTomSelect();
                }
            });
        },
        
        updateTomSelect() {
            if (!this.tomSelectInstance) return;
            this.tomSelectInstance.clearOptions();
            
            // Ambil ID indikator yang sudah diaudit pada tahun ini
            let hiddenIds = this.tahunId && this.existingAudits[this.tahunId] 
                ? this.existingAudits[this.tahunId] 
                : [];
                
            // Filter list indikator: sembunyikan yang ada di hiddenIds KECUALI indikator saat ini (untuk edit)
            const options = this.indikatorList
                .filter(item => {
                    if (this.currentAuditIndikatorId && item.id === this.currentAuditIndikatorId) {
                        return true;
                    }
                    return !hiddenIds.includes(item.id);
                })
                .map(item => ({
                    id: item.id.toString(),
                    text: '[' + item.kode + '] ' + item.nama
                }));
                
            this.tomSelectInstance.addOption(options);
            this.tomSelectInstance.refreshOptions(false);
        }
    }));
});
