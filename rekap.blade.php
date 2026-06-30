@extends('layouts.app')

@section('title', 'Rekap Absensi')

@section('content')
<div class="space-y-6">
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Rekapitulasi Kehadiran Mahasiswa</h1>
            <p class="text-xs text-slate-400 font-medium">Akumulasi total kehadiran mahasiswa dihitung dari seluruh sesi presensi yang tersimpan.</p>
        </div>
        @if($selectedKelasKuliahId)
            <a href="{{ route('presensi.print', ['kelas_kuliah_id' => $selectedKelasKuliahId]) }}" target="_blank" class="px-5 py-3 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-sm rounded-2xl shadow-lg shadow-slate-900/10 transition-all flex items-center gap-2 group hover:-translate-y-0.5">
                <i class="fa-solid fa-print"></i>
                <span>Cetak Rekap Kelas</span>
            </a>
        @endif
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm">
        <form action="{{ route('presensi.rekap') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <!-- Kelas Kuliah Filter -->
            <div class="space-y-1.5">
                <label for="kelas_kuliah_id" class="text-xs font-bold text-slate-500 uppercase tracking-wider">Kelas Kuliah</label>
                <select name="kelas_kuliah_id" id="kelas_kuliah_id" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-brand-500 focus:bg-white transition-all font-semibold">
                    @foreach($listKelasKuliah as $k)
                        <option value="{{ $k->id }}" {{ $selectedKelasKuliahId == $k->id ? 'selected' : '' }}>{{ $k->nama_mata_kuliah }} ({{ $k->ruangan }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Search Input -->
            <div class="space-y-1.5">
                <label for="search" class="text-xs font-bold text-slate-500 uppercase tracking-wider">Cari Mahasiswa</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="text" name="search" id="search" value="{{ $search }}" placeholder="Nama atau NIM..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-brand-500 focus:bg-white transition-all">
                </div>
            </div>

            <!-- Kelas Filter -->
            <div class="space-y-1.5">
                <label for="kelas" class="text-xs font-bold text-slate-500 uppercase tracking-wider">Kelas Mahasiswa</label>
                <select name="kelas" id="kelas" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-brand-500 focus:bg-white transition-all font-semibold">
                    <option value="">Semua Kelas</option>
                    @foreach($listKelas as $k)
                        <option value="{{ $k }}" {{ $kelas == $k ? 'selected' : '' }}>{{ $k }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Action Buttons for filter -->
            <div class="flex gap-2.5">
                <button type="submit" class="flex-1 px-4 py-2.5 bg-brand-600 hover:bg-brand-500 text-white font-semibold text-sm rounded-xl shadow-md shadow-brand-600/10 transition-all flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-filter text-xs"></i>
                    <span>Filter</span>
                </button>
                @if($search || $kelas || $kelasKuliahId)
                    <a href="{{ route('presensi.rekap') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-sm rounded-xl transition-all flex items-center justify-center">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        @if($rekapData->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-400 text-[11px] font-bold uppercase tracking-wider">
                            <th class="py-4 px-6 w-16 text-center">No</th>
                            <th class="py-4 px-6 w-32">NIM</th>
                            <th class="py-4 px-6">Nama Mahasiswa</th>
                            <th class="py-4 px-6 w-24 text-center">Kelas</th>
                            <th class="py-4 px-6 text-center w-28">Total Pertemuan</th>
                            <th class="py-4 px-6 text-center w-20 text-emerald-600">Hadir</th>
                            <th class="py-4 px-6 text-center w-20 text-blue-600">Sakit</th>
                            <th class="py-4 px-6 text-center w-20 text-amber-600">Izin</th>
                            <th class="py-4 px-6 text-center w-20 text-rose-600">Alpa</th>
                            <th class="py-4 px-6 text-center w-36">Persentase</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm font-medium text-slate-700">
                        @foreach($rekapData as $index => $row)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="py-4 px-6 text-center text-slate-400">
                                    {{ $index + 1 }}
                                </td>
                                <td class="py-4 px-6 font-semibold text-slate-800">
                                    {{ $row->mahasiswa->username }}
                                </td>
                                <td class="py-4 px-6">
                                    <span class="block font-bold text-slate-800 group-hover:text-brand-600 transition-colors">{{ $row->mahasiswa->name }}</span>
                                    <span class="text-[10px] text-slate-400 font-semibold">{{ $row->mahasiswa->jurusan }}</span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <span class="inline-block px-2.5 py-0.5 text-xs font-bold bg-indigo-50 text-indigo-600 border border-indigo-100 rounded-full">
                                        {{ $row->mahasiswa->kelas }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-center font-semibold text-slate-650">
                                    {{ $row->total }} Hari
                                </td>
                                <td class="py-4 px-6 text-center text-emerald-600 font-bold">
                                    {{ $row->hadir }}
                                </td>
                                <td class="py-4 px-6 text-center text-blue-600 font-bold">
                                    {{ $row->sakit }}
                                </td>
                                <td class="py-4 px-6 text-center text-amber-600 font-bold">
                                    {{ $row->izin }}
                                </td>
                                <td class="py-4 px-6 text-center text-rose-600 font-bold">
                                    {{ $row->alpa }}
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- Visual Progress Bar -->
                                        <div class="w-16 bg-slate-100 rounded-full h-1.5 overflow-hidden hidden sm:block">
                                            <div class="h-full rounded-full {{ $row->persentase >= 90 ? 'bg-emerald-500' : ($row->persentase >= 80 ? 'bg-amber-500' : 'bg-rose-500') }}" style="width: {{ $row->persentase }}%"></div>
                                        </div>
                                        
                                        <!-- Text Badge -->
                                        @if($row->persentase >= 90)
                                            <span class="px-2 py-0.5 text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-lg">
                                                {{ $row->persentase }}%
                                            </span>
                                        @elseif($row->persentase >= 80)
                                            <span class="px-2 py-0.5 text-xs font-bold bg-amber-50 text-amber-700 border border-amber-100 rounded-lg">
                                                {{ $row->persentase }}%
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 text-xs font-bold bg-rose-50 text-rose-700 border border-rose-100 rounded-lg">
                                                {{ $row->persentase }}%
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="py-20 text-center space-y-4">
                <div class="w-16 h-16 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-300 mx-auto">
                    <i class="fa-solid fa-file-invoice text-2xl"></i>
                </div>
                <div class="max-w-xs mx-auto space-y-1">
                    <h3 class="font-bold text-slate-700">Data Rekap Tidak Ditemukan</h3>
                    <p class="text-xs text-slate-400 font-medium">Data mahasiswa kosong atau filter pencarian tidak mencocokkan mahasiswa mana pun pada kelas kuliah ini.</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
