<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Sarana - SINFAS</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            background: #fff;
            padding: 20px 40px;
            font-size: 12pt;
        }

        /* Toolbar hanya muncul di layar, tidak di hasil cetak */
        .print-toolbar {
            background: #1e3a5f;
            color: white;
            padding: 12px 20px;
            margin: -20px -40px 30px -40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }

        .btn-action {
            background: #2563eb;
            color: white;
            border: none;
            padding: 8px 18px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-action:hover {
            background: #1d4ed8;
        }

        .btn-back {
            background: #64748b;
        }

        .btn-back:hover {
            background: #475569;
        }

        /* Header Kop Surat */
        .kop-surat {
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 3px double #000;
            padding-bottom: 12px;
            margin-bottom: 20px;
            text-align: center;
            position: relative;
        }

        .kop-title h1 {
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }

        .kop-title h2 {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .kop-title p {
            font-size: 10pt;
            font-style: italic;
            color: #333;
        }

        /* Title Document */
        .doc-header {
            text-align: center;
            margin-bottom: 25px;
        }

        .doc-header h3 {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
            margin-bottom: 6px;
        }

        .doc-meta {
            font-size: 10.5pt;
            margin-top: 5px;
            color: #222;
        }

        /* Table Cetak */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th, td {
            border: 1px solid #000;
            padding: 8px 10px;
            font-size: 10.5pt;
            vertical-align: middle;
        }

        th {
            background-color: #f2f2f2 !important;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10pt;
            text-align: center;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        tr {
            page-break-inside: avoid;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }

        /* Tanda Tangan */
        .ttd-section {
            display: flex;
            justify-content: flex-end;
            margin-top: 40px;
            page-break-inside: avoid;
        }

        .ttd-box {
            width: 250px;
            text-align: center;
            font-size: 11pt;
        }

        .ttd-space {
            height: 75px;
        }

        .ttd-name {
            font-weight: bold;
            text-decoration: underline;
        }

        @media print {
            .print-toolbar {
                display: none !important;
            }

            body {
                padding: 0;
            }

            th {
                background-color: #e5e7eb !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

    <!-- TOOLBAR INTERAKTIF DI LAYAR -->
    <div class="print-toolbar">
        <div>
            <strong>SINFAS - Modul Laporan Siap Cetak (PDF)</strong>
        </div>
        <div style="display: flex; gap: 10px;">
            <button onclick="window.print()" class="btn-action">
                🖨️ Cetak / Save to PDF
            </button>
            <button onclick="window.close()" class="btn-action btn-back">
                ❌ Tutup
            </button>
        </div>
    </div>

    <!-- KOP SURAT RESMI -->
    <div class="kop-surat">
        <div class="kop-title">
            <h1>SISTEM INFORMASI FASILITAS (SINFAS)</h1>
            <h2>STAFF SARANA DAN PRASARANA SEKOLAH</h2>
            <p>Jl. Pendidikan No. 1, Telp: (021) 555-0199 | Website: sinfas.sch.id | Email: sarana@sinfas.sch.id</p>
        </div>
    </div>

    <!-- JUDUL LAPORAN -->
    <div class="doc-header">
        @if($jenisLaporan === 'frekuensi')
            <h3>LAPORAN BARANG PALING SERING DIPINJAM</h3>
        @elseif($jenisLaporan === 'kerusakan')
            <h3>LAPORAN RIWAYAT BARANG RUSAK</h3>
        @elseif($jenisLaporan === 'keterlambatan')
            <h3>LAPORAN KETERLAMBATAN PENGEMBALIAN BARANG</h3>
        @else
            <h3>LAPORAN STOK & KONDISI BARANG</h3>
        @endif

        <div class="doc-meta">
            @if($request->filled('start_date') || $request->filled('end_date'))
                <span>Periode: {{ $request->start_date ?: 'Awal' }} s/d {{ $request->end_date ?: 'Sekarang' }}</span> | 
            @endif
            @if($kategoriInfo)
                <span>Kategori: {{ $kategoriInfo->nama_kategori }}</span> | 
            @endif
            <span>Tanggal Cetak: {{ $tanggalCetak }}</span>
        </div>
    </div>

    <!-- ISI TABEL LAPORAN -->
    @if(count($items) > 0)
        <table>
            @if($jenisLaporan === 'frekuensi')
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 15%;">Kode Barang</th>
                        <th style="width: 30%;">Nama Barang</th>
                        <th style="width: 20%;">Kategori</th>
                        <th style="width: 15%;">Total Dipinjam</th>
                        <th style="width: 15%;">Selesai (Kembali)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $idx => $item)
                        <tr>
                            <td class="text-center">{{ $idx + 1 }}</td>
                            <td class="text-center"><strong>{{ $item->kode_barang }}</strong></td>
                            <td>{{ $item->nama_barang }}</td>
                            <td>{{ $item->barang_detail->kategori->nama_kategori ?? '-' }}</td>
                            <td class="text-center font-bold">{{ $item->total_dipinjam }} kali</td>
                            <td class="text-center">{{ $item->total_selesai }} kali</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="4" class="text-right font-bold">TOTAL FREKUENSI GLOBAL:</td>
                        <td colspan="2" class="text-center font-bold">{{ $totalFrekuensiGlobal }} kali peminjaman</td>
                    </tr>
                </tbody>

            @elseif($jenisLaporan === 'kerusakan')
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 15%;">Tgl Lapor</th>
                        <th style="width: 25%;">Nama Barang (Kode)</th>
                        <th style="width: 20%;">Peminjam</th>
                        <th style="width: 15%;">Kondisi</th>
                        <th style="width: 20%;">Catatan Laporan Kerusakan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $idx => $item)
                        <tr>
                            <td class="text-center">{{ $idx + 1 }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal_pengembalian)->locale('id')->isoFormat('DD/MM/YYYY') }}</td>
                            <td>
                                <strong>{{ $item->nama_barang }}</strong><br>
                                <small>({{ $item->kode_barang }})</small>
                            </td>
                            <td>{{ $item->user->nama_lengkap ?? $item->peminjaman->nama_peminjam ?? '-' }}</td>
                            <td class="text-center font-bold">
                                {{ strtoupper(str_replace('_', ' ', $item->kondisi_pengembalian)) }}
                            </td>
                            <td>{{ $item->catatan_kondisi ?: ($item->keterangan ?: '-') }}</td>
                        </tr>
                    @endforeach
                </tbody>

            @elseif($jenisLaporan === 'keterlambatan')
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 20%;">Nama Peminjam</th>
                        <th style="width: 15%;">Role</th>
                        <th style="width: 25%;">Barang</th>
                        <th style="width: 15%;">Tgl Tenggat</th>
                        <th style="width: 20%;">Keterlambatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $idx => $item)
                        <tr>
                            <td class="text-center">{{ $idx + 1 }}</td>
                            <td>
                                <strong>{{ $item->nama_peminjam }}</strong><br>
                                <small>{{ $item->user->nis_nip ?? '' }}</small>
                            </td>
                            <td class="text-center">{{ ucfirst($item->role_peminjam) }}</td>
                            <td>{{ $item->nama_barang }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal_kembali)->locale('id')->isoFormat('DD/MM/YYYY') }}</td>
                            <td class="text-center font-bold" style="color: #b91c1c;">
                                +{{ $item->durasi_terlambat }} Hari
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            @elseif($jenisLaporan === 'stok')
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 15%;">Kode Barang</th>
                        <th style="width: 30%;">Nama Barang</th>
                        <th style="width: 15%;">Baik (Tersedia)</th>
                        <th style="width: 15%;">Dipinjam</th>
                        <th style="width: 10%;">Rusak</th>
                        <th style="width: 10%;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $idx => $item)
                        <tr>
                            <td class="text-center">{{ $idx + 1 }}</td>
                            <td class="text-center"><strong>{{ $item->kode_barang }}</strong></td>
                            <td>{{ $item->nama_barang }}</td>
                            <td class="text-center">{{ $item->jumlah_baik }}</td>
                            <td class="text-center">{{ $item->jumlah_kurang_baik }}</td>
                            <td class="text-center">{{ $item->jumlah_rusak_berat }}</td>
                            <td class="text-center font-bold">
                                {{ $item->jumlah_baik + $item->jumlah_kurang_baik + $item->jumlah_rusak_berat }}
                            </td>
                        </tr>
                    @endforeach
                    <tr style="background: #f9fafb;">
                        <td colspan="3" class="text-right font-bold">TOTAL KESELURUHAN UNIT ASET:</td>
                        <td class="text-center font-bold">{{ $totalBaik }}</td>
                        <td class="text-center font-bold">{{ $totalKurangBaik }}</td>
                        <td class="text-center font-bold">{{ $totalRusakBerat }}</td>
                        <td class="text-center font-bold">{{ $totalStokKeseluruhan }}</td>
                    </tr>
                </tbody>
            @endif
        </table>
    @else
        <div style="text-align: center; padding: 40px; border: 1px dashed #999; margin-bottom: 30px;">
            <p style="font-style: italic;">Tidak ada data laporan yang sesuai dengan kriteria yang dipilih.</p>
        </div>
    @endif

    <!-- TANDA TANGAN STAFF SARANA -->
    <div class="ttd-section">
        <div class="ttd-box">
            <p>Mengetahui / Disetujui Oleh,</p>
            <p><strong>Staff Sarana & Prasarana</strong></p>
            <div class="ttd-space"></div>
            <p class="ttd-name">{{ $adminUser->nama_lengkap ?? 'Administrator Sarana' }}</p>
            <p>NIP. {{ $adminUser->nis_nip ?? '------------------------' }}</p>
        </div>
    </div>

    <script>
        // Otomatis panggil print dialog setelah halaman termuat
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 500);
        });
    </script>
</body>
</html>
