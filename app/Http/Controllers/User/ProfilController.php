<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PeminjamanRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load(['siswa', 'pegawai']);

        $stats = [
            'pending'         => PeminjamanRequest::where('username', $user->username)->where('status', 'pending')->count(),
            'sedang_dipinjam' => PeminjamanRequest::where('username', $user->username)->whereIn('status', ['disetujui', 'dipinjam'])->count(),
            'selesai'         => PeminjamanRequest::where('username', $user->username)->whereIn('status', ['selesai', 'dikembalikan'])->count(),
        ];

        return view('user.profil', compact('user', 'stats'));
    }

    public function update(Request $request)
    {
        $user = Auth::user()->load(['siswa', 'pegawai']);

        // Dukung input nama atau nama_lengkap
        if (!$request->has('nama') && $request->has('nama_lengkap')) {
            $request->merge(['nama' => $request->input('nama_lengkap')]);
        }

        $validated = $request->validate([
            'nama'     => 'required|string|max:255',
            'email'    => 'required|email|unique:akun,email,' . $user->username . ',username',
            'no_hp'    => 'required|string|max:20',
            'kelas'    => 'nullable|string|max:20',
            'jabatan'  => 'nullable|string|max:100',
            'password' => 'nullable|string|min:6|confirmed',
        ], [
            'nama.required'      => 'Nama lengkap wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.email'        => 'Format email tidak valid.',
            'email.unique'       => 'Email sudah digunakan oleh akun lain.',
            'no_hp.required'     => 'Nomor telepon/WhatsApp wajib diisi.',
            'password.min'       => 'Password baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        // Update data di tabel akun
        $user->email = $validated['email'];
        if (\Illuminate\Support\Facades\Schema::hasColumn('akun', 'nama')) {
            $user->nama = $validated['nama'];
        }
        if (\Illuminate\Support\Facades\Schema::hasColumn('akun', 'nomor_kontak')) {
            $user->nomor_kontak = $validated['no_hp'];
        }

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        // Update data di tabel siswa
        if ($user->siswa) {
            $user->siswa->update([
                'nama'  => $validated['nama'],
                'no_hp' => $validated['no_hp'],
                'kelas' => $validated['kelas'] ?? $user->siswa->kelas,
            ]);
        }

        // Update data di tabel pegawai
        if ($user->pegawai) {
            $user->pegawai->update([
                'nama'    => $validated['nama'],
                'no_hp'   => $validated['no_hp'],
                'jabatan' => $validated['jabatan'] ?? $user->pegawai->jabatan,
            ]);
        }

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}