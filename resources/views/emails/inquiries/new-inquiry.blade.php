<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $inquiry->commodity_interest }} — Inquiry Ekspor Baru</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f1ea; font-family: Georgia, 'Times New Roman', serif; color:#2b2620;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f1ea; padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px; background-color:#ffffff; border:1px solid #d8d0bd;">
                    <tr>
                        <td style="padding:24px 32px; border-bottom:2px solid #7a5230;">
                            <p style="margin:0; font-size:11px; letter-spacing:0.08em; text-transform:uppercase; color:#7a5230;">Refi Spice — Notifikasi Internal</p>
                            <h1 style="margin:8px 0 0; font-size:22px; line-height:1.3; color:#2b2620;">Inquiry Ekspor Baru</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px 32px;">
                            <p style="margin:0 0 16px; font-size:14px; line-height:1.6;">
                                Sebuah permintaan inquiry baru diterima melalui formulir kontak di situs Refi Spice pada
                                {{ $inquiry->created_at?->clone()->timezone('Asia/Jakarta')->translatedFormat('d F Y, H:i') }} WIB.
                            </p>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:14px; line-height:1.6;">
                                <tr>
                                    <td style="padding:8px 0; width:40%; color:#7a5230; font-weight:bold; vertical-align:top;">Nama Lengkap</td>
                                    <td style="padding:8px 0;">{{ $inquiry->full_name }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:8px 0; color:#7a5230; font-weight:bold; vertical-align:top;">Email Buyer</td>
                                    <td style="padding:8px 0;">
                                        @if ($inquiry->email)
                                            <a href="mailto:{{ $inquiry->email }}" style="color:#7a5230;">{{ $inquiry->email }}</a>
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:8px 0; color:#7a5230; font-weight:bold; vertical-align:top;">Telepon / WhatsApp</td>
                                    <td style="padding:8px 0;">{{ $inquiry->phone ?: '—' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:8px 0; color:#7a5230; font-weight:bold; vertical-align:top;">Perusahaan</td>
                                    <td style="padding:8px 0;">{{ $inquiry->company_name }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:8px 0; color:#7a5230; font-weight:bold; vertical-align:top;">Negara Tujuan</td>
                                    <td style="padding:8px 0;">{{ $inquiry->destination_country }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:8px 0; color:#7a5230; font-weight:bold; vertical-align:top;">Komoditas Diminati</td>
                                    <td style="padding:8px 0;">{{ $inquiry->commodity_interest }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:8px 0; color:#7a5230; font-weight:bold; vertical-align:top;">Estimasi Volume</td>
                                    <td style="padding:8px 0;">{{ $inquiry->volume_estimate }}</td>
                                </tr>
                                @if ($inquiry->additional_notes)
                                    <tr>
                                        <td style="padding:8px 0; color:#7a5230; font-weight:bold; vertical-align:top;">Catatan Tambahan</td>
                                        <td style="padding:8px 0;">{{ $inquiry->additional_notes }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td style="padding:8px 0; color:#7a5230; font-weight:bold; vertical-align:top;">Alamat IP</td>
                                    <td style="padding:8px 0;">{{ $inquiry->ip_address ?? '—' }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:16px 32px 24px; border-top:1px solid #d8d0bd;">
                            <p style="margin:0 0 12px;">
                                <a href="{{ route('filament.admin.resources.inquiries.edit', ['record' => $inquiry]) }}" style="display:inline-block; padding:10px 16px; background-color:#7a5230; color:#ffffff; text-decoration:none; font-size:13px; font-weight:bold; border-radius:4px;">
                                    Buka Detail di Panel Admin
                                </a>
                            </p>
                            <p style="margin:0; font-size:12px; color:#7a6f5c;">
                                Tandai status inquiry ini (Baru / Dihubungi / Selesai) melalui admin panel Refi Spice.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
