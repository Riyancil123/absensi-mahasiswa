@extends('layouts.app')

@section('title', 'Tambah Data Dosen')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('dosen.index') }}" class="w-10 h-10 rounded-xl bg-white text-slate-500 border border-slate-200 flex items-center justify-center hover:bg-slate-50 hover:text-slate-700 transition-colors shadow-sm">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Tambah Dosen</h1>
            <p class="text-sm text-slate-500">Daftarkan dosen baru ke dalam sistem.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 md:p-8">
        <form action="{{ route('dosen.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- NPP -->
            <div>
                <label for="npp" class="block text-sm font-semibold text-slate-700 mb-2">NPP (Nomor Pokok Pendidik)</label>
                <div class="relative">
                    <i class="fa-solid fa-id-card absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" id="npp" name="npp" value="{{ old('npp') }}" placeholder="Contoh: 1974020101" class="w-full pl-11 pr-4 py-3 bg-slate-50 border @error('npp') border-rose-300 focus:border-rose-500 @else border-slate-200 focus:border-brand-500 @enderror rounded-xl text-sm focus:outline-none focus:bg-white transition-all">
                </div>
                @error('npp')
                    <p class="mt-2 text-xs text-rose-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nama Lengkap -->
            <div>
                <label for="nama" class="block text-sm font-semibold text-slate-700 mb-2">Nama Lengkap (Beserta Gelar)</label>
                <div class="relative">
                    <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" id="nama" name="nama" value="{{ old('nama') }}" placeholder="Contoh: Dr. Ir. John Doe, M.T." class="w-full pl-11 pr-4 py-3 bg-slate-50 border @error('nama') border-rose-300 focus:border-rose-500 @else border-slate-200 focus:border-brand-500 @enderror rounded-xl text-sm focus:outline-none focus:bg-white transition-all">
                </div>
                @error('nama')
                    <p class="mt-2 text-xs text-rose-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">Alamat Email</label>
                <div class="relative">
                    <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Contoh: dosen@kampus.ac.id" class="w-full pl-11 pr-4 py-3 bg-slate-50 border @error('email') border-rose-300 focus:border-rose-500 @else border-slate-200 focus:border-brand-500 @enderror rounded-xl text-sm focus:outline-none focus:bg-white transition-all">
                </div>
                @error('email')
                    <p class="mt-2 text-xs text-rose-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- No. HP -->
            <div>
                <label for="no_hp" class="block text-sm font-semibold text-slate-700 mb-2">No. HP (Opsional)</label>
                <div class="relative">
                    <i class="fa-solid fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp') }}" placeholder="Contoh: 08123456789" class="w-full pl-11 pr-4 py-3 bg-slate-50 border @error('no_hp') border-rose-300 focus:border-rose-500 @else border-slate-200 focus:border-brand-500 @enderror rounded-xl text-sm focus:outline-none focus:bg-white transition-all">
                </div>
                @error('no_hp')
                    <p class="mt-2 text-xs text-rose-500">{{ $message }}</p>
                @enderror
            </div>

            <p class="text-xs text-slate-400"><i class="fa-solid fa-circle-info mr-1"></i> Password bawaan untuk akun dosen baru adalah <span class="font-semibold text-slate-500">password</span>.</p>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('dosen.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-sm px-6 py-3 rounded-xl transition-all">
                    Batal
                </a>
                <button type="submit" class="bg-gradient-to-tr from-brand-600 to-indigo-600 hover:from-brand-700 hover:to-indigo-700 text-white font-semibold text-sm px-6 py-3 rounded-xl shadow-md shadow-brand-500/10 transition-all duration-150">
                    Simpan Data Dosen
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
