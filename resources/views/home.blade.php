<x-layout.app :brand="$brand" :seo="$seo" :contact="$contact">
    <x-section.hero :hero="$hero" />

    <x-section.cerita-kami :content="$ceritaKami" :milestones="$milestones" />

    <x-section.koleksi-produk
        :content="$koleksiProduk"
        :featured-products="$featuredProducts"
        :other-products="$otherProducts"
    />

    <x-section.kualitas
        :content="$kualitas"
        :company-stats="$companyStats"
        :certifications="$certifications"
    />

    <x-section.rantai-pasok :content="$rantaiPasok" :process-steps="$processSteps" />

    <x-section.kemitraan
        :content="$kemitraan"
        :testimonials="$testimonials"
        :partners="$partners"
    />

    <x-section.kontak
        :content="$contact"
        :footer="$footer"
        :brand="$brand"
        :commodity-options="$commodityOptions"
        :volume-options="$volumeOptions"
    />
</x-layout.app>
