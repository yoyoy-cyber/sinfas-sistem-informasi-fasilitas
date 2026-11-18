<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\PeminjamanRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * Class PeminjamanController
 *
 * Mengontrol seluruh alur transaksi peminjaman sarana dari sisi pengguna (Siswa & Guru/Pegawai),
 * meliputi formulir pengajuan, penyimpanan data permohonan, pemantauan status real-time,
 * penelusuran detail pengajuan, hingga pembatalan permohonan yang masih pending.
 *
 * @package App\Http\Controllers\User
 */
class PeminjamanController extends Controller
{
    /**
     * Menampilkan formulir pengajuan peminjaman barang.
     *
     * Alur:
     * 1. Mencari data barang berdasarkan primary key $kode_barang beserta kategorinya.
     * 2. Menampilkan view form pengajuan peminjaman user.
     *
     * @param string $kode_barang Kode unik barang yang ingin dipinjam (contoh: 'BRG001').
     * @return View Tampilan formulir pengajuan peminjaman.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Jika kode barang tidak ditemukan di basis data.
     */
    public function create(string $kode_barang): View
    {
        $barang = Barang::with('kategori')->findOrFail($kode_barang);
        return view('user.peminjaman.create', compact('barang'));
    }

    /**
     * Menyimpan data pengajuan peminjaman baru ke basis data.
     *
     * Aturan Bisnis & Validasi:
     * - Tanggal pinjam minimal hari ini (tidak boleh tanggal lampau).
     * - Tanggal kembali harus setelah tanggal pinjam.
     * - Status awal peminjaman selalu disetel menjadi 'pending' (menunggu persetujuan admin).
     *
     * Efek Samping:
     * - Menambah 1 baris baru pada tabel `peminjaman_requests`.
     * - Memicu Database Trigger `trg_after_insert_peminjaman` untuk mencatat log otomatis.
     * - Menyimpan flash session 'show_peminjaman_modal' agar popup perhatian tampil di halaman status.
     *
     * @param Request $request Data request HTTP yang berisi input formulir peminjam.
     * @param string $kode_barang Kode unik barang yang diajukan.
     * @return RedirectResponse Pengalihan ke halaman daftar status peminjaman pengguna.
     */
    public function store(Request $request, string $kode_barang): RedirectResponse
    {
        $barang = Barang::findOrFail($kode_barang);
        $user = Auth::user();

        // 1. Validasi input formulir
        $validated = $request->validate([
            'tanggal_pinjam'   => 'required|date|after_or_equal:today',
            'tanggal_kembali'  => 'required|date|after:tanggal_pinjam',
            'no_telepon'       => 'required|string|max:20',
            'alasan_keperluan' => 'required|string|max:500',
        ]);

        // 2. Identifikasi identitas khusus siswa (NIS) jika peminjam adalah siswa
        $nis = $user->siswa?->nis ?? null;

        // 3. Susun data peminjaman sesuai struktur tabel
        $dataPinjam = [
            'username'         => $user->username,
            'nis'              => $nis,
            'kode_barang'      => $barang->kode_barang,
            'nama_barang'      => $barang->nama_barang,
            'nama_peminjam'    => $user->nama_lengkap,
            'role_peminjam'    => $user->role,
            'tanggal_pinjam'   => $validated['tanggal_pinjam'],
            'tanggal_kembali'  => $validated['tanggal_kembali'],
            'no_telepon'       => $validated['no_telepon'],
            'alasan_keperluan' => $validated['alasan_keperluan'],
            'status'           => 'pending', // Menunggu verifikasi admin sarana
        ];

        // Kompatibilitas jika kolom id_akun ada di struktur database
        if (Schema::hasColumn('peminjaman_requests', 'id_akun')) {
            $dataPinjam['id_akun'] = $user->id_akun;
        }

        // 4. Simpan ke database
        PeminjamanRequest::create($dataPinjam);

        return redirect()->route('user.status')
            ->with('success', 'Pengajuan peminjaman berhasil dikirim! Menunggu persetujuan admin.')
            ->with('show_peminjaman_modal', true);
    }

    /**
     * Menampilkan daftar seluruh riwayat status pengajuan peminjaman milik pengguna yang sedang login.
     *
     * Keamanan Data:
     * Menggunakan filter `where('username', Auth::user()->username)` sehingga pengguna
     * hanya dapat melihat data miliknya sendiri.
     *
     * @return View Halaman daftar status pengajuan peminjaman.
     */
    public function status(): View
    {
        $pengajuan = PeminjamanRequest::with(['barang.kategori'])
            ->where('username', Auth::user()->username)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.status', compact('pengajuan'));
    }

    /**
     * Menampilkan rincian lengkap dari suatu pengajuan peminjaman tertentu.
     *
     * Keamanan:
     * Memastikan ID pengajuan tersebut memang milik pengguna yang sedang login
     * untuk mencegah akses data lintas pengguna (IDOR prevention).
     *
     * @param int|string $id ID unik pengajuan peminjaman.
     * @return View Halaman detail rincian pengajuan.
     */
    public function statusDetail($id): View
    {
        $pengajuan = PeminjamanRequest::with(['barang.kategori'])
            ->where('username', Auth::user()->username)
            ->findOrFail($id);

        return view('user.status-detail', compact('pengajuan'));
    }

    /**
     * Membatalkan permohonan peminjaman oleh peminjam sebelum diproses admin.
     *
     * Aturan Bisnis:
     * - Pembatalan HANYA diizinkan jika status pengajuan saat ini masih 'pending'.
     * - Jika permohonan sudah disetujui, ditolak, atau selesai, pembatalan akan ditolak.
     *
     * Efek Samping:
     * - Mengubah status pengajuan di tabel `peminjaman_requests` menjadi 'dibatalkan'.
     * - Menyimpan alasan pembatalan dari peminjam.
     * - Memicu Database Trigger `trg_after_update_peminjaman` untuk mencatat audit log pembatalan.
     * - TIDAK memunculkan popup "menunggu persetujuan admin" (karena aksi ini adalah pembatalan).
     *
     * @param Request $request Data request yang memuat 'alasan_pembatalan'.
     * @param int|string $id ID pengajuan yang ingin dibatalkan.
     * @return RedirectResponse Pengalihan kembali ke halaman status dengan pesan konfirmasi.
     */
    public function cancel(Request $request, $id): RedirectResponse
    {
        // 1. Ambil data pengajuan milik user aktif
        $pengajuan = PeminjamanRequest::where('username', Auth::user()->username)
            ->findOrFail($id);

        // 2. Validasi aturan bisnis: hanya status pending yang dapat dibatalkan
        if ($pengajuan->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini tidak dapat dibatalkan karena sudah diproses atau disetujui.');
        }

        $alasan = $request->input('alasan_pembatalan', 'Dibatalkan oleh peminjam');

        // 3. Pastikan kolom alasan_pembatalan tersedia pada skema basis data
        if (!Schema::hasColumn('peminjaman_requests', 'alasan_pembatalan')) {
            try {
                Schema::table('peminjaman_requests', function ($table) {
                    $table->text('alasan_pembatalan')->nullable()->after('alasan_penolakan');
                });
            } catch (\Throwable $e) {
                // Abaikan jika database tidak mengizinkan operasi DDL secara dinamis
            }
        }

        // 4. Update status dan alasan pembatalan
        $updatePayload = ['status' => 'dibatalkan'];
        if (Schema::hasColumn('peminjaman_requests', 'alasan_pembatalan')) {
            $updatePayload['alasan_pembatalan'] = $alasan;
        }

        $pengajuan->update($updatePayload);

        return redirect()->route('user.status')
            ->with('success', 'Pengajuan peminjaman untuk "' . ($pengajuan->nama_barang ?? 'barang') . '" berhasil dibatalkan.');
    }

    /**
     * Mengarahkan permintaan riwayat peminjaman ke halaman status terpadu.
     *
     * @return RedirectResponse
     */
    public function history(): RedirectResponse
    {
        return redirect()->route('user.status');
    }
}
