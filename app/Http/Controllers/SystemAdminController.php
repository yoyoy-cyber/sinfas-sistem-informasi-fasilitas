<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Siswa;
use App\Models\Pegawai;
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
            'pegawai'       => User::where('role', 'pegawai')->count(),
            'admin_sarana'  => User::where('role', 'admin_sarana')->count(),
            'admin_sistem'  => User::where('role', 'admin_sistem')->count(),
            'total_barang'  => Barang::count(),
        ];

        $today = Carbon::today();
        
        // Peminjaman yang melewati tanggal kembali dan belum selesai
        $peminjamanTerlambat = PeminjamanRequest::where('tanggal_kembali', '<', $today)
            ->whereIn('status', ['disetujui', 'dipinjam'])
            ->with(['user', 'barang'])
            ->orderBy('tanggal_kembali', 'asc')
            ->get();

        // 5 Pengguna yang baru saja terdaftar (dari tabel akun)
        $recentUsers = User::orderBy('created_at', 'desc')->take(5)->get();

        return view('system-admin.dashboard', compact('stats', 'peminjamanTerlambat', 'recentUsers'));
    }

    /**
     * Halaman Kelola Pengguna (Akun List)
     */
    public function users(Request $request)
    {
        $query = User::with(['siswa', 'pegawai']);

        // Filter Pencarian (Username, Email)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('siswa', fn($s) => $s->where('nama', 'like', "%{$search}%")->orWhere('nis', 'like', "%{$search}%"))
                  ->orWhereHas('pegawai', fn($p) => $p->where('nama', 'like', "%{$search}%")->orWhere('nip', 'like', "%{$search}%"));
            });
        }

        // Filter Role
        if ($request->filled('role') && in_array($request->input('role'), ['siswa', 'pegawai', 'admin_sarana', 'admin_sistem'])) {
            $query->where('role', $request->input('role'));
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        $roleCounts = [
            'all'          => User::count(),
            'siswa'        => User::where('role', 'siswa')->count(),
            'pegawai'      => User::where('role', 'pegawai')->count(),
            'admin_sarana' => User::where('role', 'admin_sarana')->count(),
            'admin_sistem' => User::where('role', 'admin_sistem')->count(),
        ];

        return view('system-admin.users.index', compact('users', 'roleCounts'));
    }

    /**
     * Tambah Pengguna Baru (Akun + Siswa/Pegawai)
     */
    public function createUser(Request $request)
    {
        $validated = $request->validate([
            'nama'     => 'required|string|max:255',
            'nis_nip'  => 'required|string|max:20',
            'username' => 'required|string|max:50|unique:akun,username',
            'email'    => 'required|email|unique:akun,email',
            'password' => 'required|string|min:6',
            'no_hp'    => 'required|string|max:20',
            'kelas'    => 'nullable|string|max:20',
            'jabatan'  => 'nullable|string|max:100',
            'role'     => 'required|in:siswa,pegawai,admin_sarana,admin_sistem',
        ], [
            'username.unique' => 'Username sudah digunakan.',
            'email.unique'    => 'Email sudah digunakan oleh akun lain.',
            'password.min'    => 'Password minimal harus 6 karakter.',
        ]);

        // Buat akun di tabel akun
        $user = User::create([
            'username' => $validated['username'],
            'email'    => $validated['email'],
            'role'     => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        // Buat data profil siswa atau pegawai
        if ($validated['role'] === 'siswa') {
            Siswa::create([
                'nis'      => $validated['nis_nip'],
                'username' => $validated['username'],
                'nama'     => $validated['nama'],
                'kelas'    => $validated['kelas'] ?? null,
                'no_hp'    => $validated['no_hp'],
            ]);
        } else {
            Pegawai::create([
                'nip'      => $validated['nis_nip'],
                'username' => $validated['username'],
                'nama'     => $validated['nama'],
                'jabatan'  => $validated['jabatan'] ?? null,
                'no_hp'    => $validated['no_hp'],
            ]);
        }

        return back()->with('success', 'Akun ' . $validated['nama'] . ' berhasil ditambahkan!');
    }

    /**
     * Update Pengguna (Akun + Siswa/Pegawai)
     */
    public function updateUser(Request $request, $username)
    {
        $user = User::findOrFail($username);

        $validated = $request->validate([
            'nama'     => 'required|string|max:255',
            'no_hp'    => 'required|string|max:20',
            'email'    => 'required|email|unique:akun,email,' . $user->username . ',username',
            'kelas'    => 'nullable|string|max:20',
            'jabatan'  => 'nullable|string|max:100',
            'role'     => 'required|in:siswa,pegawai,admin_sarana,admin_sistem',
            'password' => 'nullable|string|min:6',
        ]);

        // Update tabel akun
        $user->email = $validated['email'];
        $user->role  = $validated['role'];
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        // Update data siswa
        if ($user->siswa) {
            $user->siswa->update([
                'nama'  => $validated['nama'],
                'no_hp' => $validated['no_hp'],
                'kelas' => $validated['kelas'] ?? $user->siswa->kelas,
            ]);
        }

        // Update data pegawai
        if ($user->pegawai) {
            $user->pegawai->update([
                'nama'    => $validated['nama'],
                'no_hp'   => $validated['no_hp'],
                'jabatan' => $validated['jabatan'] ?? $user->pegawai->jabatan,
            ]);
        }

        return back()->with('success', 'Akun berhasil diperbarui!');
    }

    /**
     * Hapus Pengguna (Akun + cascade ke Siswa/Pegawai)
     */
    public function deleteUser($username)
    {
        $user = User::findOrFail($username);

        if ($user->username === Auth::user()->username) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif.');
        }

        $nama = $user->nama_lengkap;

        // Hapus data siswa/pegawai terlebih dahulu (set null username di siswa/pegawai dulu)
        if ($user->siswa) {
            $user->siswa->update(['username' => null]);
        }
        if ($user->pegawai) {
            $user->pegawai->update(['username' => null]);
        }

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
