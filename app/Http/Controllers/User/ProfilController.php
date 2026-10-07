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

        $validated = $request->validate([
            'nama'     => 'required|string|max:255',
            'email'    => 'required|email|unique:akun,email,' . $user->username . ',username',
            'no_hp'    => 'required|string|max:20',
            'kelas'    => 'nullable|string|max:20',
            'jabatan'  => 'nullable|string|max:100',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        // Update email di tabel akun
        $user->email = $validated['email'];
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