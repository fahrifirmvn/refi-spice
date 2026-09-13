# Placeholder Content Checklist

Item-item berikut di-seed sebagai **konten sementara/aspirational** untuk keperluan development dan QA. **Wajib dikonfirmasi ulang bersama klien dan diganti dengan data/aset asli sebelum go-live production.**

## 1. Sertifikasi (`certifications` table)

Lima badge berikut disematkan di seeder (`DemoContentSeeder`) mengikuti visual mockup Frame 4, namun **belum terverifikasi keasliannya** — klien belum mengonfirmasi sertifikasi mana yang benar-benar dimiliki PT Refi Rempah Nusantara saat ini:

- HACCP Certified
- ISO 22000
- USDA Organic
- EU Organic
- Halal Indonesia

**Tindakan sebelum go-live:**
- Konfirmasi ke klien sertifikasi mana yang valid & aktif.
- Minta scan/PDF sertifikat resmi + nomor registrasi untuk keperluan legal (jangan tampilkan badge tanpa dasar hukum yang valid — risiko klaim palsu/false advertising).
- Ganti `logo_path` placeholder (`null`) dengan logo resmi masing-masing lembaga sertifikasi (dengan izin penggunaan logo dari lembaga terkait, bila diperlukan).
- Hapus badge yang tidak dapat dikonfirmasi.

## 2. Alamat Email Sales/Notifikasi

`MAIL_SALES_ADDRESS` di `.env` lokal saat ini menggunakan alamat sementara `admin@refi-spice.com` (bukan alamat email sales resmi yang akan menerima notifikasi inquiry baru). **Wajib diganti dengan alamat email resmi klien sebelum go-live** (lihat `config/mail.php` → `sales_notification_address`, PRD Section 12.2).

## 3. Foto Produk & Aset Visual

Seluruh kolom path gambar (`primary_image_path`, `logo_path`, `image_path`) di-seed dengan nilai `null` (placeholder kosong). Foto produk, logo mitra, dan logo sertifikasi asli perlu diunggah melalui Admin Panel (Fase 5) sebelum go-live.

## 4. Data Kontak & Kantor (`page_contents` — section `contact`)

Alamat kantor, nomor telepon, WhatsApp, dan jam operasional yang di-seed diambil langsung dari mockup Frame 7 sebagai referensi visual. **Wajib dikonfirmasi ke klien** bahwa data ini akurat dan terkini sebelum go-live.

## 5. Volume Kontrak Mitra & Nama Mitra (`partners` table)

Lima nama mitra (Nordic Spice Co., Al-Madina Spice Trading, Atelier Parfumerie Grasse, Kyoto Botanical Extract Ltd, Americas Spice Alliance) diambil dari mockup Frame 6 sebagai contoh visual — **perlu dikonfirmasi ke klien** apakah ini nama mitra riil yang boleh dipublikasikan (izin penggunaan nama perusahaan pihak ketiga), atau harus diganti dengan mitra riil/anonim sesuai kebijakan klien.
