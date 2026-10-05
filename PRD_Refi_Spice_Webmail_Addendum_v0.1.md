# ADDENDUM PRD — Webmail Operasional Refi Spice
## Change Request CR-WEBMAIL-001 | `mail.domain.com`

| | |
|---|---|
| **Versi Dokumen** | 0.1.2 (Draft revisi untuk persetujuan) |
| **Tanggal** | 16 September 2026 |
| **Status** | Perlu persetujuan pemilik proyek sebelum implementasi |
| **Dokumen Induk** | `PRD_Refi_Spice_MASTER_v2.md` v2.0.0 |
| **Area Perubahan** | Infrastruktur email dan webmail operasional; tidak mengubah scope website publik |

---

## 1. Latar Belakang dan Tujuan

Website Refi Spice sudah memiliki alur inquiry dan notifikasi email. Namun, staf ekspor dan manajer tetap membutuhkan mailbox operasional yang mudah dipakai untuk membaca, membalas, mencari, dan mengelola email perusahaan tanpa bergantung pada aplikasi desktop atau UI teknis.

Tujuan Change Request ini adalah menyediakan webmail pada `https://mail.domain.com` dengan pengalaman modern yang familiar bagi pengguna Gmail, sambil mempertahankan mail server self-hosted HestiaCP yang telah ditetapkan pada PRD induk.

### Sasaran Pengguna

| Peran | Kebutuhan utama |
|---|---|
| Staf ekspor/sales | Menerima inquiry, membalas buyer, mencari riwayat email, mengirim lampiran katalog/dokumen. |
| Manajer | Memantau inbox operasional, mengakses mailbox yang diizinkan, dan meninjau komunikasi buyer. |
| Administrator infrastruktur | Membuat akun mailbox, mengelola domain, reset password, backup, dan keamanan mail server. |

---

## 2. Keputusan Arsitektur

### 2.1 Keputusan Utama

- `mail.domain.com` adalah **webmail produksi**, bukan bagian dari aplikasi Laravel dan bukan panel Filament.
- Client webmail dipilih pada gerbang **Go/No-Go W0**. Kandidat yang dievaluasi adalah **Roundcube** (baseline yang aktif dipelihara) dan **SnappyMail** (opsional; hanya boleh dipilih bila lolos pemeriksaan maintenance, rilis keamanan, dan kompatibilitas). UI yang familier adalah kriteria penting, tetapi tidak mengalahkan keamanan dan kemampuan patch.
- Mailbox asli disimpan serta disajikan oleh mail stack HestiaCP melalui SMTP/IMAP. Webmail hanya menjadi antarmuka pengguna.
- Login webmail menggunakan kredensial mailbox pengguna langsung terhadap IMAP/SMTP. Laravel, database aplikasi, dan repository tidak menyimpan password mailbox.
- `mail.domain.com` diprovisikan oleh administrator pada mail domain HestiaCP beserta webmail client yang dipilih; tidak dibuat virtual host Laravel kustom untuk webmail.
- Laravel tetap mengirim notifikasi inquiry melalui SMTP dan queue. Tidak ada SMTP custom di dalam kode Laravel.
- Mailpit hanya dipakai pada **lokal/staging** untuk menangkap email uji aplikasi. Mailpit tidak boleh menjadi inbox produksi, penyimpanan email staf, atau endpoint publik.

### 2.1.1 Kriteria Go/No-Go Client Webmail

Semua kriteria berikut adalah **wajib lulus** pada W0; satu kegagalan berarti kandidat tidak dapat dipakai untuk W1/W4 sampai risiko ditutup dengan bukti baru.

| Kriteria | Ambang lulus yang objektif |
|---|---|
| Maintenance | Rilis stabil resmi terakhir berumur ≤12 bulan pada tanggal evaluasi **atau** ada bukti aktivitas commit resmi ≤6 bulan, disertai jalur rilis/patch yang masih aktif. |
| Security | Tidak ada CVE/advisory **High** atau **Critical** yang belum dipatch dan memengaruhi versi/konfigurasi target lebih dari 90 hari sejak pengungkapan. Patch yang tersedia harus dapat dipasang pada stack target. |
| Kompatibilitas | Dokumentasi resmi mengonfirmasi kompatibilitas dengan versi HestiaCP dan PHP target; hasil tidak boleh berdasarkan asumsi atau blog pihak ketiga saja. |
| Transport dan autentikasi | IMAP/SMTP memakai TLS, autentikasi mailbox langsung berjalan, dan dokumentasi resmi menunjukkan konfigurasi/perilaku session dan logout yang dapat diuji pada W1. |
| Operasional | Versi dipin, sumber paket dapat diverifikasi, ada prosedur update/rollback, dan tidak membutuhkan fork source atau virtual host Laravel kustom. |

Penilaian W0 mencatat URL bukti, tanggal pemeriksaan, versi yang dinilai, serta keputusan Pass/Fail untuk setiap baris. Bila seluruh kandidat gagal, proyek berhenti sebelum staging/produksi dan membuka keputusan arsitektur baru; tidak ada pengecualian informal.

### 2.2 Diagram Alur

```text
Lokal / staging
Laravel → SMTP → Mailpit → pemeriksaan email uji oleh developer

Produksi
Buyer / internet → MX domain → HestiaCP mail server → mailbox IMAP
Staf / manajer → https://mail.domain.com (client yang lolos W0) → IMAP / SMTP submission
Laravel → SMTP submission → HestiaCP mail server atau relay → buyer / staf
```

### 2.3 Batas Scope

#### In-scope

- Subdomain `mail.domain.com` ber-HTTPS.
- Webmail yang lolos Go/No-Go W0 dan terhubung ke mailbox HestiaCP.
- Akun mailbox per staf dan mailbox fungsi, misalnya `sales@`, `export@`, atau `manager@`.
- Untuk go-live W4, alamat fungsi hanya menggunakan alias ke akun personal yang ditunjuk atau forwarding ke satu PIC.
- Inbox, Sent, Drafts, Trash, compose, reply/forward, pencarian, attachment, signature, dan tampilan responsif.
- Integrasi Laravel: notifikasi inquiry dikirim ke mailbox fungsi yang disetujui.
- Pengujian lokal, staging, UX, keamanan dasar, dan alur inquiry end-to-end.

#### Out-of-scope

- Membangun webmail custom di Laravel/Filament.
- Menjadikan Mailpit sebagai mailbox produksi.
- Groupware penuh: kalender, dokumen bersama, video meeting, CRM, atau chat.
- Sinkronisasi dengan Gmail/Microsoft 365, mobile native app, dan migrasi mailbox historis, kecuali disetujui sebagai Change Request lanjutan.
- Auto-switch SMTP relay dari kode aplikasi.
- Delegasi mailbox bersama (misalnya IMAP ACL/shared folder, akses multi-pengguna di client webmail, atau riwayat Sent bersama). Ini memerlukan Change Request terpisah dengan desain akses dan auditnya sendiri.

---

## 3. Pengalaman Pengguna yang Ditargetkan

Desain mengikuti pola mental Gmail, bukan menyalin identitas visualnya:

- Sidebar ringkas: Tulis Email, Inbox, Terkirim, Draft, Sampah.
- Daftar email dengan pengirim, subjek, potongan isi, waktu, status belum dibaca, dan attachment indicator.
- Panel baca yang jelas dengan tombol Balas, Teruskan, Arsip/Hapus, dan download attachment.
- Pencarian cepat berdasarkan pengirim, subjek, atau kata dalam isi email.
- Compose sederhana dengan auto-save draft, signature perusahaan, dan validasi attachment. Draft yang sedang diketik harus tersimpan sebelum sesi idle berakhir sehingga logout/timeout tidak menghilangkan isi kerja pengguna.
- Tampilan nyaman untuk desktop dan ponsel; pengguna tidak perlu memahami IMAP, SMTP, port, atau log server.

Kustomisasi visual hanya dilakukan melalui branding ringan yang didukung client webmail. Tidak boleh mem-fork source webmail hanya untuk mengejar tampilan mirip Gmail, karena akan menambah beban patch keamanan dan pemeliharaan.

---

## 4. Model Akun dan Akses

### 4.1 Prinsip

- Setiap manusia memakai akun personal bila memungkinkan, misalnya `nama@domain.com`.
- Alamat fungsi seperti `sales@domain.com` atau `export@domain.com` dipakai untuk alur bisnis yang jelas.
- Satu password bersama untuk mailbox fungsi **tidak diperbolehkan**. Untuk go-live W4 gunakan alias/forwarding; delegasi akses per pengguna hanya dipertimbangkan dalam Change Request lanjutan dengan audit yang tervalidasi.
- Password mailbox tidak disimpan pada Laravel, database aplikasi, atau repository.

### 4.2 Keputusan yang Wajib Disetujui Sebelum Implementasi

| Keputusan | Default yang direkomendasikan | Penyetuju | Batas keputusan |
|---|---|---|---|
| Domain webmail | `mail.domain.com` pada domain bisnis utama, HTTPS wajib. | Pemilik proyek + administrator infrastruktur | W0 selesai |
| Akun awal | Satu mailbox personal per staf; setiap alamat memiliki pemilik bisnis dan pemilik teknis. | Manajer + administrator infrastruktur | W0 selesai |
| `sales@` / alamat fungsi | **Alias ke akun personal yang ditunjuk**; untuk tim kecil, forwarding ke satu PIC. Inbox bersama, riwayat Sent bersama, dan delegasi akses tidak termasuk W4; semuanya membutuhkan Change Request lanjutan—bukan password bersama. | Manajer + pemilik proyek | W0 selesai |
| Reset password dan provisioning | Hanya administrator infrastruktur yang menjalankan reset/provisioning; manajer dapat mengajukan permintaan, bukan memegang akses admin. | Pemilik proyek | W0 selesai |
| Retensi, attachment, backup | Tetapkan retensi, ukuran maksimum, frekuensi backup, dan pemilik uji restore tertulis. | Pemilik proyek + manajer | W0 selesai |

**Exit condition W0 yang wajib:** tabel di atas terisi lengkap dengan nama pemilik/penyetuju aktual. Implementasi tidak boleh dimulai bila model `sales@`, pemilik reset password, atau retensi masih belum diputuskan.

---

## 5. Keamanan dan Operasional

- HTTPS wajib pada `mail.domain.com`; IMAP dan SMTP submission wajib memakai TLS.
- SMTP submission memakai akun terautentikasi; jangan membuka relay anonim.
- DNS produksi harus memiliki MX, SPF, DKIM, dan DMARC; PTR/divalidasi sebelum pengiriman produksi dinyatakan siap.
- Aktifkan proteksi brute force/rate limit yang tersedia pada HestiaCP/mail stack dan gunakan password kuat per akun.
- Webmail dan HestiaCP admin adalah dua endpoint berbeda; akses panel HestiaCP dibatasi hanya untuk administrator infrastruktur.
- MFA wajib dinilai pada W0. Minimal panel administrator wajib memakai MFA bila didukung; keputusan MFA untuk mailbox pengguna ditulis bersama kontrol pengganti bila client/stack tidak mendukungnya.
- Target awal session webmail: idle timeout 15 menit dan masa sesi maksimum 8 jam, setelah divalidasi terhadap kemampuan client terpilih serta kebutuhan operasional. W1 wajib membuktikan bahwa timeout saat compose tidak menghilangkan draft; bila tidak, parameter atau client harus diubah sebelum UAT.
- Audit minimum mencakup register otorisasi mailbox/alamat fungsi, log administrasi HestiaCP, serta log autentikasi mail stack (misalnya Dovecot/SMTP). Target awal retensi log adalah 90 hari, disesuaikan berdasarkan kapasitas, kebutuhan operasional, dan kewajiban yang berlaku; lokasi dan prosedur peninjauan divalidasi pada staging. Isi email tidak dicatat sebagai log audit.
- Backup mailbox, database aplikasi, dan media harus memiliki retensi serta uji restore terpisah.
- Mailpit hanya bind ke localhost atau jaringan development/staging yang terlindungi; tidak memakai mailbox atau data buyer produksi.
- Update keamanan HestiaCP, webmail client, dan mail stack dijadwalkan serta diuji pada staging sebelum production.
- W0 harus mendokumentasikan versi kandidat, tanggal rilis terakhir, advisory/CVE yang relevan, kompatibilitas PHP/HestiaCP target, dan prosedur patch/rollback. Kandidat tanpa maintenance atau jalur patch yang memadai tidak boleh dipakai di produksi.

---

## 6. Strategi Pengujian yang Efektif

### 6.1 Local Webmail Lab

Mailpit tidak memiliki IMAP mailbox sehingga tidak cukup untuk menguji pengalaman webmail nyata. Local lab menggunakan dua jalur paralel:

| Tujuan | Komponen lokal | Hasil yang dibuktikan |
|---|---|---|
| QA email Laravel | Laravel + Mailpit | Template inquiry, queue, isi HTML/text, attachment, dan kegagalan SMTP. |
| QA webmail | Client hasil W0 + mail server uji ber-IMAP/SMTP | Login mailbox, folder, compose, reply, draft, pencarian, attachment, dan responsif. |

Local lab dibuat disposable melalui Docker Compose atau virtual machine terisolasi. Ia menggunakan domain uji dan akun dummy; tidak menerima email Internet dan tidak memakai kredensial produksi.

### 6.2 Staging Sebagai Verifikasi Otoritatif

Karena produksi memakai HestiaCP, staging VPS dengan konfigurasi serupa adalah sumber kebenaran untuk:

- SSL pada `mail.domain.com`.
- Mail domain, akun, IMAP/SMTP TLS, dan login client webmail terpilih.
- DNS pengiriman, SPF/DKIM/DMARC, relay bila dipakai, serta perilaku attachment nyata.
- Pengiriman inquiry Laravel ke `sales@` dan balasan email ke buyer dummy.

### 6.3 Uji UX / User-Friendly

Gunakan tiga skenario tugas tanpa pendampingan berlebihan:

1. Staf menemukan inquiry baru, membuka pesan, dan membalas dengan attachment katalog.
2. Manajer mencari email buyer berdasarkan nama perusahaan/subjek lalu meneruskan ke staf ekspor.
3. Staf membuat draft, keluar/masuk kembali, lalu melanjutkan dan mengirim draft tersebut.

Kriteria lulus:

- Pengguna menemukan Inbox dan tombol Tulis Email tanpa penjelasan teknis.
- Tiga tugas selesai pada desktop serta ponsel tanpa error atau kebingungan besar.
- Tidak ada pengaturan server, port, atau kredensial teknis yang terlihat di alur staf/manajer.
- Screenshot dibuktikan pada desktop 1440 px, tablet 768 px, dan ponsel 375 px.
- Feedback dari minimal satu perwakilan staf dan satu manajer dicatat sebelum go-live.

### 6.4 Uji Fungsional dan Keamanan

- Login salah berulang ditolak/dibatasi.
- Akun A tidak dapat membaca mailbox akun B tanpa akses yang diizinkan.
- Kirim, terima, reply, forward, delete, restore dari Trash, dan pencarian berjalan.
- Attachment yang diizinkan dapat diunggah/diunduh; tipe/ukuran terlarang mendapat pesan jelas.
- Laravel inquiry masuk ke mailbox fungsi dan dapat dibalas menggunakan alamat bisnis yang benar.
- Sertifikat TLS valid, tidak ada mixed-content, dan koneksi IMAP/SMTP memakai TLS.
- Backup serta restore satu mailbox uji divalidasi sebelum go-live.
- Kirim email uji ke Gmail dan Outlook, lalu buktikan pesan tiba di Inbox (bukan Spam/Junk) dengan header/DKIM yang dapat diperiksa.
- Saat compose, biarkan sesi melewati idle timeout uji; setelah login kembali, draft harus dapat dibuka tanpa kehilangan isi yang telah diketik.

---

## 7. Rencana Implementasi Bertahap

### Fase W0 — Keputusan dan Desain (tanpa perubahan produksi)

1. Setujui daftar akun, shared inbox/alias, retensi, dan penanggung jawab.
2. Konfirmasi `mail.domain.com`, domain DNS, dan kebijakan branding ringan.
3. Buat wireframe ringkas berdasarkan daftar layar: login, inbox, baca pesan, compose, pencarian, mobile.
4. Nilai Roundcube dan SnappyMail menggunakan ambang Pass/Fail pada Section 2.1.1. Pilih hanya kandidat yang lulus seluruh kriteria; bila tidak ada yang lulus, hentikan sebelum implementasi produksi.

**Exit criteria:** keputusan akun dan scope tertulis; pemilik reset password telah ditetapkan; tidak ada asumsi password bersama atau delegasi mailbox W4; client webmail dan rencana patch-nya lulus seluruh Go/No-Go Section 2.1.1.

### Fase W1 — Proof of Concept Lokal

1. Jalankan mail server uji ber-IMAP/SMTP dan client webmail hasil W0 pada lingkungan disposable.
2. Jalankan Mailpit secara terpisah untuk test Laravel yang sudah ada.
3. Buat mailbox dummy: `sales`, `export`, `manager`, dan `buyer-test`.
4. Jalankan smoke test teknis: login, kirim/terima, reply, draft, pencarian, attachment, serta uji TLS/session. Catat isu kompatibilitas dan konfigurasi.

**Exit criteria:** smoke test fungsional lulus, kredensial mailbox tidak tersimpan pada Laravel, dan tidak ada blocker kompatibilitas sebelum menyentuh VPS produksi.

### Fase W2 — Staging HestiaCP

1. Siapkan staging VPS/HestiaCP yang dipatch dan tidak menerima trafik bisnis.
2. Tambahkan mail domain, SSL, webmail client terpilih, mailbox dummy, dan DNS staging.
3. Konfigurasikan SPF/DKIM/DMARC staging sesuai domain yang dipakai.
4. Hubungkan aplikasi staging melalui SMTP dan queue `database`; jalankan worker terkelola.

**Exit criteria:** login HTTPS, alur email end-to-end, proteksi akses, serta pengujian attachment lolos pada stack yang setara production.

### Fase W3 — UAT dan Hardening

1. Staf/manajer menjalankan tiga skenario UX pada staging.
2. Perbaiki branding ringan, copy, signature, folder, atau konfigurasi yang menghambat tugas.
3. Uji backup/restore mailbox uji dan review log keamanan.
4. Persetujuan tertulis untuk akun awal dan daftar DNS production.

**Exit criteria:** UAT disetujui, tidak ada blocker keamanan, serta rencana rollback tersedia.

### Fase W4 — Go-Live Terkendali

1. Provision akun production dan DNS mail domain.
2. Aktifkan HTTPS, webmail, IMAP/SMTP TLS, anti-brute-force, backup, dan monitoring.
3. Ubah konfigurasi Laravel production ke SMTP yang disetujui serta `QUEUE_CONNECTION=database`.
4. Jalankan satu inquiry uji dengan buyer dummy, verifikasi masuk ke `sales@`, lalu verifikasi reply keluar.
5. Uji pengiriman ke Gmail dan Outlook lalu pastikan penerimaan di Inbox, bukan Spam/Junk. Pantau deliverability dan error mail selama 2–4 minggu; keputusan memakai SMTP relay tetap manual.

**Exit criteria:** email masuk/keluar, login webmail, backup, dan akses staff terverifikasi dengan bukti pengujian.

### 7.1 Rollback Go-Live

**Pemicu rollback:** login atau pengiriman gagal secara luas, TLS/autentikasi tidak valid, routing email tidak sesuai, kehilangan penerimaan email, atau pengiriman uji masuk Spam/Junk secara konsisten tanpa perbaikan yang aman.

1. Hentikan perubahan lanjutan dan dokumentasikan waktu, gejala, serta mailbox terdampak.
2. Jika ada provider email sebelumnya, kembalikan record MX ke provider tersebut (TTL diturunkan sebelum go-live) dan verifikasi penerimaan email. Mailbox HestiaCP tidak dihapus selama rollback.
3. Jika tidak ada provider sebelumnya, pertahankan mail server menerima email lalu nonaktifkan akses webmail yang bermasalah; staf memakai client alternatif yang telah disetujui sementara perbaikan divalidasi di staging.
4. Pulihkan konfigurasi/webmail dari backup yang sudah diuji bila rollback aplikasi diperlukan, lalu lakukan root-cause review sebelum mencoba go-live ulang.

---

## 8. Definition of Done

Webmail dianggap selesai hanya bila:

- [ ] `https://mail.domain.com` memakai HTTPS valid dan menampilkan webmail yang disetujui.
- [ ] Mailbox per akun berfungsi via IMAP/SMTP TLS.
- [ ] Staf dapat membaca, membalas, mencari, membuat draft, dan mengirim attachment tanpa bantuan teknis.
- [ ] Laravel inquiry diterima oleh mailbox fungsi yang disetujui dan dapat dibalas.
- [ ] Mailpit tetap terpisah sebagai alat lokal/staging; tidak menangkap atau menyimpan email produksi.
- [ ] Akun, akses inbox fungsi, rate limit, password policy, TLS, SPF/DKIM/DMARC, dan backup telah diuji.
- [ ] Pengiriman ke akun Gmail dan Outlook eksternal diterima di Inbox, bukan Spam/Junk, dengan bukti header autentikasi yang dicatat.
- [ ] UAT staf/manajer serta screenshot tiga breakpoint terdokumentasi.
- [ ] Tidak ada password, API key, atau konfigurasi sensitif dalam repository.

---

## 9. Dampak pada PRD Induk

| PRD induk | Dampak addendum |
|---|---|
| Section 12 — Arsitektur | Menambahkan client webmail, subdomain, dan batas Laravel/Mailpit. |
| Section 13 — Environment | Menambahkan local lab dan staging mail-stack yang setara. |
| Section 14 — QA | Menambahkan smoke test, UAT, security, restore, dan deliverability eksternal. |
| Section 15 — Backup | Menambahkan retensi, pemilik, dan bukti restore mailbox. |
| Section 19 — Roadmap | Menambahkan W0–W4 beserta gerbang Go/No-Go dan rollback. |
| Section 20 — Definition of Done | Menambahkan kriteria webmail, akses, keamanan, dan Inbox Gmail/Outlook. |

Addendum ini adalah Change Request terpisah. Implementasi webmail tidak dimulai sampai dokumen ini disetujui dan keputusan pada Section 4.2 telah dijawab.

> Nomor section pada tabel ini merujuk ke `PRD_Refi_Spice_MASTER_v2.md` v2.0.0. Saat dokumen induk berubah versi, pemetaan ini wajib diverifikasi ulang pada review Change Request berikutnya.
