@extends('layouts.app')

@section('title', Auth::user()->role === 'admin' ? 'Dashboard Admin Utama' : 'Dashboard Dosen Pengampu')

@section('styles')
<!-- Leaflet CSS for Map -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<style>
    #map {
        height: 280px;
        z-index: 10;
    }
</style>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Welcome Banner (Green Theme) -->
    <div class="relative bg-gradient-to-r from-slate-900 via-slate-800 to-emerald-950 p-6 md:p-8 rounded-3xl overflow-hidden shadow-xl shadow-slate-900/10 text-white flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 -ml-10 -mb-10 w-48 h-48 bg-teal-500/10 rounded-full blur-2xl pointer-events-none"></div>
        
        <div class="relative space-y-1">
            <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight">
                {{ Auth::user()->role === 'admin' ? 'Portal Administrator Utama' : 'Portal Dosen Pengampu' }}
            </h2>
            <p class="text-slate-300 text-sm md:text-base font-medium max-w-2xl">
                {{ Auth::user()->role === 'admin' 
                    ? 'Kelola data master pengguna, kontrol seluruh kelas perkuliahan, dan atur parameter GPS geofencing presensi kampus.' 
                    : 'Monitor rekap kehadiran mahasiswa dan buka/tutup sesi absensi kelas kuliah yang Anda ampu.' }}
            </p>
        </div>
        <div class="relative flex flex-wrap gap-2.5 shrink-0 w-full lg:w-auto justify-end">
            @if(Auth::user()->role === 'admin')
                <a href="{{ route('kelas-kuliah.create') }}" class="px-5 py-3 bg-brand-600 hover:bg-brand-500 text-white font-semibold text-sm rounded-2xl shadow-lg shadow-brand-600/30 transition-all flex items-center gap-2 group hover:-translate-y-0.5">
                    <i class="fa-solid fa-plus transition-transform group-hover:rotate-90"></i>
                    <span>Tambah Kelas Kuliah</span>
                </a>
            @else
                <a href="{{ route('presensi.index') }}" class="px-5 py-3 bg-brand-600 hover:bg-brand-500 text-white font-semibold text-sm rounded-2xl shadow-lg shadow-brand-600/30 transition-all flex items-center gap-2 group hover:-translate-y-0.5">
                    <i class="fa-solid fa-clipboard-user"></i>
                    <span>Mulai Monitoring Kehadiran</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 md:gap-6">
        <!-- Stat Card 1 -->
        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-100 text-brand-600 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-user-graduate text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Mahasiswa</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-0.5">{{ $totalMahasiswa }}</h3>
            </div>
        </div>

        <!-- Stat Card 2 -->
        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-user-check text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Hadir Hari Ini</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-0.5">{{ $hadirToday }}</h3>
            </div>
        </div>

        <!-- Stat Card 3 -->
        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-2xl bg-teal-50 border border-teal-100 text-teal-600 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-notes-medical text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Sakit / Izin</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-0.5">{{ $sakitToday + $izinToday }}</h3>
            </div>
        </div>

        <!-- Stat Card 4 -->
        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-2xl bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-user-slash text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Alpa Hari Ini</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-0.5">{{ $alpaToday }}</h3>
            </div>
        </div>

        <!-- Stat Card 5 -->
        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow col-span-2 lg:col-span-1">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-chart-line text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Persentase</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-0.5">{{ $attendanceRate }}%</h3>
            </div>
        </div>
    </div>

    <!-- Geofencing & Class Controls -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Map Panel (Left Columns) -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm lg:col-span-2 space-y-4">
            <div>
                <h3 class="font-bold text-slate-800 text-lg">Area Geofencing Kelas Absensi</h3>
                <p class="text-xs text-slate-400 font-medium">
                    @if(Auth::user()->role === 'admin')
                        Geser penanda pin (marker) biru di peta atau klik area peta manapun untuk merubah koordinat GPS kelas absensi.
                    @else
                        Posisi pusat GPS perkuliahan yang diizinkan untuk melakukan presensi mandiri (Hanya Admin yang dapat merubah).
                    @endif
                </p>
            </div>
            <div id="map" class="rounded-2xl border border-slate-200 overflow-hidden shadow-inner"></div>
        </div>

        <!-- Form settings Panel (Right Column) -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="font-bold text-slate-800 text-lg">Koordinat & Radius Geofence</h3>
                <p class="text-xs text-slate-400 font-medium mb-4">Parameter wilayah absen mandiri berbasis lokasi GPS.</p>
                
                @if(Auth::user()->role === 'admin')
                    <form action="{{ route('settings.update') }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <!-- Latitude -->
                        <div class="space-y-1.5">
                            <label for="location_latitude" class="text-xs font-bold text-slate-500 uppercase tracking-wider">Latitude</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                                    <i class="fa-solid fa-map-pin"></i>
                                </span>
                                <input type="text" name="location_latitude" id="location_latitude" value="{{ $classLat }}" class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:border-brand-500 focus:bg-white transition-all font-semibold">
                            </div>
                        </div>

                        <!-- Longitude -->
                        <div class="space-y-1.5">
                            <label for="location_longitude" class="text-xs font-bold text-slate-500 uppercase tracking-wider">Longitude</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                                    <i class="fa-solid fa-map-pin"></i>
                                </span>
                                <input type="text" name="location_longitude" id="location_longitude" value="{{ $classLon }}" class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:border-brand-500 focus:bg-white transition-all font-semibold">
                            </div>
                        </div>

                        <!-- Radius Limit -->
                        <div class="space-y-1.5">
                            <label for="allowed_radius" class="text-xs font-bold text-slate-500 uppercase tracking-wider">Radius Toleransi (Meter)</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 font-bold text-xs">m</span>
                                <input type="number" name="allowed_radius" id="allowed_radius" min="5" max="2000" value="{{ $allowedRadius }}" class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-brand-500 focus:bg-white transition-all font-semibold">
                            </div>
                            @error('allowed_radius')
                                <p class="text-xs font-semibold text-rose-500 flex items-center gap-1 mt-1"><i class="fa-solid fa-circle-exclamation text-[10px]"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="w-full py-3 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-md shadow-brand-600/10 transition-all flex items-center justify-center gap-2">
                            <i class="fa-solid fa-floppy-disk"></i>
                            <span>Simpan Parameter GPS</span>
                        </button>
                    </form>
                @else
                    <div class="space-y-4">
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-3">
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase">Latitude</p>
                                <p class="text-sm font-semibold text-slate-800">{{ $classLat }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase">Longitude</p>
                                <p class="text-sm font-semibold text-slate-800">{{ $classLon }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase">Radius Toleransi</p>
                                <p class="text-sm font-semibold text-slate-800">{{ $allowedRadius }} meter</p>
                            </div>
                        </div>
                        <div class="p-3 bg-amber-50 border border-amber-100 text-amber-800 rounded-xl flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-info text-amber-500 mt-0.5"></i>
                            <p class="text-[11px] font-medium leading-relaxed">Hanya akun dengan peran **Admin** yang memiliki wewenang untuk memperbarui koordinat maps dan batas radius jangkauan presensi.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Kelas Kuliah Session Control -->
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-4">
        <div>
            <h3 class="font-bold text-slate-800 text-lg">Kontrol Sesi Absensi Kelas</h3>
            <p class="text-xs text-slate-400 font-medium">Buka atau tutup sesi absen mandiri mahasiswa pada masing-masing kelas kuliah di bawah ini.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 text-xs font-semibold uppercase tracking-wider">
                        <th class="px-6 py-4">Mata Kuliah</th>
                        <th class="px-6 py-4">Dosen Pengampu</th>
                        <th class="px-6 py-4">Ruangan</th>
                        <th class="px-6 py-4">Status Sesi</th>
                        <th class="px-6 py-4 text-center">Aksi Toggle</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
                    @forelse($kelasKuliahs as $kelas)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 font-semibold text-slate-800">
                                {{ $kelas->nama_mata_kuliah }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $kelas->dosen->name }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-slate-100 text-slate-700 px-2.5 py-1 rounded-lg text-xs font-medium border border-slate-200">
                                    {{ $kelas->ruangan }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($kelas->status_absen)
                                    <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 px-2.5 py-1 rounded-full text-xs font-semibold border border-emerald-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Absen Dibuka
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-500 px-2.5 py-1 rounded-full text-xs font-semibold border border-slate-200">
                                        Absen Ditutup
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <form action="{{ route('kelas.toggle-absen', $kelas->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @if($kelas->status_absen)
                                        <button type="submit" class="bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-100 px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all shadow-sm">
                                            <i class="fa-solid fa-lock mr-1.5"></i>Tutup Sesi
                                        </button>
                                    @else
                                        <button type="submit" class="bg-emerald-50 hover:bg-emerald-100 text-emerald-600 border border-emerald-100 px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all shadow-sm">
                                            <i class="fa-solid fa-lock-open mr-1.5"></i>Buka Sesi
                                        </button>
                                    @endif
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-400">
                                <i class="fa-solid fa-folder-open text-3xl mb-3 block"></i>
                                Tidak ada kelas kuliah yang ditugaskan kepada Anda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Line Chart: Weekly Trend -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm lg:col-span-2 space-y-4">
            <div>
                <h3 class="font-bold text-slate-800 text-lg">Grafik Tren Presensi</h3>
                <p class="text-xs text-slate-400 font-medium">Statistik jumlah kehadiran harian dalam 7 hari kuliah aktif terakhir</p>
            </div>
            <div class="h-80 w-full relative">
                <canvas id="weeklyTrendChart"></canvas>
            </div>
        </div>

        <!-- Doughnut Chart: Today's Distribution -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-4">
            <div>
                <h3 class="font-bold text-slate-800 text-lg">Distribusi Kehadiran Hari Ini</h3>
                <p class="text-xs text-slate-400 font-medium">Rasio status presensi (Hadir / Sakit / Izin / Alpa)</p>
            </div>
            <div class="h-64 w-full relative flex items-center justify-center">
                @if($totalPresensiToday > 0)
                    <canvas id="todayDistributionChart"></canvas>
                @else
                    <div class="text-center space-y-2.5 p-4">
                        <div class="w-16 h-16 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-300 mx-auto">
                            <i class="fa-solid fa-calendar-minus text-2xl"></i>
                        </div>
                        <p class="text-xs font-semibold text-slate-400">Belum ada data presensi hari ini.</p>
                        <span class="inline-block text-[11px] text-slate-400 font-medium">Mahasiswa dapat absen mandiri setelah sesi kelas dibuka.</span>
                    </div>
                @endif
            </div>
            @if($totalPresensiToday > 0)
            <div class="grid grid-cols-2 gap-3 text-xs pt-2">
                <div class="flex items-center gap-2 bg-emerald-50/50 p-2 rounded-xl border border-emerald-100/50">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    <span class="text-slate-600 font-semibold">Hadir: <b>{{ $hadirToday }}</b></span>
                </div>
                <div class="flex items-center gap-2 bg-blue-50/50 p-2 rounded-xl border border-blue-100/50">
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                    <span class="text-slate-600 font-semibold">Sakit: <b>{{ $sakitToday }}</b></span>
                </div>
                <div class="flex items-center gap-2 bg-amber-50/50 p-2 rounded-xl border border-amber-100/50">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                    <span class="text-slate-600 font-semibold">Izin: <b>{{ $izinToday }}</b></span>
                </div>
                <div class="flex items-center gap-2 bg-rose-50/50 p-2 rounded-xl border border-rose-100/50">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                    <span class="text-slate-600 font-semibold">Alpa: <b>{{ $alpaToday }}</b></span>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Leaflet JS for Map -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- 1. Leaflet Map Setup ---
        const initialLat = {{ $classLat }};
        const initialLon = {{ $classLon }};
        
        var map = L.map('map').setView([initialLat, initialLon], 16);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Draggable Marker if admin, otherwise locked
        var marker = L.marker([initialLat, initialLon], {
            draggable: {{ Auth::user()->role === 'admin' ? 'true' : 'false' }}
        }).addTo(map);

        // Circular representation of the geofenced radius limit (Green Theme)
        var circle = L.circle([initialLat, initialLon], {
            color: '#10b981',
            fillColor: '#a7f3d0',
            fillOpacity: 0.15,
            radius: {{ $allowedRadius }}
        }).addTo(map);

        @if(Auth::user()->role === 'admin')
        // Update inputs on marker drag end
        marker.on('dragend', function(e) {
            var position = marker.getLatLng();
            document.getElementById('location_latitude').value = position.lat.toFixed(6);
            document.getElementById('location_longitude').value = position.lng.toFixed(6);
            circle.setLatLng(position);
        });

        // Update marker and inputs on map click
        map.on('click', function(e) {
            var position = e.latlng;
            marker.setLatLng(position);
            circle.setLatLng(position);
            document.getElementById('location_latitude').value = position.lat.toFixed(6);
            document.getElementById('location_longitude').value = position.lng.toFixed(6);
        });

        // Update map marker, circle, and view when inputs are edited manually
        const latInput = document.getElementById('location_latitude');
        const lonInput = document.getElementById('location_longitude');

        function updateMapFromInputs() {
            const lat = parseFloat(latInput.value);
            const lon = parseFloat(lonInput.value);
            if (!isNaN(lat) && !isNaN(lon) && lat >= -90 && lat <= 90 && lon >= -180 && lon <= 180) {
                const newPos = [lat, lon];
                marker.setLatLng(newPos);
                circle.setLatLng(newPos);
                map.setView(newPos, map.getZoom());
            }
        }

        if (latInput) latInput.addEventListener('input', updateMapFromInputs);
        if (lonInput) lonInput.addEventListener('input', updateMapFromInputs);

        // Dynamic radius circles updates
        const radiusInput = document.getElementById('allowed_radius');
        if(radiusInput) {
            radiusInput.addEventListener('input', function() {
                const newRadius = parseInt(this.value);
                if(newRadius >= 5) {
                    circle.setRadius(newRadius);
                }
            });
        }
        @endif

        // --- 2. Chart.js Configurations (Green Theme primary) ---
        const weeklyCtx = document.getElementById('weeklyTrendChart');
        if(weeklyCtx) {
            new Chart(weeklyCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($dates) !!},
                    datasets: [
                        {
                            label: 'Hadir',
                            data: {!! json_encode($chartData['Hadir']) !!},
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.05)',
                            fill: true,
                            tension: 0.35,
                            borderWidth: 3
                        },
                        {
                            label: 'Sakit',
                            data: {!! json_encode($chartData['Sakit']) !!},
                            borderColor: '#3b82f6',
                            borderWidth: 2,
                            fill: false
                        },
                        {
                            label: 'Izin',
                            data: {!! json_encode($chartData['Izin']) !!},
                            borderColor: '#f59e0b',
                            borderWidth: 2,
                            fill: false
                        },
                        {
                            label: 'Alpa',
                            data: {!! json_encode($chartData['Alpa']) !!},
                            borderColor: '#ef4444',
                            borderWidth: 2,
                            fill: false
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: { usePointStyle: true, font: { family: 'Plus Jakarta Sans', size: 11 } }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0, font: { family: 'Plus Jakarta Sans', size: 10 } }
                        },
                        x: {
                            ticks: { font: { family: 'Plus Jakarta Sans', size: 10 } }
                        }
                    }
                }
            });
        }

        const todayCtx = document.getElementById('todayDistributionChart');
        if (todayCtx) {
            new Chart(todayCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Hadir', 'Sakit', 'Izin', 'Alpa'],
                    datasets: [{
                        data: [{{ $hadirToday }}, {{ $sakitToday }}, {{ $izinToday }}, {{ $alpaToday }}],
                        backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444'],
                        borderWidth: 3,
                        borderColor: '#ffffff',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '72%',
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }
    });
</script>
@endsection
