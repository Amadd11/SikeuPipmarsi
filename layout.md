Sesuai PRD.md bagian 6 (Halaman & Fitur) dan bagian 9 (Struktur Direktori),
buatkan:

1. resources/views/layouts/app.blade.php — layout utama dengan:
   - Sidebar fixed kiri (260px, background navy #0b1f38) berisi menu navigasi
     sesuai grup: Utama (Dashboard), Keuangan (Rencana Pendapatan, Rencana
     Pengeluaran, Aktivitas & Realisasi), Mutu & Evaluasi (Indikator Mutu,
     Rekapitulasi, Audit & Monitoring), Output (Cetak Laporan)
   - Logo "SI**KEU** PIPMARSI" + chip tahun anggaran aktif
   - Topbar dengan judul halaman aktif (gunakan @yield atau slot) + chip TA + avatar
   - Root <body x-data="{ openModal: null }"> untuk state modal global
   - Slot konten utama via @yield('content') atau {{ $slot }}
   - Container notifikasi toast (pojok kanan bawah)

2. resources/views/layouts/guest.blade.php — layout sederhana untuk halaman login

3. Komponen Blade reusable di resources/views/components/:
   - stat-card.blade.php (props: label, value, sub, icon, variant)
   - progress-bar.blade.php (props: value, max, color)
   - badge-status.blade.php (props: realisasi, target — auto hitung status
     Belum/Rendah/Proses/Selesai sesuai PRD)
   - modal.blade.php (props: name — pakai Alpine x-show terhadap openModal)
   - bidang-tabs.blade.php (props: bidangList, activeBidang)

4. app/Providers/AppServiceProvider.php — tambahkan View Composer global
   sesuai PRD.md bagian 8 (Shared Data): inject authUser, userRole,
   tahunAktif, bidangList ke semua view

Gunakan palet warna sesuai PRD.md bagian 11 (Desain Visual). Font: Outfit
(body) dan Lora (judul halaman) — load dari Google Fonts.

Buat juga app/Http/Middleware/RoleMiddleware.php sesuai PRD.md bagian 3
(Roles & Akses), dan daftarkan sebagai alias 'role' di bootstrap/app.php.