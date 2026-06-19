# PRD — SIKEU PIPMARSI
**Sistem Informasi Keuangan · Persatuan Istri Perawat Marisi**

> Stack: **Laravel 13 · Blade Templating · Alpine.js · Tailwind CSS 3 · MySQL 8 · mPDF**  
> Auth: Laravel Breeze (Blade preset)  
> Dev: Laragon (Windows), PHP 8.3+, Vite

---

## 1. Gambaran Umum

SIKEU PIPMARSI adalah sistem informasi keuangan berbasis web untuk organisasi PIPMARSI. Sistem mengelola siklus keuangan lengkap: perencanaan anggaran → pencatatan transaksi → monitoring indikator mutu → pelaporan resmi. Semua data dikelompokkan per **Tahun Anggaran** dan per **Bidang Kerja**.

---

## 2. Tech Stack & Konvensi

### Backend
- **Laravel 13**, PHP 8.3+
- Pattern: `Route → Middleware → Controller → Service → Model`
- Validasi: **Form Request** (satu class per operasi)
- Audit otomatis: **Eloquent Observer** → tabel `audit_logs`
- PDF: **`laravel-mpdf`** via Blade template

### Frontend
- **Blade Templating Engine** (native Laravel views)
- **Alpine.js 3.x** untuk interaktivitas (modal, tab, dropdown, toggle) tanpa reload
- **Tailwind CSS 3** untuk styling
- **ApexCharts** (via CDN/npm) untuk donut chart & bar chart di dashboard
- Komunikasi data dinamis (filter tab, update partial) via **AJAX fetch + Blade partial** atau **Alpine `x-data`** dengan data di-passing dari controller sebagai JSON
- Controller return: `return view('module.index', compact('data'))`

### Database
- MySQL 8, Eloquent ORM
- Semua tabel utama pakai `SoftDeletes`
- Format currency: `DECIMAL(15,2)`

---

## 3. Roles & Akses

| Role | Akses |
|---|---|
| `super_admin` | Full semua modul + manajemen user |
| `bendahara` | Semua modul keuangan, laporan, indikator |
| `pengurus` | Read-only: dashboard, rekapitulasi, laporan |
| `koordinator` | Pengeluaran & indikator bidangnya sendiri saja |

Implementasi: `RoleMiddleware` di `app/Http/Middleware/`, dipakai di route group.  
Koordinator punya `bidang_kerja_id` di tabel `users` — filter data berdasarkan ini.

---

## 4. Struktur Database (ERD)

### Urutan Migrasi
Jalankan sesuai urutan ini (hindari FK error):

1. `users` — tambah kolom `role` (enum) dan `bidang_kerja_id` (FK nullable)
2. `tahun_anggaran`
3. `bidang_kerja`
4. `kategori_pendapatan`
5. `kategori_pengeluaran`
6. `indikator_mutu`
7. `pendapatan`
8. `pengeluaran`
9. `transaksi`
10. `capaian_indikator`
11. `audit_logs`

---

### Skema Tabel

#### `tahun_anggaran`
```
id, tahun YEAR UNIQUE, label VARCHAR(20), is_aktif BOOLEAN DEFAULT false,
created_at, updated_at
```
> Hanya 1 record boleh `is_aktif = true` sekaligus. Enforce via service/observer.

#### `bidang_kerja`
```
id, kode VARCHAR(5) UNIQUE, nama VARCHAR(100), deskripsi TEXT NULL,
warna_hex VARCHAR(7) NULL, urutan TINYINT DEFAULT 0,
created_at, updated_at
```

#### `kategori_pendapatan`
```
id, nama VARCHAR(100) UNIQUE, created_at, updated_at
```

#### `kategori_pengeluaran`
```
id, nama VARCHAR(100) UNIQUE, created_at, updated_at
```

#### `indikator_mutu`
```
id, kode VARCHAR(10) UNIQUE, bidang_kerja_id FK,
nama VARCHAR(250), target TEXT,
periode_evaluasi ENUM('Per kegiatan','Bulanan','Semesteran','Kuartalan','Tahunan','Per rapat','Periodik'),
urutan TINYINT DEFAULT 0, is_aktif BOOLEAN DEFAULT true,
created_at, updated_at
```

#### `pendapatan`
```
id, tahun_anggaran_id FK, kategori_pendapatan_id FK,
nama_sumber VARCHAR(200), jumlah_rencana DECIMAL(15,2) DEFAULT 0,
jumlah_realisasi DECIMAL(15,2) DEFAULT 0, keterangan TEXT NULL,
created_by FK users NULL, updated_by FK users NULL,
deleted_at, created_at, updated_at
```

#### `pengeluaran`
```
id, tahun_anggaran_id FK, bidang_kerja_id FK,
kategori_pengeluaran_id FK, indikator_mutu_id FK NULL,
nama_kegiatan VARCHAR(250), jumlah_anggaran DECIMAL(15,2) DEFAULT 0,
jumlah_realisasi DECIMAL(15,2) DEFAULT 0, keterangan TEXT NULL,
created_by FK users NULL, updated_by FK users NULL,
deleted_at, created_at, updated_at
```

#### `transaksi`
```
id, kode_transaksi VARCHAR(30) UNIQUE,
tahun_anggaran_id FK, tanggal DATE,
jenis ENUM('masuk','keluar'), uraian VARCHAR(300),
bidang_kerja_id FK NULL,
transaksable_type VARCHAR(100) NULL, transaksable_id BIGINT NULL,  -- polymorphic
jumlah DECIMAL(15,2), nomor_bukti VARCHAR(50) NULL,
dicatat_oleh VARCHAR(100) NULL, created_by FK users NULL,
deleted_at, created_at, updated_at
```
> `kode_transaksi` format: `TRX-{YYYYMMDD}-{seq 3 digit}`, generate di service.  
> Polymorphic: `transaksable_type = App\Models\Pendapatan` atau `App\Models\Pengeluaran`

#### `capaian_indikator`
```
id, indikator_mutu_id FK, tahun_anggaran_id FK,
status ENUM('tercapai','proses','belum','') DEFAULT '',
catatan TEXT NULL, updated_by FK users NULL, updated_at_manual TIMESTAMP NULL,
created_at, updated_at
```
> **UNIQUE** constraint: `(indikator_mutu_id, tahun_anggaran_id)`  
> Gunakan `updateOrCreate` saat update status.

#### `audit_logs`
```
id, user_id FK NULL, event ENUM('created','updated','deleted','login','logout'),
auditable_type VARCHAR(100), auditable_id BIGINT,
old_values JSON NULL, new_values JSON NULL,
ip_address VARCHAR(45) NULL, user_agent TEXT NULL,
created_at
```

---

### Seeder yang Harus Dibuat

```
BidangKerjaSeeder        → 5 bidang (B1–B5, lihat data di bawah)
IndikatorMutuSeeder      → 30 indikator (lihat data di bawah)
KategoriPendapatanSeeder → 5 kategori
KategoriPengeluaranSeeder→ 11 kategori
TahunAnggaranSeeder      → tahun berjalan sebagai aktif
UserSeeder               → 1 super_admin, 1 bendahara
```

---

## 5. Master Data Seeder

### Bidang Kerja (B1–B5)
| Kode | Nama | Warna Hex |
|---|---|---|
| B1 | Pengembangan Organisasi | `#0e8a72` |
| B2 | Pendidikan | `#1a5fa0` |
| B3 | Penelitian & Pengabmas | `#7b2fa8` |
| B4 | Publikasi | `#b85010` |
| B5 | Kerjasama Antar Lembaga | `#a0272e` |

### Indikator Mutu (30 total)
**B1 — Pengembangan Organisasi:**
- B1.1 Struktur organisasi tersusun dan disahkan → Tahunan
- B1.2 Tingkat kehadiran rapat pengurus ≥ 80% → Per rapat
- B1.3 Program kerja tahunan tersusun → Tahunan
- B1.4 Rekrutmen anggota baru ≥ 10%/tahun → Tahunan
- B1.5 Database anggota terupdate (akurasi ≥ 95%) → Semesteran
- B1.6 Musyawarah/Kongres terlaksana sesuai AD/ART → Periodik
- B1.7 LPJ tersusun dan diserahkan tepat waktu → Tahunan

**B2 — Pendidikan:**
- B2.1 Min. 2 pelatihan/workshop per tahun → Tahunan
- B2.2 Kepuasan peserta pelatihan ≥ 80 → Per kegiatan
- B2.3 ≥ 70% peserta lulus uji kompetensi → Per kegiatan
- B2.4 Modul/kurikulum diperbarui min. 1x/tahun → Tahunan
- B2.5 ≥ 60% anggota mendapat pendampingan pendidikan → Tahunan
- B2.6 Min. 1 MoU dengan institusi pendidikan aktif → Tahunan

**B3 — Penelitian & Pengabmas:**
- B3.1 Min. 1 penelitian terdokumentasi/tahun → Tahunan
- B3.2 Min. 2 kegiatan pengabdian masyarakat/tahun → Tahunan
- B3.3 ≥ 30% anggota terlibat kegiatan riset → Tahunan
- B3.4 Laporan dampak pengabmas tersusun pasca kegiatan → Per kegiatan
- B3.5 Min. 1 proposal diajukan ke sumber dana eksternal → Tahunan
- B3.6 100% kegiatan terdokumentasi resmi → Per kegiatan

**B4 — Publikasi:**
- B4.1 Min. 2 edisi buletin terbit/tahun → Semesteran
- B4.2 Konten website/medsos diperbarui ≥ 2x/bulan → Bulanan
- B4.3 Min. 4 artikel terpublikasi per tahun → Tahunan
- B4.4 100% kegiatan utama terpublikasikan → Per kegiatan
- B4.5 Engagement rate medsos ≥ 3% → Bulanan
- B4.6 Min. 1 produk edukasi per kuartal → Kuartalan

**B5 — Kerjasama Antar Lembaga:**
- B5.1 Min. 3 MoU/perjanjian kerjasama aktif → Tahunan
- B5.2 ≥ 70% MoU terealisasi dalam program nyata → Tahunan
- B5.3 Hadir min. 2 forum nasional/tahun → Tahunan
- B5.4 Min. 1 kegiatan bersama pemerintah/tahun → Tahunan
- B5.5 Kepuasan mitra kerjasama ≥ 75 → Tahunan
- B5.6 Laporan kerjasama tearsip tiap semester → Semesteran

### Kategori Pendapatan
`Iuran Anggota`, `Dana Pemerintah / Hibah`, `Donasi / Sponsor`, `Usaha Organisasi`, `Lain-lain`

### Kategori Pengeluaran
`Rapat & Koordinasi`, `Pelatihan & Workshop`, `Penelitian`, `Pengabdian Masyarakat`, `Publikasi & Dokumentasi`, `Kerjasama & MoU`, `Honorarium`, `Administrasi & ATK`, `Konsumsi`, `Transportasi`, `Lain-lain`

---

## 6. Halaman & Fitur Per Modul

### Layout Utama (`layouts/app.blade.php`)
- Sidebar kiri fixed (lebar 260px), background `#0b1f38`
- Logo "SI**KEU** PIPMARSI" + chip tahun anggaran aktif
- Menu navigasi dengan grup: Utama | Keuangan | Mutu & Evaluasi | Output
- Topbar: judul halaman aktif + chip TA + avatar user
- Konten main area di kanan sidebar
- Notifikasi toast pojok kanan bawah (auto-dismiss 3 detik)
- Warna aktif menu: `#0e8a72` (teal), border-left highlight

---

### Halaman: Dashboard (`/dashboard`)

**Stat Cards (4 kartu, grid 4 kolom):**
- Total Rencana Pendapatan = `SUM(pendapatan.jumlah_rencana)` TA aktif
- Realisasi Pendapatan = `SUM(pendapatan.jumlah_realisasi)` + persentase dari rencana
- Total Anggaran Belanja = `SUM(pengeluaran.jumlah_anggaran)` TA aktif
- Realisasi Pengeluaran = `SUM(pengeluaran.jumlah_realisasi)` + persentase dari anggaran

**Panel Saldo & Serapan:**
- Saldo Kas = Realisasi Pendapatan − Realisasi Pengeluaran
- Progress bar serapan anggaran (%)

**Panel Capaian Indikator Mutu:**
- Donut chart (ApexCharts): Tercapai / Proses / Belum
- Persentase tercapai dari total 30 indikator

**Panel Serapan Per Bidang:**
- 5 baris progress bar, satu per bidang (B1–B5)
- Anggaran vs realisasi dengan persentase

**Timeline Aktivitas Terbaru:**
- 5 transaksi terakhir: tanggal, uraian, jumlah, jenis (masuk/keluar)

**Panel Peringatan (alert otomatis):**
- Serapan > 90%: "Anggaran hampir habis"
- Realisasi pendapatan < 50% target: "Realisasi pendapatan rendah"
- Indikator mutu belum tercapai mendekati akhir periode

Controller return:
```php
public function index()
{
    $tahunAktif = TahunAnggaran::where('is_aktif', true)->first();

    return view('dashboard.index', [
        'stats' => $this->financeService->getDashboardStats($tahunAktif),
        'serapanPerBidang' => $this->financeService->getSerapanPerBidang($tahunAktif),
        'capaihanMutu' => $this->financeService->getCapaianMutuSummary($tahunAktif),
        'aktivitasTerbaru' => $this->financeService->getAktivitasTerbaru($tahunAktif, 5),
        'peringatan' => $this->financeService->getPeringatan($tahunAktif),
    ]);
}
```
> Data untuk chart (ApexCharts) di-passing ke view sebagai array PHP, lalu di-`json_encode()` di Blade dan dibaca oleh JS:
> ```blade
> <script>
>   const capaianMutu = @json($capaihanMutu);
>   const serapanPerBidang = @json($serapanPerBidang);
> </script>
> ```

---

### Halaman: Rencana Pendapatan (`/pendapatan`)

**Tabel kolom:**
`#` | `Sumber Pendapatan` | `Kategori` | `Keterangan` | `Rencana (Rp)` | `Realisasi (Rp)` | `Sisa` | `%` | `Status` | `Aksi`

**Badge Status** (berdasarkan `realisasi / rencana`):
- `Belum` (0%) → abu-abu
- `Rendah` (> 0% < 50%) → merah
- `Proses` (50% – 99%) → kuning/oranye
- `Selesai` (≥ 100%) → hijau

**Footer tabel:** Total Rencana | Total Realisasi | Total Sisa

**Tombol Aksi:**
- `+ Tambah Pendapatan` → buka modal form
- Per baris: `Edit Realisasi` (modal kecil update realisasi saja) | `Hapus`

**Modal Tambah/Edit Pendapatan:**
- Field: Nama Sumber*, Kategori* (dropdown), Jumlah Rencana* (number), Realisasi (number), Keterangan (textarea)

**Modal Update Realisasi:**
- Field: Nama Pos (readonly), Realisasi Baru* (number)

**Business rules:**
- Hapus pendapatan → cek apakah ada transaksi terkait, jika ada tampilkan warning
- Update realisasi via modal tidak membuat entry transaksi (update langsung)
- Update realisasi via transaksi di modul Aktivitas → auto-update `jumlah_realisasi`

---

### Halaman: Rencana Pengeluaran (`/pengeluaran`)

**Tab filter bidang** (5 tab berwarna sesuai warna bidang):
- Tab aktif: opacity penuh, shadow
- Tab non-aktif: opacity 55%, sedikit grayscale
- Klik tab → filter tabel via Alpine.js (`x-show`/`x-data` pada data yang sudah di-passing dari controller) **atau** AJAX GET ke route dengan query param `?bidang=B1` yang merender partial Blade tabel saja

**Tabel kolom per bidang:**
`#` | `Kegiatan / Pos` | `Kategori` | `Indikator Mutu` | `Anggaran (Rp)` | `Realisasi (Rp)` | `Sisa` | `Serapan` | `Status` | `Aksi`

- Kolom Indikator Mutu: tampilkan kode (berwarna sesuai bidang) + nama indikator di bawahnya (font kecil, muted)
- Footer bidang aktif: total anggaran + total realisasi

**Tabel Ringkasan Semua Bidang** (di bawah tabel utama):
`Bidang` | `Anggaran` | `Realisasi` | `Sisa` | `Serapan (%)` → baris total di bawah

**Modal Tambah/Edit Pengeluaran:**
- Field: Nama Kegiatan*, Bidang* (dropdown), Kategori* (dropdown), Anggaran* (number), Realisasi (number), Indikator Mutu Terkait (dropdown indikator), Keterangan

**Aksi per baris:** `Edit Realisasi` | `Hapus`

---

### Halaman: Aktivitas & Realisasi (`/aktivitas`)

**Tabel Jurnal kolom:**
`Kode` | `Tanggal` | `Jenis` | `Uraian` | `Bidang` | `Pos Terkait` | `No. Bukti` | `Jumlah (Rp)` | `Dicatat Oleh` | `Aksi`

- Badge jenis: `Pemasukan` (hijau) | `Pengeluaran` (merah)

**Modal Catat Transaksi:**
- Field: Tanggal* (date), Jenis* (dropdown: Pemasukan/Pengeluaran), Uraian*, Bidang Terkait (dropdown bidang), Pos Terkait (dropdown dinamis berdasarkan bidang + jenis), Jumlah* (number), No. Bukti, Dicatat Oleh

**Business rules (PENTING):**
1. Saat transaksi `masuk` disimpan → `pendapatan.jumlah_realisasi += jumlah` (pada pos terkait)
2. Saat transaksi `keluar` disimpan → `pengeluaran.jumlah_realisasi += jumlah`
3. Saat transaksi dihapus (soft delete) → **reversal**: kurangi kembali realisasi pos terkait
4. Kode transaksi di-generate otomatis di service: `TRX-{YYYYMMDD}-{seq 3 digit}`, seq dimulai dari 001 per hari
5. Dropdown "Pos Terkait" berubah dinamis berdasarkan bidang dan jenis yang dipilih — implementasi via **Alpine.js**: data semua pos pengeluaran/pendapatan di-passing dari controller sebagai JSON ke `x-data`, lalu di-`x-for` filter sesuai pilihan bidang/jenis tanpa request tambahan

---

### Halaman: Indikator Mutu (`/indikator`)

**Stat Cards (4 kartu):**
- Total Indikator | Tercapai | Dalam Proses | Belum Tercapai

**Filter Tab Bidang:** (sama dengan pengeluaran, 5 tab)

**Card per Bidang:** (muncul sesuai tab aktif)
- Header card: icon + nama bidang + progress bar capaian bidang
- List indikator per bidang: kode, nama, target, periode evaluasi
- Per indikator: dropdown status (`Tercapai` / `Dalam Proses` / `Belum Tercapai`) + textarea catatan (collapsible)
- Badge jumlah pos pengeluaran terkait (link ke pengeluaran dengan filter indikator tsb)

**Business rules:**
- Update status → `updateOrCreate` pada `capaian_indikator` dengan `(indikator_mutu_id, tahun_anggaran_id)`
- Status default: string kosong `''` (belum diisi)
- Perubahan status tercatat di `audit_logs`

---

### Halaman: Rekapitulasi (`/rekapitulasi`)

**Stat Cards (4 kartu, read-only):**
- Rencana Pendapatan | Realisasi Pendapatan | Total Anggaran | Realisasi Belanja

**Panel Posisi Keuangan** (tabel dua kolom):
```
Rencana Pendapatan     | Rp xxx
Realisasi Pendapatan   | Rp xxx  (xxx%)
Selisih Pendapatan     | Rp xxx
Total Anggaran Belanja | Rp xxx
Realisasi Belanja      | Rp xxx  (xxx%)
Efisiensi Anggaran     | Rp xxx
Saldo Kas              | Rp xxx
```

**Panel Capaian Indikator Mutu Per Bidang:**
- Bar chart horizontal (ApexCharts): % indikator tercapai per bidang

**Tabel Rincian Per Bidang:**
`#` | `Bidang` | `Pos Kegiatan` | `Anggaran` | `Realisasi` | `Sisa` | `Serapan` | `Mutu Terkait`

---

### Halaman: Audit & Monitoring (`/audit`)

**Panel Hasil Pemeriksaan:**
- Daftar hasil check kepatuhan (generate saat klik tombol "Jalankan Audit")
- Setiap item: icon (✅/⚠️/❌), judul, detail, waktu
- Kondisi yang diperiksa:
  - Total anggaran belanja > total rencana pendapatan → warning
  - Pos pengeluaran dengan realisasi > 0 tapi tidak ada transaksi terkait → warning
  - Indikator mutu yang belum ada status dan sudah ada anggaran terkait → info
  - Transaksi tanpa nomor bukti → info

**Panel KPI Keuangan:**
- Rasio Serapan = Realisasi Belanja / Total Anggaran × 100%
- Rasio Realisasi Pendapatan = Realisasi Pendapatan / Rencana × 100%
- Total Transaksi periode ini
- Rata-rata nilai transaksi

**Log Audit Trail:**
- Daftar semua entry `audit_logs` untuk TA aktif
- Kolom: icon jenis, judul aksi, detail, waktu, user

**Tombol:** `Jalankan Audit` → form POST (atau Alpine fetch AJAX) ke `POST /audit/run` → `AuditController@run()` panggil `AuditService::runChecks()` → redirect back dengan data hasil via session flash, atau return partial Blade jika AJAX

---

### Halaman: Cetak Laporan (`/laporan`)

**4 kartu laporan** (grid 4 kolom), klik → generate PDF:
1. 📥 **Laporan Pendapatan** — Rencana & Realisasi
2. 📤 **Laporan Pengeluaran** — Per Bidang Kerja
3. 🎯 **Laporan Indikator Mutu** — Capaian 5 Bidang
4. 📊 **Laporan Rekapitulasi** — Ringkasan Lengkap

Setiap laporan:
- Buka di tab baru sebagai PDF (response mPDF)
- Header: nama organisasi (PIPMARSI), judul laporan, periode TA, tanggal cetak
- Footer: area tanda tangan Bendahara + stempel
- Route: `GET /laporan/{type}` → `LaporanController@generate($type)`

---

### Halaman: Manajemen User (`/users`) — Super Admin only

**Tabel:** Nama | Email | Role | Bidang (jika koordinator) | Status Aktif | Aksi  
**Modal Tambah/Edit:** Nama*, Email*, Password (kosong = tidak ganti saat edit), Role*, Bidang Kerja (muncul jika role = koordinator), Status Aktif  
**Aksi:** Edit | Nonaktifkan | Reset Password

---

### Halaman: Tahun Anggaran (`/tahun-anggaran`) — Super Admin only

**Tabel:** Tahun | Label | Status (Aktif/Tidak)  
**Tombol:** Tambah TA | Set Aktif (hanya 1 yang boleh aktif)  
> Saat set aktif TA baru, semua data modul otomatis difilter ke TA tersebut (via View Composer shared data)

---

## 7. Service Layer

### `FinanceService`
```php
getDashboardStats(TahunAnggaran $ta): array
getSerapanPerBidang(TahunAnggaran $ta): array
getCapaianMutuSummary(TahunAnggaran $ta): array
getAktivitasTerbaru(TahunAnggaran $ta, int $limit): Collection
getPeringatan(TahunAnggaran $ta): array
generateKodeTransaksi(string $tanggal): string  // TRX-YYYYMMDD-001
updateRealisasiFromTransaksi(Transaksi $t, string $op): void  // op: 'add' | 'reverse'
```

### `AuditService`
```php
runChecks(TahunAnggaran $ta): array  // return list of findings
getKpi(TahunAnggaran $ta): array
```

### `LaporanService`
```php
generatePendapatan(TahunAnggaran $ta): \Mpdf\Mpdf
generatePengeluaran(TahunAnggaran $ta): \Mpdf\Mpdf
generateMutu(TahunAnggaran $ta): \Mpdf\Mpdf
generateRekap(TahunAnggaran $ta): \Mpdf\Mpdf
```

---

## 8. Shared Data (View Composer)

Gunakan **View Composer** agar data global tersedia di semua view tanpa repeat di setiap controller.

`app/Providers/AppServiceProvider.php` (atau `ViewServiceProvider` terpisah):
```php
View::composer('*', function ($view) {
    $view->with([
        'authUser'   => auth()->user(),
        'userRole'   => auth()->user()?->role,
        'tahunAktif' => TahunAnggaran::where('is_aktif', true)->first(),
        'bidangList' => BidangKerja::orderBy('urutan')->get(),
    ]);
});
```

Gunakan variabel ini di `layouts/app.blade.php` untuk sidebar, chip tahun anggaran, dan filter role-based menu.

---

## 9. Struktur Direktori

```
app/
  Http/
    Controllers/
      DashboardController.php
      PendapatanController.php
      PengeluaranController.php
      TransaksiController.php
      IndikatorMutuController.php
      RekapitulasiController.php
      AuditController.php
      LaporanController.php
      UserController.php
      TahunAnggaranController.php
    Requests/
      StorePendapatanRequest.php
      UpdatePendapatanRequest.php
      UpdateRealisasiPendapatanRequest.php
      StorePengeluaranRequest.php
      UpdatePengeluaranRequest.php
      UpdateRealisasiPengeluaranRequest.php
      StoreTransaksiRequest.php
      UpdateCapaianRequest.php
      StoreUserRequest.php
      UpdateUserRequest.php
      ... (satu per operasi form)
    Middleware/
      RoleMiddleware.php
  Models/
    User.php
    TahunAnggaran.php
    BidangKerja.php
    KategoriPendapatan.php
    KategoriPengeluaran.php
    Pendapatan.php
    Pengeluaran.php
    Transaksi.php
    IndikatorMutu.php
    CapaianIndikator.php
    AuditLog.php
  Services/
    FinanceService.php
    AuditService.php
    LaporanService.php
  Observers/
    AuditLogObserver.php   ← register di AppServiceProvider::boot()
  Providers/
    AppServiceProvider.php  ← View Composer untuk shared data

resources/
  views/
    layouts/
      app.blade.php          ← sidebar + topbar + slot konten
      guest.blade.php         ← layout untuk halaman login
    components/
      stat-card.blade.php
      progress-bar.blade.php
      badge-status.blade.php
      modal.blade.php
      bidang-tabs.blade.php
    dashboard/
      index.blade.php
    pendapatan/
      index.blade.php
      partials/_table.blade.php
      partials/_modal-form.blade.php
      partials/_modal-realisasi.blade.php
    pengeluaran/
      index.blade.php
      partials/_table.blade.php
      partials/_modal-form.blade.php
      partials/_ringkasan.blade.php
    aktivitas/
      index.blade.php
      partials/_table.blade.php
      partials/_modal-form.blade.php
    indikator/
      index.blade.php
      partials/_card-bidang.blade.php
    rekapitulasi/
      index.blade.php
    audit/
      index.blade.php
    laporan/
      index.blade.php
      pendapatan.blade.php   ← template PDF
      pengeluaran.blade.php  ← template PDF
      mutu.blade.php         ← template PDF
      rekap.blade.php        ← template PDF
    users/
      index.blade.php
      partials/_modal-form.blade.php
    tahun-anggaran/
      index.blade.php
    auth/
      login.blade.php
  css/
    app.css     ← Tailwind entry
  js/
    app.js      ← Alpine.js init + ApexCharts helper functions

routes/
  web.php

database/
  migrations/
  seeders/
```

### Pola Komponen Blade

Gunakan **Blade Components** untuk elemen reusable, contoh:

```blade
{{-- resources/views/components/stat-card.blade.php --}}
@props(['label', 'value', 'sub' => null, 'icon' => null, 'variant' => 'teal'])

<div class="sc {{ $variant }}">
    <div class="sc-ico">{{ $icon }}</div>
    <div class="sc-lbl">{{ $label }}</div>
    <div class="sc-val">{{ $value }}</div>
    @if($sub)<div class="sc-sub">{{ $sub }}</div>@endif
</div>
```

Pemakaian:
```blade
<x-stat-card label="Total Pendapatan Rencana" :value="'Rp ' . number_format($stats['rencana_pendapatan'], 0, ',', '.')" icon="💰" variant="t" />
```

### Pola Modal dengan Alpine.js

```blade
{{-- resources/views/components/modal.blade.php --}}
@props(['name'])

<div x-show="openModal === '{{ $name }}'" x-cloak
     class="mover" :class="{ open: openModal === '{{ $name }}' }">
    <div class="mbox">
        {{ $slot }}
    </div>
</div>
```

Root `x-data` didefinisikan di `layouts/app.blade.php`:
```blade
<body x-data="{ openModal: null }">
```
Buka modal: `@click="openModal = 'm-pend'"` · Tutup: `@click="openModal = null"`

### Pola AJAX Partial (untuk tab bidang & update tanpa reload)

Controller:
```php
public function index(Request $request)
{
    $bidang = $request->query('bidang', 'B1');
    $data = $this->financeService->getPengeluaranByBidang($bidang);

    if ($request->ajax()) {
        return view('pengeluaran.partials._table', compact('data'))->render();
    }

    return view('pengeluaran.index', compact('data', 'bidang'));
}
```

Alpine.js fetch saat klik tab:
```html
<div @click="fetch(`/pengeluaran?bidang=B1`, {headers: {'X-Requested-With':'XMLHttpRequest'}})
              .then(r => r.text())
              .then(html => document.getElementById('peng-content').innerHTML = html)">
```

---

## 10. Konvensi Penting

### Format Data
- **Currency**: tampil sebagai `Rp 1.000.000` (format ID), simpan sebagai `DECIMAL(15,2)`
- **Persentase**: hitung di backend, tampilkan dengan `{{ $value }}%` di Blade
- **Tanggal**: simpan sebagai `DATE`, tampil sebagai `DD/MM/YYYY` (format ID)

### Kalkulasi Otomatis
- `jumlah_realisasi` di tabel `pendapatan` dan `pengeluaran` **HANYA** diubah via:
  1. Modal "Update Realisasi" langsung (manual)
  2. Service `updateRealisasiFromTransaksi()` saat transaksi disimpan/dihapus
- Jangan ada update realisasi di tempat lain

### Audit Log
- Observer otomatis catat semua `created`, `updated`, `deleted` pada model:  
  `Pendapatan`, `Pengeluaran`, `Transaksi`, `CapaianIndikator`, `User`
- Format `old_values` dan `new_values`: JSON dari `$model->getDirty()` dan `$model->getOriginal()`

### Soft Deletes
- Semua tabel utama (`pendapatan`, `pengeluaran`, `transaksi`) pakai `SoftDeletes`
- Saat soft delete transaksi → **wajib** jalankan reversal realisasi di Observer atau Controller

---

## 11. Desain Visual

Acuan warna (dari prototipe):
```
--navy:    #0b1f38   (sidebar, heading utama)
--teal:    #0e8a72   (aksen utama, menu aktif, tombol primer)
--gold:    #c8960c   (aksen sekunder, warning)
--red:     #c0392b   (danger, pengeluaran)
--green:   #1a7a4e   (sukses, pendapatan masuk)
--blue:    #1a5fa0   (bidang B2)
--purple:  #7b2fa8   (bidang B3)
--orange:  #b85010   (bidang B4)
--bg:      #f2f4f8   (background halaman)
--card:    #ffffff
--border:  #e0e5ee
--muted:   #6b7a90
```

Font: `Outfit` (body) + `Lora` (judul halaman, serif)

---

## 12. Acceptance Criteria (Definition of Done)

- [ ] Semua migrasi dan seeder berhasil dijalankan
- [ ] Login/logout berfungsi, redirect sesuai role
- [ ] Dashboard menampilkan data yang konsisten dengan DB
- [ ] CRUD pendapatan, pengeluaran, transaksi berfungsi penuh
- [ ] Saat transaksi disimpan/dihapus, realisasi pos terkait ter-update otomatis
- [ ] Update status indikator tersimpan per tahun anggaran
- [ ] 4 jenis laporan PDF ter-generate dengan data yang benar
- [ ] Role koordinator hanya bisa akses bidangnya sendiri
- [ ] Audit log tercatat untuk setiap perubahan data utama
- [ ] Semua halaman responsif untuk layar ≥ 768px
