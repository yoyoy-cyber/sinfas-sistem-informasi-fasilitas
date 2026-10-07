<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\PeminjamanRequest;
use App\Models\Pengembalian;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::check() && Auth::user()->role !== 'admin_sarana') {
                return redirect()->route('user.dashboard')->with('error', 'Akses Ditolak.');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $jenisLaporan = $request->get('jenis_laporan', 'frekuensi');
        $kategoris = Kategori::all();

        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $idKategori = $request->get('id_kategori');
        $kondisiBarang = $request->get('kondisi_barang');
        $statusPengembalian = $request->get('status_pengembalian');
        $rolePeminjam = $request->get('role_peminjam');

        $dataLaporan = $this->getLaporanData($jenisLaporan, $request);

        return view('admin.laporan.index', array_merge([
            'jenisLaporan' => $jenisLaporan,
            'kategoris' => $kategoris,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'idKategori' => $idKategori,
            'kondisiBarang' => $kondisiBarang,
            'statusPengembalian' => $statusPengembalian,
            'rolePeminjam' => $rolePeminjam,
        ], $dataLaporan));
    }

    public function cetak(Request $request)
    {
        $jenisLaporan = $request->get('jenis_laporan', 'frekuensi');
        $kategoriInfo = null;
        if ($request->filled('id_kategori')) {
            $kategoriInfo = Kategori::find($request->id_kategori);
        }

        $dataLaporan = $this->getLaporanData($jenisLaporan, $request);

        return view('admin.laporan.cetak', array_merge([
            'jenisLaporan' => $jenisLaporan,
            'kategoriInfo' => $kategoriInfo,
            'request' => $request,
            'tanggalCetak' => Carbon::now()->locale('id')->isoFormat('D MMMM YYYY'),
            'adminUser' => Auth::user(),
        ], $dataLaporan));
    }

    private function buildTimeBuckets($startDate, $endDate, $defaultYear = null)
    {
        $defaultYear = $defaultYear ?: Carbon::now()->year;

        // Jika start & end date diisi lengkap
        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->endOfDay();

            if ($start->greaterThan($end)) {
                $tmp = $start;
                $start = $end;
                $end = $tmp;
            }

            // Jika dalam 1 bulan kalender yang sama dan rentang <= 31 hari -> Tampilkan per hari
            if ($start->format('Y-m') === $end->format('Y-m') && $start->diffInDays($end) <= 31) {
                $buckets = [];
                $curr = $start->copy();
                while ($curr->lessThanOrEqualTo($end)) {
                    $key = $curr->format('Y-m-d');
                    $buckets[$key] = [
                        'label' => $curr->locale('id')->isoFormat('D MMM'),
                        'key' => $key,
                    ];
                    $curr->addDay();
                }
                return [
                    'type' => 'daily',
                    'periodLabel' => $start->locale('id')->isoFormat('D MMM YYYY') . ' - ' . $end->locale('id')->isoFormat('D MMM YYYY'),
                    'buckets' => $buckets,
                ];
            }

            // Rentang beberapa bulan -> Tampilkan per bulan
            $buckets = [];
            $curr = $start->copy()->startOfMonth();
            $endMonth = $end->copy()->startOfMonth();
            while ($curr->lessThanOrEqualTo($endMonth)) {
                $key = $curr->format('Y-m');
                $buckets[$key] = [
                    'label' => $curr->locale('id')->isoFormat('MMM YYYY'),
                    'key' => $key,
                ];
                $curr->addMonth();
            }
            return [
                'type' => 'monthly',
                'periodLabel' => $start->locale('id')->isoFormat('D MMM YYYY') . ' - ' . $end->locale('id')->isoFormat('D MMM YYYY'),
                'buckets' => $buckets,
            ];
        }

        // Jika hanya ada start_date
        if ($startDate) {
            $start = Carbon::parse($startDate)->startOfMonth();
            $end = Carbon::now()->endOfMonth();
            if ($start->greaterThan($end)) {
                $end = $start->copy()->addMonths(5)->endOfMonth();
            }
            $buckets = [];
            $curr = $start->copy();
            while ($curr->lessThanOrEqualTo($end)) {
                $key = $curr->format('Y-m');
                $buckets[$key] = [
                    'label' => $curr->locale('id')->isoFormat('MMM YYYY'),
                    'key' => $key,
                ];
                $curr->addMonth();
            }
            return [
                'type' => 'monthly',
                'periodLabel' => 'Mulai ' . $start->locale('id')->isoFormat('MMMM YYYY'),
                'buckets' => $buckets,
            ];
        }

        // Jika hanya ada end_date
        if ($endDate) {
            $end = Carbon::parse($endDate)->endOfMonth();
            $start = $end->copy()->subMonths(11)->startOfMonth();
            $buckets = [];
            $curr = $start->copy();
            while ($curr->lessThanOrEqualTo($end)) {
                $key = $curr->format('Y-m');
                $buckets[$key] = [
                    'label' => $curr->locale('id')->isoFormat('MMM YYYY'),
                    'key' => $key,
                ];
                $curr->addMonth();
            }
            return [
                'type' => 'monthly',
                'periodLabel' => 'Hingga ' . $end->locale('id')->isoFormat('MMMM YYYY'),
                'buckets' => $buckets,
            ];
        }

        // Default: 12 Bulan Sepanjang Tahun Berjalan
        $buckets = [];
        for ($m = 1; $m <= 12; $m++) {
            $date = Carbon::createFromDate($defaultYear, $m, 1);
            $key = $date->format('Y-m');
            $buckets[$key] = [
                'label' => $date->locale('id')->isoFormat('MMM'),
                'key' => $key,
            ];
        }
        return [
            'type' => 'monthly',
            'periodLabel' => 'Tahun ' . $defaultYear . ' (12 Bulan)',
            'buckets' => $buckets,
        ];
    }

    private function getLaporanData($jenisLaporan, Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $idKategori = $request->get('id_kategori');

        if ($jenisLaporan === 'frekuensi') {
            // LAPORAN #1: Frekuensi & Tren Peminjaman Barang
            $query = PeminjamanRequest::with(['barang.kategori', 'user'])
                ->select(
                    'kode_barang',
                    'nama_barang',
                    DB::raw('COUNT(*) as total_dipinjam'),
                    DB::raw('SUM(CASE WHEN status = "selesai" THEN 1 ELSE 0 END) as total_selesai'),
                    DB::raw('SUM(CASE WHEN status = "disetujui" THEN 1 ELSE 0 END) as total_aktif'),
                    DB::raw('SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as total_pending'),
                    DB::raw('SUM(CASE WHEN status = "ditolak" THEN 1 ELSE 0 END) as total_ditolak')
                )
                ->groupBy('kode_barang', 'nama_barang');

            if ($startDate) {
                $query->whereDate('tanggal_pinjam', '>=', $startDate);
            }
            if ($endDate) {
                $query->whereDate('tanggal_pinjam', '<=', $endDate);
            }
            if ($idKategori) {
                $query->whereHas('barang', function ($q) use ($idKategori) {
                    $q->where('id_kategori', $idKategori);
                });
            }

            $items = $query->orderBy('total_dipinjam', 'desc')->get();

            foreach ($items as $item) {
                $item->barang_detail = Barang::with('kategori')->find($item->kode_barang);
            }

            $totalFrekuensiGlobal = $items->sum('total_dipinjam');
            $totalBarangDipinjamUnique = $items->count();

            // GENERATE DATA GRAFIK BULANAN / PERIODIK
            $timeData = $this->buildTimeBuckets($startDate, $endDate);
            $buckets = $timeData['buckets'];
            $isDaily = ($timeData['type'] === 'daily');

            $totalDipinjamSeries = array_fill_keys(array_keys($buckets), 0);
            $selesaiSeries = array_fill_keys(array_keys($buckets), 0);
            $aktifSeries = array_fill_keys(array_keys($buckets), 0);

            $chartQuery = PeminjamanRequest::query();
            if ($startDate) {
                $chartQuery->whereDate('tanggal_pinjam', '>=', $startDate);
            } else {
                $chartQuery->whereYear('tanggal_pinjam', Carbon::now()->year);
            }
            if ($endDate) {
                $chartQuery->whereDate('tanggal_pinjam', '<=', $endDate);
            }
            if ($idKategori) {
                $chartQuery->whereHas('barang', function ($q) use ($idKategori) {
                    $q->where('id_kategori', $idKategori);
                });
            }
            $peminjamanRecords = $chartQuery->get();

            $topBarangCounts = [];
            foreach ($peminjamanRecords as $p) {
                $dateKey = $isDaily ? Carbon::parse($p->tanggal_pinjam)->format('Y-m-d') : Carbon::parse($p->tanggal_pinjam)->format('Y-m');
                if (isset($buckets[$dateKey])) {
                    $totalDipinjamSeries[$dateKey]++;
                    if ($p->status === 'selesai') {
                        $selesaiSeries[$dateKey]++;
                    } elseif ($p->status === 'disetujui') {
                        $aktifSeries[$dateKey]++;
                    }
                }
                $bName = $p->nama_barang ?: 'Barang';
                $topBarangCounts[$bName] = ($topBarangCounts[$bName] ?? 0) + 1;
            }

            arsort($topBarangCounts);
            $top5Barang = array_slice($topBarangCounts, 0, 5, true);

            $chartData = [
                'type' => 'frekuensi',
                'periodLabel' => $timeData['periodLabel'],
                'timeType' => $timeData['type'],
                'labels' => array_values(array_map(fn($b) => $b['label'], $buckets)),
                'datasets' => [
                    [
                        'label' => 'Total Dipinjam',
                        'data' => array_values($totalDipinjamSeries),
                        'borderColor' => '#3b82f6',
                        'backgroundColor' => 'rgba(59, 130, 246, 0.15)',
                        'borderWidth' => 3,
                        'pointBackgroundColor' => '#3b82f6',
                        'pointRadius' => 4,
                        'pointHoverRadius' => 6,
                        'fill' => true,
                        'tension' => 0.35
                    ],
                    [
                        'label' => 'Selesai (Kembali)',
                        'data' => array_values($selesaiSeries),
                        'borderColor' => '#10b981',
                        'backgroundColor' => 'rgba(16, 185, 129, 0.12)',
                        'borderWidth' => 2.5,
                        'pointBackgroundColor' => '#10b981',
                        'pointRadius' => 4,
                        'pointHoverRadius' => 6,
                        'fill' => true,
                        'tension' => 0.35
                    ],
                    [
                        'label' => 'Sedang Dipinjam',
                        'data' => array_values($aktifSeries),
                        'borderColor' => '#f59e0b',
                        'backgroundColor' => 'rgba(245, 158, 11, 0.12)',
                        'borderWidth' => 2.5,
                        'pointBackgroundColor' => '#f59e0b',
                        'pointRadius' => 4,
                        'pointHoverRadius' => 6,
                        'fill' => true,
                        'tension' => 0.35
                    ]
                ],
                'topBarangLabels' => array_keys($top5Barang),
                'topBarangData' => array_values($top5Barang),
            ];

            return [
                'items' => $items,
                'totalFrekuensiGlobal' => $totalFrekuensiGlobal,
                'totalBarangDipinjamUnique' => $totalBarangDipinjamUnique,
                'chartData' => $chartData,
            ];

        } elseif ($jenisLaporan === 'kerusakan') {
            // LAPORAN #2: Kerusakan & Riwayat Kondisi Barang
            $kondisi = $request->get('kondisi_barang');

            $query = Pengembalian::with(['barang.kategori', 'user', 'peminjaman']);

            if ($kondisi) {
                $query->where('kondisi_pengembalian', $kondisi);
            } else {
                $query->whereIn('kondisi_pengembalian', ['rusak_ringan', 'rusak_berat']);
            }

            if ($startDate) {
                $query->whereDate('tanggal_pengembalian', '>=', $startDate);
            }
            if ($endDate) {
                $query->whereDate('tanggal_pengembalian', '<=', $endDate);
            }
            if ($idKategori) {
                $query->whereHas('barang', function ($q) use ($idKategori) {
                    $q->where('id_kategori', $idKategori);
                });
            }

            $items = $query->orderBy('tanggal_pengembalian', 'desc')->get();

            $totalRusakRingan = $items->where('kondisi_pengembalian', 'rusak_ringan')->count();
            $totalRusakBerat = $items->where('kondisi_pengembalian', 'rusak_berat')->count();

            // GENERATE DATA GRAFIK BULANAN / PERIODIK
            $timeData = $this->buildTimeBuckets($startDate, $endDate);
            $buckets = $timeData['buckets'];
            $isDaily = ($timeData['type'] === 'daily');

            $rusakRinganSeries = array_fill_keys(array_keys($buckets), 0);
            $rusakBeratSeries = array_fill_keys(array_keys($buckets), 0);
            $totalRusakSeries = array_fill_keys(array_keys($buckets), 0);

            $kategoriRusakCounts = [];
            foreach ($items as $k) {
                $dateKey = $isDaily ? Carbon::parse($k->tanggal_pengembalian)->format('Y-m-d') : Carbon::parse($k->tanggal_pengembalian)->format('Y-m');
                if (isset($buckets[$dateKey])) {
                    $totalRusakSeries[$dateKey]++;
                    if ($k->kondisi_pengembalian === 'rusak_ringan') {
                        $rusakRinganSeries[$dateKey]++;
                    } elseif ($k->kondisi_pengembalian === 'rusak_berat') {
                        $rusakBeratSeries[$dateKey]++;
                    }
                }
                $katName = $k->barang->kategori->nama_kategori ?? 'Lainnya';
                $kategoriRusakCounts[$katName] = ($kategoriRusakCounts[$katName] ?? 0) + 1;
            }

            $chartData = [
                'type' => 'kerusakan',
                'periodLabel' => $timeData['periodLabel'],
                'timeType' => $timeData['type'],
                'labels' => array_values(array_map(fn($b) => $b['label'], $buckets)),
                'datasets' => [
                    [
                        'label' => 'Total Rusak',
                        'data' => array_values($totalRusakSeries),
                        'backgroundColor' => 'rgba(239, 68, 68, 0.85)',
                        'borderColor' => '#dc2626',
                        'borderWidth' => 1.5,
                        'borderRadius' => 6,
                    ],
                    [
                        'label' => 'Rusak Ringan',
                        'data' => array_values($rusakRinganSeries),
                        'backgroundColor' => 'rgba(245, 158, 11, 0.85)',
                        'borderColor' => '#d97706',
                        'borderWidth' => 1.5,
                        'borderRadius' => 6,
                    ],
                    [
                        'label' => 'Rusak Berat',
                        'data' => array_values($rusakBeratSeries),
                        'backgroundColor' => 'rgba(153, 27, 27, 0.85)',
                        'borderColor' => '#7f1d1d',
                        'borderWidth' => 1.5,
                        'borderRadius' => 6,
                    ]
                ],
                'pieLabels' => ['Rusak Ringan', 'Rusak Berat'],
                'pieData' => [$totalRusakRingan, $totalRusakBerat],
                'pieColors' => ['#f59e0b', '#dc2626'],
                'kategoriLabels' => array_keys($kategoriRusakCounts),
                'kategoriData' => array_values($kategoriRusakCounts),
            ];

            return [
                'items' => $items,
                'totalRusakRingan' => $totalRusakRingan,
                'totalRusakBerat' => $totalRusakBerat,
                'totalKasusKerusakan' => $items->count(),
                'chartData' => $chartData,
            ];

        } elseif ($jenisLaporan === 'keterlambatan') {
            // LAPORAN #3: Keterlambatan Pengembalian Barang
            $rolePeminjam = $request->get('role_peminjam');
            $statusFilter = $request->get('status_pengembalian');

            $query = PeminjamanRequest::with(['user', 'barang.kategori', 'pengembalian']);

            if ($rolePeminjam) {
                $query->where('role_peminjam', $rolePeminjam);
            }

            if ($startDate) {
                $query->whereDate('tanggal_kembali', '>=', $startDate);
            }
            if ($endDate) {
                $query->whereDate('tanggal_kembali', '<=', $endDate);
            }

            $allRequests = $query->whereIn('status', ['disetujui', 'selesai'])->get();

            $items = collect();

            foreach ($allRequests as $p) {
                $isLate = false;
                $durasiHari = 0;
                $statusKeterlambatan = '';
                $tglPengembalian = null;

                if ($p->pengembalian && $p->pengembalian->status === 'disetujui') {
                    $tglPengembalian = $p->pengembalian->tanggal_pengembalian;
                    $kembaliExpected = Carbon::parse($p->tanggal_kembali);
                    $kembaliActual = Carbon::parse($tglPengembalian);

                    if ($kembaliActual->greaterThan($kembaliExpected)) {
                        $isLate = true;
                        $durasiHari = $kembaliExpected->diffInDays($kembaliActual);
                        $statusKeterlambatan = 'Dikembalikan Terlambat';
                    }
                } elseif ($p->status === 'disetujui') {
                    $kembaliExpected = Carbon::parse($p->tanggal_kembali);
                    $now = Carbon::now()->startOfDay();

                    if ($now->greaterThan($kembaliExpected)) {
                        $isLate = true;
                        $durasiHari = $kembaliExpected->diffInDays($now);
                        $statusKeterlambatan = 'Belum Dikembalikan (Terlambat)';
                    }
                }

                if ($isLate) {
                    $p->durasi_terlambat = $durasiHari;
                    $p->status_keterlambatan = $statusKeterlambatan;
                    $p->tgl_realisasi_pengembalian = $tglPengembalian;

                    if ($statusFilter) {
                        if ($statusFilter === 'dikembalikan_terlambat' && $statusKeterlambatan === 'Dikembalikan Terlambat') {
                            $items->push($p);
                        } elseif ($statusFilter === 'belum_dikembalikan' && str_contains($statusKeterlambatan, 'Belum')) {
                            $items->push($p);
                        }
                    } else {
                        $items->push($p);
                    }
                }
            }

            // GENERATE DATA GRAFIK BULANAN / PERIODIK
            $timeData = $this->buildTimeBuckets($startDate, $endDate);
            $buckets = $timeData['buckets'];
            $isDaily = ($timeData['type'] === 'daily');

            $siswaLateSeries = array_fill_keys(array_keys($buckets), 0);
            $guruLateSeries = array_fill_keys(array_keys($buckets), 0);
            $totalLateSeries = array_fill_keys(array_keys($buckets), 0);

            $dikembalikanTerlambatCount = 0;
            $belumDikembalikanCount = 0;

            foreach ($items as $item) {
                $dateRef = $item->tgl_realisasi_pengembalian ?: $item->tanggal_kembali;
                $dateKey = $isDaily ? Carbon::parse($dateRef)->format('Y-m-d') : Carbon::parse($dateRef)->format('Y-m');

                if (isset($buckets[$dateKey])) {
                    $totalLateSeries[$dateKey]++;
                    if ($item->role_peminjam === 'siswa') {
                        $siswaLateSeries[$dateKey]++;
                    } else {
                        $guruLateSeries[$dateKey]++;
                    }
                }

                if (str_contains($item->status_keterlambatan, 'Belum')) {
                    $belumDikembalikanCount++;
                } else {
                    $dikembalikanTerlambatCount++;
                }
            }

            $chartData = [
                'type' => 'keterlambatan',
                'periodLabel' => $timeData['periodLabel'],
                'timeType' => $timeData['type'],
                'labels' => array_values(array_map(fn($b) => $b['label'], $buckets)),
                'datasets' => [
                    [
                        'label' => 'Total Terlambat',
                        'data' => array_values($totalLateSeries),
                        'backgroundColor' => 'rgba(239, 68, 68, 0.85)',
                        'borderColor' => '#dc2626',
                        'borderWidth' => 1.5,
                        'borderRadius' => 6,
                    ],
                    [
                        'label' => 'Siswa Terlambat',
                        'data' => array_values($siswaLateSeries),
                        'backgroundColor' => 'rgba(245, 158, 11, 0.85)',
                        'borderColor' => '#d97706',
                        'borderWidth' => 1.5,
                        'borderRadius' => 6,
                    ],
                    [
                        'label' => 'Guru / Staff',
                        'data' => array_values($guruLateSeries),
                        'backgroundColor' => 'rgba(59, 130, 246, 0.85)',
                        'borderColor' => '#2563eb',
                        'borderWidth' => 1.5,
                        'borderRadius' => 6,
                    ]
                ],
                'pieLabels' => ['Sudah Kembali (Terlambat)', 'Belum Dikembalikan'],
                'pieData' => [$dikembalikanTerlambatCount, $belumDikembalikanCount],
                'pieColors' => ['#f59e0b', '#ef4444'],
            ];

            return [
                'items' => $items,
                'totalTerlambat' => $items->count(),
                'totalSiswaTerlambat' => $items->where('role_peminjam', 'siswa')->count(),
                'totalGuruTerlambat' => $items->where('role_peminjam', 'guru')->count(),
                'chartData' => $chartData,
            ];

        } else {
            // LAPORAN #4: Rekapitulasi Stok & Status Inventaris (Stock Opname)
            $query = Barang::with('kategori');

            if ($idKategori) {
                $query->where('id_kategori', $idKategori);
            }

            $items = $query->orderBy('nama_barang', 'asc')->get();

            $totalBaik = $items->sum('jumlah_baik');
            $totalKurangBaik = $items->sum('jumlah_kurang_baik');
            $totalRusakBerat = $items->sum('jumlah_rusak_berat');
            $totalStokKeseluruhan = $totalBaik + $totalKurangBaik + $totalRusakBerat;

            // Kategori Breakdown untuk Bar Chart
            $kategorisList = Kategori::with('barangs')->get();
            if ($idKategori) {
                $kategorisList = $kategorisList->where('id_kategori', $idKategori);
            }

            $katLabels = [];
            $katBaik = [];
            $katDipinjam = [];
            $katRusak = [];

            foreach ($kategorisList as $k) {
                $katLabels[] = $k->nama_kategori;
                $katBaik[] = $k->barangs->sum('jumlah_baik');
                $katDipinjam[] = $k->barangs->sum('jumlah_kurang_baik');
                $katRusak[] = $k->barangs->sum('jumlah_rusak_berat');
            }

            $chartData = [
                'type' => 'stok',
                'periodLabel' => 'Status Stok Real-Time ' . ($idKategori && count($items) > 0 && isset($items[0]->kategori) ? '('.$items[0]->kategori->nama_kategori.')' : 'Semua Kategori'),
                'timeType' => 'category',
                'labels' => $katLabels,
                'datasets' => [
                    [
                        'label' => 'Stok Baik (Tersedia)',
                        'data' => $katBaik,
                        'backgroundColor' => 'rgba(16, 185, 129, 0.85)',
                        'borderColor' => '#059669',
                        'borderWidth' => 1.5,
                        'borderRadius' => 6,
                    ],
                    [
                        'label' => 'Sedang Dipinjam',
                        'data' => $katDipinjam,
                        'backgroundColor' => 'rgba(245, 158, 11, 0.85)',
                        'borderColor' => '#d97706',
                        'borderWidth' => 1.5,
                        'borderRadius' => 6,
                    ],
                    [
                        'label' => 'Rusak Berat',
                        'data' => $katRusak,
                        'backgroundColor' => 'rgba(239, 68, 68, 0.85)',
                        'borderColor' => '#dc2626',
                        'borderWidth' => 1.5,
                        'borderRadius' => 6,
                    ]
                ],
                'pieLabels' => ['Stok Baik (Tersedia)', 'Sedang Dipinjam', 'Rusak Berat'],
                'pieData' => [$totalBaik, $totalKurangBaik, $totalRusakBerat],
                'pieColors' => ['#10b981', '#f59e0b', '#ef4444'],
            ];

            return [
                'items' => $items,
                'totalBaik' => $totalBaik,
                'totalKurangBaik' => $totalKurangBaik,
                'totalRusakBerat' => $totalRusakBerat,
                'totalStokKeseluruhan' => $totalStokKeseluruhan,
                'chartData' => $chartData,
            ];
        }
    }
}
