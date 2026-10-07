<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik
        $stats = [
            'tersedia' => Barang::where('jumlah_baik', '>', 0)->count(),
            'dipinjam' => Barang::where('jumlah_baik', 0)->where('jumlah_kurang_baik', '>', 0)->count(),
            'rusak' => Barang::where('jumlah_rusak_berat', '>', 0)->where('jumlah_baik', 0)->count(),
        ];

        // Ambil semua kategori (untuk tombol filter)
        $kategoris = Kategori::orderBy('nama_kategori', 'asc')->get();

        // Ambil semua barang yang tersedia (untuk ditampilkan), diurutkan dari yang terbaru diupdate/ditambahkan
        $barangs = Barang::with('kategori')
            ->withCount('peminjamanRequests')
            ->where('jumlah_baik', '>', 0)
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('user.dashboard', compact('stats', 'kategoris', 'barangs'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q', '');

        $barangs = Barang::with('kategori')
            ->withCount('peminjamanRequests')
            ->where(function($b) use ($query) {
                $b->where('nama_barang', 'like', "%{$query}%")
                  ->orWhereHas('kategori', function($q) use ($query) {
                      $q->where('nama_kategori', 'like', "%{$query}%");
                  });
            })
            ->orderBy('updated_at', 'desc')
            ->get();

        $kategoris = Kategori::orderBy('nama_kategori', 'asc')->get();

        $stats = [
            'tersedia' => Barang::where('jumlah_baik', '>', 0)->count(),
            'dipinjam' => Barang::where('jumlah_baik', 0)->where('jumlah_kurang_baik', '>', 0)->count(),
            'rusak' => Barang::where('jumlah_rusak_berat', '>', 0)->where('jumlah_baik', 0)->count(),
        ];

        return view('user.dashboard', compact('stats', 'kategoris', 'barangs', 'query'));
    }

    public function kategori($id_kategori)
    {
        $kategoris = Kategori::orderBy('nama_kategori', 'asc')->get();

        $barangs = Barang::with('kategori')
            ->withCount('peminjamanRequests')
            ->where('id_kategori', $id_kategori)
            ->where('jumlah_baik', '>', 0)
            ->orderBy('updated_at', 'desc')
            ->get();

        $stats = [
            'tersedia' => Barang::where('jumlah_baik', '>', 0)->count(),
            'dipinjam' => Barang::where('jumlah_baik', 0)->where('jumlah_kurang_baik', '>', 0)->count(),
            'rusak' => Barang::where('jumlah_rusak_berat', '>', 0)->where('jumlah_baik', 0)->count(),
        ];

        return view('user.dashboard', compact('stats', 'kategoris', 'barangs', 'id_kategori'));
    }
}