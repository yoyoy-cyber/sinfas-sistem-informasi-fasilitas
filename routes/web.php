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
        'nis_nip' => 'required|string',
        'password' => 'required|string',
    ]);

    $loginField = filter_var($credentials['nis_nip'], FILTER_VALIDATE_EMAIL) ? 'email' : 'nis_nip';

    if (Auth::attempt([$loginField => $credentials['nis_nip'], 'password' => $credentials['password']])) {
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
        'nis_nip' => 'NIS/NIP, Email, atau Password salah!',
    ])->onlyInput('nis_nip');
})->name('login.post');

Route::post('/register', function () {
    $request = request();
    
    $validated = $request->validate([
        'nama_lengkap' => 'required|string|max:255',
        'nis_nip' => 'required|string|unique:users,nis_nip',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6|confirmed',
        'no_hp' => 'required|string',
        'role' => 'required|in:siswa,guru',
    ]);

    $user = User::create([
        'nama_lengkap' => $validated['nama_lengkap'],
        'nis_nip' => $validated['nis_nip'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'no_hp' => $validated['no_hp'],
        'role' => $validated['role'],
    ]);

    Auth::login($user);
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
    
    Route::put('/users/{id}', [SystemAdminController::class, 'updateUser'])->name('update-user');
    
    Route::delete('/users/{id}', [SystemAdminController::class, 'deleteUser'])->name('delete-user');
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