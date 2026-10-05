Inquiry Ekspor Baru

Nama: {{ $inquiry->full_name }}
Email: {{ $inquiry->email ?: '—' }}
Telepon / WhatsApp: {{ $inquiry->phone ?: '—' }}
Perusahaan: {{ $inquiry->company_name }}
Negara tujuan: {{ $inquiry->destination_country }}
Komoditas diminati: {{ $inquiry->commodity_interest }}
Estimasi volume: {{ $inquiry->volume_estimate }}
@if ($inquiry->additional_notes)
Catatan: {{ $inquiry->additional_notes }}
@endif

Tindak lanjuti inquiry ini di panel admin:
{{ route('filament.admin.resources.inquiries.edit', ['record' => $inquiry]) }}
