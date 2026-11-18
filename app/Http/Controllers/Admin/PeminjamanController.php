<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PeminjamanRequest;
use App\Models\Penyetujuan;
use App\Models\StaffSarana;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * Class PeminjamanController (Admin)
 *
 * Mengelola seluruh verifikasi pengajuan peminjaman barang dari sisi Admin Sarana,
 * meliputi peninjauan daftar permohonan masuk, penyetujuan (approval) beserta
 * pembaruan stok inventaris, penolakan dengan pencatatan alasan, serta monitoring
 * barang yang sedang dipinjam atau terlambat dikembalikan.
 *
 * @package App\Http\Controllers\Admin
 */
class PeminjamanController extends Controller
{
    /**
     * Konstruktor controller.
     * Menerapkan middleware autentikasi dan pengecekan otorisasi role 'admin_sarana'.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::check() && Auth::user()->role !== 'admin_sarana') {
                return redirect()->route('user.dashboard')->with('error', 'Akses Ditolak. Halaman ini khusus Admin Sarana.');
            }
            return $next($request);
        });
    }

    /**
     * Menampilkan daftar peminjaman barang berdasarkan filter status tertentu.
     *
     * Aturan Filter Status:
     * - 'pending'        : Pengajuan baru yang membutuhkan persetujuan/penolakan admin.
     * - 'sedang_dipinjam': Pengajuan yang telah disetujui dan barang sedang digunakan peminjam.
     * - 'terlambat'      : Peminjaman aktif yang tanggal kembalinya telah melewati hari ini.
     * - 'ditolak'        : Pengajuan yang ditolak admin beserta alasannya.
     * - 'dibatalkan'     : Pengajuan yang dibatalkan langsung oleh peminjam sebelum diproses.
     *
     * @param Request $request Data request yang memuat parameter query string '?status='.
     * @return View Tampilan antarmuka manajemen peminjaman admin.
     */
    public function index(Request $request): View
    {
        $status = $request->get('status', 'pending');
        $today = Carbon::today()->format('Y-m-d');

        $query = PeminjamanRequest::with(['user', 'barang', 'pengembalian']);

        // Penerapan logika filter kondisi status
        if ($status === 'pending') {
            $query->where('status', 'pending');
        } elseif ($status === 'disetujui' || $status === 'sedang_dipinjam') {
            $query->whereIn('status', ['disetujui', 'dipinjam', 'sedang_dipinjam']);
        } elseif ($status === 'terlambat') {
            $query->whereIn('status', ['disetujui', 'dipinjam', 'sedang_dipinjam'])
                  ->where('tanggal_kembali', '<', $today);
        } elseif ($status === 'ditolak') {
            $query->where('status', 'ditolak');
        } elseif ($status === 'dibatalkan') {
            $query->where('status', 'dibatalkan');
        } else {
            $query->where('status', $status);
        }

        $peminjaman = $query->orderBy('created_at', 'desc')->get();

        // Hitung akumulasi statistik counter untuk badge navigasi tab
        $counts = [
            'pending'         => PeminjamanRequest::where('status', 'pending')->count(),
            'sedang_dipinjam' => PeminjamanRequest::whereIn('status', ['disetujui', 'dipinjam', 'sedang_dipinjam'])->count(),
            'terlambat'       => PeminjamanRequest::whereIn('status', ['disetujui', 'dipinjam', 'sedang_dipinjam'])->where('tanggal_kembali', '<', $today)->count(),
            'ditolak'         => PeminjamanRequest::where('status', 'ditolak')->count(),
            'dibatalkan'      => PeminjamanRequest::where('status', 'dibatalkan')->count(),
        ];

        return view('admin.peminjaman.index', compact('peminjaman', 'status', 'counts'));
    }

    /**
     * Menyetujui (Approve) pengajuan peminjaman barang oleh Admin Sarana.
     *
     * Aturan Bisnis & Efek Samping:
     * 1. Status pengajuan diubah menjadi 'disetujui'.
     * 2. Membuat catatan pada tabel `penyetujuan` sebagai bukti legalitas admin yang menyetujui.
     * 3. Pembaruan Stok Fisik Barang:
     *    - `jumlah_baik` berkurang 1 (karena barang dibawa peminjam).
     *    - `jumlah_kurang_baik` bertambah 1 sebagai penanda aset yang sedang beredar.
     * 4. Memicu Database Trigger `trg_after_update_peminjaman` untuk mencatat audit log.
     *
     * @param int|string $id ID pengajuan peminjaman yang akan disetujui.
     * @return RedirectResponse Pengalihan kembali ke halaman sebelumnya dengan pesan sukses.
     */
    public function approve($id): RedirectResponse
    {
        $peminjaman = PeminjamanRequest::findOrFail($id);
        $peminjaman->update(['status' => 'disetujui']);

        // 1. Rekam entri penyetujuan sesuai ERD jika tabel penyetujuan aktif
        if (Schema::hasTable('penyetujuan')) {
            $adminStaff = Schema::hasTable('staff_sarana') ? StaffSarana::first() : null;
            $nip = Auth::user()->pegawai?->nip ?? $adminStaff?->nip ?? 'NIP002';
            Penyetujuan::updateOrCreate(
                ['peminjaman_id' => $peminjaman->id],
                [
                    'id_peminjaman' => $peminjaman->id_peminjaman ?? ('PMJ-' . str_pad($peminjaman->id, 6, '0', STR_PAD_LEFT)),
                    'nip'           => $nip,
                    'status'        => 'disetujui',
                    'catatan'       => 'Peminjaman disetujui oleh admin sarana.',
                ]
            );
        }

        // 2. Perbarui stok ketersediaan barang inventaris
        $barang = $peminjaman->barang;
        if ($barang) {
            $barang->jumlah_baik = max(0, $barang->jumlah_baik - 1);
            $barang->jumlah_kurang_baik = $barang->jumlah_kurang_baik + 1;
            $barang->save();
        }

        return back()->with('success', 'Peminjaman berhasil disetujui! Barang kini berstatus Sedang Dipinjam.');
    }

    /**
     * Menolak (Reject) pengajuan peminjaman barang disertai alasan penolakan.
     *
     * Aturan Bisnis & Efek Samping:
     * 1. Status pengajuan diubah menjadi 'ditolak'.
     * 2. Alasan penolakan disimpan pada kolom `alasan_penolakan` agar peminjam dapat mengetahui alasannya.
     * 3. Stok barang TIDAK berkurang karena barang tidak pernah dipinjamkan.
     * 4. Memicu Database Trigger `trg_after_update_peminjaman` untuk mencatat log penolakan.
     *
     * @param Request $request Data request yang memuat string 'alasan'.
     * @param int|string $id ID pengajuan peminjaman yang akan ditolak.
     * @return RedirectResponse Pengalihan kembali ke halaman sebelumnya dengan pesan informasi.
     */
    public function reject(Request $request, $id): RedirectResponse
    {
        $peminjaman = PeminjamanRequest::findOrFail($id);
        $peminjaman->update([
            'status'           => 'ditolak',
            'alasan_penolakan' => $request->alasan,
        ]);

        // Rekam entri pada tabel penyetujuan
        if (Schema::hasTable('penyetujuan')) {
            $adminStaff = Schema::hasTable('staff_sarana') ? StaffSarana::first() : null;
            $nip = Auth::user()->pegawai?->nip ?? $adminStaff?->nip ?? 'NIP002';
            Penyetujuan::updateOrCreate(
                ['peminjaman_id' => $peminjaman->id],
                [
                    'id_peminjaman' => $peminjaman->id_peminjaman ?? ('PMJ-' . str_pad($peminjaman->id, 6, '0', STR_PAD_LEFT)),
                    'nip'           => $nip,
                    'status'        => 'ditolak',
                    'catatan'       => $request->alasan ?: 'Peminjaman ditolak.',
                ]
            );
        }

        return back()->with('success', 'Peminjaman berhasil ditolak!');
    }

    /**
     * Menampilkan riwayat peminjaman barang untuk pengguna terkait.
     *
     * @return View Tampilan riwayat peminjaman.
     */
    public function history(): View
    {
        $peminjaman = PeminjamanRequest::with(['user', 'barang'])
            ->where('username', Auth::user()->username)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.peminjaman.history', compact('peminjaman'));
    }
}
