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

            return [
                'items' => $items,
                'totalFrekuensiGlobal' => $totalFrekuensiGlobal,
                'totalBarangDipinjamUnique' => $totalBarangDipinjamUnique,
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

            return [
                'items' => $items,
                'totalRusakRingan' => $totalRusakRingan,
                'totalRusakBerat' => $totalRusakBerat,
                'totalKasusKerusakan' => $items->count(),
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

            return [
                'items' => $items,
                'totalTerlambat' => $items->count(),
                'totalSiswaTerlambat' => $items->where('role_peminjam', 'siswa')->count(),
                'totalGuruTerlambat' => $items->where('role_peminjam', 'guru')->count(),
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

            return [
                'items' => $items,
                'totalBaik' => $totalBaik,
                'totalKurangBaik' => $totalKurangBaik,
                'totalRusakBerat' => $totalRusakBerat,
                'totalStokKeseluruhan' => $totalStokKeseluruhan,
            ];
        }
    }
}
