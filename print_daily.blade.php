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
            font-size: 12px;
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
        <span class="text-xs font-semibold text-slate-600">Mode Pratinjau Cetak. Klik tombol cetak untuk mencetak atau simpan ke PDF.</span>
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
                <td class="!border-0 !p-1 w-28 font-bold">Tanggal</td>
                <td class="!border-0 !p-1 w-4">:</td>
                <td class="!border-0 !p-1">{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}</td>
            </tr>
            <tr class="!border-0">
                <td class="!border-0 !p-1 w-28 font-bold">Kategori Laporan</td>
                <td class="!border-0 !p-1 w-4">:</td>
                <td class="!border-0 !p-1">Presensi Kelas Kuliah Mahasiswa</td>
            </tr>
        </table>
    </div>

    <!-- Data Table -->
    <table class="w-full font-sans mb-8">
        <thead>
            <tr>
                <th class="w-12 text-center">No</th>
                <th class="w-32">NIM</th>
                <th>Nama Mahasiswa</th>
                <th class="w-24 text-center">Kelas</th>
                <th class="w-32 text-center">Status Kehadiran</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($presensis as $index => $p)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="font-bold">{{ $p->user->username }}</td>
                    <td>
                        <div>{{ $p->user->name }}</div>
                        @if($p->latitude && $p->longitude)
                            <div class="text-[9px] text-slate-500 font-sans mt-0.5">GPS: {{ round($p->latitude, 5) }}, {{ round($p->longitude, 5) }} &bull; Jarak: {{ round($p->jarak, 1) }}m</div>
                        @endif
                    </td>
                    <td class="text-center">{{ $p->user->kelas }}</td>
                    <td class="text-center font-bold">
                        @if($p->status == 'Hadir')
                            <span class="text-emerald-700">HADIR</span>
                        @elseif($p->status == 'Sakit')
                            <span class="text-blue-700">SAKIT</span>
                        @elseif($p->status == 'Izin')
                            <span class="text-amber-700">IZIN</span>
                        @else
                            <span class="text-rose-700">ALPA</span>
                        @endif
                    </td>
                    <td class="text-slate-600 italic">{{ $p->keterangan ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Summary Count Block -->
    <div class="grid grid-cols-4 gap-4 max-w-xl font-sans text-xs mb-12 border border-slate-300 p-4 rounded bg-slate-50">
        <div>Hadir: <b>{{ $presensis->where('status', 'Hadir')->count() }} orang</b></div>
        <div>Sakit: <b>{{ $presensis->where('status', 'Sakit')->count() }} orang</b></div>
        <div>Izin: <b>{{ $presensis->where('status', 'Izin')->count() }} orang</b></div>
        <div>Alpa: <b>{{ $presensis->where('status', 'Alpa')->count() }} orang</b></div>
    </div>

    <!-- Signatures -->
    <div class="flex justify-between items-center text-xs font-sans pt-8">
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
