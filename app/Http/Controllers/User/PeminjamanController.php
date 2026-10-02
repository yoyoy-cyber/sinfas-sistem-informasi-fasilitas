<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\PeminjamanRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    // Tampilkan form pengajuan
    public function create($kode_barang)
    {
        $barang = Barang::with('kategori')->findOrFail($kode_barang);
        return view('user.peminjaman.create', compact('barang'));
    }

    // Simpan pengajuan peminjaman
    public function store(Request $request, $kode_barang)
    {
        $barang = Barang::findOrFail($kode_barang);
        $user = Auth::user();

        $validated = $request->validate([
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
            'no_telepon' => 'required|string|max:20',
            'alasan_keperluan' => 'required|string|min:10|max:500',
        ]);

        PeminjamanRequest::create([
            'user_id' => $user->id,
            'kode_barang' => $barang->kode_barang,
            'nama_barang' => $barang->nama_barang,
            'nama_peminjam' => $user->nama_lengkap,
            'role_peminjam' => $user->role,
            'tanggal_pinjam' => $validated['tanggal_pinjam'],
            'tanggal_kembali' => $validated['tanggal_kembali'],
            'no_telepon' => $validated['no_telepon'],
            'alasan_keperluan' => $validated['alasan_keperluan'],
            'status' => 'pending',
        ]);

        return redirect()->route('user.status')
            ->with('success', 'Pengajuan peminjaman berhasil dikirim! Menunggu persetujuan admin.')
            ->with('show_peminjaman_modal', true);
    }

    // Tampilkan halaman status pengajuan
    public function status()
    {
        $pengajuan = PeminjamanRequest::with(['barang.kategori'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.status', compact('pengajuan'));
    }

    // Tampilkan detail status pengajuan
    public function statusDetail($id)
    {
        $pengajuan = PeminjamanRequest::with(['barang.kategori'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('user.status-detail', compact('pengajuan'));
    }

    // Riwayat peminjaman (redirect ke status)
    public function history()
    {
        return redirect()->route('user.status');
    }
}