# W0 Decision Record — CR-WEBMAIL-001

| | |
|---|---|
| **Tanggal pemeriksaan** | 16 September 2026 |
| **Status** | Keputusan bisnis W0 diterima. W1 local PoC lulus dalam scope smoke test; dua perilaku alias tetap wajib diuji ulang pada HestiaCP staging sebelum go-live. |
| **Ruang perubahan** | Audit, keputusan dokumen, dan local PoC disposable. Tidak ada VPS, DNS/MX, HestiaCP, atau konfigurasi Laravel produksi yang diubah. |
| **Dokumen acuan** | `PRD_Refi_Spice_Webmail_Addendum_v0.1.md` v0.1.2 dan `WEBMAIL_IMPLEMENTATION_PLAN.md` v0.1.0 |

---

## 1. Baseline yang Terverifikasi

| Area | Hasil | Dampak |
|---|---|---|
| Aplikasi lokal | PHP 8.5.9 dan Composer 2.10.2 tersedia. | Memenuhi versi PHP yang didukung Roundcube 1.7. |
| QA email aplikasi | `.env.example` mengarahkan lokal ke Mailpit (`127.0.0.1`) dan `QUEUE_CONNECTION=sync`; production didokumentasikan menggunakan SMTP TLS dan queue database. | Mailpit tetap hanya untuk QA Laravel; bukan lab mailbox webmail. |
| Lab W1 | Docker Engine 29.7.2 dan Docker Compose v5.5.0 tersedia; service Docker aktif tetapi tidak diaktifkan otomatis. Local PoC memakai container disposable `docker-mailserver` 16.0.1 dan Roundcube 1.7.4. | W1 selesai; container, volume dummy, dan jaringan lab sudah dibersihkan setelah bukti dicatat. |
| Infrastruktur HestiaCP | Tidak ada VPS/HestiaCP yang ditempatkan dalam scope/akses workspace ini. | Tidak ada klaim kompatibilitas dari host produksi; konfigurasi nyata baru dibuktikan pada W2 staging. |

## 2. Evaluasi Client Webmail

Penilaian memakai ambang Pass/Fail pada Section 2.1.1 addendum. Bukti lengkap berada pada sumber resmi yang ditautkan dalam tabel.

| Kriteria | Roundcube 1.7.4 | SnappyMail 2.38.2 | Keputusan |
|---|---|---|---|
| Maintenance | **Pass** — rilis terbaru 6 September 2026. | **Fail** — rilis terbaru 9 Oktober 2024, melampaui ambang 12 bulan. | SnappyMail tidak lanjut. |
| Security | **Pass saat diperiksa** — rilis 1.7.4 adalah security update; halaman advisory repository tidak menampilkan advisory terpublikasi yang masih terbuka. | Tidak dinilai lanjut setelah gagal maintenance; repository mencatat advisory moderate pada 2024. | Versi target wajib dicek ulang tepat sebelum instalasi. |
| HestiaCP/PHP | **Pass** — Hestia menyediakan instalasi/upgrade Roundcube 1.7, menyatakan kebutuhan PHP ≥8.1, dan Hestia mendukung PHP 8.5. Roundcube 1.7 mendukung PHP 8.1–8.5. | Hestia mendukungnya sebagai opsi, tetapi tidak mengatasi kegagalan maintenance. | Target PoC: Roundcube 1.7.4 dengan PHP 8.5. |
| TLS dan kredensial | **Pass** — konfigurasi resmi menyediakan IMAP/SMTP TLS serta penggunaan username/password mailbox pengguna untuk SMTP. | Tidak dilanjutkan. | W1 membuktikan konfigurasi aktual. |
| Session, draft, dan UI | **Pass untuk PoC** — `session_lifetime` dapat dikonfigurasi; draft recovery local storage dan autosave tersedia. Batas sesi absolut 8 jam belum terbukti sebagai setting bawaan dan menjadi hard gate W1. | Tidak dilanjutkan. | W1 wajib menguji timeout tanpa kehilangan draft. |
| Operasional | **Pass bersyarat** — Hestia memiliki installer/updater Roundcube dan log rotation; versi harus dipin serta prosedur upgrade/rollback dicatat di staging. | Tidak dilanjutkan. | Tidak boleh mem-fork Roundcube atau membuat vhost Laravel. |

### Keputusan Teknis W0

1. **Pilih Roundcube 1.7.4 sebagai satu-satunya kandidat untuk W1 local PoC.**
2. **Tolak SnappyMail 2.38.2** untuk implementasi ini karena gagal ambang maintenance, terlepas dari UI-nya yang lebih modern.
3. Pin versi Roundcube pada `1.7.4` selama PoC. Sebelum W2/W4, administrator wajib menjalankan ulang pemeriksaan rilis/advisory dan menggunakan patch yang lebih baru bila tersedia.
4. W1 membuktikan konfigurasi session, TLS, login, dan alur kirim/terima singkat. Uji idle timeout 15 menit serta pemulihan draft setelah timeout adalah skenario UAT W3 pada staging sesuai scope lab yang dipersempit dan tidak boleh disimpulkan dari konfigurasi saja.

## 3. Keputusan Model Akun dan Akses yang Disetujui

| Keputusan | Nilai yang disetujui | Status implementasi |
|---|---|---|
| Domain produksi | `mail.domain.com` pada domain bisnis utama; HTTPS wajib. | Disetujui; DNS/sertifikat belum diubah. |
| Mailbox awal | Lima mailbox personal: `karyawan1`, `karyawan2`, `karyawan3`, `karyawan4`, dan `owner` pada domain bisnis. | Disetujui; belum diprovisikan. |
| Alamat fungsi | `sales@`, `export@`, dan alamat fungsi lain adalah alias, bukan mailbox/kuota tambahan. `sales@` dimiliki staf ekspor penanggung jawab utama; owner dapat ikut memantau bila mekanisme teknis lulus uji. | Disetujui sebagai model bisnis; lihat hard test alias di bawah. |
| Reset password/provisioning | Satu administrator infrastruktur mengelola HestiaCP; identitas administrator dicatat di register akses privat, bukan repository. Owner/karyawan mengajukan reset melalui jalur yang disepakati. | Disetujui. |
| Retensi mailbox | Tidak dibatasi selama mailbox aktif digunakan. | Disetujui. |
| Backup | Harian, rolling 30 hari, lokasi terpisah dari VPS produksi. Restore sekali sebelum go-live lalu setiap kuartal. | Disetujui; baru dikonfigurasi pada W2. |
| MFA dan kontrol pengganti | MFA HestiaCP wajib. MFA mailbox pengguna tidak wajib untuk lima pengguna ini; penggantinya password kuat, login rate-limit, serta notifikasi kepada administrator saat gagal login berulang. | Disetujui; konfigurasi aktual divalidasi W2. |

### Hard Test Alias Sebelum Staging

Model bisnis di atas tidak mengizinkan password bersama atau mailbox `sales@` terpisah. Sebelum W2, administrator harus membuktikan dua hal pada stack yang relevan:

1. `sales@` dapat menerima pesan untuk staf penanggung jawab utama **dan**, bila owner dipilih sebagai pemantau, dapat didistribusikan ke owner tanpa mengubahnya menjadi mailbox baru.
2. Staf penanggung jawab dapat membalas menggunakan identitas `sales@` melalui SMTP yang terautentikasi, dengan hasil yang tetap sesuai kontrol anti-spoofing server.

Dokumentasi Hestia menyatakan alias mengalihkan alamat ke akun utama, tetapi tidak sendirinya membuktikan distribusi multi-mailbox maupun izin outbound "send as". Jika salah satu hard test gagal, jangan membuat workaround password bersama; kembali ke pemilik proyek untuk memilih model alamat fungsi yang didukung.

## 4. Gate untuk Memulai W1

W1 dapat dimulai hanya bila seluruh kondisi ini terpenuhi:

- [x] Kandidat client lulus penilaian W0: Roundcube 1.7.4.
- [x] Domain produksi dan pemilik bisnis disetujui.
- [x] Daftar akun awal dan model `sales@` disetujui.
- [x] Administrator reset password/provisioning ditunjuk sebagai peran tunggal dan dicatat di register akses privat.
- [x] Kebijakan retensi/backup/MFA disetujui.
- [x] Runtime lab disposable telah tersedia secara lokal dan smoke test W1 tercatat di bawah.

## 5. Hasil W1 — Local PoC Disposable

Pengujian memakai domain fiktif `webmail.test`, akun dummy, dan sertifikat self-signed berumur tujuh hari. Semua port hanya terikat ke `127.0.0.1`; tidak ada DNS, mailbox, atau kredensial produksi yang digunakan.

| Area | Bukti hasil | Status |
|---|---|---|
| Web client | Roundcube Elastic merespons HTTP 200 pada localhost dan membentuk sesi login HTTP 302 untuk mailbox dummy. | Pass |
| IMAP/TLS | IMAPS pada port 993 menegosiasikan TLS 1.3 / AES-256-GCM; login IMAP dummy berhasil. Peringatan trust sertifikat diharapkan karena CA hanya untuk lab. | Pass untuk lab |
| SMTP/TLS | Submission pada port 587 menegosiasikan STARTTLS TLS 1.3 / AES-256-GCM; autentikasi SMTP dan enqueue berhasil. | Pass untuk lab |
| Alias masuk | Pesan ke `sales@webmail.test` diteruskan ke `karyawan1@webmail.test`; log LMTP mencatat `orig_to=<sales@webmail.test>` dan `status=sent`. | Pass pada docker-mailserver |
| Kirim sebagai alias | Staf pemilik alias dapat mengirim sebagai `sales@webmail.test` melalui SMTP terautentikasi; delivery ke `owner@webmail.test` tercatat `status=sent`. | Pass pada docker-mailserver; uji ulang HestiaCP wajib |
| Session/draft controls | Roundcube memuat `session_lifetime=15` menit, `draft_autosave=60` detik, `compose_save_localstorage=true`, `login_rate_limit=3`, dan SameSite `Lax`. | Konfigurasi terverifikasi; perilaku idle/draft menjadi UAT W3 |
| UI awal | Layar login Elastic jelas (logo, Username, Password, Login), tetapi belum merupakan evaluasi kesetaraan Gmail atau UAT alur kerja. | W3 diperlukan |

### Batas dan tindak lanjut

- Pengecualian verifikasi sertifikat IMAP/SMTP di `webmail-lab.roundcube.php` hanya untuk CA self-signed local PoC. Setting itu dilarang masuk ke staging/produksi.
- Local PoC bukan bukti bahwa HestiaCP mendukung distribusi alias ke dua mailbox atau "send as". W2 harus membuktikan alias pada stack HestiaCP yang sebenarnya dan tidak boleh memakai password bersama sebagai fallback.
- Tidak ada uji deliverability eksternal, restore backup, MFA panel, rate-limit operasional, maupun UAT lima pengguna pada W1; semuanya tetap berada pada W2–W4 sesuai implementasi plan.

## 6. Sumber Bukti Resmi

- HestiaCP: [Mail Domains](https://hestiacp.com/docs/user-guide/mail-domains), [installer Roundcube](https://github.com/hestiacp/hestiacp/blob/main/bin/v-add-sys-roundcube), dan [release notes](https://github.com/hestiacp/hestiacp/releases).
- Roundcube: [releases](https://github.com/roundcube/roundcubemail/releases), [PHP support](https://github.com/roundcube/roundcubemail/wiki/Version-History), [security advisories](https://github.com/roundcube/roundcubemail/security/advisories), dan [default configuration](https://github.com/roundcube/roundcubemail/blob/master/config/defaults.inc.php).
- SnappyMail: [releases](https://github.com/the-djmaze/snappymail/releases) dan [security advisories](https://github.com/the-djmaze/snappymail/security/advisories).
