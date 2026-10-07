<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PeminjamanRequest;
use App\Models\Pengembalian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengembalianController extends Controller
{
    // Tampilkan daftar pengembalian & barang yang sedang dipinjam
    public function index()
    {
        $username = Auth::user()->username;

        // Barang yang sedang dipinjam (status 'disetujui' dan belum diajukan pengembalian / atau pengembalian ditolak)
        $sedangDipinjam = PeminjamanRequest::with(['barang.kategori', 'pengembalian'])
            ->where('username', $username)
            ->where('status', 'disetujui')
            ->where(function ($query) {
                $query->whereDoesntHave('pengembalian')
                      ->orWhereHas('pengembalian', function ($q) {
                          $q->where('status', 'ditolak');
                      });
            })
            ->orderBy('tanggal_kembali', 'asc')
            ->get();

        // Riwayat pengajuan pengembalian
        $riwayatPengembalian = Pengembalian::with(['barang.kategori', 'peminjaman'])
            ->where('username', $username)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.pengembalian.index', compact('sedangDipinjam', 'riwayatPengembalian'));
    }

    // Tampilkan form pengembalian barang
    public function create($peminjaman_id)
    {
        $peminjaman = PeminjamanRequest::with(['barang.kategori', 'pengembalian'])
            ->where('username', Auth::user()->username)
            ->findOrFail($peminjaman_id);

        // Jika status peminjaman bukan disetujui
        if ($peminjaman->status !== 'disetujui') {
            return redirect()->route('user.pengembalian.index')
                ->with('error', 'Barang ini belum disetujui untuk dipinjam atau sudah selesai.');
        }

        // Cek jika sudah ada pengembalian yang pending atau disetujui
        if ($peminjaman->pengembalian && in_array($peminjaman->pengembalian->status, ['pending', 'disetujui'])) {
            return redirect()->route('user.pengembalian.show', $peminjaman->pengembalian->id)
                ->with('info', 'Anda sudah mengajukan pengembalian untuk barang ini.');
        }

        return view('user.pengembalian.create', compact('peminjaman'));
    }

    // Simpan data pengembalian barang
    public function store(Request $request, $peminjaman_id)
    {
        $peminjaman = PeminjamanRequest::with('pengembalian')
            ->where('username', Auth::user()->username)
            ->findOrFail($peminjaman_id);

        if ($peminjaman->status !== 'disetujui') {
            return redirect()->route('user.pengembalian.index')
                ->with('error', 'Status peminjaman tidak valid untuk pengembalian.');
        }

        $validated = $request->validate([
            'tanggal_pengembalian' => 'required|date',
            'kondisi_pengembalian' => 'required|in:baik,rusak_ringan,rusak_berat',
            'catatan_kondisi' => 'nullable|string|max:1000',
            'bukti_foto' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ], [
            'tanggal_pengembalian.required' => 'Tanggal pengembalian wajib diisi.',
            'kondisi_pengembalian.required' => 'Pilih salah satu kondisi barang.',
            'kondisi_pengembalian.in' => 'Kondisi barang tidak valid.',
            'bukti_foto.required' => 'Bukti foto kondisi barang wajib diunggah.',
            'bukti_foto.image' => 'File bukti foto harus berupa gambar.',
            'bukti_foto.mimes' => 'Format foto harus JPEG, PNG, JPG, atau WEBP.',
            'bukti_foto.max' => 'Ukuran foto maksimal 5 MB.',
        ]);

        // Upload bukti foto
        $fotoPath = null;
        if ($request->hasFile('bukti_foto')) {
            $file = $request->file('bukti_foto');
            $fileName = 'kembali_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/pengembalian', $fileName);
            $fotoPath = 'pengembalian/' . $fileName;
        }

        // Generate kode_kembali
        $lastId = \App\Models\Pengembalian::max('id') ?? 0;
        $kodeKembali = 'KB-' . str_pad($lastId + 1, 6, '0', STR_PAD_LEFT);

        // Simpan atau update data pengembalian
        $pengembalian = Pengembalian::updateOrCreate(
            ['peminjaman_id' => $peminjaman->id],
            [
                'username'             => Auth::user()->username, // FK → akun sesuai ERD
                'kode_kembali'         => $kodeKembali,
                'kode_barang'          => $peminjaman->kode_barang,
                'nama_barang'          => $peminjaman->nama_barang,
                'tanggal_pengembalian' => $validated['tanggal_pengembalian'],
                'kondisi_pengembalian' => $validated['kondisi_pengembalian'],
                'catatan_kondisi'      => $validated['catatan_kondisi'],
                'bukti_foto'           => $fotoPath,
                'status'               => 'pending',
                'alasan_penolakan'     => null,
                'verifikasi_oleh'      => null,
                'tanggal_verifikasi'   => null,
            ]
        );

        return redirect()->route('user.pengembalian.index')
            ->with('success', 'Formulir pengembalian berhasil dikirim! Menunggu verifikasi dari Admin Sarana.');
    }

    // Tampilkan detail pengembalian
    public function show($id)
    {
        $pengembalian = Pengembalian::with(['barang.kategori', 'peminjaman', 'user'])
            ->where('username', Auth::user()->username)
            ->findOrFail($id);

        return view('user.pengembalian.show', compact('pengembalian'));
    }
}
