<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KategoriController extends Controller
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

    /**
     * Tampilkan daftar kategori
     */
    public function index()
    {
        $kategoris = Kategori::withCount('barangs')
            ->orderBy('nama_kategori', 'asc')
            ->get();

        $stats = [
            'total' => $kategoris->count(),
            'terisi' => $kategoris->filter(fn($k) => $k->barangs_count > 0)->count(),
            'kosong' => $kategoris->filter(fn($k) => $k->barangs_count == 0)->count(),
        ];

        return view('admin.kategori.index', compact('kategoris', 'stats'));
    }

    /**
     * Simpan kategori baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori,nama_kategori',
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique' => 'Nama kategori sudah terdaftar.',
            'nama_kategori.max' => 'Nama kategori maksimal 100 karakter.',
        ]);

        $kategori = Kategori::create([
            'nama_kategori' => trim($validated['nama_kategori']),
        ]);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori "' . $kategori->nama_kategori . '" berhasil ditambahkan!')
            ->with('highlight_id', $kategori->id_kategori);
    }

    /**
     * Update kategori
     */
    public function update(Request $request, $id_kategori)
    {
        $kategori = Kategori::findOrFail($id_kategori);

        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori,nama_kategori,' . $id_kategori . ',id_kategori',
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique' => 'Nama kategori sudah terdaftar.',
            'nama_kategori.max' => 'Nama kategori maksimal 100 karakter.',
        ]);

        $oldName = $kategori->nama_kategori;
        $kategori->update([
            'nama_kategori' => trim($validated['nama_kategori']),
        ]);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori "' . $oldName . '" berhasil diperbarui menjadi "' . $kategori->nama_kategori . '"!')
            ->with('highlight_id', $kategori->id_kategori);
    }

    /**
     * Hapus kategori
     */
    public function destroy($id_kategori)
    {
        $kategori = Kategori::withCount('barangs')->findOrFail($id_kategori);

        // Keamanan: Cek apakah kategori masih dipakai oleh barang
        if ($kategori->barangs_count > 0) {
            return redirect()->route('admin.kategori.index')
                ->with('error', 'Kategori "' . $kategori->nama_kategori . '" tidak dapat dihapus karena masih digunakan oleh ' . $kategori->barangs_count . ' barang inventaris.');
        }

        $nama = $kategori->nama_kategori;
        $kategori->delete();

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori "' . $nama . '" berhasil dihapus!');
    }
}
