<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Daftar anggota OSIS yang telah diterima — SMKN 1 Tarumajaya">
    <meta name="robots" content="noindex, nofollow">
    <title>Daftar Anggota OSIS Diterima — SMKN 1 Tarumajaya</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Times+New+Roman&family=Source+Serif+4:ital,wght@0,400;0,600;0,700;1,400&display=swap"
        rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        /* ── 1. Setup Halaman Khusus DomPDF & Cetak ── */
        @page {
            size: a4 portrait;
            margin: 40px 50px 40px 50px;
        }

        body {
            font-family: 'Times New Roman', Georgia, serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #000000;
            background-color: #ffffff;
        }

        .paper {
            width: 100%;
            background-color: #ffffff;
        }

        /* ── 2. Kop Surat Menyamping ── */
        table.kop-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2px;
            table-layout: fixed;
        }

        table.kop-table td {
            padding-bottom: 0.5rem;
            vertical-align: middle;
        }

        .kop-logo-cell {
            width: 15%;
            text-align: left;
        }

        .kop-logo {
            width: 80px;
            height: auto;
            object-fit: contain;
        }

        .kop-text-cell {
            width: 85%;
            text-align: left;
            padding-left: 10px;
        }

        .kop-nama {
            font-size: 15pt;
            font-weight: bold;
            color: #000000;
            letter-spacing: 0.02em;
            text-transform: uppercase;
            line-height: 1.2;
        }

        .kop-alamat {
            font-size: 8.5pt;
            color: #222222;
            line-height: 1.3;
            margin-top: 3px;
        }

        .kop-garis-tebal {
            border-top: 3px solid #000000;
            margin-top: 5px;
            margin-bottom: 2px;
            font-size: 0;
            line-height: 0;
        }

        .kop-garis-tipis {
            border-top: 1px solid #000000;
            margin-bottom: 0.8rem;
            font-size: 0;
            line-height: 0;
        }

        /* ── 3. Nomor Surat ── */
        .nomor-surat {
            margin-bottom: 1rem;
            font-size: 10.5pt;
        }

        .nomor-surat table {
            border-collapse: collapse;
            width: auto;
        }

        .nomor-surat td {
            padding: 2px 0;
            vertical-align: top;
        }

        .nomor-surat td.label-surat {
            width: 60pt;
        }

        .nomor-surat td.titik-surat {
            width: 15pt;
            text-align: center;
        }

        /* ── 4. Judul Dokumen ── */
        .judul-wrap {
            text-align: center;
            margin-top: 3rem;
            margin-bottom: 1rem;
        }

        .judul-dokumen {
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .judul-sub {
            font-size: 10.5pt;
            margin-top: 0.1rem;
        }

        /* ── 5. Pembuka & Penutup ── */
        .pembuka,
        .penutup {
            font-size: 11pt;
            text-align: justify;
            margin-bottom: 0.8rem;
            text-indent: 30pt;
        }

        /* ── 6. Table Data Siswa ── */
        .table-wrap {
            margin-bottom: 0.5rem;
            width: 100%;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10pt;
            table-layout: fixed;
        }

        table.data-table th {
            background-color: #f2f2f2;
            padding: 5px;
            font-weight: bold;
            text-align: center;
            border: 1px solid #000000;
            text-transform: uppercase;
            font-size: 9.5pt;
        }

        table.data-table th.left {
            text-align: left;
        }

        table.data-table td {
            padding: 6px 5px;
            border: 1px solid #000000;
            vertical-align: middle;
            word-wrap: break-word;
        }

        table.data-table td.center {
            text-align: center;
        }

        /* ── 7. Summary & TTD ── */
        .summary {
            margin-top: 0.4rem;
            font-size: 9.5pt;
            text-align: right;
            font-style: italic;
        }

        .ttd-section {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1.5rem;
            table-layout: fixed;
        }

        .ttd-section td {
            width: 45%;
            vertical-align: top;
            text-align: center;
            font-size: 10.5pt;
        }

        .ttd-section td.ttd-space {
            width: 10%;
        }

        .ttd-kota-tanggal {
            margin-bottom: 0.2rem;
        }

        .ttd-jabatan {
            font-weight: bold;
            margin-bottom: 50pt;
        }

        .ttd-nama {
            font-weight: bold;
            text-decoration: underline;
        }

        .ttd-nip {
            font-size: 10pt;
            margin-top: 0.1rem;
        }

        /* ── 8. SIMULASI MONITOR BROWSER ── */
        @media screen {
            body {
                background-color: #e0e0e0;
                padding: 20px 0;
            }

            .paper {
                width: 210mm;
                min-height: 297mm;
                padding: 40px 50px;
                margin: 0 auto;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            }
        }
    </style>
</head>

<body>

    @php
        $approvedMembers = $approvedMembers ?? collect([]);
        $count = $approvedMembers->count();
        $totalHalaman = $count <= 15 ? 1 : 1 + ceil(($count - 15) / 25);
        $terbilang = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh'];
        $labelLampiran = $totalHalaman <= 10 ? $terbilang[$totalHalaman] : $totalHalaman;
        $subJudulTambahan = '';
        if (isset($filter) && isset($value) && $filter != '') {
            $subJudulTambahan = ' Berdasarkan ' . ucfirst($filter) . ' ' . $value;
        }
    @endphp

    <article class="paper" role="main">
        <table style="width: 100%; border-collapse: collapse; table-layout: fixed;">
            <thead>
                <tr>
                    <td style="border: none; padding: 0;">
                        {{-- ══ KOP SURAT ══ --}}
                        <table class="kop-table">
                            <tr>
                                <td class="kop-logo-cell">
                                    <img class="kop-logo"
                                        src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('image/logosmkn1trmj.png'))) }}"
                                        alt="Logo Sekolah">
                                </td>
                                <td class="kop-text-cell">
                                    <p class="kop-nama">SMK Negeri 1 Tarumajaya</p>
                                    <p class="kop-alamat">
                                        Jl. Ifolia, Pusaka Rakyat, Kec. Tarumajaya, Kabupaten Bekasi, Jawa Barat
                                        17214<br>
                                        Telp: (021) 88982124 &nbsp;|&nbsp; Email: info@smkn1tarumajaya.sch.id
                                        &nbsp;|&nbsp; NPSN: 20244766
                                    </p>
                                </td>
                            </tr>
                        </table>
                        <div class="kop-garis-tebal"></div>
                        <div class="kop-garis-tipis"></div>
                    </td>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td style="border: none; padding: 0;">

                        @php
                            $approvedMembers = $approvedMembers ?? collect([]);
                            $count = $approvedMembers->count();

                            // 1. Logika Halaman & Lampiran
                            $totalHalaman = $count <= 15 ? 1 : 1 + ceil(($count - 15) / 25);
                            $terbilang = [
                                '',
                                'satu',
                                'dua',
                                'tiga',
                                'empat',
                                'lima',
                                'enam',
                                'tujuh',
                                'delapan',
                                'sembilan',
                                'sepuluh',
                            ];
                            $labelLampiran = $totalHalaman <= 10 ? $terbilang[$totalHalaman] : $totalHalaman;

                            // 2. Kustomisasi Judul tambahan jika ada filter
                            $subJudulTambahan = '';
                            if (isset($filter) && isset($value) && $filter != '') {
                                $subJudulTambahan = ' Berdasarkan ' . ucfirst($filter) . ' ' . $value;
                            }

                            // ── KUNCI PERBAIKAN: Mengubah Bulan Angka ke Romawi Manual (Anti-Error) ──
                            $bulanAngka = (int) date('m');
                            $romawiArray = [
                                '',
                                'I',
                                'II',
                                'III',
                                'IV',
                                'V',
                                'VI',
                                'VII',
                                'VIII',
                                'IX',
                                'X',
                                'XI',
                                'XII',
                            ];
                            $bulanRomawi = $romawiArray[$bulanAngka];

                            // 3. Menentukan Nomor Urut Surat Berdasarkan Opsi 1
                            $noSurat = '001';
                            if (isset($filter) && $filter === 'divisi' && isset($value)) {
                                $noSurat = 'OSIS-' . strtoupper(substr(str_replace(' ', '', $value), 0, 3));
                            } elseif (isset($filter) && $filter === 'kelas' && isset($value)) {
                                $noSurat = str_replace(' ', '-', strtoupper($value));
                            }
                        @endphp
                        {{-- ══ NOMOR SURAT ══ --}}
                        <div class="nomor-surat">
                            <table style="width: auto;">
                                <tr>
                                    <td style="width: 65px;">Nomor</td>
                                    <td style="width: 15px;" class="center">:</td>
                                    <td>
                                        <strong>{{ $noSurat }}/OSIS/SMKN1TRJ/{{ $bulanRomawi }}/{{ date('Y') }}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Lampiran</td>
                                    <td class="center">:</td>
                                    <td style="text-transform: capitalize;">{{ $totalHalaman }} ({{ $labelLampiran }})
                                        Lembar</td>
                                </tr>
                                <tr>
                                    <td>Perihal</td>
                                    <td class="center">:</td>
                                    <td><strong>Daftar Anggota OSIS yang Diterima{{ $subJudulTambahan }}</strong></td>
                                </tr>
                            </table>
                        </div>

                        {{-- ══ JUDUL DOKUMEN ══ --}}
                        <div class="judul-wrap">
                            <p class="judul-dokumen">Daftar Anggota OSIS yang Diterima</p>
                            <p class="judul-sub">
                                @php
                                    $tahunSekarang = (int) date('Y');
                                    $bulanSekarang = (int) date('m');

                                    // Jika bulan Juli (7) ke atas, berarti sudah masuk tahun ajaran baru
                                    if ($bulanSekarang >= 7) {
                                        $tahunAkademik = $tahunSekarang . '/' . ($tahunSekarang + 1);
                                    } else {
                                        // Jika bulan Januari - Juni, berarti masih ekor dari tahun ajaran lalu
                                        $tahunAkademik = $tahunSekarang - 1 . '/' . $tahunSekarang;
                                    }
                                @endphp
                                Tahun Pelajaran {{ $tahunAkademik }}
                            </p>
                            @if ($subJudulTambahan)
                                <p class="judul-sub" style="font-weight: bold; margin-top: 2px;">
                                    ({{ strtoupper($filter) }}: {{ $value }})</p>
                            @endif
                        </div>

                        {{-- ══ PEMBUKA ══ --}}
                        <p class="pembuka">
                            Berdasarkan hasil seleksi dokumen dan wawancara penerimaan pengurus Organisasi Siswa Intra
                            Sekolah (OSIS) SMK Negeri 1 Tarumajaya, dengan ini dikeluarkan daftar resmi nama-nama siswa
                            yang dinyatakan
                            lolos dan diterima menjadi bagian dari keanggotaan OSIS{{ $subJudulTambahan }} untuk
                            periode
                            tahun pelajaran aktif saat ini:
                        </p>

                        {{-- ══ TABEL DATA SISWA DINAMIS ══ --}}
                        <main class="table-wrap">
                            <table class="data-table" role="table">
                                <thead>
                                    <tr>
                                        <th scope="col" style="width: 8%;">No.</th>
                                        <th scope="col" style="width: 37%;">Nama Siswa</th>
                                        <th scope="col" style="width: 15%;">Kelas</th>
                                        <th scope="col" style="width: 25%;">Divisi / Bidang</th>
                                        <th scope="col" style="width: 15%;">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($approvedMembers as $index => $member)
                                        <tr style="page-break-inside: avoid;">
                                            <td class="center">{{ $index + 1 }}.</td>
                                            <td>{{ $member->nama ?? $member->nama_lengkap }}</td>
                                            <td class="center">{{ $member->kelas }}</td>
                                            <td class="center">{{ $member->divisi ?? $member->pilihan_divisi }}</td>
                                            <td class="center">Diterima</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="center"
                                                style="padding: 1.5rem; font-style: italic; color: #555;">
                                                Belum ada data anggota yang disetujui untuk kriteria ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <p class="summary">
                                Total Siswa Diterima: <strong>{{ $count }} Orang</strong>
                            </p>
                        </main>

                        {{-- ══ PENUTUP ══ --}}
                        <p class="penutup">
                            Demikian surat ketetapan hasil seleksi ini disampaikan untuk diketahui oleh seluruh calon
                            anggota pengurus dan dapat dipergunakan secara semestinya.
                        </p>

                        {{-- ══ TANDA TANGAN ══ --}}
                        <table class="ttd-section" style="page-break-inside: avoid; width: 100%;">
                            <tr>
                                <td style="width: 45%;">
                                    <p style="visibility: hidden;">Spacer</p>
                                    <p>Mengetahui,</p>
                                    <p class="ttd-jabatan">Kepala Sekolah <br> SMKN 1 Tarumajaya</p>
                                    <p class="ttd-nama" style="margin-top: 50pt;">
                                        ......................................................</p>
                                    <p class="ttd-nip">NIP. ......................................................</p>
                                </td>
                                <td style="width: 10%;"></td>
                                <td style="width: 45%;">
                                    <p class="ttd-kota-tanggal">Tarumajaya, {{ now()->locale('id')->isoFormat('D MMMM Y') }}</p>
                                    <p style="visibility: hidden;">Spacer</p>
                                    <p class="ttd-jabatan">Pembina OSIS <br> SMKN 1 Tarumajaya</p>
                                    <p class="ttd-nama" style="margin-top: 50pt;">
                                        ......................................................</p>
                                    <p class="ttd-nip">NIP. ......................................................</p>
                                </td>
                            </tr>
                        </table>

                    </td>
                </tr>
            </tbody>
        </table>
    </article>

    {{-- SCRIPT NATIVE PRINT BROWSER --}}
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                window.print();
            }, 500);
        });

        window.onafterprint = function() {
            window.close();
        };
    </script>
</body>

</html>
