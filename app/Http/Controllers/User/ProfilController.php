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
        $user = Auth::user();

        $stats = [
            'pending'         => PeminjamanRequest::where('user_id', $user->id)->where('status', 'pending')->count(),
            'sedang_dipinjam' => PeminjamanRequest::where('user_id', $user->id)->whereIn('status', ['disetujui', 'dipinjam'])->count(),
            'selesai'         => PeminjamanRequest::where('user_id', $user->id)->whereIn('status', ['selesai', 'dikembalikan'])->count(),
        ];

        return view('user.profil', compact('user', 'stats'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'no_hp' => 'required|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->nama_lengkap = $validated['nama_lengkap'];
        $user->email = $validated['email'];
        $user->no_hp = $validated['no_hp'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}