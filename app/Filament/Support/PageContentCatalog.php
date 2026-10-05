<?php

namespace App\Filament\Support;

use Illuminate\Support\Str;

/**
 * Maps raw PageContent section_key/field_key database identifiers to
 * human-friendly Indonesian labels and helper text for the admin panel,
 * so non-technical staff never have to interpret snake_case keys directly.
 */
class PageContentCatalog
{
    /**
     * @return array<string, array{label: string, description: string}>
     */
    public static function sections(): array
    {
        return [
            'brand' => [
                'label' => 'Identitas Merek',
                'description' => 'Tagline di sebelah logo pada header, tampil di semua halaman.',
            ],
            'seo' => [
                'label' => 'SEO & Metadata',
                'description' => 'Teks yang muncul di hasil pencarian Google dan saat tautan dibagikan ke media sosial.',
            ],
            'hero' => [
                'label' => 'Bagian Utama (Hero)',
                'description' => 'Bagian paling atas halaman depan — pertama kali dilihat pengunjung.',
            ],
            'cerita_kami' => [
                'label' => 'Cerita Kami',
                'description' => 'Bagian narasi sejarah dan profil perusahaan.',
            ],
            'koleksi_produk' => [
                'label' => 'Koleksi Produk',
                'description' => 'Judul & teks pengantar bagian daftar produk (produknya sendiri diatur di menu Produk).',
            ],
            'kualitas' => [
                'label' => 'Kualitas & Statistik',
                'description' => 'Bagian angka pencapaian dan standar kualitas perusahaan.',
            ],
            'rantai_pasok' => [
                'label' => 'Rantai Pasok',
                'description' => 'Bagian "Dari Kebun ke Dunia" yang menjelaskan proses produksi.',
            ],
            'kemitraan' => [
                'label' => 'Kemitraan & Klien',
                'description' => 'Bagian daftar mitra/klien internasional (logo mitra diatur di menu Mitra & Klien).',
            ],
            'contact' => [
                'label' => 'Kontak & Formulir Inquiry',
                'description' => 'Bagian formulir hubungi kami & info kontak di bagian bawah halaman.',
            ],
            'footer' => [
                'label' => 'Footer',
                'description' => 'Teks hak cipta di baris paling bawah setiap halaman.',
            ],
        ];
    }

    /**
     * @return array<string, array<string, array{label: string, helper: string}>>
     */
    public static function fields(): array
    {
        return [
            'brand' => [
                'tagline' => ['label' => 'Tagline', 'helper' => 'Teks kecil di bawah nama "REFI SPICE" pada logo header.'],
            ],
            'seo' => [
                'meta_title' => ['label' => 'Judul SEO (Meta Title)', 'helper' => 'Judul yang tampil di tab browser & hasil pencarian Google.'],
                'meta_description' => ['label' => 'Deskripsi SEO (Meta Description)', 'helper' => 'Ringkasan yang tampil di bawah judul pada hasil pencarian Google.'],
                'og_image_path' => ['label' => 'Gambar Bagikan Media Sosial (OG Image)', 'helper' => 'URL gambar pratinjau saat tautan situs dibagikan ke Facebook/WhatsApp.'],
            ],
            'hero' => [
                'eyebrow' => ['label' => 'Label Kecil di Atas Judul', 'helper' => 'Teks kecil huruf kapital di atas judul utama.'],
                'headline' => ['label' => 'Judul Utama', 'helper' => 'Judul besar paling utama di halaman depan.'],
                'subheadline' => ['label' => 'Sub-judul', 'helper' => 'Baris nama latin komoditas di bawah judul utama.'],
                'body' => ['label' => 'Paragraf Pembuka', 'helper' => 'Paragraf deskripsi singkat di bawah judul.'],
                'cta_primary_label' => ['label' => 'Tombol Utama', 'helper' => 'Teks tombol ajakan utama (mengarah ke bagian Koleksi Produk).'],
                'cta_secondary_label' => ['label' => 'Tombol Kedua', 'helper' => 'Teks tombol ajakan kedua (mengarah ke bagian Kualitas).'],
                'value_prop_1_label' => ['label' => 'Keunggulan #1 — Label', 'helper' => 'Kode kecil di atas judul keunggulan pertama, mis. "01 / Indeks Kualitas".'],
                'value_prop_1_title' => ['label' => 'Keunggulan #1 — Judul', 'helper' => 'Judul singkat keunggulan pertama.'],
                'value_prop_1_description' => ['label' => 'Keunggulan #1 — Deskripsi', 'helper' => 'Penjelasan singkat keunggulan pertama.'],
                'value_prop_2_label' => ['label' => 'Keunggulan #2 — Label', 'helper' => 'Kode kecil di atas judul keunggulan kedua.'],
                'value_prop_2_title' => ['label' => 'Keunggulan #2 — Judul', 'helper' => 'Judul singkat keunggulan kedua.'],
                'value_prop_2_description' => ['label' => 'Keunggulan #2 — Deskripsi', 'helper' => 'Penjelasan singkat keunggulan kedua.'],
                'value_prop_3_label' => ['label' => 'Keunggulan #3 — Label', 'helper' => 'Kode kecil di atas judul keunggulan ketiga.'],
                'value_prop_3_title' => ['label' => 'Keunggulan #3 — Judul', 'helper' => 'Judul singkat keunggulan ketiga.'],
                'value_prop_3_description' => ['label' => 'Keunggulan #3 — Deskripsi', 'helper' => 'Penjelasan singkat keunggulan ketiga.'],
                'footer_coordinates' => ['label' => 'Koordinat Asal (Teks Kecil)', 'helper' => 'Baris koordinat lokasi asal komoditas di bagian bawah hero.'],
                'footer_badge' => ['label' => 'Lencana Sertifikasi (Teks Kecil)', 'helper' => 'Teks lencana kecil di bagian bawah hero, mis. "Sucofindo Certified".'],
            ],
            'cerita_kami' => [
                'eyebrow_label' => ['label' => 'Label Bab', 'helper' => 'Teks kecil di atas judul, mis. "Bab I • Asal Usul & Dedikasi".'],
                'eyebrow_sub' => ['label' => 'Sub-label', 'helper' => 'Teks kecil di sebelah label bab.'],
                'headline' => ['label' => 'Judul Bagian', 'helper' => 'Judul utama bagian Cerita Kami.'],
                'body_paragraph_1' => ['label' => 'Paragraf 1', 'helper' => 'Paragraf narasi pertama.'],
                'body_paragraph_2' => ['label' => 'Paragraf 2', 'helper' => 'Paragraf narasi kedua.'],
                'photo_caption_title' => ['label' => 'Judul Keterangan Foto', 'helper' => 'Judul kecil di bawah foto ilustrasi.'],
                'photo_caption_meta' => ['label' => 'Detail Keterangan Foto', 'helper' => 'Baris info tambahan di bawah judul keterangan foto.'],
                'photo_badge_title' => ['label' => 'Lencana Foto — Judul', 'helper' => 'Judul lencana kecil di atas foto, mis. "100% Single Origin".'],
                'photo_badge_subtitle' => ['label' => 'Lencana Foto — Subjudul', 'helper' => 'Subjudul lencana kecil di atas foto.'],
                'archive_plate_no' => ['label' => 'Nomor Arsip (Dekoratif)', 'helper' => 'Nomor arsip dekoratif kecil di pojok bagian ini.'],
                'archive_coord' => ['label' => 'Koordinat Arsip (Dekoratif)', 'helper' => 'Koordinat lokasi dekoratif kecil di pojok bagian ini.'],
            ],
            'koleksi_produk' => [
                'eyebrow_label' => ['label' => 'Label Kecil', 'helper' => 'Teks kecil di atas judul bagian.'],
                'eyebrow_sub' => ['label' => 'Sub-label', 'helper' => 'Teks kecil di sebelah label utama.'],
                'headline' => ['label' => 'Judul Bagian', 'helper' => 'Judul utama bagian Koleksi Produk.'],
                'body' => ['label' => 'Paragraf Pengantar', 'helper' => 'Paragraf deskripsi singkat di bawah judul.'],
                'available_also_label' => ['label' => 'Label "Tersedia Juga"', 'helper' => 'Teks label sebelum daftar produk tambahan di bagian bawah.'],
                'lab_doc_label' => ['label' => 'Label Dokumen Lab', 'helper' => 'Judul kecil kotak info dokumentasi laboratorium.'],
                'lab_doc_value' => ['label' => 'Isi Dokumen Lab', 'helper' => 'Isi teks kotak info dokumentasi laboratorium.'],
                'lab_cta_label' => ['label' => 'Tombol Minta Data Teknis', 'helper' => 'Teks tombol ajakan untuk meminta lembar data teknis (COA).'],
            ],
            'kualitas' => [
                'eyebrow_label' => ['label' => 'Label Kecil', 'helper' => 'Teks kecil di atas judul bagian.'],
                'headline' => ['label' => 'Judul Bagian', 'helper' => 'Judul utama bagian Kualitas & Statistik.'],
                'body' => ['label' => 'Paragraf Pengantar', 'helper' => 'Paragraf deskripsi singkat (angka statistik diatur di menu Statistik Perusahaan).'],
                'callout_title' => ['label' => 'Judul Kotak Sorotan', 'helper' => 'Judul kotak sorotan gelap, mis. "Single-Origin Traceability Protocol".'],
                'callout_body' => ['label' => 'Isi Kotak Sorotan', 'helper' => 'Isi teks kotak sorotan gelap.'],
                'accreditation_label' => ['label' => 'Label Baris Akreditasi', 'helper' => 'Teks pengantar sebelum daftar badge sertifikasi (badge diatur di menu Sertifikasi & Akreditasi).'],
                'document_ref' => ['label' => 'Nomor Referensi Dokumen', 'helper' => 'Nomor referensi dekoratif kecil di bagian bawah.'],
            ],
            'rantai_pasok' => [
                'eyebrow_label' => ['label' => 'Label Kecil', 'helper' => 'Teks kecil di atas judul bagian.'],
                'headline_plain' => ['label' => 'Judul — Bagian Biasa', 'helper' => 'Bagian awal judul yang ditulis dengan gaya biasa.'],
                'headline_emphasis' => ['label' => 'Judul — Bagian Ditonjolkan', 'helper' => 'Bagian akhir judul yang ditulis dengan gaya ditonjolkan/miring.'],
                'body' => ['label' => 'Paragraf Pengantar', 'helper' => 'Paragraf deskripsi singkat di bawah judul.'],
                'stat_1_label' => ['label' => 'Statistik #1 — Label', 'helper' => 'Label angka pertama, mis. "Single-Origin Terroir".'],
                'stat_1_value' => ['label' => 'Statistik #1 — Nilai', 'helper' => 'Nilai angka pertama, mis. "100% Nusantara".'],
                'stat_2_label' => ['label' => 'Statistik #2 — Label', 'helper' => 'Label angka kedua.'],
                'stat_2_value' => ['label' => 'Statistik #2 — Nilai', 'helper' => 'Nilai angka kedua.'],
                'banner_title' => ['label' => 'Judul Banner', 'helper' => 'Judul banner sorotan di bagian bawah.'],
                'banner_subtitle' => ['label' => 'Subjudul Banner', 'helper' => 'Subjudul kecil banner sorotan.'],
                'banner_body' => ['label' => 'Isi Banner', 'helper' => 'Paragraf isi banner sorotan.'],
                'banner_cert_label' => ['label' => 'Banner — Label Sertifikat', 'helper' => 'Label kecil info sertifikat pada banner.'],
                'banner_cert_value' => ['label' => 'Banner — Isi Sertifikat', 'helper' => 'Isi info sertifikat pada banner.'],
                'banner_doc_label' => ['label' => 'Banner — Label Dokumen', 'helper' => 'Label kecil info dokumen terbit pada banner.'],
                'banner_doc_value' => ['label' => 'Banner — Isi Dokumen', 'helper' => 'Isi info dokumen terbit pada banner.'],
            ],
            'kemitraan' => [
                'eyebrow_label' => ['label' => 'Label Kecil', 'helper' => 'Teks kecil di atas judul bagian.'],
                'headline' => ['label' => 'Judul Bagian', 'helper' => 'Judul utama bagian Kemitraan & Klien.'],
                'body' => ['label' => 'Paragraf Pengantar', 'helper' => 'Paragraf deskripsi singkat di bawah judul.'],
                'lot_ref' => ['label' => 'Referensi Arsip (Dekoratif)', 'helper' => 'Kode referensi dekoratif kecil di bagian ini.'],
                'partners_intro' => ['label' => 'Judul Daftar Mitra', 'helper' => 'Judul kecil di atas deretan logo mitra (logo diatur di menu Mitra & Klien).'],
                'partners_note' => ['label' => 'Catatan Daftar Mitra', 'helper' => 'Teks kecil di sebelah judul daftar mitra.'],
                'footer_note' => ['label' => 'Catatan Penutup', 'helper' => 'Paragraf catatan di bagian bawah, mis. info kontrak tahunan.'],
                'footer_port' => ['label' => 'Info Pelabuhan', 'helper' => 'Baris info pelabuhan asal pengiriman.'],
                'footer_doc' => ['label' => 'Info Dokumen', 'helper' => 'Baris info dokumen fitosanitari/COA.'],
            ],
            'contact' => [
                'eyebrow' => ['label' => 'Label Kecil', 'helper' => 'Teks kecil di atas judul bagian.'],
                'headline' => ['label' => 'Judul Bagian', 'helper' => 'Judul utama bagian Kontak.'],
                'body' => ['label' => 'Paragraf Pengantar', 'helper' => 'Paragraf deskripsi singkat di bawah judul.'],
                'form_eyebrow' => ['label' => 'Label Kecil Formulir', 'helper' => 'Teks kecil di atas judul formulir inquiry.'],
                'form_ref' => ['label' => 'Nomor Referensi Formulir (Dekoratif)', 'helper' => 'Kode referensi dekoratif kecil di formulir.'],
                'form_trust_note' => ['label' => 'Catatan Kepercayaan Formulir', 'helper' => 'Baris teks kecil di bawah formulir, mis. info NDA/sertifikat.'],
                'submit_label' => ['label' => 'Teks Tombol Kirim', 'helper' => 'Teks tombol submit formulir inquiry.'],
                'coordinates_label' => ['label' => 'Label Koordinat', 'helper' => 'Judul kecil di atas koordinat kantor.'],
                'coordinates_value' => ['label' => 'Nilai Koordinat', 'helper' => 'Koordinat GPS kantor/pelabuhan.'],
                'coordinates_sub' => ['label' => 'Sub-teks Koordinat', 'helper' => 'Baris teks kecil di bawah koordinat GPS.'],
                'head_office_address' => ['label' => 'Alamat Kantor Pusat', 'helper' => 'Alamat lengkap kantor pusat.'],
                'processing_facilities' => ['label' => 'Lokasi Fasilitas Pengolahan', 'helper' => 'Daftar lokasi fasilitas pengolahan/gudang.'],
                'email' => ['label' => 'Alamat Email', 'helper' => 'Email kontak yang ditampilkan & dituju formulir.'],
                'phone' => ['label' => 'Nomor Telepon', 'helper' => 'Nomor telepon kantor yang ditampilkan.'],
                'whatsapp' => ['label' => 'Nomor WhatsApp', 'helper' => 'Nomor WhatsApp kontak cepat.'],
                'whatsapp_caption' => ['label' => 'Keterangan WhatsApp', 'helper' => 'Teks kecil di sebelah nomor WhatsApp, mis. "Fast Desk Response".'],
                'operational_hours' => ['label' => 'Jam Operasional', 'helper' => 'Jam & hari operasional yang ditampilkan.'],
                'audit_label' => ['label' => 'Label Audit', 'helper' => 'Judul kecil info audit/lab traceability.'],
                'audit_value' => ['label' => 'Isi Audit', 'helper' => 'Isi info audit/lab traceability.'],
                'audit_badge' => ['label' => 'Lencana Audit', 'helper' => 'Teks lencana kecil, mis. "Est. 2024".'],
            ],
            'footer' => [
                'copyright' => ['label' => 'Teks Hak Cipta', 'helper' => 'Baris teks hak cipta di paling bawah setiap halaman.'],
            ],
        ];
    }

    public static function sectionLabel(?string $sectionKey): string
    {
        return static::sections()[$sectionKey]['label']
            ?? Str::of($sectionKey ?? '')->replace('_', ' ')->title()->toString();
    }

    public static function sectionDescription(?string $sectionKey): ?string
    {
        return static::sections()[$sectionKey]['description'] ?? null;
    }

    public static function fieldLabel(?string $sectionKey, ?string $fieldKey): string
    {
        return static::fields()[$sectionKey][$fieldKey]['label']
            ?? Str::of($fieldKey ?? '')->replace('_', ' ')->title()->toString();
    }

    public static function fieldHelper(?string $sectionKey, ?string $fieldKey): ?string
    {
        return static::fields()[$sectionKey][$fieldKey]['helper'] ?? null;
    }
}
