<!DOCTYPE html>
<html>

<head>
    <title>Update Status Berita: Diperlukan Perbaikan</title>
</head>

<body style="background-color: #1a0507; font-family: sans-serif; margin: 0; padding: 20px; color: #f8fafc;">
    <div
        style="display:none; white-space:nowrap; font-size:1px; line-height:1px; max-height:0px; max-width:0px; opacity:0; overflow:hidden;">
        Notification ID: news-rejected-{{ $berita->id }}-{{ time() }}
    </div>

    <div
        style="max-width: 600px; margin: 0 auto; border: 1px solid #701a22; padding: 30px; border-radius: 12px; background-color: #571319;">
        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-bottom: 20px;">
            <tr>
                <td style="text-align: center; vertical-align: middle;">
                    <img src="{{ asset('image/logosmkn1trmj.png') }}" alt="Logo SMKN 1 Tarumajaya"
                        style="width: 110px; height: 110px; object-fit: contain; vertical-align: middle; margin-right: 12px; display: inline-block;">
                    <h2
                        style="color: #fff; margin: 0; text-transform: uppercase; letter-spacing: 1px; font-size: 18px; font-weight: 800; display: inline-block; vertical-align: middle;">
                        OSIS SMKN 1 Tarumajaya</h2>
                </td>
            </tr>
        </table>

        <hr style="border: 0; border-top: 1px solid #701a22; margin-bottom: 20px;">

        <h3 style="color: #ffffff; margin: 0 0 10px 0; font-size: 16px;">Halo, {{ $notifiable->name }}!</h3>
        <p style="color: #fda4af; line-height: 1.5; margin: 0 0 15px 0; font-size: 14px;">
            Berita yang Anda ajukan saat ini dikembalikan menjadi <b>Draft</b> untuk keperluan revisi dan perbaikan.
        </p>

        <div
            style="background-color: #3f0d12; border-left: 4px solid #f87171; padding: 15px; margin-bottom: 25px; border-radius: 6px;">
            <div style="margin-bottom: 12px;">
                <span
                    style="color: #f87171; font-weight: bold; background-color: #7f1d1d; padding: 2px 8px; border-radius: 4px; font-size: 10px; letter-spacing: 0.5px; text-transform: uppercase; display: inline-block; margin-bottom: 6px;">
                    PERLU REVISI
                </span>
                <strong
                    style="color: #ffffff; font-size: 16px; line-height: 1.4; display: block;">"{{ $berita->title }}"</strong>
            </div>

            <div style="border-top: 1px dashed #701a22; margin-top: 10px; padding-top: 10px;">
                <span
                    style="color: #fda4af; font-size: 12px; font-weight: bold; display: block; margin-bottom: 4px;">Alasan
                    Penolakan / Catatan Perbaikan:</span>
                <p style="color: #ffe4e6; line-height: 1.5; font-style: italic; margin: 0; font-size: 14px;">
                    "{{ $alasan }}"
                </p>
            </div>
        </div>

        <p style="color: #fda4af; line-height: 1.5; margin: 0 0 20px 0; font-size: 14px;">
            Silakan lakukan perbaikan sesuai dengan catatan di atas melalui sistem website OSIS, lalu ajukan kembali
            draf Anda agar bisa kami tinjau ulang.
        </p>

        <div style="text-align: center; margin: 25px 0;">
            <a href="{{ route('berita.edit', $berita->slug) }}"
                style="background-color: #e11d48; color: #ffffff; padding: 12px 30px; text-decoration: none; border-radius: 50px; font-weight: bold; font-size: 14px; display: inline-block;">Edit
                Berita Anda</a>
        </div>

        <hr style="border: 0; border-top: 1px solid #701a22; margin-top: 25px;">

        <p
            style="font-size: 11px; color: #fff; opacity: 0.7; text-align: center; margin: 15px 0 0 0; line-height: 1.5;">
            Sistem Informasi Berita OSIS SMKN 1 TARUMAJAYA<br>
            © {{ date('Y') }} OSIS SMKN 1 Tarumajaya. All rights reserved.
        </p>
    </div>
</body>

</html>
