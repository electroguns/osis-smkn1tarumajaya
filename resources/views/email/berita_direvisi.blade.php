<!DOCTYPE html>
<html>
<head>
    <title>Notifikasi Revisi Berita</title>
</head>
<body style="background-color: #1a0507; font-family: sans-serif; margin: 0; padding: 20px; color: #f8fafc;">
    <div style="display:none; white-space:nowrap; font-size:1px; line-height:1px; max-height:0px; max-width:0px; opacity:0; overflow:hidden;">
        Notification ID: revision-{{ uniqid() . '-' . time() }}
    </div>

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

        <h3 style="color: #ffffff; margin: 0 0 10px 0; font-size: 16px;">Halo Ketua Divisi Humas,</h3>
        <p style="color: #fda4af; line-height: 1.5; margin: 0 0 15px 0; font-size: 14px;">
            Pemberitahuan bahwa anggota divisi Anda, <b style="color: #ffffff;">{{ $anggota_divisi_humas->name }}</b>, telah selesai menyunting dan merevisi draf berita berikut:
        </p>
        
        <div style="background-color: #3f0d12; border-left: 4px solid #f43f5e; padding: 15px; margin-bottom: 25px; border-radius: 6px;">
            <div style="margin-bottom: 8px;">
                <strong style="color: #ffffff; font-size: 16px; line-height: 1.4;">"{{ $berita->title }}"</strong>
            </div>

            <p style="margin: 0; font-size: 13px; color: #fda4af;">
                Status saat ini: 
                <span style="color: #34d399; font-weight: bold; background-color: #064e3b; padding: 2px 8px; border-radius: 4px; font-size: 12px;">
                    MENUNGGU PENINJAUAN ULANG
                </span>
            </p>
        </div>

        <p style="color: #fda4af; line-height: 1.5; margin: 0 0 20px 0; font-size: 14px;">
            Silakan masuk ke sistem website OSIS SMKN 1 Tarumajaya untuk memeriksa hasil revisi, menyetujui publikasi, atau memberikan catatan kembali jika diperlukan.
        </p>

        <div style="text-align: center; margin: 25px 0;">
            <a href="{{ route('berita.index') }}" style="background-color: #e11d48; color: #ffffff; padding: 12px 30px; text-decoration: none; border-radius: 50px; font-weight: bold; font-size: 14px; display: inline-block;">Buka Hub Berita</a>
        </div>

        <hr style="border: 0; border-top: 1px solid #701a22; margin-top: 25px;">

        <p style="font-size: 11px; color: #fff; opacity: 0.7; text-align: center; margin: 15px 0 0 0; line-height: 1.5;">
            Sistem Informasi Berita OSIS SMKN 1 TARUMAJAYA<br>
            © {{ date('Y') }} OSIS SMKN 1 Tarumajaya. All rights reserved.
        </p>
    </div>
</body>
</html>