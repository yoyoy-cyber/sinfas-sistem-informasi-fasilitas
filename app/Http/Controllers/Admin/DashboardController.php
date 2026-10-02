<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\PeminjamanRequest;
use App\Models\Pengembalian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // 🔒 KEAMANAN: Cek role admin_sarana
        if (Auth::check() && Auth::user()->role !== 'admin_sarana') {
            return redirect()->route('user.dashboard')->with('error', 'Anda tidak memiliki akses ke halaman Admin.');
        }

        // Statistik untuk admin
        $stats = [
            'total_barang' => Barang::count(),
            'tersedia' => Barang::where('jumlah_baik', '>', 0)->count(),
            'dipinjam' => Barang::where('jumlah_baik', 0)->where('jumlah_kurang_baik', '>', 0)->count(),
            'rusak' => Barang::where('jumlah_rusak_berat', '>', 0)->where('jumlah_baik', 0)->count(),
            'pending_requests' => PeminjamanRequest::where('status', 'pending')->count(),
            'pending_pengembalian' => Pengembalian::where('status', 'pending')->count(),
        ];

        // Ambil data peminjaman PENDING saja (belum disetujui/ditolak)
        $permintaanTertunda = PeminjamanRequest::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        // Ambil data pengembalian PENDING saja (menunggu verifikasi)
        $pengembalianTertunda = Pengembalian::with(['user', 'peminjaman'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.dashboard', compact('stats', 'permintaanTertunda', 'pengembalianTertunda'));
    }
}