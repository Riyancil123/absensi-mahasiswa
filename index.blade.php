@extends('layouts.app')

@section('title', 'Kelola Data Dosen')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Daftar Dosen</h1>
            <p class="text-sm text-slate-500">Kelola data dosen pengampu presensi mahasiswa.</p>
        </div>
        <a href="{{ route('dosen.create') }}" class="flex items-center gap-2 bg-gradient-to-tr from-brand-600 to-indigo-600 hover:from-brand-700 hover:to-indigo-700 text-white px-5 py-2.5 rounded-xl font-semibold text-sm shadow-md shadow-brand-500/20 hover:shadow-lg transition-all duration-150">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Dosen</span>
        </a>
    </div>

    <!-- Search Section -->
    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex flex-col md:flex-row gap-4 items-center justify-between">
        <form action="{{ route('dosen.index') }}" method="GET" class="w-full md:w-auto flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
            <div class="relative flex-1 min-w-[280px]">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau NPP dosen..." class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-brand-500 focus:bg-white transition-all">
            </div>
            <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white font-medium text-sm px-5 py-2.5 rounded-xl transition-all">
                Cari
            </button>
            @if($search)
                <a href="{{ route('dosen.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-medium text-sm px-5 py-2.5 rounded-xl text-center transition-all">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 text-xs font-semibold uppercase tracking-wider">
                        <th class="px-6 py-4">Nama Dosen</th>
                        <th class="px-6 py-4">NPP (Username)</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">No. HP</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
                    @forelse($dosens as $dosen)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 font-semibold text-slate-800">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center font-bold">
                                        {{ strtoupper(substr($dosen->name, 0, 2)) }}
                                    </div>
                                    <span>{{ $dosen->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-medium text-slate-700">
                                {{ $dosen->username }}
                            </td>
                            <td class="px-6 py-4 text-slate-500">
                                {{ $dosen->email }}
                            </td>
                            <td class="px-6 py-4 text-slate-500">
                                {{ $dosen->no_hp ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Edit Button -->
                                    <a href="{{ route('dosen.edit', $dosen->id) }}" class="text-slate-500 hover:text-brand-600 hover:bg-slate-100 p-2 rounded-lg transition-all" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>

                                    <!-- Delete Button -->
                                    <form action="{{ route('dosen.destroy', $dosen->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" data-confirm-delete="Apakah Anda yakin ingin menghapus data dosen ini? Semua data kelas kuliah yang diampu dosen ini juga akan terhapus." class="text-slate-500 hover:text-rose-600 hover:bg-rose-50 p-2 rounded-lg transition-all" title="Hapus">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-400">
                                <i class="fa-solid fa-folder-open text-3xl mb-3 block"></i>
                                Tidak ada data dosen yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($dosens->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $dosens->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
