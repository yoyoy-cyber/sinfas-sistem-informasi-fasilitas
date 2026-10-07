<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\BarangController;
use App\Http\Controllers\SystemAdminController;

/*
|--------------------------------------------------------------------------
| 1. ROUTE AUTH (LOGIN & REGISTER)
|--------------------------------------------------------------------------
*/

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/login', function () {
    $request = request();
    
    $credentials = $request->validate([
        'username' => 'required|string',
        'password' => 'required|string',
    ]);

    // Coba login dengan username atau email (sesuai tabel akun)
    $loginField = filter_var($credentials['username'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

    if (Auth::attempt([$loginField => $credentials['username'], 'password' => $credentials['password']])) {
        $request->session()->regenerate();
        $user = Auth::user();

        // Redirect berdasarkan ROLE
        if ($user->role === 'admin_sistem') {
            return redirect()->intended('/super-admin/dashboard');
        } elseif ($user->role === 'admin_sarana') {
            return redirect()->intended('/admin/dashboard');
        }
        
        return redirect()->intended('/user/dashboard');
    }

    return back()->withErrors([
        'username' => 'Username, Email, atau Password salah!',
    ])->onlyInput('username');
})->name('login.post');

Route::post('/register', function () {
    $request = request();
    
    $validated = $request->validate([
        'nama'     => 'required|string|max:255',
        'nis_nip'  => 'required|string|max:20',
        'username' => 'required|string|max:50|unique:akun,username',
        'email'    => 'required|email|unique:akun,email',
        'password' => 'required|string|min:6|confirmed',
        'no_hp'    => 'required|string|max:20',
        'kelas'    => 'nullable|string|max:20',
        'role'     => 'required|in:siswa,pegawai',
    ]);

    // Buat akun di tabel akun (sesuai ERD)
    $akun = User::create([
        'username' => $validated['username'],
        'email'    => $validated['email'],
        'role'     => $validated['role'],
        'password' => Hash::make($validated['password']),
    ]);

    // Buat data siswa atau pegawai sesuai role
    if ($validated['role'] === 'siswa') {
        \App\Models\Siswa::create([
            'nis'      => $validated['nis_nip'],
            'username' => $validated['username'],
            'nama'     => $validated['nama'],
            'kelas'    => $validated['kelas'] ?? null,
            'no_hp'    => $validated['no_hp'],
        ]);
    } else {
        \App\Models\Pegawai::create([
            'nip'      => $validated['nis_nip'],
            'username' => $validated['username'],
            'nama'     => $validated['nama'],
            'no_hp'    => $validated['no_hp'],
        ]);
    }

    Auth::login($akun);
    return redirect()->route('user.dashboard');
})->name('register.post');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');


/*
|--------------------------------------------------------------------------
| 2. ROUTE USER (SISWA/GURU)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/kategori/{id_kategori}', [UserDashboardController::class, 'kategori'])->name('kategori');
    Route::get('/search', [UserDashboardController::class, 'search'])->name('search');

    // Route Profil
    Route::get('/profil', [App\Http\Controllers\User\ProfilController::class, 'index'])->name('profil');
    Route::put('/profil', [App\Http\Controllers\User\ProfilController::class, 'update'])->name('profil.update');
    
    // Route Status Pengajuan (TAMBAHKAN INI)
    Route::get('/status-pengajuan', [App\Http\Controllers\User\PeminjamanController::class, 'status'])->name('status');
    Route::get('/status-pengajuan/{id}', [App\Http\Controllers\User\PeminjamanController::class, 'statusDetail'])->name('status.detail');

    // Route Peminjaman User
    Route::get('/peminjaman/riwayat', [App\Http\Controllers\User\PeminjamanController::class, 'history'])->name('peminjaman.history');
    Route::get('/peminjaman/{kode_barang}', [App\Http\Controllers\User\PeminjamanController::class, 'create'])->name('peminjaman.create');
    Route::post('/peminjaman/{kode_barang}', [App\Http\Controllers\User\PeminjamanController::class, 'store'])->name('peminjaman.store');

    // Route Pengembalian User
    Route::get('/pengembalian', [App\Http\Controllers\User\PengembalianController::class, 'index'])->name('pengembalian.index');
    Route::get('/pengembalian/{peminjaman_id}/create', [App\Http\Controllers\User\PengembalianController::class, 'create'])->name('pengembalian.create');
    Route::post('/pengembalian/{peminjaman_id}', [App\Http\Controllers\User\PengembalianController::class, 'store'])->name('pengembalian.store');
    Route::get('/pengembalian/{id}', [App\Http\Controllers\User\PengembalianController::class, 'show'])->name('pengembalian.show');
});


/*
|--------------------------------------------------------------------------
| 3. ROUTE ADMIN SARANA & CRUD BARANG
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // CRUD BARANG
    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
    Route::get('/barang/create', [BarangController::class, 'create'])->name('barang.create');
    Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
    Route::get('/barang/{kode_barang}', [BarangController::class, 'show'])->name('barang.show');
    Route::get('/barang/{kode_barang}/edit', [BarangController::class, 'edit'])->name('barang.edit');
    Route::put('/barang/{kode_barang}', [BarangController::class, 'update'])->name('barang.update');
    Route::delete('/barang/{kode_barang}', [BarangController::class, 'destroy'])->name('barang.destroy');
    
    // APPROVE/REJECT PEMINJAMAN
    Route::post('/peminjaman/{id}/approve', [App\Http\Controllers\Admin\PeminjamanController::class, 'approve'])->name('peminjaman.approve');
    Route::post('/peminjaman/{id}/reject', [App\Http\Controllers\Admin\PeminjamanController::class, 'reject'])->name('peminjaman.reject');
    Route::get('/peminjaman', [App\Http\Controllers\Admin\PeminjamanController::class, 'index'])->name('peminjaman.index');

    // VERIFIKASI PENGEMBALIAN
    Route::get('/pengembalian', [App\Http\Controllers\Admin\PengembalianController::class, 'index'])->name('pengembalian.index');
    Route::get('/pengembalian/{id}', [App\Http\Controllers\Admin\PengembalianController::class, 'show'])->name('pengembalian.show');
    Route::post('/pengembalian/{id}/approve', [App\Http\Controllers\Admin\PengembalianController::class, 'approve'])->name('pengembalian.approve');
    Route::post('/pengembalian/{id}/reject', [App\Http\Controllers\Admin\PengembalianController::class, 'reject'])->name('pengembalian.reject');

    // MODUL LAPORAN STAFF SARANA
    Route::get('/laporan', [App\Http\Controllers\Admin\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/cetak', [App\Http\Controllers\Admin\LaporanController::class, 'cetak'])->name('laporan.cetak');
});


/*
|--------------------------------------------------------------------------
| 4. ROUTE ADMIN SISTEM (SUPER ADMIN)
|--------------------------------------------------------------------------
*/

Route::prefix('super-admin')->name('system-admin.')->group(function () {
    Route::get('/dashboard', [SystemAdminController::class, 'index'])->name('dashboard');
    Route::get('/users', [SystemAdminController::class, 'users'])->name('users');
    Route::post('/users/create', [SystemAdminController::class, 'createUser'])->name('create-user');
    
    Route::put('/users/{username}', [SystemAdminController::class, 'updateUser'])->name('update-user');
    
    Route::delete('/users/{username}', [SystemAdminController::class, 'deleteUser'])->name('delete-user');
    Route::post('/warning/{id}', [SystemAdminController::class, 'sendWarning'])->name('send-warning');
});


/*
|--------------------------------------------------------------------------
| 5. ROOT LANDING PAGE
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    $totalBarang = \App\Models\Barang::count();
    $totalKategori = \App\Models\Kategori::count();
    $totalDipinjam = \App\Models\PeminjamanRequest::whereIn('status', ['disetujui', 'dipinjam', 'sedang_dipinjam'])->count();
    $kategoris = \App\Models\Kategori::take(8)->get();
    $featuredBarang = \App\Models\Barang::with('kategori')->where('jumlah_baik', '>', 0)->latest('updated_at')->take(6)->get();

    return view('welcome', compact('totalBarang', 'totalKategori', 'totalDipinjam', 'kategoris', 'featuredBarang'));
})->name('landing');

Route::get('/about', function () {
    return view('about');
})->name('about');