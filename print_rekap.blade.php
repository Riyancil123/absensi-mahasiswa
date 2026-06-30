<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body {
                background-color: #ffffff;
                color: #000000;
            }
            .no-print {
                display: none !important;
            }
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #cbd5e1;
            padding: 8px 12px;
            text-align: left;
            font-size: 11px;
        }
        th {
            background-color: #f8fafc;
            font-weight: bold;
        }
    </style>
</head>
<body class="bg-white p-8 font-serif">

    <!-- Print Header Control Bar (No Print) -->
    <div class="no-print mb-6 p-4 bg-slate-100 rounded-xl flex items-center justify-between border border-slate-200">
        <span class="text-xs font-semibold text-slate-600">Mode Pratinjau Cetak Rekapitulasi Kehadiran. Klik tombol cetak untuk mencetak atau simpan ke PDF.</span>
        <button onclick="window.print()" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-lg shadow-sm transition-all flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak Dokumen
        </button>
    </div>

    <!-- Document Header -->
    <div class="text-center space-y-2 mb-8 border-b-2 border-slate-900 pb-4">
        <h1 class="text-xl font-bold uppercase tracking-wide">KEMENTERIAN PENDIDIKAN DAN KEBUDAYAAN</h1>
        <h2 class="text-lg font-bold uppercase tracking-wide">UNIVERSITAS INDONESIA</h2>
        <p class="text-xs italic font-sans text-slate-500">Kampus UI Depok, Jawa Barat - Telp: (021) 123456 - Email: info@ui.ac.id</p>
    </div>

    <!-- Report Info -->
    <div class="mb-6 space-y-1">
        <h3 class="text-base font-bold text-center underline uppercase mb-4">{{ $title }}</h3>
        <table class="!border-0 w-auto text-xs font-sans">
            <tr class="!border-0">
                <td class="!border-0 !p-1 w-28 font-bold">Mata Kuliah</td>
                <td class="!border-0 !p-1 w-4">:</td>
                <td class="!border-0 !p-1 font-semibold">{{ $kelas->nama_mata_kuliah }}</td>
            </tr>
            <tr class="!border-0">
                <td class="!border-0 !p-1 w-28 font-bold">Ruangan</td>
                <td class="!border-0 !p-1 w-4">:</td>
                <td class="!border-0 !p-1">{{ $kelas->ruangan }}</td>
            </tr>
            <tr class="!border-0">
                <td class="!border-0 !p-1 w-28 font-bold">Kategori Laporan</td>
                <td class="!border-0 !p-1 w-4">:</td>
                <td class="!border-0 !p-1">Rekapitulasi Total Kehadiran Mahasiswa</td>
            </tr>
            <tr class="!border-0">
                <td class="!border-0 !p-1 w-28 font-bold">Tanggal Unduh</td>
                <td class="!border-0 !p-1 w-4">:</td>
                <td class="!border-0 !p-1">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y | H:i') }} WIB</td>
            </tr>
        </table>
    </div>

    <!-- Data Table -->
    <table class="w-full font-sans mb-8">
        <thead>
            <tr>
                <th class="w-10 text-center">No</th>
                <th class="w-24">NIM</th>
                <th>Nama Mahasiswa</th>
                <th class="w-20 text-center">Kelas</th>
                <th>Jurusan</th>
                <th class="w-24 text-center">Total Sesi</th>
                <th class="w-16 text-center text-emerald-600">Hadir</th>
                <th class="w-16 text-center text-blue-600">Sakit</th>
                <th class="w-16 text-center text-amber-600">Izin</th>
                <th class="w-16 text-center text-rose-600">Alpa</th>
                <th class="w-20 text-center">Persentase</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rekapData as $index => $row)
                <tr>
                    <td class="text-center text-slate-500">{{ $index + 1 }}</td>
                    <td class="font-bold">{{ $row->nim }}</td>
                    <td class="font-semibold">{{ $row->nama }}</td>
                    <td class="text-center">{{ $row->kelas }}</td>
                    <td>{{ $row->jurusan }}</td>
                    <td class="text-center">{{ $row->total }} Hari</td>
                    <td class="text-center font-bold text-emerald-750">{{ $row->hadir }}</td>
                    <td class="text-center font-bold text-blue-750">{{ $row->sakit }}</td>
                    <td class="text-center font-bold text-amber-750">{{ $row->izin }}</td>
                    <td class="text-center font-bold text-rose-750">{{ $row->alpa }}</td>
                    <td class="text-center font-bold {{ $row->persentase >= 90 ? 'text-emerald-700' : ($row->persentase >= 80 ? 'text-amber-700' : 'text-rose-700') }}">
                        {{ $row->persentase }}%
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Signatures -->
    <div class="flex justify-between items-center text-xs font-sans pt-12">
        <div class="w-48 text-center space-y-16">
            <p>Petugas Presensi,</p>
            <div class="space-y-1">
                <p class="font-bold underline">Administrator Absensi</p>
                <p class="text-slate-500">NIP. 19900824 202012 1 002</p>
            </div>
        </div>
        <div class="w-48 text-center space-y-16">
            <p>Depok, {{ \Carbon\Carbon::today()->translatedFormat('d F Y') }}<br>Dosen Pengampu,</p>
            <div class="space-y-1">
                <p class="font-bold underline">{{ $kelas->dosen ? $kelas->dosen->name : 'Dosen Pengampu' }}</p>
                <p class="text-slate-500">NPP. {{ $kelas->dosen ? $kelas->dosen->username : '-' }}</p>
            </div>
        </div>
    </div>

    <!-- Auto Print Script -->
    <script>
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 500);
        });
    </script>
</body>
</html>
