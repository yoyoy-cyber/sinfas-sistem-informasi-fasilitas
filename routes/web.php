<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\BarangController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\SystemAdminController;

/*
|--------------------------------------------------------------------------
| 1. ROUTE AUTH (LOGIN & LOGOUT)
|--------------------------------------------------------------------------
*/

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

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
    Route::post('/status-pengajuan/{id}/cancel', [App\Http\Controllers\User\PeminjamanController::class, 'cancel'])->name('status.cancel');

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
    
    // CRUD KATEGORI
    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
    Route::put('/kategori/{id_kategori}', [KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{id_kategori}', [KategoriController::class, 'destroy'])->name('kategori.destroy');
    
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

    // PROFIL ADMIN SARANA
    Route::get('/profil', [App\Http\Controllers\Admin\ProfilController::class, 'index'])->name('profil');
    Route::put('/profil', [App\Http\Controllers\Admin\ProfilController::class, 'update'])->name('profil.update');

    // FITUR ADVANCED DATABASE (VIEW, SP, FUNCTION, TRIGGER)
    Route::get('/database-features', function () {
        $viewData = \App\Models\ViewPeminjamanLengkap::limit(10)->get();
        $barangSample = \App\Models\Barang::first();
        $funcResult = null;
        if ($barangSample) {
            $funcResult = \Illuminate\Support\Facades\DB::selectOne(
                "SELECT func_total_dipinjam_barang(?) AS total_dipinjam, func_status_ketersediaan_barang(?) AS status_barang",
                [$barangSample->kode_barang, $barangSample->kode_barang]
            );
        }
        \Illuminate\Support\Facades\DB::statement("CALL sp_rekap_status_peminjaman(@p_tot, @p_pend, @p_setuju, @p_batal, @p_tolak)");
        $procSummary = \Illuminate\Support\Facades\DB::selectOne(
            "SELECT @p_tot AS total, @p_pend AS pending, @p_setuju AS disetujui, @p_batal AS dibatalkan, @p_tolak AS ditolak"
        );
        $triggerLogs = \App\Models\LogAktivitasPeminjaman::latest('id')->limit(15)->get();

        return view('admin.database-features', compact('viewData', 'barangSample', 'funcResult', 'procSummary', 'triggerLogs'));
    })->name('database-features');
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

/*
|--------------------------------------------------------------------------
| STORAGE LINK & FILE SERVING FALLBACK (Untuk Shared Hosting / cPanel)
|--------------------------------------------------------------------------
*/
// Jalankan migrasi database di hosting & bersihkan cache view/route (DirectAdmin / cPanel tanpa akses SSH)
Route::get('/migrate', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $output = \Illuminate\Support\Facades\Artisan::output();

        // Bersihkan cache view, route, config agar kode terbaru langsung aktif
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        if (function_exists('opcache_reset')) {
            @opcache_reset();
        }

        return '<pre style="background:#0f172a; color:#38bdf8; padding:2rem; border-radius:12px; font-family:monospace; line-height:1.6;">'
            . '✅ <strong>Migration & Clear Cache Berhasil Dijalankan di Hosting!</strong><br><br>'
            . htmlspecialchars($output ?: 'Database sudah up to date.')
            . '<br><br><a href="' . url('/user/dashboard') . '" style="color:#4ade80; text-decoration:none; font-weight:bold;">← Kembali ke Dashboard</a></pre>';
    } catch (\Throwable $e) {
        return '<pre style="background:#0f172a; color:#ef4444; padding:2rem; border-radius:12px; font-family:monospace; line-height:1.6;">'
            . '❌ <strong>Gagal menjalankan migrasi:</strong><br><br>'
            . htmlspecialchars($e->getMessage())
            . '</pre>';
    }
});

Route::get('/clear-cache', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        if (function_exists('opcache_reset')) {
            @opcache_reset();
        }
        return '<pre style="background:#0f172a; color:#38bdf8; padding:2rem; border-radius:12px; font-family:monospace;">'
            . '✅ <strong>Semua cache (view, config, route, opcache) berhasil dibersihkan!</strong><br><br>'
            . '<a href="' . url('/user/dashboard') . '" style="color:#4ade80; font-weight:bold;">← Kembali ke Dashboard</a></pre>';
    } catch (\Throwable $e) {
        return 'Gagal bersihkan cache: ' . $e->getMessage();
    }
});

Route::get('/storage-link', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('storage:link');
        return 'Storage link berhasil dibuat! <br><br><a href="' . url('/') . '">Kembali ke Beranda</a>';
    } catch (\Exception $e) {
        return 'Gagal membuat storage link: ' . $e->getMessage();
    }
});

// Sinkronkan semua file dari storage/app/public ke public/storage dan public_html/storage (DirectAdmin / cPanel tanpa SSH)
Route::get('/storage-sync', function () {
    $source = storage_path('app/public');
    $destinations = array_unique([
        public_path('storage'),
        base_path('public_html/storage'),
    ]);

    if (!file_exists($source)) {
        return 'Folder source storage/app/public belum ada.';
    }

    $count = 0;
    try {
        foreach ($destinations as $dest) {
            if (!file_exists($dest)) {
                @mkdir($dest, 0755, true);
            }

            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::SELF_FIRST
            );

            foreach ($iterator as $item) {
                $subPath = $iterator->getSubPathName();
                $target = $dest . DIRECTORY_SEPARATOR . $subPath;
                if ($item->isDir()) {
                    if (!file_exists($target)) {
                        @mkdir($target, 0755, true);
                    }
                } else {
                    @copy($item->getRealPath(), $target);
                    $count++;
                }
            }
        }
        return "Berhasil menyinkronkan {$count} file ke folder storage publik! <br><br>Foto bukti pengembalian dan gambar barang sekarang sudah aktif dan bisa dibuka.<br><br><a href='" . url('/') . "'>Kembali ke Web</a>";
    } catch (\Throwable $e) {
        return 'Error sinkronisasi: ' . $e->getMessage();
    }
});

// Fallback jika web hosting belum / tidak support symlink public/storage
Route::get('/storage/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);
    $realFullPath = realpath($fullPath);
    $realBasePath = realpath(storage_path('app/public'));

    if ($realFullPath && $realBasePath && str_starts_with($realFullPath, $realBasePath) && file_exists($realFullPath)) {
        return response()->file($realFullPath);
    }

    abort(404);
})->where('path', '.*');