<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Kendaraan - SIKANDIS</title>
    <link href="https://fonts.googleapis.com/css2?family=Times+New+Roman&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Times New Roman', Times, serif; 
            background: #e2e8f0; 
            color: #000; 
        }

        /* Control Bar */
        #control-bar {
            position: fixed; top: 0; left: 0; right: 0;
            background: #1e3a8a; color: #fff;
            padding: 12px 24px; display: flex;
            align-items: center; justify-content: space-between;
            z-index: 1000; font-family: sans-serif;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .btn {
            background: #22c55e; color: #fff;
            border: none; padding: 8px 16px;
            border-radius: 6px; font-weight: bold;
            cursor: pointer; display: inline-flex;
            align-items: center; gap: 6px;
            text-decoration: none; font-size: 14px;
        }
        .btn-close {
            background: #ef4444; 
        }
        .btn-group {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* Print Container */
        .print-container {
            background: #fff;
            width: 210mm; /* A4 width */
            min-height: 297mm; /* A4 height */
            margin: 70px auto 20px auto;
            padding: 15mm;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            position: relative;
        }

        /* KOP SURAT */
        .kop-surat {
            display: flex;
            align-items: center;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 2px;
            position: relative;
        }
        .kop-surat::after {
            content: '';
            position: absolute;
            left: 0; right: 0; bottom: -4px;
            border-bottom: 1px solid #000;
        }
        .kop-logo-left {
            width: 80px;
            flex-shrink: 0;
        }
        .kop-logo-right {
            width: 80px;
            flex-shrink: 0;
            object-fit: contain;
        }
        .kop-text {
            flex-grow: 1;
            text-align: center;
            padding: 0 16px;
        }
        .kop-text h1 { font-size: 18px; margin: 0; text-transform: uppercase; letter-spacing: 1px; }
        .kop-text h2 { font-size: 20px; margin: 4px 0; text-transform: uppercase; letter-spacing: 1px; font-weight: bold; }
        .kop-text p { font-size: 12px; margin: 2px 0; }

        /* Report Metadata */
        .report-title {
            text-align: center;
            margin: 25px 0 15px;
        }
        .report-title h3 {
            font-size: 16px;
            text-transform: uppercase;
            text-decoration: underline;
            margin-bottom: 4px;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 15px;
            font-size: 12px;
        }
        .meta-table td { padding: 2px; vertical-align: top; }
        .meta-label { width: 130px; }

        /* Data Table */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin-bottom: 30px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: middle;
        }
        table.data-table th {
            background: #f8fafc;
            text-align: center;
            font-weight: bold;
        }

        /* Signatures */
        .signature-section {
            display: flex;
            justify-content: flex-end;
            margin-top: 40px;
        }
        .signature-box {
            width: 250px;
            text-align: center;
            font-size: 12px;
        }
        .signature-space {
            height: 70px;
        }

        /* Print Override */
        @media print {
            body { background: #fff; }
            #control-bar { display: none; }
            .print-container {
                margin: 0; box-shadow: none;
                width: auto; height: auto;
                padding: 0;
            }
            @page {
                size: A4 portrait;
                margin: 15mm;
            }
            table { page-break-inside: auto; }
            tr { page-break-inside: avoid; page-break-after: auto; }
            thead { display: table-header-group; }
            tfoot { display: table-footer-group; }
        }
    </style>
</head>
<body>

    <!-- Control Bar (Visible Only on Screen) -->
    <div id="control-bar">
        <div style="font-weight: bold;">Preview Laporan Data Kendaraan</div>
        <div class="btn-group">
            <button class="btn" onclick="window.print()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                Cetak ke PDF / Printer
            </button>
            <button class="btn btn-close" onclick="window.close()">Tutup</button>
        </div>
    </div>

    <!-- Printable Area -->
    <div class="print-container">
        
        <!-- KOP SURAT: Pemerintah Kota Bengkulu (Dapat disesuaikan) -->
        <div class="kop-surat">
            <img src="{{ asset('assets/images/logobkl.png') }}" alt="Logo Kiri" class="kop-logo-left">
            <div class="kop-text">
                <h1>PEMERINTAH KOTA BENGKULU</h1>
                <h2>DINAS KOMUNIKASI DAN INFORMATIKA</h2>
                <p>Jalan Basuki Rahmat Nomor 1, Kota Bengkulu, Kodepos 38221</p>
                <p>Telepon: (0736) 21123, Email: kominfo@bengkulukota.go.id</p>
            </div>
            <img src="{{ asset('assets/images/logo-kominfo.png') }}" alt="Logo Kominfo" class="kop-logo-right">
        </div>

        <div class="report-title">
            <h3>Daftar Inventaris Kendaraan Dinas</h3>
        </div>

        <table class="meta-table">
            <tr>
                <td class="meta-label">Dicetak Oleh</td>
                <td>: {{ auth()->user()->name }} ({{ auth()->user()->role->nama_role }})</td>
                <td class="meta-label">Tanggal Cetak</td>
                <td>: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}</td>
            </tr>
            <tr>
                <td class="meta-label">Filter Status</td>
                <td>: <span style="text-transform: capitalize">{{ $status }}</span></td>
                <td class="meta-label">Filter Baris (Pencarian)</td>
                <td>: {{ request('q') ? request('q') : 'Semua Data' }}</td>
            </tr>
        </table>

        <!-- Tabel Data Kendaraan -->
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 14%;">No. Polisi</th>
                    <th style="width: 20%;">Nama Kendaraan</th>
                    <th style="width: 15%;">Kategori & Penggunaan</th>
                    <th style="width: 15%;">Status Pajak</th>
                    <th style="width: 31%;">Data Pemegang Saat Ini</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kendaraans as $index => $k)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td style="text-align: center; font-weight: bold;">{{ $k->no_polisi }}</td>
                    <td>
                        {{ $k->nama_kendaraan }}<br>
                        <span style="font-size: 10px; color: #555;">Tahun: {{ $k->tahun }}</span>
                    </td>
                    <td style="text-align: center;">
                        {{ $k->kategori->nama_kategori ?? '-' }}<br>
                        <span style="text-transform: capitalize; font-size: 10px;">({{ $k->jenis_penggunaan }})</span>
                    </td>
                    <td style="text-align: center;">
                        @if($k->status_pajak == 'Aktif')
                            Aktif
                        @elseif($k->status_pajak == 'Hampir Jatuh Tempo')
                            Hampir Jatuh Tempo
                        @elseif($k->status_pajak == 'Telah Jatuh Tempo')
                            Jatuh Tempo
                        @else
                            Belum Diatur
                        @endif
                        @if($k->pajak)
                        <br><span style="font-size: 10px;">{{ \Carbon\Carbon::parse($k->pajak)->format('d/m/Y') }}</span>
                        @endif
                    </td>
                    <td>
                        @if($k->pemegangAktif && $k->pemegangAktif->pegawai)
                            <strong>{{ $k->pemegangAktif->pegawai->nama }}</strong><br>
                            <span style="font-size: 10px;">
                                NIP: {{ $k->pemegangAktif->pegawai->nip }}<br>
                                {{ $k->pemegangAktif->pegawai->jabatan }}
                                @if($k->pemegangAktif->pegawai->unit)
                                    - {{ $k->pemegangAktif->pegawai->unit->nama_unit }}
                                @endif
                            </span>
                        @else
                            <i style="color: #666; font-size: 11px;">Tidak ada pemegang (Standby/Pool)</i>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px;">Tidak ada data kendaraan yang cocok dengan filter.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Bagian Tanda Tangan -->
        <div class="signature-section">
            <div class="signature-box">
                <p>Bengkulu, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                <p>Petugas Inventaris / Admin,</p>
                <div class="signature-space"></div>
                <p style="text-decoration: underline; font-weight: bold;">{{ auth()->user()->name }}</p>
                <p>NIP. {{ auth()->user()->nip }}</p>
            </div>
        </div>

    </div>
</body>
</html>
