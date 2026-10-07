<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\PeminjamanRequest;
use App\Models\Pengembalian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengembalianController extends Controller
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

    // Tampilkan daftar verifikasi pengembalian
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');

        $pengembalian = Pengembalian::with(['user', 'barang.kategori', 'peminjaman'])
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->get();

        // Hitung badge counter
        $counts = [
            'pending' => Pengembalian::where('status', 'pending')->count(),
            'disetujui' => Pengembalian::where('status', 'disetujui')->count(),
            'ditolak' => Pengembalian::where('status', 'ditolak')->count(),
        ];

        return view('admin.pengembalian.index', compact('pengembalian', 'status', 'counts'));
    }

    // Setujui verifikasi pengembalian
    public function approve($id)
    {
        $pengembalian = Pengembalian::with(['barang', 'peminjaman'])->findOrFail($id);

        $pengembalian->update([
            'status'           => 'disetujui',
            'verifikasi_oleh'  => Auth::user()->nama_lengkap,
            'tanggal_verifikasi' => now(),
            'alasan_penolakan' => null,
        ]);

        // Update status peminjaman jadi selesai
        if ($pengembalian->peminjaman) {
            $pengembalian->peminjaman->update(['status' => 'selesai']);
        }

        // Update stok barang sesuai kondisi barang yang dikembalikan
        $barang = $pengembalian->barang;
        if ($barang) {
            // Kurangi peminjaman aktif dari jumlah_kurang_baik
            $barang->jumlah_kurang_baik = max(0, $barang->jumlah_kurang_baik - 1);

            // Tambah stok sesuai kondisi fisik pengembalian
            if ($pengembalian->kondisi_pengembalian === 'baik') {
                $barang->jumlah_baik = $barang->jumlah_baik + 1;
            } elseif ($pengembalian->kondisi_pengembalian === 'rusak_ringan') {
                $barang->jumlah_kurang_baik = $barang->jumlah_kurang_baik + 1;
            } elseif ($pengembalian->kondisi_pengembalian === 'rusak_berat') {
                $barang->jumlah_rusak_berat = $barang->jumlah_rusak_berat + 1;
            }

            $barang->save();
        }

        return back()->with('success', 'Pengembalian barang berhasil diverifikasi dan disetujui! Stok kondisi barang telah diperbarui.');
    }

    // Tolak verifikasi pengembalian
    public function reject(Request $request, $id)
    {
        $pengembalian = Pengembalian::findOrFail($id);

        $request->validate([
            'alasan' => 'required|string|min:3|max:500',
        ], [
            'alasan.required' => 'Alasan penolakan wajib diisi.',
            'alasan.min' => 'Alasan penolakan minimal 3 karakter.',
        ]);

        $pengembalian->update([
            'status'             => 'ditolak',
            'alasan_penolakan'   => $request->alasan,
            'verifikasi_oleh'    => Auth::user()->nama_lengkap,
            'tanggal_verifikasi' => now(),
        ]);

        return back()->with('success', 'Pengembalian barang telah ditolak. Alasan penolakan telah tercatat.');
    }

    // Detail data pengembalian (JSON untuk modal / AJAX)
    public function show($id)
    {
        $pengembalian = Pengembalian::with(['user', 'barang.kategori', 'peminjaman'])->findOrFail($id);

        return response()->json([
            'id' => $pengembalian->id,
            'peminjam' => $pengembalian->user->nama_lengkap ?? $pengembalian->peminjaman->nama_peminjam ?? '-',
            'role_peminjam' => $pengembalian->user->role ?? $pengembalian->peminjaman->role_peminjam ?? '-',
            'no_telepon' => $pengembalian->peminjaman->no_telepon ?? '-',
            'nama_barang' => $pengembalian->nama_barang,
            'kode_barang' => $pengembalian->kode_barang,
            'kategori' => $pengembalian->barang->kategori->nama_kategori ?? '-',
            'tanggal_pinjam' => $pengembalian->peminjaman ? \Carbon\Carbon::parse($pengembalian->peminjaman->tanggal_pinjam)->locale('id')->isoFormat('DD MMMM YYYY') : '-',
            'tanggal_pengembalian' => \Carbon\Carbon::parse($pengembalian->tanggal_pengembalian)->locale('id')->isoFormat('DD MMMM YYYY'),
            'kondisi' => $pengembalian->kondisi_pengembalian,
            'catatan_kondisi' => $pengembalian->catatan_kondisi ?? '-',
            'bukti_foto' => $pengembalian->bukti_foto ? asset('storage/' . $pengembalian->bukti_foto) : null,
            'status' => $pengembalian->status,
            'alasan_penolakan' => $pengembalian->alasan_penolakan,
            'verifikasi_oleh' => $pengembalian->verifikasi_oleh,
            'tanggal_verifikasi' => $pengembalian->tanggal_verifikasi ? \Carbon\Carbon::parse($pengembalian->tanggal_verifikasi)->locale('id')->isoFormat('DD MMMM YYYY HH:mm') : null,
        ]);
    }
}
