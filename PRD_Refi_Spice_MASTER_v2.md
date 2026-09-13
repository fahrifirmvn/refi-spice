# PRD MASTER — Website Company Profile Refi Spice
## PT Refi Rempah Nusantara | refi-spice.com

| | |
|---|---|
| **Versi Dokumen** | 2.0.0 |
| **Status** | Ready for Implementation |
| **Tanggal Revisi** | 13 September 2026 |
| **Menggantikan** | `PRD_Refi_Spice_Website.md` v1.0, `PRD_Refi_Spice_Infrastructure.md` v1.0 |
| **Pemilik Dokumen** | [Nama kamu/agency] |
| **Konsep Desain** | Heritage Craft (disetujui client — 7 frame mockup, lihat Lampiran A) |

### Riwayat Revisi

| Versi | Tanggal | Perubahan |
|---|---|---|
| 1.0.0 | — | PRD desain awal (3 konsep) |
| 1.1.0 | — | PRD desain final Heritage Craft (7 section detail) |
| 1.2.0 | — | PRD infrastruktur & mail server terpisah |
| **2.0.0** | 13 Sep 2026 | **Konsolidasi jadi satu dokumen master.** Penambahan: skema data produk konkret, admin panel scope, image pipeline, SEO/performance, security/anti-spam, testing plan, accessibility, backup/rollback, Definition of Done per section, environment variables lengkap. |

---

## DAFTAR ISI

1. Ringkasan Eksekutif
2. Tujuan & Non-Tujuan (Scope)
3. Design System
4. Spesifikasi Konten Per Section
5. Arsitektur Data (Database Schema)
6. Admin Panel (CMS Internal)
7. Image & Asset Pipeline
8. Interaksi & Animasi
9. Responsive & Accessibility
10. SEO & Performance
11. Keamanan & Anti-Spam
12. Arsitektur Mail Server
13. Environment Configuration
14. Testing & QA Plan
15. Backup, Deployment & Rollback
16. Struktur Kode & Konvensi
17. Prinsip Anti-"AI Slop"
18. Boundary — Hal yang Tidak Boleh Dilakukan Agent
19. Rencana Implementasi Bertahap (Roadmap)
20. Definition of Done (Acceptance Criteria)
21. Lampiran

---

## 1. RINGKASAN EKSEKUTIF

Website company profile single-page untuk Refi Spice, eksportir rempah-rempah single-origin Nusantara, ditujukan untuk buyer B2B internasional (importir, distributor, industri F&B/farmasi). Dibangun dengan Laravel + Blade, desain "Heritage Craft" (editorial heritage dengan elemen dokumen arsip/lab), 7 section utama dalam satu halaman scroll dengan navigasi anchor.

Tiga pilar teknis:
- **Frontend**: Laravel Blade + Tailwind/CSS custom + Alpine.js + animasi scroll subtle (AOS/GSAP)
- **Backend**: Data produk & inquiry berbasis database, dengan admin panel minimal untuk maintenance konten tanpa developer
- **Infrastruktur**: Local-first development, target akhir VPS dengan mail server self-hosted (HestiaCP), strategi mail dimulai tanpa relay dengan jalur migrasi manual ke relay jika diperlukan

---

## 2. TUJUAN & NON-TUJUAN (SCOPE)

### 2.1 Tujuan (In-Scope)
- Landing page 7 section sesuai mockup Heritage Craft yang disetujui
- Katalog produk database-driven, dapat dikelola lewat admin panel sederhana
- Form inquiry dengan validasi, penyimpanan DB, dan notifikasi email
- Mail architecture yang portable (direct-send → relay tanpa refactor)
- Responsive penuh (desktop/tablet/mobile), accessible, SEO-ready
- Siap deploy ke VPS dengan effort migrasi minimal

### 2.2 Non-Tujuan (Out-of-Scope untuk fase ini — dicatat eksplisit agar tidak "scope creep")
- Multi-bahasa (i18n) — saat ini Bahasa Indonesia saja; struktur konten sebaiknya tidak menutup kemungkinan ini di masa depan, tapi implementasi i18n TIDAK dikerjakan di fase ini
- E-commerce/checkout — ini company profile, bukan toko online; inquiry form adalah titik akhir konversi, bukan pembelian langsung
- Blog/artikel — tidak ada di 7 section yang disetujui; jika client minta nanti, ini perubahan scope yang perlu PRD addendum
- Login/portal khusus buyer — tidak ada member area di fase ini
- Aplikasi mobile native — web responsive saja
- Real-time chat/live support widget — tidak diminta di mockup

Jika ada permintaan baru dari client yang di luar daftar ini, catat sebagai **Change Request** terpisah, jangan diam-diam dikerjakan tanpa update dokumen ini.

---

## 3. DESIGN SYSTEM

*(Tidak berubah signifikan dari PRD sebelumnya, disalin & dirapikan di sini sebagai bagian dari master document — tetap merujuk ke 7 frame mockup sebagai sumber kebenaran visual pixel-level.)*

### 3.1 Palet Warna

```css
:root {
  --color-primary: #8C3B2E;           /* Merah bata — CTA, aksen, link aktif */
  --color-bg-cream: #F2E8D8;          /* Background utama */
  --color-bg-beige: #EDE0CB;          /* Background sekunder */
  --color-bg-dark: #2B1D14;           /* Dark section — Stats & Footer */
  --color-accent-gold: #C9A961;       /* Highlight di dark section */
  --color-text-ink: #2B1E16;          /* Teks utama di background terang */
  --color-text-muted: #6B5644;        /* Teks sekunder di background terang */
  --color-text-cream: #F2E8D8;        /* Teks di background dark */
  --color-text-cream-muted: #B8A990;  /* Teks sekunder di background dark */
  --color-border-hairline: rgba(43, 30, 22, 0.15);
  --color-success: #4A7A4A;           /* Form success state — BARU */
  --color-error: #B03A2E;             /* Form error state — BARU */
}
```

> **Catatan tambahan (gap yang diperbaiki):** PRD sebelumnya tidak mendefinisikan warna untuk state error/success form — developer akan menebak sendiri kalau tidak ditentukan. Ditambahkan di atas, harus dicek kontrasnya (lihat Section 9.2 Accessibility).

**Alternasi background per section (urutan wajib):**

| # | Section | Background |
|---|---|---|
| 1 | Hero | Cream |
| 2 | Cerita Kami | Beige |
| 3 | Koleksi Produk | Beige |
| 4 | Warisan & Kualitas | **Dark** |
| 5 | Rantai Pasok | Cream |
| 6 | Kemitraan | Cream/beige terang |
| 7 | Kontak | Cream (form) + **Dark** (footer bar) |

### 3.2 Tipografi

```css
--font-heading: "Fraunces", "Playfair Display", serif;
--font-body: "Inter", "Work Sans", sans-serif;
--font-label: "Inter", sans-serif; /* uppercase, letter-spacing 0.08–0.12em */
```

| Elemen | Ukuran Desktop | Ukuran Mobile | Weight | Line-height |
|---|---|---|---|---|
| H1 (hero) | 56–72px | 32–40px | 500–600 | 1.1 |
| H2 (section) | 40–48px | 28–32px | 500–600 | 1.15 |
| Body | 16–18px | 15–16px | 400 | 1.6–1.7 |
| Label/eyebrow | 11–12px | 10–11px | 500–600 | 1.4 |

> **Gap diperbaiki:** versi sebelumnya hanya kasih ukuran desktop. Ukuran mobile eksplisit ditambahkan agar tidak diserahkan ke tebakan developer (risiko heading kegedean di layar kecil, general problem di web dengan tipografi besar).

**Lisensi font — wajib dicek sebelum implementasi:** Fraunces & Inter tersedia gratis di Google Fonts (open source, aman dipakai komersial). Jika akhirnya pakai "Playfair Display" sebagai alternatif, itu juga Google Fonts — aman. Hindari font berbayar tanpa lisensi jelas.

### 3.3 Spacing, Grid, Border

- Container max-width: 1360px, padding horizontal 24px (mobile) – 96px (desktop)
- Section padding vertikal: 56–64px (mobile), 96–120px (desktop)
- Grid gap: 24–32px
- Border radius: 4–8px (TIDAK full-rounded)
- Border hairline: 1px solid `--color-border-hairline`

### 3.4 Elemen Dekoratif Khas (identitas brand — wajib dipertahankan)

- Archive/document framing pada foto (label "ARCHIVAL PLATE NO. XX/ID" + koordinat GPS)
- Lot/batch numbering pada card produk
- Garis ornamen horizontal dengan teks tengah
- Angka statistik besar serif + label kecil (bukan icon+number generik SaaS)
- Progress indicator navbar ("0X / 07")

---

## 4. SPESIFIKASI KONTEN PER SECTION

*(Ringkas — detail visual penuh ada di mockup. Fokus di sini pada data yang perlu terstruktur dan acceptance criteria.)*

### Section 1 — Hero (`#hero`)
- Konten: eyebrow text, H1, sub-paragraph, 2 CTA, 3 value-proposition mini, footer strip (koordinat + copyright + badge)
- **Data**: seluruh teks hero disimpan di tabel `page_contents` (key-value, lihat Section 5.4) — bukan hardcode Blade — supaya bisa diubah tanpa deploy ulang

### Section 2 — Cerita Kami (`#cerita-kami`)
- Konten: foto arsip + narasi + drop-cap + timeline milestone (tahun, judul, deskripsi)
- **Data**: tabel `milestones` (year, title, description, order)

### Section 3 — Koleksi Produk (`#koleksi-produk`)
- **Ini bagian paling kritis secara data** — lihat skema lengkap di Section 5.1

### Section 4 — Warisan & Kualitas (`#kualitas`)
- Konten: 4 stat (label, angka, deskripsi), badge sertifikasi
- **Data**: tabel `company_stats` (label, value, suffix, description, order), tabel `certifications` (name, logo_path, is_active)

### Section 5 — Rantai Pasok (`#rantai-pasok`)
- Konten: 4 tahapan proses (icon, nomor, judul, foto, deskripsi, data poin)
- **Data**: tabel `process_steps` (step_number, title, description, image_path, data_label, data_value, order)

### Section 6 — Kemitraan (`#kemitraan`)
- Konten: testimoni (quote, nama, jabatan, perusahaan, badge standar), grid partner logo
- **Data**: tabel `testimonials`, tabel `partners` (name, logo_path, is_active)

### Section 7 — Kontak (`#kontak`)
- Form inquiry + info kontak statis (alamat, koordinat, kontak langsung, jam operasional)
- **Data**: form → tabel `inquiries` (lihat Section 5.2); info statis boleh di `page_contents` atau config

---

## 5. ARSITEKTUR DATA (DATABASE SCHEMA)

Ini bagian yang paling kurang matang di draft sebelumnya. Berikut skema konkret yang harus diikuti (boleh disesuaikan tipe kolom saat migration, tapi struktur logisnya wajib).

### 5.1 Produk & Spesifikasi (Section 3 — paling kompleks)

```
products
├── id
├── slug                      (unique, untuk URL/anchor jika perlu)
├── name                      "Biji Pala & Fuli Banda"
├── latin_name                "Myristica fragrans"
├── lot_number                "LOT 01"
├── origin_region              "Banda Neira" 
├── origin_label               "ORIGIN: KEPULAUAN BANDA, MALUKU"
├── sensory_profile            (text) "Hangat, kayu manis manis, sedikit aroma pinus vulkanis."
├── export_readiness           "FCL / LCL"
├── primary_image_path
├── is_featured                (boolean — tampil di 3 kartu utama Section 3)
├── is_active
├── order
├── created_at / updated_at

product_specifications          <-- relasi 1-to-many ke products
├── id
├── product_id                 (FK)
├── spec_key                   "Minyak Atsiri" | "Kadar Air" | "Grade Fisik"
├── spec_value                 ">11.0%" | "<10.0%" | "ABCD & Sound Shriveled"
├── order

product_tags                    <-- untuk "Tersedia Juga: Kapulaga, Lada Putih, dst"
├── id
├── name
├── is_active
```

**Kenapa `product_specifications` dipisah, bukan kolom tetap (`kadar_air`, `minyak_atsiri`, dst) di tabel `products`:**
Setiap jenis rempah punya spesifikasi teknis yang beda (pala pakai "kadar minyak atsiri", kayu manis pakai "potongan stick", cengkeh pakai "headless ratio"). Kalau dipaksa jadi kolom tetap, tabel `products` akan punya puluhan kolom nullable yang membingungkan. Model key-value per produk jauh lebih fleksibel dan tetap gampang ditampilkan (loop di Blade).

### 5.2 Inquiry (Form Kontak)

```
inquiries
├── id
├── full_name
├── company_name
├── destination_country
├── commodity_interest        (FK ke commodities, atau string jika sederhana)
├── volume_estimate           (enum/string: "1-5 MT", "5-20 MT", "20+ MT", dst — konfirmasi ke client dulu)
├── additional_notes           (text, nullable)
├── status                     (enum: new, contacted, closed — BARU, untuk admin tracking)
├── email_sent_at              (timestamp, nullable)
├── email_failed_reason        (text, nullable)
├── ip_address                 (BARU — untuk anti-spam audit trail, lihat Section 11)
├── created_at / updated_at
```

> **Gap diperbaiki:** ditambahkan `status` (supaya admin bisa tandai inquiry mana yang sudah ditindaklanjuti — tanpa ini, tabel inquiries jadi kuburan data yang tidak actionable) dan `ip_address` (untuk keperluan anti-spam/audit, lihat Section 11).

### 5.3 Section Lain (ringkas, pola serupa)

```
milestones (id, year, title, description, order)
company_stats (id, label, value, suffix, description, order)
certifications (id, name, logo_path, is_active, order)
process_steps (id, step_number, title, description, image_path, data_label, data_value, order)
testimonials (id, quote, author_name, author_title, author_company, standard_badge, is_active)
partners (id, name, logo_path, is_active, order)
```

### 5.4 Konten Statis Fleksibel

```
page_contents
├── id
├── section_key      "hero", "cerita_kami", dst
├── field_key         "headline", "subheadline", "eyebrow_text"
├── field_value        (text)
```

Untuk teks yang sifatnya "hampir tidak pernah berubah tapi lebih aman kalau bisa diedit tanpa deploy" (headline hero, deskripsi section) — pola key-value sederhana ini menghindari perlu bikin tabel khusus untuk tiap section yang isinya cuma 1-2 field teks.

### 5.5 Prinsip Umum Skema

- Semua tabel yang tampil urut di halaman punya kolom `order` (integer) — supaya admin bisa reorder tanpa migrasi ulang
- Semua tabel konten (bukan transactional seperti `inquiries`) punya kolom `is_active` (boolean) — supaya bisa "sembunyikan sementara" tanpa hapus data
- Gunakan **soft delete** (`SoftDeletes` trait) untuk `products`, `testimonials`, `partners` — data B2B seperti ini sering perlu di-restore kalau admin salah hapus

---

## 6. ADMIN PANEL (CMS INTERNAL)

**Ini gap besar yang tidak ada sama sekali di draft sebelumnya**, padahal dijanjikan implisit ("mudah update tanpa sentuh kode").

### 6.1 Keputusan yang Perlu Dikonfirmasi ke Client/Tim
Sebelum development admin panel dimulai, perlu diputuskan:
- Siapa yang akan pakai (client sendiri, atau kamu sebagai maintainer)?
- Seberapa sering data produk/testimoni berubah? (kalau jarang — misal <1x/bulan — admin panel minimal sudah cukup; kalau sering, worth invest lebih)

### 6.2 Rekomendasi Scope Minimal (MVP Admin)
Gunakan package siap pakai untuk mempercepat, bukan bangun dari nol:
- **Filament PHP** (recommended — modern, cepat setup, cocok untuk kebutuhan CRUD sederhana seperti ini) ATAU
- **Laravel Nova** (berbayar, lebih mature) ATAU
- Custom minimal CRUD views jika tidak mau dependency tambahan

Scope CRUD yang dibutuhkan:
- Products + Product Specifications (nested form)
- Milestones, Company Stats, Certifications, Process Steps, Testimonials, Partners
- Inquiries (read + update status, TIDAK perlu create/delete manual — ini data masuk dari public form)
- Page Contents (edit headline/text per section)

### 6.3 Autentikasi Admin
- Gunakan Laravel breeze/fortify standar untuk login admin — **jangan buka registrasi publik**, akun admin dibuat manual via seeder/tinker
- Wajib ada rate-limiting di halaman login admin (bawaan Laravel sudah cukup, pastikan tidak dinonaktifkan)

---

## 7. IMAGE & ASSET PIPELINE

**Gap signifikan lain** — web ini padat foto (7 section × 2-4 foto = puluhan gambar), tanpa strategi jelas ini jadi web lambat begitu foto asli (resolusi tinggi dari client) masuk.

### 7.1 Strategi Upload & Storage
- Gunakan `spatie/laravel-medialibrary` untuk seluruh upload gambar (produk, proses, testimoni, dll) — bukan manual `Storage::put`
- Definisikan **conversion presets** sejak awal (bukan menyusul):
  - `thumbnail` — 400px width, untuk grid/preview
  - `medium` — 800px width, untuk card produk
  - `large` — 1600px width, untuk hero/full-width image
  - Semua auto-convert ke **WebP** dengan fallback JPEG untuk browser lama

### 7.2 Placeholder Strategy (Sebelum Foto Asli Client Masuk)
- Placeholder harus **secara visual jelas ditandai** sebagai belum final — bukan sekadar foto stock random yang "kelihatan pas" (risiko: developer lupa ganti karena "kelihatan sudah bagus")
- Rekomendasi teknis: overlay watermark text tipis "PLACEHOLDER — MENUNGGU ASET KLIEN" pada gambar placeholder di environment local/staging (bisa dinonaktifkan via config flag saat foto asli sudah masuk)
- Simpan daftar placeholder yang perlu diganti di file `PLACEHOLDER_CHECKLIST.md` terpisah, di-update tiap ada placeholder baru ditambahkan

### 7.3 Color Grading Konsistensi
- Saat foto asli masuk (kemungkinan diambil di lokasi/waktu berbeda-beda, konsistensi visual tidak terjamin), terapkan preset filter CSS/server-side (warm tone, sedikit desaturate) sesuai PRD desain Section 3 — didefinisikan sebagai satu CSS class/mixin (`.photo-heritage-grade`) yang diterapkan konsisten ke semua elemen `<img>` foto konten (bukan ke UI icon/dekorasi)

### 7.4 Lazy Loading & Performance
- Semua gambar non-hero pakai `loading="lazy"` native attribute
- Hero image: preload via `<link rel="preload">` karena above-the-fold
- Ukuran file target: thumbnail <50KB, medium <150KB, large/hero <300KB (WebP)

---

## 8. INTERAKSI & ANIMASI

*(Konten teknis sama seperti draft sebelumnya, dengan penambahan detail implementasi.)*

### 8.1 Prinsip
Animasi terasa seperti "membuka dokumen arsip perlahan" — bukan landing page SaaS modern yang serba bouncy.

### 8.2 Spesifikasi

| Elemen | Efek | Durasi | Easing |
|---|---|---|---|
| Scroll reveal per elemen | fade + translateY(12–20px) | 600–800ms | `cubic-bezier(0.16, 1, 0.3, 1)` |
| Stagger antar item grid | delay bertahap | 80–120ms/item | sama seperti di atas |
| Navbar transparent→solid | background + border transition | 300ms | ease |
| Hover tombol | color/border transition | 200ms | ease, max scale 1.02 |
| Hover card produk | border color + image scale(1.03) | 400ms | ease |
| Underline link nav | slide-in dari kiri | 200ms | ease |
| Count-up statistik | angka naik | 1.5–2s | ease-out |

### 8.3 Yang Harus Dihindari
- Custom cursor magnetic/blob
- Splash screen loading dengan logo berputar
- Scroll-jacking / full-page-snap
- Typewriter text effect

### 8.4 Implementasi Teknis (BARU — detail yang hilang di draft sebelumnya)
- Gunakan **AOS.js** untuk scroll reveal sederhana (cukup untuk 90% kebutuhan), dengan config custom (bukan default):
  ```js
  AOS.init({
    duration: 700,
    easing: 'ease-out-cubic',
    once: true,        // animasi hanya sekali, tidak berulang saat scroll naik-turun
    offset: 80
  });
  ```
- Gunakan **GSAP** khusus untuk count-up statistik (Section 4) dan progress indicator navbar — AOS tidak didesain untuk ini
- **Respect `prefers-reduced-motion`**: wajib deteksi media query ini dan matikan/kurangi animasi untuk user yang set preferensi tersebut di OS mereka (ini bagian dari accessibility, sering terlewat)
  ```css
  @media (prefers-reduced-motion: reduce) {
    *, *::before, *::after {
      animation-duration: 0.01ms !important;
      transition-duration: 0.01ms !important;
    }
  }
  ```

---

## 9. RESPONSIVE & ACCESSIBILITY

### 9.1 Responsive Breakpoints

| Breakpoint | Perilaku |
|---|---|
| Desktop ≥1280px | Layout penuh sesuai mockup |
| Tablet 768–1279px | Grid 3→2 kolom; split layout tetap side-by-side bila muat |
| Mobile <768px | Semua split jadi stack; navbar hamburger (slide-in kanan); grid produk 1 kolom; heading diskalakan turun 30–40% |

### 9.2 Accessibility (BARU — tidak ada sama sekali di draft sebelumnya)

- **Kontras warna**: semua kombinasi teks/background wajib lolos WCAG AA minimum (rasio 4.5:1 untuk teks normal, 3:1 untuk teks besar) — cek khusus teks `--color-text-cream-muted` di atas `--color-bg-dark`, dan warna gold accent di atas dark background (rawan gagal kontras kalau tidak dicek)
- **Form**: setiap input wajib punya `<label>` yang terasosiasi (bukan hanya placeholder), pesan error terhubung via `aria-describedby`
- **Navigasi keyboard**: seluruh interactive element (nav, tombol CTA, form) harus bisa diakses via Tab, dengan focus state yang terlihat jelas (jangan hilangkan `outline` tanpa pengganti visual)
- **Alt text**: setiap gambar konten wajib punya `alt` deskriptif (bukan kosong/nama file) — termasuk foto produk dan proses; ini juga membantu SEO
- **Semantic HTML**: gunakan heading hierarchy yang benar (satu `<h1>` per halaman di Hero, `<h2>` per section title, jangan loncat level)

---

## 10. SEO & PERFORMANCE

**Tidak dibahas sama sekali di draft sebelumnya** — krusial untuk company profile B2B yang harus ditemukan buyer.

### 10.1 On-Page SEO
- Meta title & description unik, deskriptif, mengandung kata kunci relevan (rempah ekspor, single-origin spice Indonesia, dst) — kelola via `page_contents` atau meta fields khusus, bukan hardcode
- Open Graph tags (`og:title`, `og:description`, `og:image`) — penting karena link ini kemungkinan di-share via email/WhatsApp ke calon buyer
- Structured data (JSON-LD) tipe `Organization` minimal (nama, logo, alamat, kontak) — membantu Google Knowledge Panel
- `sitemap.xml` dan `robots.txt` sederhana (meski single-page, tetap best practice)
- Semantic heading hierarchy (lihat Section 9.2)

### 10.2 Performance Target
- Lighthouse score target: Performance ≥85, Accessibility ≥90, SEO ≥95 (angka realistis untuk site dengan banyak gambar, bukan target 100 yang tidak realistis)
- Core Web Vitals: LCP <2.5s, CLS <0.1 (rawan gagal kalau gambar tidak punya `width`/`height` attribute — set eksplisit untuk cegah layout shift)
- Font loading: gunakan `font-display: swap` untuk Google Fonts agar teks tidak invisible saat font sedang load
- CSS/JS minifikasi & bundling standar Laravel (Vite) — pastikan build production (`npm run build`) bukan asset dev yang ke-deploy

---

## 11. KEAMANAN & ANTI-SPAM

**Gap kritis** — form publik tanpa proteksi ini adalah risiko operasional nyata, bukan sekadar "nice to have".

### 11.1 Form Inquiry
- **Honeypot field** tersembunyi (field tambahan yang human tidak isi tapi bot biasanya isi) — cara termudah & tanpa dependency
- **Rate limiting** via Laravel throttle middleware pada route submit (misal maksimal 5 submission per IP per 10 menit)
- **Google reCAPTCHA v3** atau **Cloudflare Turnstile** (lebih privacy-friendly & gratis) — pertimbangkan jika honeypot+rate-limit masih kebobolan spam setelah live
- Validasi server-side ketat di `StoreInquiryRequest` (format email jika ada field email, panjang maksimal tiap field untuk cegah payload besar)
- Simpan `ip_address` di tabel `inquiries` (sudah ditambahkan di Section 5.2) untuk audit jika ada abuse

### 11.2 Umum
- CSRF protection — bawaan Laravel, pastikan tidak ada route form yang di-exclude tanpa alasan kuat
- `.env` tidak pernah di-commit (sudah ada di draft sebelumnya, dipertahankan)
- HTTPS wajib di production (Let's Encrypt via HestiaCP, gratis)
- Admin panel: rate-limit login, pertimbangkan 2FA jika Filament/Nova mendukung (built-in di kedua package)
- Content Security Policy (CSP) header dasar — mencegah XSS dari sumber tak terduga, terutama karena ada admin panel yang menerima input teks bebas

---

## 12. ARSITEKTUR MAIL SERVER

*(Tidak berubah signifikan dari PRD infrastruktur sebelumnya — dipertahankan karena sudah solid, dengan penambahan detail rate-limit request.)*

### 12.1 Prinsip
Laravel hanya bicara lewat Mail abstraction + `.env`. Direct-send dan SMTP relay sama-sama "hanya SMTP" dari sudut pandang kode — migrasi = ganti env variable, nol perubahan kode.

### 12.2 Flow Inquiry → Email

```
User submit form
      │
      ▼
InquiryController::store()
      │
      ├── Validasi (StoreInquiryRequest) + cek honeypot + rate limit
      │
      ├── Simpan ke `inquiries` (SELALU berhasil dulu, terlepas status email)
      │
      └── Dispatch Mail (ShouldQueue) → NewInquiryNotification
                │
                ├── Sukses → update email_sent_at
                ├── Gagal  → log ke channel 'mail', catat email_failed_reason
                │            (TIDAK melempar exception ke user)
```

> **Tambahan penting yang hilang di draft sebelumnya:** karena direct-send SMTP handshake ke server penerima bisa lambat (beberapa detik), dan di lokal `QUEUE_CONNECTION=sync` berarti proses ini blocking di request cycle — **di production, `QUEUE_CONNECTION` WAJIB `database` (bukan `sync`)** supaya user tidak menunggu proses kirim email selesai sebelum melihat halaman sukses. Ini detail kecil yang gampang terlewat kalau developer copy-paste config lokal ke production tanpa sadar.

### 12.3 Monitoring (Pengganti Auto-Switch — Keputusan Tetap Manual)
- Field `email_sent_at` & `email_failed_reason` di tabel `inquiries`
- Log channel terpisah untuk mail (`config/logging.php` tambah channel `mail`)
- Command `php artisan mail:health-check` (opsional, murah diimplementasi) — hitung rasio gagal N hari terakhir sebagai bahan keputusan manual pindah ke relay

### 12.4 Deliverability Risk & Mitigasi (Infrastructure Layer)
- PTR record wajib mengarah ke `mail.refi-spice.com`
- SPF, DKIM (via HestiaCP), DMARC (`p=none` dulu, baru naikkan ke `quarantine`/`reject`)
- IP warm-up jika volume mulai besar
- Indikator waktu pindah ke relay: rasio gagal konsisten tinggi, laporan email tidak sampai, atau volume mulai besar (newsletter dsb)

---

## 13. ENVIRONMENT CONFIGURATION

**Draft sebelumnya hanya sebagian variable. Berikut versi lengkap.**

### 13.1 Local (`.env` development)

```env
APP_NAME="Refi Spice"
APP_ENV=local
APP_KEY=                        # generate via `php artisan key:generate`
APP_DEBUG=true
APP_URL=http://localhost
APP_LOCALE=id

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=sqlite
# atau: mysql / DB_HOST / DB_PORT / DB_DATABASE / DB_USERNAME / DB_PASSWORD

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=sync

FILESYSTEM_DISK=public           # untuk local; lihat 13.3 untuk production

MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025                   # Mailpit
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=no-reply@refi-spice.local
MAIL_FROM_NAME="Refi Spice (Local)"
MAIL_SALES_ADDRESS=sales@refi-spice.com   # tetap set ke alamat asli agar testing representatif

RECAPTCHA_SITE_KEY=              # kosongkan/pakai test key di lokal
RECAPTCHA_SECRET_KEY=
```

### 13.2 Production (Direct-Send — Fase Awal)

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://www.refi-spice.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=refi_spice_prod
DB_USERNAME=***
DB_PASSWORD=***

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database         # WAJIB bukan sync — lihat Section 12.2

FILESYSTEM_DISK=public

MAIL_MAILER=smtp
MAIL_HOST=mail.refi-spice.com
MAIL_PORT=587
MAIL_USERNAME=no-reply@refi-spice.com
MAIL_PASSWORD=***
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no-reply@refi-spice.com
MAIL_FROM_NAME="Refi Spice"
MAIL_SALES_ADDRESS=sales@refi-spice.com

RECAPTCHA_SITE_KEY=***
RECAPTCHA_SECRET_KEY=***
```

Perlu tambahan: jalankan `php artisan queue:work` sebagai daemon (via Supervisor, dikonfigurasi di infrastructure layer/HestiaCP) supaya job queue (email) benar-benar diproses.

### 13.3 Production (Fallback Relay)
Hanya ganti blok `MAIL_*` sesuai provider relay pilihan — tidak ada field lain yang berubah.

### 13.4 Checklist `.env.example`
Wajib disediakan file `.env.example` di repository (tanpa nilai sensitif) yang mencakup SEMUA variable di atas + komentar penjelasan — supaya siapa pun yang clone project tahu apa saja yang perlu diisi, tanpa harus baca PRD ini dari awal.

---

## 14. TESTING & QA PLAN

**Tidak ada rencana konkret di draft sebelumnya.** Berikut skenario minimal yang wajib dijalankan sebelum dianggap selesai.

### 14.1 Functional Testing

| Skenario | Expected Result |
|---|---|
| Submit form inquiry dengan data valid | Data tersimpan di `inquiries`, email muncul di Mailpit (lokal), halaman sukses tampil |
| Submit form dengan field wajib kosong | Validasi error tampil per field, data TIDAK tersimpan |
| Submit form saat mail server down (simulasi) | Data tetap tersimpan, `email_failed_reason` terisi, user tetap lihat halaman sukses |
| Submit form 6x dalam 10 menit dari IP sama | Submission ke-6 ditolak rate-limit |
| Isi honeypot field (simulasi bot) | Submission ditolak diam-diam (tanpa error message ke "bot") |
| Admin login dengan kredensial salah 5x | Rate-limit login aktif |
| Admin tambah/edit/hapus produk | Perubahan langsung terlihat di halaman publik tanpa deploy |
| Admin reorder produk (`order` field) | Urutan tampilan berubah sesuai |

### 14.2 Visual/Cross-Browser Testing
- Chrome, Safari, Firefox, Edge — desktop terbaru
- Safari iOS & Chrome Android — mobile (perhatikan khusus: font rendering serif, animasi scroll di Safari sering berbeda perilaku)
- Bandingkan tiap section dengan mockup asli side-by-side (pixel-diff manual, tidak perlu tool otomatis untuk proyek sekecil ini)

### 14.3 Performance Testing
- Jalankan Lighthouse (Chrome DevTools) setelah build production, sebelum go-live
- Test dengan throttling "Slow 3G" untuk memastikan lazy loading gambar bekerja

### 14.4 Accessibility Testing
- Jalankan axe DevTools browser extension minimal sekali sebelum go-live
- Test navigasi keyboard-only (Tab melalui seluruh halaman, submit form tanpa mouse)

---

## 15. BACKUP, DEPLOYMENT & ROLLBACK

**Tidak dibahas sama sekali di draft sebelumnya.**

### 15.1 Backup
- Database: backup harian otomatis (via HestiaCP cron atau `php artisan schedule` + `spatie/laravel-backup`), retensi minimal 7 hari
- Uploaded media (foto produk dll): included dalam backup rutin yang sama, atau sinkronisasi terpisah ke storage eksternal jika volume besar

### 15.2 Deployment
- Gunakan Git-based deployment (push ke branch `main`/`production` → deploy manual atau via simple script) — tidak perlu CI/CD kompleks untuk skala proyek ini, tapi minimal:
  ```bash
  git pull
  composer install --no-dev --optimize-autoloader
  php artisan migrate --force
  npm run build
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
  php artisan queue:restart
  ```
- **Maintenance mode** saat deploy: `php artisan down` / `php artisan up` untuk hindari user melihat state setengah-deploy

### 15.3 Rollback Plan
- Sebelum tiap deploy production, tag Git commit sebelumnya agar mudah `git checkout` mundur jika ada masalah kritis
- Migration baru sebaiknya reversible (`down()` method diisi dengan benar, bukan dikosongkan) — supaya `php artisan migrate:rollback` benar-benar berfungsi kalau diperlukan

---

## 16. STRUKTUR KODE & KONVENSI

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── HomeController.php          (render landing page + data section)
│   │   └── InquiryController.php
│   └── Requests/
│       └── StoreInquiryRequest.php
├── Mail/
│   └── NewInquiryNotification.php
├── Models/
│   ├── Product.php
│   ├── ProductSpecification.php
│   ├── Inquiry.php
│   ├── Milestone.php
│   ├── CompanyStat.php
│   ├── Certification.php
│   ├── ProcessStep.php
│   ├── Testimonial.php
│   ├── Partner.php
│   └── PageContent.php
├── Filament/ (jika pakai Filament untuk admin)
│   └── Resources/
config/
├── mail.php                            (+ sales_notification_address)
resources/
├── views/
│   ├── components/section/
│   │   ├── hero.blade.php
│   │   ├── cerita-kami.blade.php
│   │   ├── koleksi-produk.blade.php
│   │   ├── kualitas.blade.php
│   │   ├── rantai-pasok.blade.php
│   │   ├── kemitraan.blade.php
│   │   └── kontak.blade.php
│   └── emails/
│       └── inquiry-notification.blade.php
database/
├── migrations/
├── seeders/
│   └── DemoContentSeeder.php           (BARU — isi data awal/placeholder terstruktur, bukan manual insert)
routes/
├── web.php
tests/
├── Feature/
│   ├── InquirySubmissionTest.php
│   └── AdminProductManagementTest.php
```

### 16.1 Konvensi
- Penamaan Blade component konsisten kebab-case sesuai section (`x-section.hero`, dst)
- Setiap Model punya factory (`database/factories/`) untuk kebutuhan testing
- Gunakan Laravel Form Request untuk semua validasi (jangan validasi inline di controller)
- Gunakan Eloquent scope untuk query berulang, misal `Product::active()->ordered()->get()`

---

## 17. PRINSIP ANTI-"AI SLOP"

*(Dipertahankan penuh dari PRD sebelumnya — bagian ini sudah baik, tidak ada gap besar.)*

- Icon: hindari icon set generik flat tanpa treatment; gunakan line-art botanical, outline tipis dalam lingkaran border
- Border/frame dekoratif: hairline 1px, hindari box-shadow tebal/gradient ala SaaS modern
- Foto: placeholder jelas ditandai, color grading konsisten saat foto asli masuk
- Layout: variasi struktur antar section wajib dipertahankan, jangan pola sama diulang
- Copy: microcopy tetap spesifik/teknis (angka, satuan, istilah industri), jangan digenerikkan
- Animasi: easing custom, stagger halus, bukan default library mentah

---

## 18. BOUNDARY — HAL YANG TIDAK BOLEH DILAKUKAN AGENT

- Jangan implementasi logic SMTP custom di luar Laravel Mail abstraction
- Jangan hardcode hostname/kredensial/IP production di source code
- Jangan commit `.env` ke Git
- Jangan buat auto-switch direct-send↔relay di dalam kode (keputusan manual pemilik proyek)
- Jangan simulasikan konfigurasi DNS (SPF/DKIM/DMARC/PTR) di dalam repository Laravel
- Jangan buat kegagalan email menyebabkan HTTP error yang terlihat user
- Jangan ganti Laravel dengan framework lain atau tambahkan SPA architecture yang tidak perlu
- Jangan hardcode data produk/konten di Blade — semua wajib lewat database (kecuali label statis UI seperti "Kirim" pada tombol)
- Jangan skip validasi server-side dengan alasan "sudah divalidasi di frontend"
- Jangan deploy tanpa menjalankan build production asset (`npm run build`) — asset dev tidak boleh ke production
- Jangan buat migration dengan `down()` kosong tanpa alasan kuat (rollback harus benar-benar berfungsi)

---

## 19. RENCANA IMPLEMENTASI BERTAHAP (ROADMAP)

### Fase 1 — Fondasi
1. Setup project Laravel, Git repo, `.env.example`
2. Migration seluruh tabel (Section 5)
3. Model + relasi + factory
4. Seeder data placeholder (realistis, sesuai mockup, bukan lorem ipsum)

### Fase 2 — Frontend Statis
5. Blade components 7 section sesuai design system (Section 3)
6. Integrasi data dari database ke Blade (bukan hardcode)
7. Responsive per breakpoint (Section 9.1)

### Fase 3 — Interaksi
8. Animasi scroll (AOS/GSAP) sesuai Section 8
9. Form inquiry: validasi + honeypot + rate-limit (tanpa email dulu — pastikan solid)

### Fase 4 — Mail & Notifikasi
10. Setup Mailpit lokal
11. Mailable `NewInquiryNotification` + view email
12. Integrasi flow submit → save → email (Section 12.2)

### Fase 5 — Admin Panel
13. Install & konfigurasi Filament (atau pilihan lain)
14. CRUD untuk seluruh entitas konten
15. Autentikasi admin + rate-limit login

### Fase 6 — Hardening
16. SEO meta, structured data, sitemap (Section 10.1)
17. Accessibility audit + perbaikan (Section 9.2)
18. Performance audit (Lighthouse) + optimasi gambar

### Fase 7 — QA & Go-Live Prep
19. Jalankan seluruh skenario testing (Section 14)
20. Setup backup otomatis (Section 15.1)
21. Dokumentasi deployment (Section 15.2)

### Fase 8 — Infrastruktur (Terpisah, Setelah Aplikasi Matang)
22. Setup VPS + HestiaCP
23. Setup DNS (SPF/DKIM/DMARC/PTR)
24. Deploy production, migrasi `.env`, testing direct-send end-to-end
25. Monitoring pasca-launch (2-4 minggu) untuk evaluasi keputusan direct-send vs relay

---

## 20. DEFINITION OF DONE (ACCEPTANCE CRITERIA)

**Tidak ada di draft sebelumnya — kriteria "selesai" harus eksplisit, bukan subjektif.**

Sebuah section dianggap selesai jika DAN HANYA JIKA:
- [ ] Visual cocok dengan mockup (dicek side-by-side, toleransi kecil untuk hal yang memang berbeda karena real browser vs mockup statis)
- [ ] Data ditarik dari database, bukan hardcode
- [ ] Responsive benar di 3 breakpoint (desktop/tablet/mobile)
- [ ] Animasi sesuai spesifikasi Section 8, termasuk `prefers-reduced-motion`
- [ ] Kontras warna lolos WCAG AA
- [ ] Semua gambar punya `alt` text
- [ ] Tidak ada console error di browser devtools

Keseluruhan proyek dianggap siap go-live jika DAN HANYA JIKA:
- [ ] Seluruh 7 section memenuhi kriteria di atas
- [ ] Form inquiry lolos semua skenario testing (Section 14.1)
- [ ] Admin panel bisa CRUD seluruh entitas tanpa error
- [ ] Lighthouse score memenuhi target (Section 10.2)
- [ ] `.env.example` lengkap dan akurat
- [ ] Backup otomatis terkonfirmasi jalan (bukan cuma terpasang, tapi dicek hasil backup-nya benar-benar ada)
- [ ] Tidak ada credential/secret ter-commit di riwayat Git (cek dengan `git log -p | grep -i password` atau tools sejenis sebelum go-live)

---

## 21. LAMPIRAN

### Lampiran A — Referensi Mockup
7 frame desain Heritage Craft (1920×1080 masing-masing), disetujui client:
1. Hero — "Warisan Rempah, Cerita Dunia"
2. Cerita Kami — "Menjaga Api Tradisi, Merawat Tanah Leluhur"
3. Koleksi Produk — "Koleksi Rempah Pilihan"
4. Warisan & Kualitas — "Angka di Balik Kepercayaan Dunia"
5. Rantai Pasok — "Dari Kebun ke Dunia: Jejak Perjalanan Rempah"
6. Kemitraan — "Dipercaya oleh Pengolah Rempah & Industri Dunia"
7. Kontak & Footer — "Hubungi Divisi Ekspor & Kemitraan Dagang"

### Lampiran B — Open Questions (Perlu Konfirmasi Sebelum/Selama Development)
Hal-hal berikut belum final dan sebaiknya dikonfirmasi ke client sebelum development section terkait dimulai, supaya tidak bolak-balik ubah skema:

1. Daftar lengkap pilihan dropdown "Komoditas Rempah yang Diminati" dan "Estimasi Volume per Pengiriman" di form — draft sekarang pakai contoh, perlu daftar final dari client
2. Apakah badge sertifikasi (HACCP, ISO 22000, USDA Organic, dst) di Section 4 adalah sertifikasi yang benar-benar dimiliki client saat ini, atau aspirasional? Ini menentukan apakah perlu logo resmi/nomor sertifikat atau cukup placeholder
3. Siapa yang akan mengoperasikan admin panel — client sendiri atau kamu? Menentukan seberapa detail dokumentasi/training yang perlu disiapkan
4. Konfirmasi final: apakah "sales@refi-spice.com" sudah pasti alamat yang aktif dan dipantau, atau perlu alamat sementara sebelum mail server production siap?

### Lampiran C — Dependency/Package yang Direkomendasikan

| Kebutuhan | Package |
|---|---|
| Admin panel | `filament/filament` |
| Image management | `spatie/laravel-medialibrary` |
| Database backup | `spatie/laravel-backup` |
| Anti-spam | Honeypot custom (tanpa dependency) + `google/recaptcha` (opsional) |
| Animasi scroll | AOS (via CDN/npm) + GSAP (untuk count-up) |
| Local mail testing | Mailpit (Docker) |

---

*Dokumen ini adalah single source of truth untuk implementasi. Perubahan scope di luar Section 2.1 wajib dicatat sebagai Change Request dan memperbarui nomor versi dokumen ini.*
