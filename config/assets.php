<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Placeholder Image Watermark
    |--------------------------------------------------------------------------
    |
    | PRD Section 7.2: selama foto asli dari client belum tersedia, seluruh
    | slot gambar konten menampilkan placeholder yang jelas ditandai sebagai
    | belum final. Matikan flag ini (via .env) setelah aset asli klien masuk
    | dan setiap model sudah punya path gambar yang valid.
    |
    */
    'placeholder_watermark' => env('SHOW_PLACEHOLDER_WATERMARK', true),

];
