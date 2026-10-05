# Rencana Implementasi Bertahap — Webmail Operasional Refi Spice

| | |
|---|---|
| **Versi** | 0.3.0 (W0/W1 tercatat; rencana UX shell Gmail-familiar ditambahkan) |
| **Tanggal** | 21 September 2026 |
| **Status** | W0 dan W1 local PoC tercatat. Track revisi UI/UX local lab telah diaudit ulang; implementasi shell Gmail-familiar belum dimulai dan tetap tidak mengizinkan perubahan VPS, DNS, atau produksi. |
| **Dasar** | `PRD_Refi_Spice_MASTER_v2.md` v2.0.0 dan `PRD_Refi_Spice_Webmail_Addendum_v0.1.md` v0.1.2 |
| **Change Request** | CR-WEBMAIL-001 |

---

## 1. Tujuan dan Prinsip Eksekusi

Rencana ini menerjemahkan addendum webmail menjadi langkah yang dapat diuji tanpa langsung menyentuh layanan produksi. Hasil yang dituju adalah `https://mail.domain.com` yang aman, mudah dipakai staf/manajer, dan terpisah dari Laravel serta Mailpit.

Prinsip pelaksanaan:

- Hanya satu fase aktif pada satu waktu. Fase berikutnya tidak dimulai sebelum exit gate fase sebelumnya dibuktikan.
- Mailpit tetap khusus lokal/staging untuk QA email Laravel; ia bukan mail server dan bukan kandidat webmail produksi.
- Tidak ada password bersama, password mailbox di Laravel, atau kredensial produksi dalam repository/dokumen bukti.
- Keputusan arsitektur, DNS, dan akses diputuskan tertulis sebelum perubahan yang sulit dibalik.
- Bila exit gate gagal, berhenti di fase tersebut, catat temuan, lalu pilih perbaikan atau keputusan baru. Tidak ada bypass informal.

## 2. Peran dan Bukti Minimal

| Peran | Tanggung jawab | Bukti yang dihasilkan |
|---|---|---|
| Pemilik proyek | Menyetujui scope, biaya, kebijakan akun/retensi, dan go-live. | Persetujuan W0 dan W3/W4 tertulis. |
| Manajer operasional | Menetapkan pemilik mailbox, PIC alamat fungsi, serta ikut UAT. | Daftar akun, hasil tiga skenario UAT. |
| Administrator infrastruktur | Menilai client, mengelola HestiaCP/DNS/mailbox/backup, dan memimpin rollback. | Checklist Go/No-Go, bukti TLS/DNS/restore/log. |
| Developer aplikasi | Menjaga SMTP Laravel, queue, dan Mailpit lokal/staging; membantu uji inquiry end-to-end. | Bukti inquiry end-to-end tanpa secret. |

Simpan bukti pada lokasi proyek yang disetujui dan akses-terbatas (misalnya folder dokumentasi internal), bukan di Git bila mengandung header email, alamat buyer, IP, atau konfigurasi sensitif. Gunakan akun dummy pada W1/W2.

## 3. Urutan dan Gerbang Keputusan

```text
W0 Keputusan & Go/No-Go
  └─lulus→ W1 Local PoC
               └─lulus→ W2 Staging HestiaCP
                            └─lulus→ W3 UAT & kesiapan operasi
                                         └─disetujui→ W4 Go-Live terkendali
                                                              └─2–4 minggu→ penutupan
```

## 4. Fase W0 — Keputusan dan Go/No-Go

**Tujuan:** mengunci keputusan bisnis/akses dan memilih kandidat webmail yang layak diuji.

**Perubahan yang diizinkan:** riset dan dokumen keputusan saja. Tidak ada provisioning VPS, perubahan DNS, atau perubahan `.env` produksi.

| Langkah | Penanggung jawab | Output/bukti |
|---|---|---|
| Isi Section 4.2 addendum dengan domain, pemilik akun, model `sales@`, pemilik reset password, retensi, attachment, dan backup. | Pemilik proyek, manajer, administrator | Tabel keputusan lengkap dengan nama dan persetujuan. |
| Nilai Roundcube dan SnappyMail memakai seluruh ambang Pass/Fail Section 2.1.1 addendum. | Administrator | Matriks kandidat: URL bukti, versi, tanggal cek, setiap kriteria Pass/Fail, dan keputusan. |
| Tetapkan client terpilih, versi yang dipin, sumber paket, owner patch, dan frekuensi review update. | Administrator + pemilik proyek | Keputusan Go/No-Go dan ringkasan patch/rollback. |
| Tetapkan parameter awal: idle 15 menit, sesi maksimum 8 jam, log 90 hari, serta posisi MFA. | Administrator + manajer | Catatan konfigurasi awal dan kontrol pengganti bila MFA mailbox belum tersedia. |
| Tetapkan rencana komunikasi go-live dan PIC insiden. | Pemilik proyek + manajer | Kontak PIC, jendela perubahan, dan jalur eskalasi. |

**Exit gate W0:**

- Semua keputusan Section 4.2 memiliki pemilik dan penyetuju aktual.
- Satu client lulus semua ambang Section 2.1.1; jika tidak ada, rencana berhenti dan dibuka keputusan arsitektur baru.
- Model W4 untuk alamat fungsi hanya alias-ke-personal atau forwarding ke satu PIC. Shared mailbox/delegasi belum diimplementasikan.
- Pemilik password reset, patch, backup, dan rollback sudah ditetapkan.

## 5. Fase W1 — Local Proof of Concept

**Tujuan:** membuktikan client terpilih dapat menjalankan alur IMAP/SMTP dan tidak menimbulkan friksi teknis mendasar.

**Precondition:** W0 lulus. Lab bersifat disposable, memakai domain dan mailbox dummy; tidak menerima email Internet atau memakai kredensial produksi.

| Langkah | Penanggung jawab | Output/bukti |
|---|---|---|
| Siapkan mail server uji ber-IMAP/SMTP dan client webmail versi W0 pada Docker Compose atau VM terisolasi. | Administrator | Inventaris versi, arsitektur lab, dan cara memusnahkan lab. |
| Siapkan `sales`, `export`, `manager`, dan `buyer-test` sebagai akun dummy. | Administrator | Daftar akun dummy tanpa password. |
| Jalankan login, kirim/terima, reply, forward, pencarian, attachment, draft, delete/restore, dan logout. | Administrator + developer | Checklist hasil smoke test dan error yang ditemukan. |
| Uji TLS, autentikasi langsung, idle timeout, sesi maksimum, dan draft setelah timeout. | Administrator | Bukti bahwa draft tetap dapat dibuka setelah login ulang; bila gagal, opsi konfigurasi/client yang diperbaiki. |
| Jalankan QA Laravel terpisah lewat Mailpit. | Developer | Bukti Mailpit hanya menerima email uji aplikasi. |

**Exit gate W1:** semua smoke test inti lulus, draft tidak hilang saat timeout, TLS/authentication sesuai rencana, dan tidak ada kredensial mailbox di Laravel. Bila client tidak dapat memenuhi konfigurasi sesi atau stabilitas dasar, kembali ke W0 untuk memilih kandidat lain.

## 6. Fase W2 — Staging Setara HestiaCP

**Tujuan:** memverifikasi konfigurasi nyata yang mendekati produksi tanpa trafik bisnis.

**Precondition:** W1 lulus, staging VPS tersedia, dan tidak memakai data buyer/akun produksi.

| Langkah | Penanggung jawab | Output/bukti |
|---|---|---|
| Patch HestiaCP dan mail stack, lalu catat versi sebelum mengaktifkan mail domain. | Administrator | Inventaris versi dan status patch. |
| Provision mail domain staging, HTTPS, client webmail terpilih, mailbox dummy, serta IMAP/SMTP TLS. | Administrator | Screenshot/hasil pemeriksaan HTTPS dan login dummy. |
| Konfigurasikan SPF, DKIM, DMARC, PTR, anti-brute-force/rate limit, dan logging. | Administrator | Rekaman DNS, hasil autentikasi, lokasi log, dan retensi 90 hari. |
| Konfigurasikan backup mailbox staging dan lakukan restore satu mailbox dummy. | Administrator | Bukti backup dan restore berhasil. |
| Hubungkan aplikasi staging ke SMTP yang disetujui, gunakan queue `database` dan worker terkelola. | Developer + administrator | Inquiry dummy diterima `sales@` dan reply keluar memakai alamat bisnis staging. |
| Jalankan tes eksternal ke Gmail dan Outlook. | Administrator + developer | Bukti pesan berada di Inbox, bukan Spam/Junk; simpan hasil autentikasi/header secara aman. |

**Exit gate W2:** HTTPS, login, IMAP/SMTP TLS, inquiry end-to-end, backup/restore, logging, serta pengiriman Gmail/Outlook lulus. Masalah deliverability, TLS, atau routing adalah blocker W3.

## 7. Fase W3 — UAT dan Kesiapan Operasi

**Tujuan:** memastikan pengalaman benar-benar ramah staf/manajer dan operasi siap merespons insiden.

**Precondition:** W2 lulus.

| Langkah | Penanggung jawab | Output/bukti |
|---|---|---|
| Jalankan tiga skenario UAT dari Section 6.3 addendum pada desktop dan ponsel. | Manajer + perwakilan staf | Checklist tugas, durasi/friksi, dan persetujuan/temuan. |
| Ambil screenshot desktop 1440 px, tablet 768 px, ponsel 375 px; terapkan branding ringan/copy/signature yang diperlukan. | Developer/administrator sesuai client | Bukti tampilan tanpa data sensitif. |
| Uji password reset oleh administrator, akses manager sesuai izin, dan prosedur review log. | Administrator + manajer | Bukti akses minimum dan catatan audit. |
| Latih pengguna tentang login, reset password, attachment, phishing dasar, dan pelaporan masalah. | Manajer + administrator | Materi singkat dan daftar peserta. |
| Simulasikan rollback tanpa mengganti DNS produksi: langkah, komunikasi, dan verifikasi penerimaan email. | Administrator | Catatan drill rollback dan temuan. |

**Exit gate W3:** minimal satu staf dan satu manajer menyelesaikan tugas tanpa kebingungan besar, seluruh blocker keamanan/UX ditutup, backup/restore serta rollback drill lolos, dan pemilik proyek memberi persetujuan tertulis untuk jendela go-live.

## 8. Fase W4 — Go-Live Terkendali dan Monitoring

**Tujuan:** mengaktifkan produksi dengan jejak bukti dan kemampuan rollback yang jelas.

**Precondition:** W3 lulus; backup diverifikasi; pemilik proyek menyetujui jendela perubahan; TTL DNS diturunkan bila ada provider sebelumnya.

| Urutan | Penanggung jawab | Verifikasi sebelum lanjut |
|---|---|---|
| 1. Backup konfigurasi dan catat baseline DNS/mailbox. | Administrator | Backup dapat dibaca; daftar perubahan disetujui. |
| 2. Provision mailbox produksi dan alias/forwarding sesuai W0. | Administrator | Pemilik masing-masing akun memverifikasi aksesnya. |
| 3. Aktifkan HTTPS, webmail, IMAP/SMTP TLS, log, backup, anti-brute-force, dan DNS mail. | Administrator | TLS/DNS/authentication checker lulus. |
| 4. Arahkan Laravel produksi ke SMTP yang disetujui dan queue `database`; jalankan worker terkelola. | Developer + administrator | Inquiry dummy diterima dan status queue normal. |
| 5. Kirim/terima uji internal serta Gmail/Outlook; cek Inbox dan header autentikasi. | Administrator + manajer | Tidak ada mail loss, spam placement, atau routing salah. |
| 6. Informasikan URL, cara login, dan kanal bantuan kepada pengguna. | Manajer | Pengguna awal dapat login dan tahu PIC bantuan. |

**Monitoring 2–4 minggu:** tinjau error queue, mail log autentikasi, failed delivery/bounce, spam placement, kapasitas mailbox, dan hasil backup sesuai jadwal. Catat insiden, keputusan relay, dan tindakan patch dalam log operasi.

**Rollback:** gunakan pemicu dan prosedur Section 7.1 addendum. Jika provider sebelumnya tersedia, kembalikan MX setelah TTL diturunkan; jika tidak, tetap terima email dan nonaktifkan UI webmail yang bermasalah sambil memakai client alternatif yang sudah disetujui. Jangan menghapus mailbox saat rollback.

**Exit gate W4:** monitoring selesai tanpa blocker berulang, bukti backup tersedia, Gmail/Outlook konsisten menerima ke Inbox, dan pemilik proyek menyetujui penutupan CR-WEBMAIL-001.

## 9. Daftar Blocker yang Tidak Boleh Dilewati

- Tidak ada kandidat client yang lulus Section 2.1.1 addendum.
- Model `sales@`, pemilik reset password, atau retensi belum disetujui.
- HTTPS, IMAP/SMTP TLS, SPF, DKIM, DMARC, atau PTR belum valid.
- Draft hilang saat session timeout, atau akun dapat membaca mailbox lain tanpa otorisasi.
- Backup mailbox belum pernah diuji restore.
- Pengiriman ke Gmail/Outlook berakhir di Spam/Junk atau gagal diterima.
- Tidak ada jalur rollback atau PIC insiden saat jendela go-live.

## 10. Batas Implementasi Saat Ini

W0 dan W1 telah selesai dalam scope yang tercatat pada `WEBMAIL_W0_DECISION_RECORD.md`. Tahap infrastruktur berikutnya tetap **W2 staging setara HestiaCP** dan tidak boleh dilompati untuk menyimpulkan kesiapan produksi. Namun, revisi pengalaman pengguna dapat dikerjakan dan diuji pada local lab terlebih dahulu melalui Track UX di bawah ini.

Track UX tidak mengizinkan provisioning HestiaCP, perubahan DNS/MX, pemasangan pada VPS, perubahan Laravel produksi, penggunaan buyer nyata, atau pelonggaran TLS self-signed yang hanya ada pada lab.

## 11. Track UX — Revisi Refi Mail pada Local Lab

**Tujuan:** mengubah local Roundcube dari branding CSS awal menjadi pengalaman webmail yang akrab, ringkas, dan mudah dipakai staf/manajer. Referensi interaksi adalah pola Gmail yang familiar, tanpa menyalin merek, logo, atau fitur akun Google.

### 11.1 Keputusan desain yang dikunci

| Area | Keputusan | Alasan |
|---|---|---|
| Tema awal | Light-first; dark mode tidak diaktifkan otomatis. | Menghilangkan konflik permukaan gelap/kartu putih dan memudahkan keterbacaan kantor. |
| Login | Satu halaman dengan alamat email dan password sekaligus, lalu tombol `Masuk`. | Sesuai alur staf; tidak ada login bertahap. |
| Pemulihan password | Tidak membuat tautan pemulihan mandiri yang belum didukung backend. Copy bantuan mengarahkan pengguna ke administrator. | Mencegah janji UX palsu dan mempertahankan model reset password W0. |
| Bahasa | Copy antarmuka Refi Mail memakai Bahasa Indonesia yang sederhana. | Mengurangi friksi pengguna non-teknis. |
| Navigasi | Satu sidebar: `Tulis`, `Kotak Masuk`, `Berbintang`, `Ditunda`, `Terkirim`, `Draf`, dan `Lainnya`. | Menyatukan task menu dan folder Roundcube yang kini terasa terpisah. |
| Inbox | Fokus pengirim, subjek, ringkasan, status belum dibaca, dan waktu. | Mempercepat triage inquiry buyer. |
| Kategori Gmail | Tidak membuat tab `Primary/Promotions/Social` pada rilis awal. | Struktur tersebut membutuhkan klasifikasi dan berpotensi membingungkan mailbox bisnis kecil. |
| Alias pengirim | `sales@` hanya muncul pada compose bila server mengizinkan `send as`; tidak pernah menjadi login atau password bersama. | Konsisten dengan keputusan akses W0. |

### 11.2 Fase UX-0 — Audit teknis skin dan peta elemen

**Tujuan:** memastikan perubahan UI tidak merusak autentikasi, IMAP/SMTP, maupun upgrade Roundcube.

| Langkah | Output | Exit gate |
|---|---|---|
| Petakan template, selector, plugin, dan breakpoint skin Elastic yang dipakai login, mailbox, baca pesan, dan compose. | Daftar elemen Roundcube dan titik override. | Tidak ada asumsi bahwa CSS saja dapat menyusun ulang navigasi. |
| Tentukan struktur skin `Refi Mail` yang dapat dipisahkan dari core Roundcube dan versi plugin yang dipin. | Keputusan teknis serta daftar file yang akan dibuat/diubah. | Tidak ada edit core/vendor Roundcube. |
| Inventaris perilaku yang harus tetap berfungsi: login/logout, folder, pencarian, baca, reply, draft, attachment, dan logout. | Checklist regresi local. | Semua perilaku memiliki cara uji sebelum desain diubah. |

### 11.3 Fase UX-1 — Login satu halaman

**Ruang kerja:** hanya login local Roundcube dan stylesheet/template skin Refi Mail.

| Implementasi | Kriteria selesai |
|---|---|
| Kartu login tunggal dengan logo Refi Mail, judul `Masuk ke email kerja`, email, password, bantuan administrator, dan tombol `Masuk`. | Kedua field terlihat tanpa langkah lanjutan, label dapat dibaca, fokus keyboard jelas, dan tombol tidak menutupi validasi server. |
| Terapkan light theme konsisten; hilangkan artefak ikon/input dan dark-mode override Elastic yang tidak selaras. | Tidak ada latar/komponen yang berubah ke gelap secara tidak konsisten. |
| Tampilkan error autentikasi Roundcube apa adanya tetapi dengan ruang dan kontras yang jelas. | Login gagal tidak menyamarkan error dan kredensial tidak pernah ditulis ke log UI/repository. |

**Exit gate UX-1:** pengguna dummy dapat login dan logout; validasi server tetap bekerja; tampilan diverifikasi pada lebar 1440 px, 768 px, dan 375 px.

### 11.4 Fase UX-2 — Shell mailbox dan daftar pesan

**Ruang kerja:** header, sidebar, toolbar daftar, serta tabel/list inbox tanpa mengubah protokol mail.

| Implementasi | Kriteria selesai |
|---|---|
| Header dengan menu responsif, brand Refi Mail, pencarian yang mudah ditemukan, dan avatar akun. | Pencarian Roundcube tetap dapat dijalankan dari keyboard dan status akun tidak ambigu. |
| Sidebar tunggal dengan tombol `Tulis` sebagai aksi utama dan jumlah unread bila tersedia. | Folder aktif terlihat jelas; navigasi keyboard/touch tetap dapat digunakan. |
| Daftar pesan padat dan mudah dipindai: checkbox, bintang, pengirim, subjek/ringkasan, lampiran bila ada, serta waktu. | Pesan unread/read dapat dibedakan tanpa hanya mengandalkan warna. |
| Toolbar disederhanakan: pilih, refresh, aksi pesan, pagination. | Aksi asli Roundcube tetap tersedia dan tidak ada tombol dekoratif tanpa perilaku. |

**Exit gate UX-2:** tiga email dummy W1 dapat ditemukan, dibuka, ditandai, dan dicari; inbox tetap usable di desktop, tablet, serta ponsel.

### 11.5 Fase UX-3 — Baca pesan dan compose operasional

**Ruang kerja:** halaman baca email, reply/forward, editor compose, draft, dan attachment.

| Implementasi | Kriteria selesai |
|---|---|
| Rapikan hirarki baca pesan: pengirim, penerima, waktu, subjek, isi, attachment, lalu reply. | Informasi buyer tidak tersembunyi oleh toolbar atau card dekoratif. |
| Buat compose fokus pada `Kepada`, `Subjek`, isi pesan, attachment, dan `Kirim`; simpan draft tetap memakai mekanisme Roundcube. | Draft masih muncul setelah reload sesuai kemampuan client, attachment dummy dapat dikirim, dan error pengiriman terlihat. |
| Tampilkan pilihan `Dari` secara kondisional untuk alias yang telah diizinkan server. | `sales@` tidak muncul sebagai opsi bila alias belum diverifikasi pada stack tersebut. |

**Exit gate UX-3:** kirim, reply, forward, draft, attachment, dan compose dari alias dummy lulus pada lab tanpa mengubah pembatasan keamanan W0.

### 11.6 Fase UX-4 — Responsif, aksesibilitas, dan polish

| Pemeriksaan | Kriteria selesai |
|---|---|
| Desktop 1440 px | Sidebar, search, toolbar, list, dan content tidak saling bertumpuk. |
| Tablet 768 px | Navigasi tetap dapat dibuka; pengirim dan subjek tidak hilang. |
| Ponsel 375 px | Sidebar berubah menjadi menu yang dapat dibuka; tombol sentuh cukup besar; compose dan baca pesan tidak melebar horizontal. |
| Keyboard dan fokus | Tab/Shift+Tab, Enter, Escape, dan focus indicator dapat dipakai pada login, navigasi utama, dan compose. |
| Kontras/copy | Status penting tidak hanya dibedakan warna; copy Bahasa Indonesia konsisten dan tidak teknis. |

**Exit gate UX-4:** tidak ada blocker usability pada tiga ukuran layar dan seluruh aksi email utama tetap dapat dilakukan tanpa mouse.

### 11.7 Fase UX-5 — Uji pengguna local dan keputusan ke staging

**Peserta:** pemilik proyek dan minimal satu calon pengguna, menggunakan akun dummy dan email dummy saja.

| Skenario | Bukti lulus |
|---|---|
| Login, menemukan inquiry, membaca ringkasan, lalu membalas. | Pengguna menyelesaikan tanpa arahan teknis berulang. |
| Menulis email dari `sales@` bila tersedia, melampirkan katalog dummy, lalu menyimpan draft. | Pengguna memahami alamat pengirim dan menemukan draft kembali. |
| Mencari email lama dan logout. | Pengguna menemukan hasil dan tahu cara keluar dari akun. |

Catat hambatan, kalimat yang membingungkan, dan ukuran layar yang digunakan. Hanya masalah UX yang telah ditutup dan fungsi email yang tetap lulus yang boleh dibawa sebagai baseline ke W2 staging; uji local bukan pengganti W2/W3/W4.

### 11.8 Urutan eksekusi yang direkomendasikan

1. UX-0 audit skin dan checklist regresi.
2. UX-1 login, lalu uji manual dengan akun dummy oleh pemilik proyek.
3. UX-2 inbox/shell, lalu uji daftar tiga email dummy.
4. UX-3 baca/compose/alias, lalu smoke test kirim-terima.
5. UX-4 responsive dan aksesibilitas.
6. UX-5 uji pengguna local, catat temuan, lalu putuskan apakah desain siap dibawa ke staging W2.

### 11.9 Rencana koreksi keandalan pembaca dan struktur inbox

**Status:** rencana implementasi local lab. Belum ada pernyataan bahwa error `MutationObserver` atau pembacaan pesan sudah tuntas; local webmail tidak tersedia saat audit ini.

**Scope dan batas:**

- Hanya Roundcube `1.7.4` di local lab, skin `webmail-lab-skin/refi-mail`, plugin `webmail-lab-plugin/refi_branding`, dan konfigurasi local yang sudah ada.
- Tidak mengubah core/image Roundcube, IMAP/SMTP, akun dummy, TLS production/staging, DNS, atau Laravel.
- Semua label/sidebar harus memicu perilaku Roundcube yang benar. Jangan menambahkan folder, tombol, atau status Gmail yang hanya dekoratif.
- Referensi visual adalah pola kerja Gmail yang familiar, bukan penyalinan merek, logo, atau fitur Google.

#### P0 — Pembaca pesan sebagai state machine

**Masalah yang ditutup:** `refi-mail.js` saat ini menyimpulkan status pembaca dari class DOM `tr.message.selected`, event klik/keyboard, load iframe, dan tiga timer. CSS kemudian menyembunyikan iframe bila class tersebut tidak ada. Refresh, pindah folder, hapus pesan, seleksi ganda, atau respons yang terlambat dapat membuat daftar dan iframe tidak sinkron.

**Keputusan:** ganti mekanisme tersebut dengan satu state eksplisit pada shell mailbox, misalnya `data-refi-reader-state="empty|loading|ready|error"`, serta satu UID permintaan aktif. CSS hanya merender state ini; tidak boleh lagi menggunakan ada/tidaknya class baris terpilih sebagai sumber kebenaran.

| Langkah | File target | Detail implementasi | Kriteria lulus |
|---|---|---|---|
| Ikat lifecycle Roundcube | `webmail-lab-plugin/skins/elastic/refi-mail.js` | Setelah event `rcmail` `init`, daftarkan listener native pada `rcmail.message_list` untuk `select`, serta listener `rcmail` untuk `listupdate`, `selectfolder`, dan respons daftar. Gunakan API event Roundcube, bukan polling DOM atau `MutationObserver`. | Satu handler aktif per halaman; refresh, pagination, search, folder switch, dan perubahan daftar selalu membatalkan UID pembaca yang lama. |
| Transisi seleksi | `refi-mail.js` | Tidak ada atau lebih dari satu pesan terpilih menjadi `empty`. Satu UID menjadi `loading` sebelum Roundcube menjadwalkan preview. Simpan UID tersebut agar respons lama tidak dapat menimpa seleksi baru. | Mouse dan keyboard menghasilkan state yang sama; seleksi cepat tidak membuka isi pesan sebelumnya. |
| Konfirmasi hasil iframe | `refi-mail.js` | Pada `messagecontframe` `load`, validasi bahwa frame yang dimuat benar-benar aksi preview dan UID-nya sama dengan UID aktif sebelum menjadi `ready`. Load dokumen kosong, error, atau UID usang tidak boleh menjadi sukses. Tangani kegagalan transport/halaman frame yang terdeteksi sebagai `error`, dengan aksi coba lagi yang menjalankan perilaku Roundcube yang sama. | Pembaca hanya menampilkan isi bila UID dan frame cocok; pesan error terlihat bila pemuatan gagal. |
| Reset atomik | `refi-mail.js`, `mail.html` bila diperlukan | Ketika daftar dibersihkan, folder berubah, pesan dipindahkan/dihapus, atau hasil pencarian berubah, reset UID, state, toolbar, dan placeholder dalam satu fungsi. Tombol kembali juga harus mengembalikan daftar tanpa meninggalkan iframe lama. | Tidak ada email lama tersisa setelah folder/hasil berubah; toolbar tidak aktif untuk pesan yang sudah hilang. |
| Render state | `refi-mail.css`, `templates/mail.html` | Pertahankan placeholder pada state `empty`; tampilkan indikator pemuatan yang dapat dibaca screen reader pada `loading`; tampilkan isi pada `ready`; tampilkan pesan gagal dan tombol coba lagi pada `error`. Hapus aturan yang menyembunyikan seluruh `.iframe-wrapper` dari class `.refi-message-selected`. | Tidak ada kondisi “email dipilih tetapi tidak terbaca” karena selector CSS yang keliru. |

**Catatan teknis:** Roundcube 1.7.4 sudah memiliki event list `select`, event aplikasi `listupdate`/`selectfolder`, dan lifecycle preview `msglist_select()` → `msglist_get_preview()` → `show_message()`. Integrasi harus mengikuti lifecycle itu; timer boleh dipakai hanya sebagai timeout kegagalan yang terikat pada UID permintaan, bukan sebagai mekanisme sinkronisasi.

**Exit gate P0:** uji manual dengan jaringan normal dan request yang diperlambat: pilih mouse, tombol panah, refresh, folder switch, search, pagination, delete, move, dan cancel/ubah seleksi cepat. Pada setiap akhir aksi, UID baris aktif dan isi frame harus identik, atau state harus `empty`/`error` secara eksplisit.

#### P1 — Satu sistem layout dan CSS berdasarkan DOM aktual

**Masalah yang ditutup:** `refi-mail.css` memiliki 1.615 baris dengan dua lapisan layout desktop yang saling menimpa, selector toolbar yang belum selalu cocok dengan DOM runtime, ukuran teks hingga `0.72rem`, `100vh`/minimum `36rem`, dan banyak `!important`.

**Keputusan:** tulis ulang stylesheet menjadi override tipis berlapis yang hanya menyasar markup yang disediakan skin atau DOM Elastic yang sudah dicatat. Jangan menambal aturan lama dengan lapisan ketiga.

| Urutan | File target | Detail implementasi | Kriteria lulus |
|---|---|---|---|
| Rekam kontrak DOM | `templates/mail.html`, DevTools local lab | Catat elemen dan class aktual untuk toolbar daftar, toolbar pembaca dari `includes/mail-menu.html`, search, pagination, quota, dialog, compose, addressbook, dan settings pada Roundcube 1.7.4. | Setiap selector baru memiliki elemen runtime yang dibuktikan; selector mati dihapus. |
| Konsolidasi token dan baseline | `refi-mail.css` | Satu deklarasi token warna, spacing, typography, dan breakpoint; satu blok login, shell mailbox, daftar, pembaca, compose, dialog, lalu responsive. Pakai selector skin seperti `.refi-list-toolbar` dan `.refi-message-toolbar` sebagai anchor. | Tidak ada deklarasi token atau shell desktop kedua; `!important` hanya dipertahankan bila diperlukan untuk override Elastic yang terbukti. |
| Terapkan ukuran aman | `refi-mail.css` | Gunakan layout flex/grid dengan `min-height: 0`, scroll per panel, dan tinggi viewport dinamis bila tersedia; jangan mengunci shell dengan `100vh` plus minimum tinggi desktop. Teks operasional minimum `0.875rem`/14 px, kecuali metadata sekunder yang tetap terbaca. | Kontrol tidak terpotong pada tinggi pendek, zoom 200%, landscape, atau keyboard ponsel. |
| Pulihkan kuota | `mail.html`, `refi-mail.css` | Footer sidebar tetap tampil saat quota ada. Ringkas sebagai indikator penggunaan; jangan sembunyikan seluruh footer. | Mailbox mendekati penuh tetap memberi informasi penggunaan ruang. |
| Verifikasi selector toolbar | `mail.html`, `refi-mail.css` | Cocokkan ukuran ikon/label dengan struktur nyata dari container `listcontrols`, `listmenulink`, dan menu mail Elastic, bukan asumsi `#mailtoolbar` generik. | Toolbar tidak overflow pada desktop/tablet; nama aksi tersedia untuk pembaca layar dan tooltip. |

**Exit gate P1:** inspeksi computed style dan screenshot shell pada 1440×900, 1280×720, 768×1024, 375×667, 200% zoom desktop, serta keyboard virtual ponsel. Tidak boleh ada overlap, clipping, scrollbar horizontal tidak perlu, aturan dead, atau label aksi yang hilang tanpa jalur alternatif.

#### P2 — Inbox list-first dan pembaca sebagai halaman utama

**Masalah yang ditutup:** pada lebar 1280 px, pembaca kosong menggunakan kira-kira 631 px sedangkan daftar hanya 447 px. Struktur awal masih memaksa tiga panel walaupun belum ada email yang dibaca.

**Keputusan:** desktop awal adalah sidebar ringkas + daftar pesan lebar. Panel pembaca tidak mengambil ruang ketika state `empty`. Ketika satu email `ready`, pembaca menjadi area utama dengan aksi kembali yang jelas; preview samping tidak menjadi alur default. Pada ponsel, gunakan perpindahan panel native Roundcube dan satu panel penuh per waktu.

| Langkah | File target | Detail implementasi | Kriteria lulus |
|---|---|---|---|
| Mode inbox kosong | `refi-mail.css`, `refi-mail.js` | State `empty` memberikan seluruh area utama kepada daftar setelah sidebar. Sembunyikan panel pembaca secara layout, bukan dengan meniadakan iframe atau menipu state Roundcube. | Daftar pesan desktop memiliki lebar mayoritas area kerja sebelum pengguna membuka email. |
| Mode pembaca | `refi-mail.css`, `refi-mail.js`, `mail.html` bila perlu | State `loading`/`ready` mengutamakan pembaca sebagai halaman utama. Aksi kembali mereset state dan mengembalikan fokus ke baris pemicu. Jika preview samping dipertahankan sebagai opsi kemudian, ia harus opt-in dan tidak mengubah default. | Klik/buka pesan menghasilkan hirarki pengirim, subjek, metadata, isi, attachment, dan reply yang tidak terasa sebagai panel kosong di samping daftar. |
| Daftar satu baris desktop | `refi-mail.css` | Gunakan urutan checkbox, bintang/status, pengirim, subjek + ringkasan, attachment, lalu waktu dalam satu baris desktop. Mobile boleh memakai dua baris. Unread dibedakan dengan bobot teks/indikator semantik, bukan warna saja. | Inbox dapat dipindai tanpa mengorbankan subjek atau waktu; tidak ada teks metadata 10 px. |
| Navigasi sesuai tugas | `templates/mail.html`, `refi-mail.css` | Urutan visual: `Tulis`, folder mailbox nyata dengan Kotak Masuk/Terkirim/Draf lebih dahulu, lalu folder lain; Kontak dan Pengaturan sebagai utilitas bawah. Jangan membuat Berbintang/Ditunda bila stack tidak menyediakan perintah/virtual mailbox yang berfungsi. | Folder inti muncul sebelum utilitas; setiap item membuka task/folder asli. |
| Akun dan logout | `templates/mail.html`, `refi-mail.css` | Pindahkan logout dari ikon header tanpa konteks ke menu akun yang memiliki nama akun, pengaturan, dan keluar. | Keluar tidak mudah tersentuh; pengguna tahu akun yang sedang aktif. |

**Exit gate P2:** dengan minimal tiga email dummy, pengguna dapat melihat inbox, mengenali unread, membuka satu email, kembali ke daftar, refresh, berpindah folder, dan mencari email tanpa area pembaca kosong/isi usang.

#### P3 — Compose yang fokus dan Bahasa Indonesia konsisten

**Keputusan:** compose menyajikan `Kepada`, `Subjek`, isi, lampiran, dan `Kirim` sebagai alur primer. `Dari` hanya ditampilkan bila server menawarkan identity yang sah; CC/BCC, receipt, format, priority, dan opsi teknis lain masuk ke menu “Opsi lainnya”.

| Langkah | File target | Detail implementasi | Kriteria lulus |
|---|---|---|---|
| Template compose khusus skin | `webmail-lab-skin/refi-mail/templates/compose.html` | Salin struktur compose Elastic 1.7.4 yang lengkap lalu ubah markup presentasi tanpa menghapus objek Roundcube, hidden fields, attachment form, atau command native. Ini diperlukan karena CSS saja tidak dapat melipat opsi teknis dengan aman. | Kirim, draft, reply, forward, attachment, recipient picker, dan error server tetap menggunakan mekanisme native. |
| Progressive disclosure | `compose.html`, `refi-mail.css` | Attachment mudah dijangkau dari alur primer; opsi tambahan dapat dibuka/tutup dan tetap keyboard-accessible. Sidebar opsi tidak memaksa ruang desktop atau melebar di mobile. | Pengguna dapat menyusun email sederhana tanpa membaca opsi teknis; opsi tetap tersedia saat diperlukan. |
| Lokalisasi skin | `meta.json`, `localization/id_ID.inc` pada skin | Aktifkan lokalisasi skin dan override label yang buruk/tidak konsisten, termasuk pencarian, `Dari`, `Detail`, `Header`, dan `more` menjadi `Lainnya`. Pertahankan label Roundcube bila terjemahan standar sudah jelas. | Tidak ada campuran Inggris/Indonesia pada alur inbox, pencarian, pembaca, dan compose. |
| Validasi draft | `webmail-lab.roundcube.php` hanya bila audit menemukan perubahan perlu | Pertahankan `draft_autosave = 60` dan local storage yang sudah ada; buktikan perilaku ketika reload, sesi habis, dan koneksi terputus sebelum mengubah konfigurasi. | Draft dummy dapat ditemukan kembali sesuai kemampuan Roundcube; gagal simpan terlihat dan tidak diklaim berhasil. |

**Exit gate P3:** kirim/terima, reply, forward, attachment dummy, autosave, reload, timeout, dan alias dummy yang memang tersedia lulus. Alias `sales@` tidak muncul bila belum diizinkan mail stack.

#### P4 — Validasi end-to-end dan bukti keputusan

| Area | Skenario wajib | Bukti lulus |
|---|---|---|
| Keandalan pembaca | Mouse, keyboard, seleksi cepat, refresh, pindah folder, search/reset, pagination, delete/move, dan respons yang diperlambat. | State reader, UID aktif, dan isi frame selalu cocok; tidak ada blank pane tak terjelaskan. |
| Mail operasi | Login/logout, folder, unread/star, pencarian, baca, reply, forward, send, attachment, draft, dan restore dari Trash bila didukung. | Semua command native masih bekerja dengan akun/email dummy. |
| Responsif/aksesibilitas | 1440 px, 1280×720, 768 px, 375 px, orientation change, 200% zoom, keyboard virtual, Tab/Shift+Tab/Enter/Escape. | Tidak ada clipping atau horizontal overflow; fokus jelas dan jalur tombol yang disembunyikan tetap tersedia. |
| Permukaan lain | Kontak, Pengaturan, attachment preview, popup/dialog, error login/kirim/load. | Tidak ada screen rusak atau copy yang membingungkan pada task yang tetap tersedia. |
| Console/log | Bersihkan log sebelum run, lalu periksa console dan log browser sesudah setiap grup skenario. | Tidak ada error JavaScript baru. Hilangnya `MutationObserver` pada satu run hanya dicatat sebagai sinyal; baru ditutup setelah matriks lengkap lulus. |

**Urutan eksekusi dan gate:**

1. Buat baseline DOM/screenshot serta checklist regresi dari local lab yang kembali dapat diakses.
2. Selesaikan P0 dan jalankan seluruh skenario pembaca sebelum menyentuh layout P2.
3. Ganti CSS secara bersih pada P1, verifikasi tiga viewport dan tinggi pendek.
4. Terapkan P2, lalu uji inbox/list-first dengan tiga email dummy.
5. Terapkan P3 tanpa menghapus command/objek Roundcube dan smoke test mail operasi.
6. Jalankan P4, catat bukti/temuan, bersihkan artefak QA yang tidak perlu, lalu putuskan kelayakan baseline local untuk W2.

**Blocker:** local webmail yang tidak dapat diakses harus dipulihkan sebelum gate P0/P4 dapat dieksekusi. Tidak boleh menyimpulkan bug reader atau MutationObserver selesai dari inspeksi kode ataupun satu console yang sempat bersih.

### 11.10 Rencana implementasi UI/UX berbasis audit browser

**Status:** rencana eksekusi local lab berdasarkan pemeriksaan browser terautentikasi. Audit membuktikan login, compose, inbox, reader desktop, dan reader mobile dapat dimuat dengan mailbox dummy. Ia juga membuktikan kerusakan ukuran input login, default tiga panel, copy campuran, dan toolbar mobile. Ia belum membuktikan semua race condition pembaca.

**Baseline yang harus dipertahankan:**

- Roundcube `1.7.4`, mailserver `16.0.1`, dan seluruh perubahan hanya melalui skin/plugin/configuration local yang sudah di-mount oleh Compose.
- Login, IMAP, SMTP, attachment, draft, logout, folder, dan command Roundcube tetap native. Tidak ada edit vendor/core atau pengganti request mail dengan JavaScript custom.
- Gunakan hanya akun dan pesan dummy. Hapus akun/pesan audit sebelum local lab dibawa ke baseline W2.
- Selesaikan gate satu per satu; P1 tidak dimulai sebelum P0 lulus, dan seterusnya.

#### P0 — Pulihkan akses login dan metadata runtime

**Masalah terbukti:** input login email/password dirender sekitar 26 px lebar pada desktop. Label visual disembunyikan dan accessibility tree tidak mendapatkan nama untuk kedua input. Log Roundcube juga berisi warning metadata skin tentang key `license` yang tidak ada.

| Implementasi | File target | Kriteria selesai |
|---|---|---|
| Perbaiki kontrak layout form login terhadap markup Elastic nyata (`table`, `.input.input-group`, prepend icon, dan `.form-control`). Jangan lagi mengandalkan selector table generik yang mengubah perilaku flex input. | `webmail-lab-plugin/skins/elastic/refi-mail.css` | Email dan password memakai seluruh lebar kartu setelah ikon, memiliki label/placeholder terbaca, tinggi target sentuh minimal 44 px, serta dapat diisi mouse dan keyboard biasa. |
| Pertahankan label `<label for>` yang terlihat atau berikan accessible name eksplisit bila label visual diganti. | `webmail-lab-plugin/refi_branding.php`, `webmail-lab-skin/refi-mail/templates/login.html` bila diperlukan | Browser accessibility tree memberi nama “Alamat email” dan “Kata sandi”; error autentikasi tidak tertutup tombol Masuk. |
| Lengkapi metadata skin yang dibaca Roundcube dengan lisensi yang disetujui pemilik proyek dan URL lisensinya bila diperlukan oleh versi ini. | `webmail-lab-skin/refi-mail/meta.json` | Warning `Undefined array key "license"` tidak muncul lagi pada fresh load tanpa mengubah lisensi dependency/vendor. |

**Gate P0:** login normal dan gagal pada 1440 px, 768 px, dan 375 px; Tab/Shift+Tab dan Enter; screenshot serta accessibility snapshot menunjukkan control yang dapat diakses.

#### P1 — Satu sumber kebenaran pembaca pesan

**Masalah terbukti:** happy path klik pesan memuat isi benar, tetapi `messagecontframe` tetap memiliki atribut source placeholder sementara dokumen iframe sudah berganti ke preview. Class `.refi-message-selected` ikut berubah setelah seleksi. Keduanya tidak aman sebagai sumber kebenaran pada refresh, delete, folder switch, atau respons terlambat.

| Implementasi | File target | Kriteria selesai |
|---|---|---|
| Implementasikan state `empty`, `loading`, `ready`, dan `error` pada shell mailbox beserta UID preview aktif. | `webmail-lab-plugin/skins/elastic/refi-mail.js` | Hanya satu state pembaca aktif; UID lama tidak boleh mengubah UI setelah pengguna memilih pesan lain. |
| Dengarkan event asli Roundcube: `init`, event list `select`, event aplikasi `listupdate`/`selectfolder`, serta load frame. Reset state melalui satu fungsi ketika daftar berubah. | `refi-mail.js` | Tidak ada polling `50/250/800 ms`, tidak ada `MutationObserver`, dan tidak ada state berbasis class `tr.selected` saja. |
| Validasi aksi preview dan UID dari dokumen frame sebelum transisi menjadi `ready`; tampilkan empty/loading/error secara eksplisit. | `refi-mail.js`, `webmail-lab-skin/refi-mail/templates/mail.html`, `refi-mail.css` | Placeholder tidak pernah menutupi pesan yang berhasil dimuat; pesan lama tidak muncul setelah change cepat atau tindakan daftar. |
| Hapus aturan CSS yang menyembunyikan seluruh iframe memakai `.refi-message-selected`; CSS hanya merender `data-refi-reader-state`. | `refi-mail.css` | Error state tetap memberi jalur coba lagi dan tidak pernah menghasilkan pane putih tanpa penjelasan. |

**Gate P1:** gunakan minimal tiga pesan dummy dan uji klik, tombol panah, seleksi cepat, refresh, search/reset, pindah folder, pagination, delete, move, serta respons diperlambat. Catat state, UID, dan isi frame pada akhir setiap skenario.

#### P2 — Bangun ulang shell inbox sebagai list-first

**Masalah terbukti:** desktop membagi layar menjadi sidebar, daftar pesan sempit, dan reader kosong besar. Kontak/Pengaturan berada sebelum folder; toolbar dan metadata daftar tidak cukup mudah dipindai.

| Implementasi | File target | Kriteria selesai |
|---|---|---|
| Definisikan satu layout desktop untuk state inbox kosong: sidebar ringkas lalu daftar sebagai area utama. Panel reader tidak mengambil ruang saat state `empty`. | `refi-mail.css` | Pada desktop 1280/1440 px, daftar mendapat mayoritas area kerja sebelum pesan dibuka. |
| Ketika reader `loading`/`ready`, tampilkan pembaca sebagai area utama dengan aksi kembali yang memulihkan fokus ke baris asal. Preview samping bukan default. | `refi-mail.js`, `templates/mail.html`, `refi-mail.css` | Membuka pesan terasa sebagai halaman baca, bukan panel kosong permanen; kembali tidak meninggalkan iframe/seleksi usang. |
| Susun ulang markup sidebar menjadi aksi utama `Tulis`, folder mailbox nyata, lalu utilitas Kontak/Pengaturan di bagian bawah. | `webmail-lab-skin/refi-mail/templates/mail.html`, `refi-mail.css` | Folder inti muncul sebelum utilitas; item yang tampil seluruhnya memicu task/folder Roundcube yang nyata. |
| Terapkan satu-baris desktop: checkbox, status/bintang, pengirim, subjek + snippet, attachment, waktu. Gunakan dua baris hanya pada mobile. | `refi-mail.css` | Unread terbaca melalui bobot/indikator tambahan, bukan warna saja; metadata operasional tidak kurang dari 14 px. |
| Konsolidasikan toolbar terhadap container aktual `refi-list-toolbar`, `listcontrols`, `listmenulink`, dan toolbar dari `mail-menu.html`. | `templates/mail.html`, `refi-mail.css` | Semua aksi utama tersedia tanpa overflow; label tersembunyi tetap punya accessible name dan tooltip. |
| Tampilkan quota sidebar saat server memberikan data quota. | `templates/mail.html`, `refi-mail.css` | Pengguna tidak kehilangan peringatan kapasitas mailbox. |

**Gate P2:** inbox berisi tiga pesan dummy; pengguna dapat refresh, mencari, memilih, membuka, kembali, menandai, dan berpindah folder tanpa panel kosong atau toolbar terpotong.

#### P3 — Compose operasional dan bahasa yang konsisten

**Masalah terbukti:** compose dapat mengirim email, tetapi panel kanan selalu menampilkan receipt, delivery status, formatting, priority, dan target sent. Ini menyembunyikan alur utama. Reader masih menampilkan `Details` dan `Headers`; menu aksi masih memakai `Berikutnya`.

| Implementasi | File target | Kriteria selesai |
|---|---|---|
| Override template compose Elastic 1.7.4 lengkap, bukan CSS-only patch, lalu tempatkan Kepada, Subjek, isi, lampiran, dan Kirim sebagai alur primer. | `webmail-lab-skin/refi-mail/templates/compose.html` | Semua object/command native—recipient picker, attachment form, draft, reply/forward, signature, error send—tetap ada. |
| Pindahkan CC/BCC, receipt, DSN, formatting, priority, dan lokasi penyimpanan ke disclosure keyboard-accessible “Opsi lainnya”. Tampilkan `Dari` hanya bila identity server memang tersedia. | `compose.html`, `refi-mail.css` | Compose sederhana tidak memaksa panel teknis; opsi lanjutan masih dapat diakses dan tidak memengaruhi mobile. |
| Aktifkan localization skin dan override copy yang terbukti buruk: pencarian, `Details`, `Headers`, `more`, serta tooltip aksi. | `webmail-lab-skin/refi-mail/meta.json`, `webmail-lab-skin/refi-mail/localization/id_ID.inc`, `templates/mail.html` bila label statis diperlukan | Tidak ada English yang tersisa pada login, inbox, reader, search, dan compose; `more` diterjemahkan sebagai “Lainnya”, bukan “Berikutnya”. |
| Ganti tombol logout ikon langsung dengan menu akun yang menyatukan identitas akun, Pengaturan, dan Keluar. | `templates/mail.html`, `refi-mail.css` | Keluar tidak mudah tersentuh; setiap icon-only control punya nama accessible yang bermakna. |

**Gate P3:** kirim internal dummy, reply, forward, lampiran, draft/reload, gagal kirim, serta compose alias yang diizinkan. Verifikasi label desktop dan mobile melalui accessibility snapshot, bukan screenshot saja.

#### P4 — Mobile, responsive, dan aksesibilitas

**Masalah terbukti:** reader mobile berhasil memuat tanpa overflow, tetapi inbox memperbesar ikon refresh secara tidak proporsional. Beberapa control icon-only terbaca sebagai glyph Font Awesome, bukan nama tindakan.

| Implementasi | File target | Kriteria selesai |
|---|---|---|
| Ganti breakpoint bertumpuk dengan tiga mode eksplisit: desktop ≥1024 px, tablet 769–1023 px, mobile ≤768 px. Gunakan `min-height: 0`, scroll per panel, dan viewport dinamis tanpa kombinasi `100vh` + minimum tinggi kaku. | `refi-mail.css` | Tidak ada clipping pada 1280×720, 768×1024, 375×667, landscape, zoom 200%, atau keyboard virtual. |
| Atur mobile inbox memakai satu panel penuh; berikan ukuran konsisten untuk toolbar dan FAB Tulis. Jangan menyembunyikan reset/filter tanpa jalur lain. | `refi-mail.css`, `templates/mail.html` bila diperlukan | Ikon refresh tidak membesar, search tetap lengkap, dan navigasi folder dapat dibuka/tutup dengan target sentuh minimal 44×44 px. |
| Tambahkan nama accessible, `aria-label`, title, dan indikator fokus untuk header, search, toolbar, akun, logout, compose FAB, dan reader actions. | `templates/mail.html`, `compose.html`, `refi_branding.php` bila hook lebih tepat | Accessibility tree memuat nama tindakan, bukan glyph; keyboard dapat memakai Tab/Shift+Tab/Enter/Escape tanpa focus trap. |

**Gate P4:** screenshot dan accessibility snapshot pada tiga viewport, plus keyboard-only login, inbox, buka/balik pesan, compose, buka/tutup opsi, serta logout.

#### P5 — Cleanup, regresi, dan keputusan baseline

1. Setelah P0–P4, hapus override CSS lama yang tergantikan; satu stylesheet tidak boleh memiliki dua implementasi shell yang hidup.
2. Jalankan smoke test local: login, send/receive, reply, forward, search, attachment, draft, folder, delete/restore, logout.
3. Bersihkan message dan mailbox audit `ux-audit`/`ux-sender`, atau dokumentasikan secara eksplisit bila tetap dibutuhkan untuk fixture local.
4. Ambil log Roundcube dan console browser dari run bersih. Absennya `browser.log` bukan bukti browser bersih; gunakan DevTools/automation console saat tersedia.
5. Catat screenshot bebas data sensitif pada 1440 px, 768 px, 375 px; hanya baseline yang lulus seluruh gate boleh dipakai untuk W2.

**Urutan implementasi:** P0 → P1 → P2 → P3 → P4 → P5. P0 dan P1 adalah blocker karena tanpa login yang usable dan reader yang deterministik, penilaian visual inbox tidak dapat dipercaya.

### 11.11 Rencana perbaikan shell Gmail-familiar berdasarkan audit visual kedua

**Status:** rencana implementasi, belum dikerjakan. Audit dilakukan pada local lab terautentikasi di viewport 1440×900 dan dibandingkan dengan screenshot pengguna. Tujuannya adalah membangun pola kerja yang familier bagi pengguna Gmail tanpa menyalin merek Google, menambahkan fitur palsu, atau mengganti command native Roundcube.

#### 11.11.1 Temuan audit yang menjadi dasar

| Area | Bukti saat ini | Dampak ke pengguna |
|---|---|---|
| Header global | Tinggi app bar 56 px. Search berada dekat brand, sedangkan sisi kanan mayoritas kosong. Nama akun dan tombol `Menu akun` tampil sebagai dua pill terpisah. | Hirarki header tidak seimbang; identitas akun memakan ruang tetapi tetap tidak terasa seperti avatar/menu akun yang familier. |
| Aksi Tulis | Link compose native berukuran sekitar 78×58 px dan label terlihat terpotong menjadi `Tulis sebua...` pada screenshot. | Aksi utama mailbox tidak segera terbaca dan kalah dominan dari folder. |
| Sidebar | Lebar 210 px, memiliki judul `Folder email` serta menu tiga titik yang tidak jelas kegunaannya bagi pengguna biasa. Kontak/Pengaturan terpisah jauh di bawah. | Sidebar terasa teknis, memiliki ruang kosong besar, dan urutan tugas utama tidak cukup jelas. |
| Toolbar inbox | `Pilih`, `Untaian`, `Pilihan`, dan refresh dirender sebagai campuran label serta ikon setinggi 58 px di kanan. | Aksi dasar sulit dipindai; `Untaian` dan `Pilihan` tampak sama penting dengan refresh walaupun jarang dipakai. |
| Baris pesan | Sel checkbox native memiliki lebar runtime 0 px. Pengirim, tanggal, ukuran, dan subjek berada dalam satu cell besar; flag berada di cell terpisah paling kanan. | Pola scan Gmail—checkbox, bintang, pengirim, subjek/snippet, waktu—tidak terbentuk. Seleksi pesan tidak terlihat. |
| Pagination | Kontrol halaman berada di footer paling bawah layar. | Pengguna harus memindahkan perhatian jauh dari toolbar dan daftar untuk mengetahui posisi halaman. |
| Toolbar pembaca | Back berukuran 35 px, sedangkan aksi Balas/Balas semua/Teruskan/Hapus/Tandai/Lainnya memakai kombinasi ikon dan label setinggi 58 px. | Terlalu banyak aksi memiliki bobot sama dan toolbar tampak bergeser ke tengah, bukan mengikuti alur baca dari kiri. |
| Isi pesan | Dokumen iframe memakai lebar hampir seluruh area 1228 px tanpa padding body dari shell. | Subjek, pengirim, metadata, dan isi pesan terlalu dekat tepi serta menghasilkan panjang baris yang melelahkan. |
| Ikon | Sebagian ikon berasal dari pseudo-element/font Elastic; beberapa tidak memiliki `aria-label`, hanya teks tersembunyi atau tooltip yang tidak konsisten. | Ikon tampak berbeda ukuran/gaya dan maknanya tidak selalu jelas bagi pengguna keyboard maupun pembaca layar. |

#### 11.11.2 Kontrak desain

1. **Familier, bukan tiruan.** Ikuti model mental Gmail—app bar, tombol Tulis dominan, folder di kiri, toolbar kontekstual, daftar pesan yang mudah dipindai, dan pembaca satu halaman—tanpa logo, nama fitur, warna merek, atau layanan Google.
2. **Command asli tetap menjadi sumber perilaku.** Tombol visual harus membungkus atau memicu command Roundcube yang sudah ada. Tidak ada ikon dekoratif, folder semu, snooze, kategori, archive, atau fitur lain bila backend tidak menyediakannya.
3. **Satu sistem ikon.** Gunakan satu set SVG lokal 20/24 px dengan stroke, viewbox, alignment, dan ketebalan konsisten. Tidak memakai emoji, glyph font campuran, CDN, atau icon font baru. Catat asal/lisensi setiap aset.
4. **Teks membantu ikon.** Aksi primer seperti `Tulis`, `Balas`, dan `Kirim` memakai label. Aksi icon-only hanya untuk pola yang umum—menu, kembali, refresh, hapus—dan wajib memiliki accessible name serta tooltip.
5. **Kepadatan kantor.** Tinggi baris desktop 44–48 px, target sentuh minimal 44×44 px, teks operasional minimal 14 px, dan body email 16 px. Ruang kosong harus membantu scan, bukan memisahkan kontrol yang berhubungan.
6. **Progressive disclosure.** Toolbar menampilkan aksi yang relevan dengan konteks. Opsi teknis dan jarang dipakai masuk ke `Lainnya`; menu tidak boleh menggandakan aksi primer.
7. **State tidak hanya warna.** Unread memakai bobot teks dan indikator semantik; selected memakai background serta indikator fokus; disabled tetap terbaca namun jelas tidak aktif.

#### 11.11.3 Target struktur desktop

```text
┌────────────────────────────────────────────────────────────────────────────┐
│ Refi Mail        [ Cari email................................ ]     (UA)   │
├─────────────────┬──────────────────────────────────────────────────────────┤
│ [ ＋ Tulis ]     │ [□▾] [↻] [⋮]                    1–50 dari 120 [‹] [›] │
│                 ├──────────────────────────────────────────────────────────┤
│ Kotak Masuk  12 │ □  ☆  Pengirim      Subjek — ringkasan          10.42 │
│ Konsep           │ □  ★  Pengirim      Subjek — ringkasan          Kemarin│
│ Terkirim         │ ...                                                      │
│ Sampah           │                                                          │
│ Surat Terhapus   │                                                          │
│                 │                                                          │
│ Kontak           │                                                          │
│ Pengaturan       │                                                          │
└─────────────────┴──────────────────────────────────────────────────────────┘
```

Ketika pesan dibuka, area daftar diganti pembaca:

```text
│ [←] [Arsip bila tersedia] [Hapus] [Tandai belum dibaca] [⋮]                │
├────────────────────────────────────────────────────────────────────────────┤
│ Subjek pesan                                                                │
│ (avatar) Pengirim <alamat>                                  waktu [⋮]       │
│          kepada saya                                                       │
│                                                                            │
│ Isi pesan dengan lebar baca maksimum 880–960 px                            │
│                                                                            │
│ [ Balas ] [ Teruskan ]                                                     │
```

Jika command arsip tidak tersedia pada mailbox/server, tombol tersebut tidak dibuat. Toolbar reader harus memakai command yang benar-benar ada.

#### 11.11.4 Fase R0 — Kontrak DOM, aset, dan baseline

| Implementasi | File target | Kriteria selesai |
|---|---|---|
| Petakan setiap kontrol runtime pada app bar, sidebar, list header, row, pagination, reader toolbar, menu, dan compose terhadap command Roundcube. | `templates/mail.html`, template/include Elastic 1.7.4 sebagai referensi baca, browser local | Matriks `kontrol → command → state aktif/disabled → label` lengkap; tidak ada tombol baru tanpa command. |
| Rekam baseline 1440×900, 1280×720, 768×1024, dan 375×812 untuk inbox kosong, selected row, reader, compose, menu akun, dan popup. | Bukti local lab | Screenshot menggunakan data dummy dan mencatat clipping, overflow, serta urutan fokus. |
| Tentukan sprite/partial SVG lokal untuk ikon yang diperlukan: menu, search, clear, compose, inbox, draft, sent, spam, trash, contacts, settings, select, refresh, more, back, reply, forward, delete, mark, previous, next, attachment, star. | Direktori aset skin Refi Mail yang sudah ada | Semua ikon satu gaya, dapat diwarna melalui CSS, tidak memerlukan jaringan, dan lisensinya jelas. |

**Gate R0:** seluruh ikon dan kontrol yang direncanakan memiliki command nyata, nama aksesibel, fallback teks, dan state disabled yang terdefinisi.

#### 11.11.5 Fase R1 — App bar dan sidebar yang familier

| Implementasi | File target | Kriteria selesai |
|---|---|---|
| Ubah app bar menjadi tiga area: brand/menu, search dominan, avatar akun. Hapus duplikasi pill nama akun + `Menu akun`; tampilkan email lengkap di popup akun. | `webmail-lab-skin/refi-mail/templates/mail.html`, `refi-mail.css` | Search berada pada pusat visual dengan lebar desktop 560–720 px; avatar membuka menu identitas, Pengaturan, dan Keluar. |
| Bangun tombol `Tulis` berbentuk pill dengan ikon plus/pensil dan label utuh. | `mail.html`, `refi-mail.css` | Label tidak terpotong pada desktop/tablet; target minimal 44 px; command compose native tetap dipakai. |
| Hilangkan judul teknis `Folder email` dari alur visual desktop dan pindahkan aksi pengelolaan folder ke menu sekunder yang diberi nama jelas. | `mail.html`, `refi-mail.css` | Pengguna langsung melihat folder setelah Tulis; aksi kelola folder tetap tersedia melalui menu aksesibel. |
| Samakan ikon dan alignment setiap folder; tampilkan unread count bila Roundcube menyediakannya. | `refi-mail.css`, aset SVG lokal | Baseline ikon, label, dan count sejajar; active folder memiliki bentuk pill dan tidak hanya mengandalkan warna. |
| Pertahankan Kontak dan Pengaturan sebagai utilitas bawah, tetapi hilangkan jarak kosong artifisial pada layar pendek. | `refi-mail.css` | Utilitas tidak menabrak quota/footer dan masih dapat dijangkau pada tinggi 720 px. |

**Gate R1:** pengguna dapat mengenali Tulis, Kotak Masuk, folder utama, akun aktif, Pengaturan, dan Keluar tanpa menebak ikon.

#### 11.11.6 Fase R2 — Toolbar inbox dan baris pesan

| Implementasi | File target | Kriteria selesai |
|---|---|---|
| Susun toolbar kiri sebagai checkbox pilih + dropdown, refresh, lalu `Lainnya`. Pindahkan `Untaian` dan pengaturan tampilan ke menu `Lainnya`. | `mail.html`, `refi-mail.css`; JavaScript hanya bila command native membutuhkan sinkronisasi presentasi | Tidak ada label/ikon bertumpuk; tiga aksi utama sejajar 40–44 px dan memiliki tooltip. |
| Pindahkan count dan pagination ke sisi kanan toolbar atas. Pertahankan include/navigation native Roundcube. | `mail.html`, `refi-mail.css` | Posisi halaman dapat diketahui tanpa melihat footer; Previous/Next disabled sesuai state native. |
| Pulihkan checkbox setiap row, lalu urutkan visual menjadi checkbox, flag/bintang, pengirim, subjek + snippet, attachment, waktu. | `mail.html` bila object mendukung kolom, `refi-mail.css`, `refi-mail.js` hanya untuk enhancement presentasi non-destruktif | Checkbox tidak lagi 0 px; setiap row dapat dipilih dengan mouse/keyboard dan double-click/open tetap native. |
| Terapkan truncation berdasarkan kolom: pengirim tetap terbaca, subjek mengambil ruang terbesar, waktu tidak membungkus. | `refi-mail.css` | Pada 1280/1440 px, pengirim, subjek, dan waktu terlihat dalam satu baris; mobile berubah menjadi dua baris. |
| Bedakan hover, focus, selected, read, dan unread secara konsisten. | `refi-mail.css` | Unread tetap dapat dikenali pada grayscale; focus ring terlihat; selected tidak menghapus readability. |
| Tambahkan empty state sederhana untuk mailbox kosong dan no-results state untuk pencarian. | `mail.html`, localization, CSS | Pesan menjelaskan kondisi dan memberi aksi relevan—misalnya `Tulis email` atau `Hapus pencarian`—tanpa ilustrasi dekoratif besar. |

**Gate R2:** dengan minimal 10 pesan dummy yang mencakup unread, flagged, attachment, subjek panjang, dan pengirim panjang, pengguna dapat scan, memilih satu/banyak pesan, refresh, mencari, membuka, dan berpindah halaman.

#### 11.11.7 Fase R3 — Pembaca pesan yang fokus

| Implementasi | File target | Kriteria selesai |
|---|---|---|
| Susun toolbar reader dari kiri: Kembali, Hapus, Tandai belum dibaca, lalu Lainnya. Letakkan Balas/Teruskan sebagai aksi teks di bawah isi dan/atau aksi primer yang konsisten, bukan semua command sekaligus di toolbar. | `mail.html`, include mail menu yang dioverride secara lengkap bila diperlukan, `refi-mail.css` | Aksi berbahaya tidak berdekatan tanpa jarak; menu `Lainnya` menampung print, source, move/copy, dan aksi jarang. |
| Beri toolbar action area kiri yang stabil; hapus spacer/header-title kosong yang mendorong tombol ke tengah. | `mail.html`, `refi-mail.css` | Tombol Kembali berada di awal toolbar dan seluruh aksi mengikuti alur kiri-ke-kanan. |
| Terapkan wrapper baca maksimum 880–960 px dengan padding 24–40 px, subject 22–24 px, avatar 40 px, metadata 14 px, body 16 px/1.5. | Skin/template preview atau CSS frame yang aman | Panjang baris nyaman; attachment dan konten HTML tidak terpotong; email lebar dapat scroll di dalam body, bukan seluruh shell. |
| Ringkas metadata awal menjadi pengirim, alamat, waktu, dan penerima. `Detail`/header lengkap menjadi disclosure sekunder. | Localization dan CSS preview | Informasi penting terlihat; header teknis tidak mendominasi isi pesan. |
| Pastikan state loading/error/ready lama tetap deterministik setelah markup toolbar berubah. | `refi-mail.js` | UID aktif dan isi frame selalu cocok; Back memulihkan daftar dan fokus row asal. |

**Gate R3:** pengguna dapat membuka, membaca, membalas, meneruskan, menghapus, menandai belum dibaca, membuka detail, dan kembali tanpa kehilangan konteks atau melihat toolbar berpindah.

#### 11.11.8 Fase R4 — Compose dan konsistensi seluruh permukaan

| Implementasi | File target | Kriteria selesai |
|---|---|---|
| Terapkan token dan ikon yang sama pada compose, popup, dialog, Kontak, dan Pengaturan yang masih memakai skin Refi Mail. | `refi-mail.css`, template skin yang relevan | Tidak ada perpindahan mendadak ke toolbar/icon style Elastic lama pada alur utama. |
| Pertahankan compose primer: Kepada, Subjek, isi, Lampiran, Kirim; opsi teknis tetap disclosure. | Template compose skin, `refi-mail.js`, `refi-mail.css` | Send, draft, recipient picker, attachment, reply, forward, dan error server tetap native. |
| Audit seluruh copy Indonesia dan tooltip. | `localization/id_ID.inc`, template skin | Tidak ada `From`, `Export...`, atau istilah Inggris lain pada alur pengguna biasa; istilah teknis hanya ada dalam detail lanjutan. |

**Gate R4:** smoke test login → inbox → reader → reply → compose → attachment → draft → send → logout lulus tanpa command hilang.

#### 11.11.9 Fase R5 — Responsif, aksesibilitas, dan UAT

| Mode | Perilaku target |
|---|---|
| Desktop ≥1024 px | Sidebar 224–240 px, search dominan, daftar satu baris, pagination di toolbar, pembaca dengan reading column terbatas. |
| Tablet 769–1023 px | Sidebar dapat diperkecil/overlay; search tetap dapat dibuka; row mempertahankan pengirim, subjek, waktu; aksi sekunder masuk menu. |
| Mobile ≤768 px | Satu panel penuh, header sticky, list dua baris, Tulis sebagai FAB 48–56 px, toolbar maksimum 3–4 aksi, folder sebagai drawer native. |
| Keyboard | Urutan Tab mengikuti visual; Space memilih checkbox; Enter membuka; Escape menutup popup/drawer; Back mengembalikan fokus. |
| Screen reader | Setiap ikon memiliki nama tindakan, selected/expanded/disabled diumumkan, heading mailbox dan subject terstruktur. |
| Zoom/kontras | Tidak ada clipping pada zoom 200%; target 44×44 px; teks/status tetap terbaca tanpa warna. |

UAT memakai minimal satu pengguna yang terbiasa Gmail tetapi belum mengenal Roundcube. Skenario tanpa instruksi:

1. Temukan satu email unread dan buka.
2. Balas pesan lalu kembali ke inbox.
3. Pilih dua pesan dan tandai sudah dibaca.
4. Cari email berdasarkan pengirim.
5. Tulis email dengan lampiran dan simpan sebagai draft.
6. Buka kembali draft, kirim, lalu logout.

**Kriteria UAT:** seluruh tugas selesai tanpa bantuan teknis, salah klik aksi destruktif, atau kebingungan ikon. Catat waktu tugas dan semua pertanyaan spontan; temuan blocker kembali ke fase terkait.

#### 11.11.10 Urutan dan prioritas implementasi

1. **R0 wajib lebih dahulu:** kontrak command dan sistem ikon mencegah tombol dekoratif atau command hilang.
2. **R1 shell:** app bar, avatar, Tulis, dan sidebar membentuk model mental dasar.
3. **R2 inbox:** toolbar, pagination, checkbox, dan row adalah area dengan frekuensi penggunaan tertinggi.
4. **R3 reader:** rapikan toolbar dan reading column tanpa merusak state machine yang sudah ada.
5. **R4 compose/konsistensi:** bawa sistem visual yang sama ke alur kirim dan permukaan pendukung.
6. **R5 responsive/UAT:** selesaikan breakpoint, aksesibilitas, dan uji pengguna sebelum baseline dibawa ke W2.

**Definition of done:** tidak cukup “mirip Gmail” secara screenshot. Selesai berarti setiap ikon memiliki command dan nama aksesibel, alur utama berhasil dengan keyboard dan touch, daftar dapat dipindai pada data yang realistis, operasi email native tetap lulus, serta pengguna uji menyelesaikan enam skenario tanpa bantuan.
