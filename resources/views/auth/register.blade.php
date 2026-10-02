@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<div style="background: white; border-radius: 1.5rem; box-shadow: 0 25px 60px rgba(0,0,0,0.35); padding: 2rem; padding-top: 4.5rem; position: relative;">
    
    <!-- CIRCULAR SF LOGO BADGE (SAMA SEPERTI DI LANDING PAGE & DASHBOARD) -->
    <div style="position: absolute; top: -3.5rem; left: 50%; transform: translateX(-50%);">
        <div style="width: 6.5rem; height: 6.5rem; background: linear-gradient(135deg, #2563eb, #1d4ed8); border-radius: 50%; border: 4px solid white; box-shadow: 0 10px 25px rgba(37, 99, 235, 0.5); display: flex; align-items: center; justify-content: center; color: white; font-weight: 900; font-size: 2.2rem; letter-spacing: -1px; text-shadow: 0 2px 8px rgba(0,0,0,0.25);">
            SF
        </div>
    </div>

    <div style="text-align: center; margin-bottom: 2rem;">
        <h1 style="font-size: 1.875rem; font-weight: 900; color: #0f172a; letter-spacing: 0.05em; margin: 0;">SINFAS</h1>
        <p style="color: #64748b; font-size: 0.875rem; margin-top: 0.25rem; font-weight: 600;">Pendaftaran Akun Baru</p>
    </div>

    @if ($errors->any())
        <div style="background: #fee2e2; border: 1px solid #fca5a5; border-radius: 12px; padding: 0.8rem 1rem; margin-bottom: 1.2rem;">
            <ul style="color: #b91c1c; margin: 0; padding-left: 1.2rem; font-size: 0.85rem; font-weight: 500;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('register.post') }}" method="POST" style="display: flex; flex-direction: column; gap: 1rem;">
        @csrf

        <input type="text" name="nama_lengkap" placeholder="Nama Lengkap" value="{{ old('nama_lengkap') }}" required
            style="width: 100%; padding: 0.75rem 1.2rem; border-radius: 9999px; border: 2px solid #cbd5e1; font-size: 0.875rem; outline: none; transition: border-color 0.2s;">

        <input type="text" name="nis_nip" placeholder="NIS / NIP" value="{{ old('nis_nip') }}" required
            style="width: 100%; padding: 0.75rem 1.2rem; border-radius: 9999px; border: 2px solid #cbd5e1; font-size: 0.875rem; outline: none; transition: border-color 0.2s;">

        <input type="email" name="email" placeholder="Alamat Email" value="{{ old('email') }}" required
            style="width: 100%; padding: 0.75rem 1.2rem; border-radius: 9999px; border: 2px solid #cbd5e1; font-size: 0.875rem; outline: none; transition: border-color 0.2s;">

        <input type="password" name="password" placeholder="Kata Sandi" required
            style="width: 100%; padding: 0.75rem 1.2rem; border-radius: 9999px; border: 2px solid #cbd5e1; font-size: 0.875rem; outline: none; transition: border-color 0.2s;">

        <input type="password" name="password_confirmation" placeholder="Konfirmasi Kata Sandi" required
            style="width: 100%; padding: 0.75rem 1.2rem; border-radius: 9999px; border: 2px solid #cbd5e1; font-size: 0.875rem; outline: none; transition: border-color 0.2s;">

        <input type="text" name="no_hp" placeholder="Nomor Telepon / WA" value="{{ old('no_hp') }}" required
            style="width: 100%; padding: 0.75rem 1.2rem; border-radius: 9999px; border: 2px solid #cbd5e1; font-size: 0.875rem; outline: none; transition: border-color 0.2s;">

        <!-- PILIHAN ROLE -->
        <div style="display: flex; gap: 1rem; justify-content: center; margin: 0.5rem 0; padding: 0.9rem; background: #eff6ff; border-radius: 14px; border: 2px solid #bfdbfe;">
            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.95rem; font-weight: 700; color: #1e40af;">
                <input type="radio" name="role" value="siswa" {{ old('role') === 'siswa' || !old('role') ? 'checked' : '' }} required style="width: 18px; height: 18px; cursor: pointer; accent-color: #2563eb;">
                Siswa
            </label>
            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.95rem; font-weight: 700; color: #1e40af;">
                <input type="radio" name="role" value="guru" {{ old('role') === 'guru' ? 'checked' : '' }} required style="width: 18px; height: 18px; cursor: pointer; accent-color: #2563eb;">
                Guru
            </label>
        </div>

        <button type="submit"
            style="width: 100%; padding: 0.85rem; background: linear-gradient(135deg, #2563eb, #1d4ed8); color: white; font-weight: 800; border: none; border-radius: 9999px; font-size: 0.95rem; letter-spacing: 0.05em; cursor: pointer; transition: all 0.3s; box-shadow: 0 4px 15px rgba(37, 99, 235, 0.4);">
            DAFTAR SEKARANG
        </button>
    </form>

    <p style="text-align: center; color: #64748b; font-size: 0.825rem; margin-top: 1.5rem;">
        Sudah punya akun? 
        <a href="{{ route('login') }}" style="color: #2563eb; font-weight: 700; text-decoration: none;">Masuk →</a>
    </p>
</div>
@endsection