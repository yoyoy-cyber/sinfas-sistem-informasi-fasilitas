<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PeminjamanRequest;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SystemAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check() || Auth::user()->role !== 'admin_sistem') {
                return redirect()->route('user.dashboard')->with('error', 'Akses Ditolak. Khusus Admin Sistem.');
            }
            return $next($request);
        });
    }

    /**
     * Dashboard Super Admin / Admin Sistem
     */
    public function index()
    {
        $stats = [
            'total_users'   => User::count(),
            'siswa'         => User::where('role', 'siswa')->count(),
            'guru'          => User::where('role', 'guru')->count(),
            'admin_sarana'  => User::where('role', 'admin_sarana')->count(),
            'admin_sistem'  => User::where('role', 'admin_sistem')->count(),
            'total_barang'  => Barang::count(),
        ];

        $today = Carbon::today();
        
        // Peminjaman yang melewati tanggal kembali dan belum selesai/masih aktif
        $peminjamanTerlambat = PeminjamanRequest::where('tanggal_kembali', '<', $today)
            ->whereIn('status', ['disetujui', 'dipinjam'])
            ->with(['user', 'barang'])
            ->orderBy('tanggal_kembali', 'asc')
            ->get();

        // 5 Pengguna yang baru saja terdaftar
        $recentUsers = User::orderBy('created_at', 'desc')->take(5)->get();

        return view('system-admin.dashboard', compact('stats', 'peminjamanTerlambat', 'recentUsers'));
    }

    /**
     * Halaman Kelola Pengguna (Users List)
     */
    public function users(Request $request)
    {
        $query = User::query();

        // Filter Pencarian (Nama, NIS/NIP, Email, No HP)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nis_nip', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");
            });
        }

        // Filter Role
        if ($request->filled('role') && in_array($request->input('role'), ['siswa', 'guru', 'admin_sarana', 'admin_sistem'])) {
            $query->where('role', $request->input('role'));
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        $roleCounts = [
            'all'          => User::count(),
            'siswa'        => User::where('role', 'siswa')->count(),
            'guru'         => User::where('role', 'guru')->count(),
            'admin_sarana' => User::where('role', 'admin_sarana')->count(),
            'admin_sistem' => User::where('role', 'admin_sistem')->count(),
        ];

        return view('system-admin.users.index', compact('users', 'roleCounts'));
    }

    /**
     * Tambah Pengguna Baru
     */
    public function createUser(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nis_nip'      => 'required|string|unique:users,nis_nip',
            'email'        => 'required|email|unique:users,email',
            'password'     => 'required|string|min:6',
            'no_hp'        => 'required|string',
            'role'         => 'required|in:siswa,guru,admin_sarana,admin_sistem',
        ], [
            'nis_nip.unique' => 'NIS/NIP sudah terdaftar.',
            'email.unique'   => 'Email sudah digunakan oleh akun lain.',
            'password.min'   => 'Password minimal harus 6 karakter.',
        ]);

        User::create([
            'nama_lengkap' => $validated['nama_lengkap'],
            'nis_nip'      => $validated['nis_nip'],
            'email'        => $validated['email'],
            'password'     => Hash::make($validated['password']),
            'no_hp'        => $validated['no_hp'],
            'role'         => $validated['role'],
        ]);

        return back()->with('success', 'Akun ' . $validated['nama_lengkap'] . ' berhasil ditambahkan!');
    }

    /**
     * Update Pengguna
     */
    
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nis_nip' => 'required|string|unique:users,nis_nip,' . $user->id, // Abaikan NIS/NIP user yang sedang diedit
            'email' => 'required|email|unique:users,email,' . $user->id, // Abaikan email user yang sedang diedit
            'no_hp' => 'required|string',
            'role' => 'required|in:siswa,guru,admin_sarana,admin_sistem',
            'password' => 'nullable|string|min:6', // Password opsional saat edit
        ]);

        $user->nama_lengkap = $validated['nama_lengkap'];
        $user->nis_nip = $validated['nis_nip'];
        $user->email = $validated['email'];
        $user->no_hp = $validated['no_hp'];
        $user->role = $validated['role'];

        // Hanya update password jika diisi
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return back()->with('success', 'Akun berhasil diperbarui!');
    }

    /**
     * Hapus Pengguna
     */
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif.');
        }

        $nama = $user->nama_lengkap;
        $user->delete();

        return back()->with('success', 'Akun ' . $nama . ' berhasil dihapus dari sistem.');
    }

    /**
     * Kirim Peringatan untuk Peminjaman Terlambat
     */
    public function sendWarning($peminjamanId)
    {
        $peminjaman = PeminjamanRequest::findOrFail($peminjamanId);
        
        $peminjaman->update([
            'catatan_admin' => ($peminjaman->catatan_admin ? $peminjaman->catatan_admin . "\n\n" : '') . 
                              '[' . Carbon::now()->format('d/m/Y H:i') . '] PERINGATAN SISTEM: Peminjaman melebihi batas waktu pengembalian. Segera lakukan pengembalian fasilitas.'
        ]);

        return back()->with('success', 'Peringatan sanksi telah berhasil dicatat untuk peminjam (' . $peminjaman->nama_peminjam . ').');
    }
}