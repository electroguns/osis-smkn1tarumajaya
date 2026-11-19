<!DOCTYPE html>
<html>
<head>
    <title>{{ $action }} - Notifikasi Laporan Kegiatan</title>
</head>
<body style="background-color: #1a0507; font-family: sans-serif; margin: 0; padding: 20px; color: #f8fafc;">
    <div style="display:none; white-space:nowrap; font-size:1px; line-height:1px; max-height:0px; max-width:0px; opacity:0; overflow:hidden;">
        Notification ID: report-{{ $action }}-{{ $report->id }}-{{ time() }}
    </div>

    @php
        $statusText = 'DRAFT';
        $statusBg = '#78350f'; // Coklat/Amber
        $statusColor = '#fbbf24';
        $pembuka = 'Ada laporan kegiatan baru yang telah diajukan dan membutuhkan tindakan Anda.';

        if ($action === 'purpose') {
            $statusText = 'MENUNGGU APPROVAL SEKRETARIS';
            $statusBg = '#7c2d12'; // Oranye Pekat
            $statusColor = '#ff7849';
            $pembuka = 'Laporan kegiatan berikut telah diajukan dan kini menunggu verifikasi serta persetujuan dari Sekretaris.';
        } elseif ($action === 'approved') {
            $statusText = 'DISETUJUI';
            $statusBg = '#064e3b'; // Hijau Gelap
            $statusColor = '#34d399';
            $pembuka = 'Kabar baik! Laporan kegiatan berikut telah ditinjau dan dinyatakan disetujui.';
        } elseif ($action === 'rejected') {
            $statusText = 'DITOLAK / REVISI';
            $statusBg = '#7f1d1d'; // Merah Darah
            $statusColor = '#f87171';
            $pembuka = 'Laporan kegiatan berikut ditolak dan memerlukan beberapa perbaikan. Silakan periksa catatan revisi di sistem.';
        } elseif ($action === 'updated') {
            $statusText = 'LAPORAN DIREVISI';
            $statusBg = '#1e3a8a'; // Biru Navy
            $statusColor = '#60a5fa';
            $pembuka = 'Laporan kegiatan telah diperbarui/direvisi oleh PIC terkait dan siap untuk diperiksa kembali.';
        }
    @endphp

    <div style="max-width: 600px; margin: 0 auto; border: 1px solid #701a22; padding: 30px; border-radius: 12px; background-color: #571319;">
        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-bottom: 20px;">
            <tr>
                <td style="text-align: center; vertical-align: middle;">
                    <img src="{{ asset('image/logosmkn1trmj.png')}}" alt="Logo SMKN 1 Tarumajaya" style="width: 110px; height: 110px; object-fit: contain; vertical-align: middle; margin-right: 12px; display: inline-block;">
                    <h2 style="color: #fff; margin: 0; text-transform: uppercase; letter-spacing: 1px; font-size: 18px; font-weight: 800; display: inline-block; vertical-align: middle;">OSIS SMKN 1 Tarumajaya</h2>
                </td>
            </tr>
        </table>

        <hr style="border: 0; border-top: 1px solid #701a22; margin-bottom: 20px;">

        <h3 style="color: #ffffff; margin: 0 0 10px 0; font-size: 16px;">Halo {{ $notifiable->name }},</h3>
        <p style="color: #fda4af; line-height: 1.5; margin: 0 0 15px 0; font-size: 14px;">
            {{ $pembuka }}
        </p>
        
        <div style="background-color: #3f0d12; border-left: 4px solid #f43f5e; padding: 18px; margin-bottom: 25px; border-radius: 6px;">
            <div style="margin-bottom: 12px;">
                <strong style="color: #ffffff; font-size: 16px; line-height: 1.4;">{{ $report->activity_name }}</strong>
            </div>
            
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="color: #ffe4e6; font-size: 14px; line-height: 1.6;">
                <tr>
                    <td style="width: 130px; padding-bottom: 5px; color: #fda4af;">Tanggal Kegiatan</td>
                    <td style="padding-bottom: 5px;">: {{ $report->activity_date }}</td>
                </tr>
                <tr>
                    <td style="padding-bottom: 5px; color: #fda4af;">Divisi Pengaju</td>
                    <td style="padding-bottom: 5px;">: {{ $report->division }}</td>
                </tr>
                <tr>
                    <td style="padding-bottom: 12px; color: #fda4af;">PIC Kegiatan</td>
                    <td style="padding-bottom: 12px;">: {{ $report->pic }}</td>
                </tr>
                <tr>
                    <td style="color: #fda4af; padding-top: 5px; vertical-align: middle;">Status Laporan</td>
                    <td style="padding-top: 5px;">: 
                        <span style="color: {{ $statusColor }}; font-weight: bold; background-color: {{ $statusBg }}; padding: 3px 8px; border-radius: 4px; font-size: 11px; display: inline-block; letter-spacing: 0.5px; text-transform: uppercase;">
                            {{ $statusText }}
                        </span>
                    </td>
                </tr>
            </table>
        </div>

        <div style="text-align: center; margin: 25px 0;">
            <a href="{{ $url }}" style="background-color: #e11d48; color: #ffffff; padding: 12px 30px; text-decoration: none; border-radius: 50px; font-weight: bold; font-size: 14px; display: inline-block;">Lihat Detail Laporan</a>
        </div>

        <p style="color: #fda4af; line-height: 1.5; margin: 20px 0 0 0; font-size: 13px; text-align: center; opacity: 0.8;">
            Terima kasih telah menggunakan sistem informasi OSIS SMKN 1 Tarumajaya.
        </p>

        <hr style="border: 0; border-top: 1px solid #701a22; margin-top: 25px;">

        <p style="font-size: 11px; color: #fff; opacity: 0.7; text-align: center; margin: 15px 0 0 0; line-height: 1.5;">
            Sistem Informasi Laporan OSIS SMKN 1 TARUMAJAYA<br>
            © {{ date('Y') }} OSIS SMKN 1 Tarumajaya. All rights reserved.
        </p>
    </div>
</body>
</html>