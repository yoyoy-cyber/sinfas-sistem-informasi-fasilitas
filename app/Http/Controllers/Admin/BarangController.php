<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
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

    // Tampilkan daftar barang
    public function index()
    {
        $barangs = Barang::with('kategori')->orderBy('updated_at', 'desc')->get();
        return view('admin.barang.index', compact('barangs'));
    }

    // Tampilkan detail barang
    public function show($kode_barang)
    {
        $barang = Barang::with('kategori')->findOrFail($kode_barang);
        return view('admin.barang.show', compact('barang'));
    }

    // Tampilkan form tambah barang
    public function create()
    {
        $kategoris = Kategori::all();
        return view('admin.barang.create', compact('kategoris'));
    }

    // Simpan barang baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_barang' => 'required|string|unique:barang,kode_barang|max:30',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'nama_barang' => 'required|string|max:255',
            'merk_model' => 'nullable|string|max:255',
            'no_seri_pabrik' => 'nullable|string|max:255',
            'ukuran_dimensi' => 'nullable|string|max:255',
            'bahan' => 'nullable|string|max:255',
            'tahun_pembelian' => 'nullable|integer|min:1900|max:' . date('Y'),
            'jumlah_baik' => 'required|integer|min:0',
            'jumlah_kurang_baik' => 'required|integer|min:0',
            'jumlah_rusak_berat' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Handle upload gambar
        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            $namaFile = time() . '_' . str_replace(' ', '_', $request->nama_barang) . '.' . $gambar->getClientOriginalExtension();
            $gambar->storeAs('public/barang', $namaFile);
            $validated['gambar'] = 'barang/' . $namaFile;
        }

        $barang = Barang::create($validated);

        return redirect()->route('admin.barang.index')
            ->with('success', 'Barang berhasil ditambahkan!')
            ->with('highlight_id', $barang->kode_barang);
    }

    // Tampilkan form edit barang
    public function edit($kode_barang)
    {
        $barang = Barang::findOrFail($kode_barang);
        $kategoris = Kategori::all();
        return view('admin.barang.edit', compact('barang', 'kategoris'));
    }

    // Update barang
    public function update(Request $request, $kode_barang)
    {
        $barang = Barang::findOrFail($kode_barang);

        $validated = $request->validate([
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'nama_barang' => 'required|string|max:255',
            'merk_model' => 'nullable|string|max:255',
            'no_seri_pabrik' => 'nullable|string|max:255',
            'ukuran_dimensi' => 'nullable|string|max:255',
            'bahan' => 'nullable|string|max:255',
            'tahun_pembelian' => 'nullable|integer|min:1900|max:' . date('Y'),
            'jumlah_baik' => 'required|integer|min:0',
            'jumlah_kurang_baik' => 'required|integer|min:0',
            'jumlah_rusak_berat' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Handle upload gambar baru
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($barang->gambar) {
                Storage::delete('public/' . $barang->gambar);
            }
            
            $gambar = $request->file('gambar');
            $namaFile = time() . '_' . str_replace(' ', '_', $request->nama_barang) . '.' . $gambar->getClientOriginalExtension();
            $gambar->storeAs('public/barang', $namaFile);
            $validated['gambar'] = 'barang/' . $namaFile;
        }

        $barang->update($validated);

        return redirect()->route('admin.barang.index')
            ->with('success', 'Barang berhasil diupdate!')
            ->with('highlight_id', $barang->kode_barang);
    }

    // Hapus barang
    public function destroy($kode_barang)
    {
        $barang = Barang::findOrFail($kode_barang);
        
        // Hapus gambar jika ada
        if ($barang->gambar) {
            Storage::delete('public/' . $barang->gambar);
        }
        
        $barang->delete();

        return redirect()->route('admin.barang.index')
            ->with('success', 'Barang berhasil dihapus!');
    }
}