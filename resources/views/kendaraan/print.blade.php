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
            width: 297mm; /* Standard A4 Landscape width */
            min-height: 210mm; 
            margin: 80px auto 40px auto;
            padding: 20mm;
            box-shadow: 0 0 20px rgba(0,0,0,0.15);
            position: relative;
        }

        /* Ensure group header and table stay together */
        .group-wrapper {
            break-inside: avoid;
            page-break-inside: avoid;
            margin-bottom: 20px;
        }

        .opd-header {
            background: #f1f5f9;
            padding: 8px 12px;
            border: 1px solid #000;
            border-bottom: none;
            font-weight: bold;
            font-size: 13px;
            text-transform: uppercase;
        }

        /* Data Table */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin-bottom: 0; /* Managed by wrapper */
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
            text-transform: uppercase;
        }

        /* Signatures */
        .signature-section {
            display: flex;
            justify-content: flex-end;
            margin-top: 30px;
            break-inside: avoid;
        }
        .signature-box {
            width: 250px;
            text-align: center;
            font-size: 12px;
        }
        .signature-space {
            height: 60px;
        }

        /* Print Override */
        @media print {
            body { background: #fff !important; }
            #control-bar { display: none !important; }
            .print-container {
                margin: 0 !important; 
                box-shadow: none !important;
                width: 100% !important; 
                height: auto !important;
                padding: 0 !important;
            }
            @page {
                size: A4 landscape;
                margin: 15mm 10mm;
            }
            .group-wrapper {
                margin-bottom: 30px;
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
        <div style="font-weight: bold;">Preview Laporan Klasifikasi Kendaraan (Landscape)</div>
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
        
        <!-- KOP SURAT -->
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
            <h3>Daftar Inventaris Kendaraan Dinas (Berdasarkan Unit Kerja)</h3>
        </div>

        <table class="meta-table">
            <tr>
                <td class="meta-label">Dicetak Oleh</td>
                <td>: {{ auth()->user()->name }} ({{ auth()->user()->role->nama_role }})</td>
                <td class="meta-label">Tanggal Cetak</td>
                <td>: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}</td>
            </tr>
            <tr>
                <td class="meta-label">Status Kendaraan</td>
                <td>: <span style="text-transform: capitalize">{{ $status }}</span></td>
                <td class="meta-label">Status Pajak</td>
                <td>: {{ $filterLabels['Status Pajak'] ?? 'Semua' }}</td>
            </tr>
            <tr>
                <td class="meta-label">Kategori</td>
                <td>: {{ $filterLabels['Kategori'] ?? 'Semua' }}</td>
                <td class="meta-label">Total Kendaraan</td>
                <td>: {{ $totalCount }} Unit</td>
            </tr>
        </table>

        @php $globalIndex = 1; @endphp

        @foreach($groupedKendaraans as $opdName => $vehicles)
            <div class="group-wrapper">
                <div class="opd-header">UNIT KERJA: {{ $opdName }}</div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 40px;">No</th>
                            <th style="width: 100px;">No. Polisi</th>
                            <th style="width: 180px;">Nama Kendaraan</th>
                            <th style="width: 100px;">Kategori</th>
                            <th style="width: 100px;">Penggunaan</th>
                            <th style="width: 110px;">Status Pajak</th>
                            <th>Personel Pemegang Saat Ini (Nama / NIP / Jabatan)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vehicles as $k)
                        <tr>
                            <td style="text-align: center;">{{ $globalIndex++ }}</td>
                            <td style="text-align: center; font-weight: bold;">{{ $k->no_polisi }}</td>
                            <td>
                                {{ $k->nama_kendaraan }}
                                <div style="font-size: 9px; color: #666;">Tahun Produksi: {{ $k->tahun }}</div>
                            </td>
                            <td style="text-align: center;">{{ $k->kategori->nama_kategori ?? '-' }}</td>
                            <td style="text-align: center; text-transform: capitalize;">{{ $k->jenis_penggunaan }}</td>
                            <td style="text-align: center;">
                                {{ $k->status_pajak }}
                                @if($k->pajak)
                                    <div style="font-size: 9px;">{{ \Carbon\Carbon::parse($k->pajak)->format('d/m/Y') }}</div>
                                @endif
                            </td>
                            <td>
                                @if($k->pemegangAktif)
                                    <strong>{{ $k->pemegangAktif->display_name }}</strong><br>
                                    <span style="font-size: 10px;">
                                        NIP: {{ $k->pemegangAktif->nip ?? ($k->pemegangAktif->pegawai->nip ?? '-') }}<br>
                                        {{ $k->pemegangAktif->display_jabatan }}
                                    </span>
                                @else
                                    <i style="color: #888;">(Standby / Belum Ada Pemegang)</i>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach

        @if($groupedKendaraans->isEmpty())
            <div style="text-align: center; padding: 40px; border: 1px solid #000; font-style: italic;">
                Tidak ada data kendaraan yang ditemukan untuk kriteria ini.
            </div>
        @endif

        <div class="signature-section">
            <div class="signature-box">
                <p>Bengkulu, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                <p>Petugas Inventaris / Admin,</p>
                <div class="signature-space"></div>
                <p style="text-decoration: underline; font-weight: bold;">{{ auth()->user()->name }}</p>
                <p>NIP. {{ auth()->user()->nip ?? '-' }}</p>
            </div>
        </div>

    </div>
</body>
</html>
