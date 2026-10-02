<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PeminjamanRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PeminjamanController extends Controller
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
        $status = $request->get('status', 'pending');
        $today = Carbon::today()->format('Y-m-d');

        $query = PeminjamanRequest::with(['user', 'barang', 'pengembalian']);

        if ($status === 'pending') {
            $query->where('status', 'pending');
        } elseif ($status === 'disetujui' || $status === 'sedang_dipinjam') {
            $query->whereIn('status', ['disetujui', 'dipinjam', 'sedang_dipinjam']);
        } elseif ($status === 'terlambat') {
            $query->whereIn('status', ['disetujui', 'dipinjam', 'sedang_dipinjam'])
                  ->where('tanggal_kembali', '<', $today);
        } elseif ($status === 'ditolak') {
            $query->where('status', 'ditolak');
        } else {
            $query->where('status', $status);
        }

        $peminjaman = $query->orderBy('created_at', 'desc')->get();

        // Hitung statistik counter untuk tab
        $counts = [
            'pending' => PeminjamanRequest::where('status', 'pending')->count(),
            'sedang_dipinjam' => PeminjamanRequest::whereIn('status', ['disetujui', 'dipinjam', 'sedang_dipinjam'])->count(),
            'terlambat' => PeminjamanRequest::whereIn('status', ['disetujui', 'dipinjam', 'sedang_dipinjam'])->where('tanggal_kembali', '<', $today)->count(),
            'ditolak' => PeminjamanRequest::where('status', 'ditolak')->count(),
        ];

        return view('admin.peminjaman.index', compact('peminjaman', 'status', 'counts'));
    }

    public function approve($id)
    {
        $peminjaman = PeminjamanRequest::findOrFail($id);
        $peminjaman->update(['status' => 'disetujui']);

        // Update stok barang
        $barang = $peminjaman->barang;
        if ($barang) {
            $barang->jumlah_baik = max(0, $barang->jumlah_baik - 1);
            $barang->jumlah_kurang_baik = $barang->jumlah_kurang_baik + 1;
            $barang->save();
        }

        return back()->with('success', 'Peminjaman berhasil disetujui! Barang kini berstatus Sedang Dipinjam.');
    }

    public function reject(Request $request, $id)
    {
        $peminjaman = PeminjamanRequest::findOrFail($id);
        $peminjaman->update([
            'status' => 'ditolak',
            'alasan_penolakan' => $request->alasan,
        ]);

        return back()->with('success', 'Peminjaman berhasil ditolak!');
    }

    public function history()
    {
        // Untuk user biasa
        $peminjaman = PeminjamanRequest::with(['user', 'barang'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.peminjaman.history', compact('peminjaman'));
    }
}