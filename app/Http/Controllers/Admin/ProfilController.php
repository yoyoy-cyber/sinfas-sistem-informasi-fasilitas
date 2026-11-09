<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\PeminjamanRequest;
use App\Models\Pengembalian;
use App\Models\StaffSarana;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class ProfilController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::check() && Auth::user()->role !== 'admin_sarana') {
                return redirect()->route('user.dashboard')->with('error', 'Akses ditolak.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $user = Auth::user()->load(['pegawai']);

        $stats = [
            'total_barang'        => Barang::count(),
            'peminjaman_pending'  => PeminjamanRequest::where('status', 'pending')->count(),
            'sedang_dipinjam'     => PeminjamanRequest::whereIn('status', ['disetujui', 'dipinjam', 'sedang_dipinjam'])->count(),
            'pengembalian_pending'=> Schema::hasTable('pengembalian') ? Pengembalian::where('status', 'pending')->count() : 0,
        ];

        return view('admin.profil.index', compact('user', 'stats'));
    }

    public function update(Request $request)
    {
        $user = Auth::user()->load(['pegawai']);

        $validated = $request->validate([
            'nama'     => 'required|string|max:255',
            'email'    => 'required|email|unique:akun,email,' . $user->username . ',username',
            'no_hp'    => 'required|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
        ], [
            'nama.required'      => 'Nama wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.email'        => 'Format email tidak valid.',
            'email.unique'       => 'Email sudah digunakan oleh akun lain.',
            'no_hp.required'     => 'Nomor telepon wajib diisi.',
            'password.min'       => 'Password baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Update di tabel akun
        $user->email = $validated['email'];
        if (Schema::hasColumn('akun', 'nama')) {
            $user->nama = $validated['nama'];
        }
        if (Schema::hasColumn('akun', 'nomor_kontak')) {
            $user->nomor_kontak = $validated['no_hp'];
        }

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        // Update data di tabel pegawai jika terhubung
        if ($user->pegawai) {
            $user->pegawai->update([
                'nama'  => $validated['nama'],
                'no_hp' => $validated['no_hp'],
            ]);
        }

        // Update data di tabel staff_sarana jika ada
        if (Schema::hasTable('staff_sarana')) {
            $nip = $user->pegawai?->nip;
            if ($nip) {
                StaffSarana::where('nip', $nip)->update([
                    'nama'  => $validated['nama'],
                    'no_hp' => $validated['no_hp'],
                ]);
            }
        }

        return back()->with('success', 'Profil Admin Sarana berhasil diperbarui!');
    }
}
