<!DOCTYPE html>

<html>

<head>
    <title>Reminder Kegiatan OSIS</title>
</head>

<body style="background-color: #1a0507; font-family: sans-serif; margin: 0; padding: 20px; color: #f8fafc;">
    <div
        style="display:none; white-space:nowrap; font-size:1px; line-height:1px; max-height:0px; max-width:0px; opacity:0; overflow:hidden;">
        Reminder Kegiatan {{ $event->title }}
    </div>

    <div
        style="max-width: 600px; margin: 0 auto; border: 1px solid #701a22; padding: 30px; border-radius: 12px; background-color: #571319;">

        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-bottom: 20px;">
            <tr>
                <td style="text-align: center; vertical-align: middle;">
                    <img src="{{$message->embed(public_path('image/logosmkn1trmj.png')) }}" alt="Logo SMKN 1 Tarumajaya" width="110"
                        height="110"
                        style="width: 110px; height: 110px; display: inline-block; vertical-align: middle; margin-right: 12px;">
                    <h2
                        style="color: #fff; margin: 0; text-transform: uppercase; letter-spacing: 1px; font-size: 18px; font-weight: 800; display: inline-block; vertical-align: middle;">
                        OSIS SMKN 1 Tarumajaya
                    </h2>
                </td>
            </tr>
        </table>

        <hr style="border: 0; border-top: 1px solid #701a22; margin-bottom: 20px;">

        <h3 style="color: #ffffff; margin: 0 0 10px 0; font-size: 16px;">
            Halo {{ $notifiable->name }},
        </h3>

        <p style="color: #fda4af; line-height: 1.6; margin-bottom: 15px; font-size: 14px;">
            Jangan sampai terlewat. Berikut adalah informasi kegiatan yang akan segera berlangsung dan memerlukan
            kehadiran Anda.
        </p>

        <div
            style="background-color: #3f0d12; border-left: 4px solid #f43f5e; padding: 18px; margin-bottom: 25px; border-radius: 6px;">

            <div style="margin-bottom: 12px;">
                <strong style="color: #ffffff; font-size: 16px;">
                    {{ $event->title }}
                </strong>
            </div>

            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                style="color: #ffe4e6; font-size: 14px; line-height: 1.6;">

                <tr>
                    <td style="width: 130px; color: #fda4af;">Kategori</td>
                    <td>: {{ ucfirst($event->category) }}</td>
                </tr>

                <tr>
                    <td style="color: #fda4af;">Waktu Absensi</td>
                    <td>: {{ $event->start_time }} - {{ $event->end_time }} WIB</td>
                </tr>

                @if ($event->date ?? false)
                    <tr>
                        <td style="color: #fda4af;">Tanggal</td>
                        <td>: {{ $event->date }}</td>
                    </tr>
                @endif

                <tr>
                    <td style="color: #fda4af; padding-top: 5px;">Status</td>
                    <td style="padding-top: 5px;">
                        :
                        <span
                            style="
                        color:#60a5fa;
                        font-weight:bold;
                        background-color:#1e3a8a;
                        padding:3px 8px;
                        border-radius:4px;
                        font-size:11px;
                        display:inline-block;
                        letter-spacing:0.5px;
                        text-transform:uppercase;">
                            Reminder Kegiatan
                        </span>
                    </td>
                </tr>

            </table>
        </div>

        <div
            style="
        background-color:#3f0d12;
        border-left:4px solid #22c55e;
        padding:18px;
        border-radius:6px;
        margin-bottom:25px;">

            <h4 style="margin:0 0 10px 0; color:#ffffff;">
                📱 Persiapan Absensi
            </h4>

            <p style="margin:0; color:#fecdd3; line-height:1.6; font-size:14px;">
                Mohon hadir tepat waktu dan siapkan
                <strong>QR Code Kartu Digital OSIS</strong>
                yang tersedia pada halaman profil sistem OSIS untuk dipindai oleh petugas presensi.
            </p>

        </div>

        <div style="text-align:center; margin:25px 0;">
            <a href="{{ $url }}"
                style="
                background-color:#e11d48;
                color:#ffffff;
                padding:12px 30px;
                text-decoration:none;
                border-radius:50px;
                font-weight:bold;
                font-size:14px;
                display:inline-block;">
                Lihat Detail Kegiatan
            </a>
        </div>

        <p style="color:#fda4af; line-height:1.5; margin:20px 0 0 0; font-size:13px; text-align:center; opacity:0.8;">
            Terima kasih atas partisipasi dan kedisiplinan seluruh pengurus OSIS SMKN 1 Tarumajaya.
        </p>

        <hr style="border:0; border-top:1px solid #701a22; margin-top:25px;">

        <p style="font-size:11px; color:#fff; opacity:0.7; text-align:center; margin:15px 0 0 0; line-height:1.5;">
            Sistem Informasi OSIS SMKN 1 Tarumajaya<br>
            © {{ date('Y') }} OSIS SMKN 1 Tarumajaya. All rights reserved.
        </p>

    </div>
</body>

</html>
