<?php

namespace Database\Seeders;

use App\Models\Certification;
use App\Models\CompanyStat;
use App\Models\Inquiry;
use App\Models\Milestone;
use App\Models\PageContent;
use App\Models\Partner;
use App\Models\Product;
use App\Models\ProcessStep;
use App\Models\ProductTag;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Seeds realistic demo content sourced from the Heritage Craft mockups
 * (Frame 1, 2, 4, 5, 6, 7) so Fase 1-2 development/QA can be verified
 * against real-looking data instead of empty tables.
 *
 * NOTE: Certification badges (HACCP, ISO 22000, USDA Organic, EU Organic,
 * Halal Indonesia) are seeded as ASPIRATIONAL placeholders per the
 * Fase 0 decision — they must be confirmed/replaced with real certificates
 * and logos before go-live. See PLACEHOLDER_CHECKLIST.md.
 */
class DemoContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedProducts();
        $this->seedProductTags();
        $this->seedMilestones();
        $this->seedCompanyStats();
        $this->seedCertifications();
        $this->seedProcessSteps();
        $this->seedTestimonials();
        $this->seedPartners();
        $this->seedPageContents();
        $this->seedSampleInquiries();
    }

    private function seedProducts(): void
    {
        $products = [
            [
                'name' => 'Biji Pala & Fuli Banda',
                'latin_name' => 'Myristica fragrans',
                'origin_region' => 'Kepulauan Banda, Maluku',
                'origin_label' => 'Banda Neira, Maluku',
                'sensory_profile' => 'Aroma hangat, sedikit manis, dengan nada kayu dan citrus khas pala Banda asli.',
                'specs' => [
                    'Kadar Minyak Atsiri' => '> 11.5%',
                    'Kadar Air' => '≤ 10%',
                    'Grade' => 'Grade A, Handpicked',
                    'Kemasan' => 'Karung goni food-grade 50kg / sesuai permintaan',
                ],
            ],
            [
                'name' => 'Cengkeh Lalpari Zanzibar',
                'latin_name' => 'Syzygium aromaticum',
                'origin_region' => 'Minahasa, Sulawesi Utara',
                'origin_label' => 'Amurang, Sulawesi Utara',
                'sensory_profile' => 'Aroma tajam eugenol tinggi, khas varietas Lalpari dengan kadar minyak atsiri unggul.',
                'specs' => [
                    'Kadar Minyak Atsiri' => '> 17%',
                    'Kadar Air' => '≤ 12%',
                    'Grade' => 'CIF Jebel Ali Spec',
                    'Kemasan' => 'Karung goni food-grade 50kg / sesuai permintaan',
                ],
            ],
            [
                'name' => 'Kayu Manis Korintje A',
                'latin_name' => 'Cinnamomum burmannii',
                'origin_region' => 'Kerinci, Sumatra Barat',
                'origin_label' => 'Kerinci, Sumatra Barat',
                'sensory_profile' => 'Manis, hangat, dengan kadar coumarin rendah khas Korintje grade A.',
                'specs' => [
                    'Kadar Minyak Atsiri' => '> 1.5%',
                    'Kadar Air' => '≤ 12%',
                    'Grade' => 'Korintje A',
                    'Kemasan' => 'Karton double-wall 25kg / sesuai permintaan',
                ],
            ],
            [
                'name' => 'Kapulaga Jawa',
                'latin_name' => 'Amomum compactum',
                'origin_region' => 'Jawa Tengah',
                'origin_label' => 'Temanggung, Jawa Tengah',
                'sensory_profile' => 'Aroma kamper-mentol lembut, ukuran biji seragam, warna hijau alami.',
                'specs' => [
                    'Kadar Air' => '≤ 10%',
                    'Grade' => 'Grade A, Handpicked',
                    'Kemasan' => 'Karung goni food-grade 25kg / sesuai permintaan',
                ],
            ],
            [
                'name' => 'Lada Putih Muntok',
                'latin_name' => 'Piper nigrum',
                'origin_region' => 'Muntok, Bangka Belitung',
                'origin_label' => 'Muntok, Bangka Belitung',
                'sensory_profile' => 'Pedas tajam bersih, tanpa kulit, warna putih gading khas Muntok.',
                'specs' => [
                    'Kadar Piperin' => '> 6%',
                    'Kadar Air' => '≤ 13%',
                    'Grade' => 'ASTA Grade',
                    'Kemasan' => 'Karung goni food-grade 50kg / sesuai permintaan',
                ],
            ],
            [
                'name' => 'Lada Hitam Lampung',
                'latin_name' => 'Piper nigrum',
                'origin_region' => 'Lampung',
                'origin_label' => 'Lampung',
                'sensory_profile' => 'Pedas kuat, aroma tanah khas, densitas biji tinggi.',
                'specs' => [
                    'Kadar Piperin' => '> 6%',
                    'Densitas' => '> 550 g/l',
                    'Grade' => 'ASTA Grade',
                    'Kemasan' => 'Karung goni food-grade 50kg / sesuai permintaan',
                ],
            ],
            [
                'name' => 'Vanili Planifolia Papuan',
                'latin_name' => 'Vanilla planifolia',
                'origin_region' => 'Papua',
                'origin_label' => 'Papua',
                'sensory_profile' => 'Aroma manis-kompleks, kadar vanillin alami tinggi, batang lentur berkualitas kuring sempurna.',
                'specs' => [
                    'Kadar Vanillin' => '> 2%',
                    'Kadar Air' => '30-38%',
                    'Grade' => 'Gourmet Grade',
                    'Kemasan' => 'Vacuum-sealed food-grade / sesuai permintaan',
                ],
            ],
        ];

        foreach ($products as $index => $data) {
            $product = Product::create([
                'slug' => Str::slug($data['name']),
                'name' => $data['name'],
                'latin_name' => $data['latin_name'],
                'lot_number' => sprintf('LOT-%04d-ID', 2024000 + $index),
                'origin_region' => $data['origin_region'],
                'origin_label' => $data['origin_label'],
                'sensory_profile' => $data['sensory_profile'],
                'export_readiness' => 'Ready to Export',
                'primary_image_path' => null,
                'is_featured' => $index < 3,
                'is_active' => true,
                'order' => $index,
            ]);

            foreach (array_values($data['specs']) as $specIndex => $value) {
                $key = array_keys($data['specs'])[$specIndex];

                $product->specifications()->create([
                    'spec_key' => $key,
                    'spec_value' => $value,
                    'order' => $specIndex,
                ]);
            }
        }
    }

    private function seedProductTags(): void
    {
        $tags = ['Single-Origin', 'Handpicked', 'Traceable Terroir', 'Non-GMO', 'Fair Trade'];

        foreach ($tags as $index => $name) {
            ProductTag::create([
                'name' => $name,
                'is_active' => true,
                'order' => $index,
            ]);
        }
    }

    private function seedMilestones(): void
    {
        $milestones = [
            [
                'year' => '1998',
                'title' => 'Akar Kemitraan Tani Pertama di Kepulauan Banda',
                'description' => 'Inisiasi 42 keluarga petani pala.',
            ],
            [
                'year' => '2012',
                'title' => 'Sertifikasi Mutu Mutlak & Fasilitas Sortir Higienis',
                'description' => 'Laboratorium kadar air & mikrobiologi.',
            ],
            [
                'year' => '2024',
                'title' => 'Jaringan Ekspor Terpadu ke 28+ Negara',
                'description' => 'Eropa, Timur Tengah, Asia Timur.',
            ],
        ];

        foreach ($milestones as $index => $data) {
            Milestone::create([
                ...$data,
                'is_active' => true,
                'order' => $index,
            ]);
        }
    }

    private function seedCompanyStats(): void
    {
        $stats = [
            [
                'label' => 'Tahun Perjalanan Dimulai',
                'value' => '1998',
                'suffix' => null,
                'description' => 'Lebih dari 2 dekade mengarungi rantai pasok rempah nusantara.',
            ],
            [
                'label' => 'Negara Destinasi Ekspor',
                'value' => '32',
                'suffix' => '+',
                'description' => 'Eropa Barat, Amerika Utara, Timur Tengah, dan Asia Pasifik.',
            ],
            [
                'label' => 'Keluarga Petani Mitra',
                'value' => '1450',
                'suffix' => '+',
                'description' => 'Binaan langsung dengan praktik pertanian lestari dan harga adil (Fair Trade).',
            ],
            [
                'label' => 'Tingkat Kelulusan Fitosanitari',
                'value' => '99.8',
                'suffix' => '%',
                'description' => 'Standar ketat bebas aflatoksin, residu pestisida, dan kontaminan mikrobiologi.',
            ],
        ];

        foreach ($stats as $index => $data) {
            CompanyStat::create([
                ...$data,
                'is_active' => true,
                'order' => $index,
            ]);
        }
    }

    private function seedCertifications(): void
    {
        // Aspirational/placeholder — see PLACEHOLDER_CHECKLIST.md.
        $certifications = ['HACCP Certified', 'ISO 22000', 'USDA Organic', 'EU Organic', 'Halal Indonesia'];

        foreach ($certifications as $index => $name) {
            Certification::create([
                'name' => $name,
                'logo_path' => null,
                'is_active' => true,
                'order' => $index,
            ]);
        }
    }

    private function seedProcessSteps(): void
    {
        $steps = [
            [
                'title' => 'Ditanam Lestari',
                'description' => 'Ditanam di tanah vulkanik kaya mineral tanpa pupuk kimia sintetis, dengan sistem tumpang sari hutan alami.',
                'data_label' => 'Eko-Biodiversitas',
                'data_value' => '100% Non-GMO',
            ],
            [
                'title' => 'Dipanen Tangan',
                'description' => 'Petani memetik secara selektif pada puncak kematangan buah pala dan bunga cengkeh demi kadar minyak atsiri tertinggi.',
                'data_label' => 'Minyak Atsiri Optimal',
                'data_value' => '> 11.5% Essence',
            ],
            [
                'title' => 'Diproses Tradisional',
                'description' => 'Pengeringan bertahap dengan sinar matahari tropis dan pengasapan terkendali untuk menjaga integritas aroma dan warna alami.',
                'data_label' => 'Kelembaban Maks',
                'data_value' => '≤ 10% Moisture',
            ],
            [
                'title' => 'Dikirim ke Dunia',
                'description' => 'Pengemasan hermetis dengan perlindungan kelembaban ganda, siap berlayar dari pelabuhan Tanjung Priok dan Tanjung Perak.',
                'data_label' => 'Port of Origin',
                'data_value' => 'SUB & TPK Ready',
            ],
        ];

        foreach ($steps as $index => $data) {
            ProcessStep::create([
                'step_number' => $index + 1,
                'title' => $data['title'],
                'description' => $data['description'],
                'image_path' => null,
                'data_label' => $data['data_label'],
                'data_value' => $data['data_value'],
                'is_active' => true,
                'order' => $index,
            ]);
        }
    }

    private function seedTestimonials(): void
    {
        Testimonial::create([
            'quote' => 'Konsistensi kualitas pala dan cengkeh dari Refi Spice melampaui ekspektasi laboratorium kami di Hamburg. Aroma murni, kadar air terjaga sempurna, dan dokumentasi fitosanitari mereka selalu presisi tanpa celah.',
            'author_name' => 'Dr. Julian V. Weber',
            'author_title' => 'Chief Procurement Officer',
            'author_company' => 'European Botanical Ingredients AG (Hamburg, Germany)',
            'standard_badge' => 'DIN EN ISO 22000 / HACCP',
            'is_active' => true,
            'order' => 0,
        ]);
    }

    private function seedPartners(): void
    {
        $partners = [
            'Nordic Spice Co. — Kopenhagen, Denmark',
            'Al-Madina Spice Trading — Dubai, Uni Emirat Arab',
            'Atelier Parfumerie Grasse — Grasse, Prancis',
            'Kyoto Botanical Extract Ltd — Kyoto, Jepang',
            'Americas Spice Alliance — New York, Amerika Serikat',
        ];

        foreach ($partners as $index => $name) {
            Partner::create([
                'name' => $name,
                'logo_path' => null,
                'is_active' => true,
                'order' => $index,
            ]);
        }
    }

    private function seedPageContents(): void
    {
        $contents = [
            'hero' => [
                'eyebrow' => 'Komoditas Rempah Single-Origin Nusantara',
                'headline' => 'Warisan Rempah, Cerita Dunia.',
                'subheadline' => 'Myristica Fragrans • Eugenia Caryophyllata',
                'body' => 'Membawa kemurnian rempah tropis dari petani nusantara menuju panggung kuliner dan farmasi global. Dedikasi tanpa kompromi pada mutu, aroma, dan keberlanjutan tradisi kepulauan rempah.',
                'cta_primary_label' => 'Jelajahi Koleksi',
                'cta_secondary_label' => 'Pelajari Standar Ekspor',
            ],
            'contact' => [
                'headline' => 'Hubungi Divisi Ekspor & Kemitraan Dagang',
                'body' => 'Kirimkan kebutuhan spesifikasi komoditas, estimasi tonase, dan pelabuhan tujuan untuk penawaran resmi (Quotation) dalam 24 jam kerja.',
                'head_office_address' => 'Menara Batavia Lt. 18, Jl. K.H. Mas Mansyur, Jakarta Pusat, 10220, Indonesia.',
                'processing_facilities' => 'Banda Neira (Kepulauan Banda, Maluku) & Amurang (Minahasa Selatan, Sulawesi Utara).',
                'email' => 'trade@refi-spice.com',
                'phone' => '+62 21 5708 920',
                'whatsapp' => '+62 811 9820 400',
                'operational_hours' => 'Senin - Jumat, 08.00 - 18.00 WIB (GMT+7)',
            ],
            'footer' => [
                'copyright' => '© 2024 PT Refi Rempah Nusantara. Seluruh Hak Cipta Dilindungi. Single-Origin Botanical Exporter, Indonesia.',
            ],
        ];

        foreach ($contents as $section => $fields) {
            foreach ($fields as $field => $value) {
                PageContent::create([
                    'section_key' => $section,
                    'field_key' => $field,
                    'field_value' => $value,
                ]);
            }
        }
    }

    private function seedSampleInquiries(): void
    {
        // A handful of sample inquiries so the admin panel resource/table
        // can be verified against realistic states (new/contacted/closed).
        Inquiry::factory()->create(['status' => 'new']);
        Inquiry::factory()->create(['status' => 'contacted']);
        Inquiry::factory()->create(['status' => 'closed']);
    }
}
