<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Aspirasi Siswa</title>
    <style>
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header h2 {
            margin: 0;
            font-size: 20px;
            text-transform: uppercase;
        }

        .header p {
            margin: 5px 0 0 0;
            color: #666;
        }

        .meta-filter {
            margin-bottom: 15px;
            background-color: #f8fafc;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #e2e8f0;
        }

        .meta-filter table {
            width: 100%;
        }

        .meta-filter td {
            padding: 2px 5px;
            border: none;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #cbd5e1;
            padding: 8px 10px;
            text-align: left;
            vertical-align: top;
        }

        .data-table th {
            background-color: #f1f5f9;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
        }

        .badge {
            padding: 3px 7px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
            display: inline-block;
            text-transform: uppercase;
        }

        .bg-apresiasi {
            bg-color: #d1fae5;
            color: #065f46;
        }

        .bg-kritik {
            bg-color: #fef3c7;
            color: #92400e;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-progress {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .status-resolved {
            background-color: #d1fae5;
            color: #065f46;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #94a3b8;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>Laporan Aspirasi Siswa</h2>
        <p>OSIS SMKN 1 Tarumajaya</p>
    </div>

    <div class="meta-filter">
        <strong>Filter Aktif:</strong>
        <table>
            <tr>
                <td width="15%">Kategori</td>
                <td>: {{ ucwords($filters['kategori']) }}</td>
                <td width="15%">Periode Tanggal</td>
                <td>: {{ $filters['tgl_mulai'] }} s/d {{ $filters['tgl_akhir'] }}</td>
            </tr>
            <tr>
                <td>Status</td>
                <td>: {{ ucwords(str_replace('_', ' ', $filters['status'])) }}</td>
                <td>Total Data</td>
                <td>: {{ $suaras->count() }} Aspirasi</td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%" class="text-center">ID</th>
                <th width="15%">Kategori</th>
                <th width="50%">Saran / Pesan</th>
                <th width="15%" class="text-center">Tanggal</th>
                <th width="15%" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($suaras as $suara)
                <tr>
                    <td class="text-center">#{{ $suara->id }}</td>
                    <td>
                        <span
                            class="badge {{ strtolower($suara->kategori) === 'apresiasi' ? 'bg-apresiasi' : 'bg-kritik' }}">
                            {{ $suara->kategori }}
                        </span>
                    </td>
                    <td>
                        @if (strtolower($suara->kategori) === 'apresiasi')
                            <strong>Rating: {{ $suara->rating }}/10</strong><br>
                        @endif
                        "{{ $suara->saran }}"
                    </td>
                    <td class="text-center">{{ $suara->created_at->format('d M Y') }}</td>
                    <td class="text-center">
                        <span
                            class="badge {{ $suara->status == 'pending' ? 'status-pending' : ($suara->status == 'on_progress' ? 'status-progress' : 'status-resolved') }}">
                            {{ str_replace('_', ' ', $suara->status) }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center" style="padding: 30px;">Tidak ada data aspirasi yang memenuhi
                        kriteria filter.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak oleh: Sekretaris OSIS pada {{ now()->format('d M Y H:i:s') }}
    </div>

</body>
</html>