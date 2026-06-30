@extends('layouts.app')

@section('title', 'Portal Presensi Mandiri')

@section('styles')
<!-- Leaflet CSS for Map -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<style>
    #map-student {
        height: 250px;
        z-index: 10;
        border-radius: 1rem;
    }
</style>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Welcome Header -->
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Portal Kehadiran Mahasiswa</h1>
            <p class="text-xs text-slate-400 font-medium font-sans">Hai {{ $user->name }}, silakan lihat jadwal kelas hari ini dan lakukan presensi mandiri.</p>
        </div>
        <div class="text-xs font-semibold text-slate-500 bg-slate-50 border border-slate-200 px-4 py-2.5 rounded-2xl">
            NIM: <span class="text-slate-800 font-bold">{{ $user->username }}</span> &bull; Kelas: <span class="text-slate-800 font-bold">{{ $user->kelas }}</span>
        </div>
    </div>

    <!-- Attendance Stats & Class Sessions Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Classes List Section (Left columns) -->
        <div class="lg:col-span-2 space-y-4">
            <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                <i class="fa-solid fa-graduation-cap text-emerald-500"></i>
                <span>Jadwal Kelas Kuliah Hari Ini</span>
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @forelse($kelasKuliahs as $kelas)
                    @php
                        $attendance = $todayAttendances->get($kelas->id);
                    @endphp
                    <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow relative overflow-hidden group">
                        @if($attendance)
                            <div class="absolute right-0 top-0 w-24 h-24 bg-emerald-500/5 rounded-full translate-x-6 -translate-y-6 flex items-center justify-center text-emerald-500 opacity-20 group-hover:scale-110 transition-transform">
                                <i class="fa-solid fa-circle-check text-4xl"></i>
                            </div>
                        @endif

                        <div class="space-y-2">
                            <!-- Header Info -->
                            <div class="flex justify-between items-start">
                                <span class="bg-slate-100 text-slate-700 px-2.5 py-1 rounded-lg text-[10px] font-bold border border-slate-200 uppercase">
                                    {{ $kelas->ruangan }}
                                </span>
                                @if($attendance)
                                    <span class="bg-emerald-50 text-emerald-700 px-2.5 py-1 rounded-full text-[10px] font-bold border border-emerald-100 uppercase">
                                        Sudah Absen: {{ $attendance->status }}
                                    </span>
                                @elseif($kelas->status_absen)
                                    <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-600 px-2.5 py-1 rounded-full text-[10px] font-bold border border-emerald-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Sesi Dibuka
                                    </span>
                                @else
                                    <span class="bg-slate-100 text-slate-500 px-2.5 py-1 rounded-full text-[10px] font-bold border border-slate-200 uppercase">
                                        Ditutup
                                    </span>
                                @endif
                            </div>

                            <!-- Course Title & Dosen -->
                            <div>
                                <h4 class="font-extrabold text-slate-800 text-base leading-snug group-hover:text-brand-600 transition-colors">
                                    {{ $kelas->nama_mata_kuliah }}
                                </h4>
                                <p class="text-xs text-slate-400 font-medium mt-0.5"><i class="fa-solid fa-user-tie mr-1 text-[10px]"></i>{{ $kelas->dosen->name }}</p>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <div class="pt-4 border-t border-slate-100 mt-4 flex items-center justify-between text-xs">
                            @if($attendance)
                                <div class="text-[10px] text-slate-400 font-medium space-y-0.5">
                                    <p><i class="fa-regular fa-clock mr-1"></i>{{ $attendance->created_at->translatedFormat('H:i') }} WIB</p>
                                    @if($attendance->jarak)
                                        <p><i class="fa-solid fa-location-dot mr-1"></i>{{ round($attendance->jarak, 1) }}m dari pusat</p>
                                    @endif
                                </div>
                                <span class="text-slate-400 font-bold flex items-center gap-1"><i class="fa-solid fa-check-double text-emerald-500"></i> Terkirim</span>
                            @elseif($kelas->status_absen)
                                <button type="button" onclick="openCheckInModal({{ $kelas->id }}, '{{ addslashes($kelas->nama_mata_kuliah) }}')" class="bg-brand-600 hover:bg-brand-500 text-white font-bold px-4 py-2 rounded-xl shadow-sm transition-all hover:scale-102">
                                    Absen Mandiri
                                </button>
                            @else
                                <span class="text-slate-400 italic">Sesi ditutup</span>
                                <button disabled class="bg-slate-100 text-slate-400 font-bold px-4 py-2 rounded-xl cursor-not-allowed border border-slate-200">
                                    Terkunci
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 bg-white py-16 text-center space-y-4 rounded-3xl border border-slate-100 shadow-sm">
                        <div class="w-16 h-16 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-300 mx-auto">
                            <i class="fa-solid fa-calendar-minus text-2xl"></i>
                        </div>
                        <div class="max-w-xs mx-auto space-y-1">
                            <h3 class="font-bold text-slate-700">Kelas Tidak Tersedia</h3>
                            <p class="text-xs text-slate-400 font-medium">Hari ini tidak ada kelas kuliah yang terdaftar di sistem.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Personal Stats & Log (Right Column) -->
        <div class="space-y-6">
            <!-- Attendance Rate Panel -->
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-4">
                <div class="text-center space-y-2">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Persentase Kehadiran Anda</p>
                    <h2 class="text-4xl font-extrabold text-brand-600">{{ $hadirRate }}%</h2>
                    <p class="text-[11px] text-slate-400 font-medium font-sans">Dihitung dari total {{ $totalSesi }} sesi perkuliahan aktif</p>
                </div>
                
                <div class="grid grid-cols-2 gap-2 text-xs font-semibold pt-2">
                    <div class="p-2 bg-emerald-50 rounded-xl border border-emerald-100 text-emerald-800 flex justify-between items-center">
                        <span>Hadir:</span>
                        <b class="text-emerald-600 font-bold">{{ $hadir }}</b>
                    </div>
                    <div class="p-2 bg-blue-50 rounded-xl border border-blue-100 text-blue-800 flex justify-between items-center">
                        <span>Sakit:</span>
                        <b class="text-blue-600 font-bold">{{ $sakit }}</b>
                    </div>
                    <div class="p-2 bg-amber-50 rounded-xl border border-amber-100 text-amber-800 flex justify-between items-center">
                        <span>Izin:</span>
                        <b class="text-amber-600 font-bold">{{ $izin }}</b>
                    </div>
                    <div class="p-2 bg-rose-50 rounded-xl border border-rose-100 text-rose-800 flex justify-between items-center">
                        <span>Alpa:</span>
                        <b class="text-rose-600 font-bold">{{ $alpa }}</b>
                    </div>
                </div>
            </div>

            <!-- Recent logs -->
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-4">
                <div>
                    <h3 class="font-bold text-slate-800 text-sm">Riwayat Absensi Terakhir</h3>
                    <p class="text-[11px] text-slate-400 font-medium font-sans">5 rekaman kehadiran Anda sebelumnya</p>
                </div>
                
                @if($recentLogs->isNotEmpty())
                    <div class="divide-y divide-slate-100 text-xs">
                        @foreach($recentLogs as $log)
                            <div class="flex items-center justify-between py-2.5 first:pt-0 last:pb-0">
                                <div>
                                    <p class="font-bold text-slate-700">{{ $log->kelasKuliah ? $log->kelasKuliah->nama_mata_kuliah : 'Kelas Umum' }}</p>
                                    <p class="text-[10px] text-slate-400 font-medium">{{ \Carbon\Carbon::parse($log->tanggal)->translatedFormat('d F Y') }}</p>
                                </div>
                                <span class="px-2 py-0.5 rounded-full font-bold text-[10px] uppercase
                                    {{ $log->status === 'Hadir' ? 'bg-emerald-50 text-emerald-600' : 
                                       ($log->status === 'Sakit' ? 'bg-blue-50 text-blue-600' : 
                                       ($log->status === 'Izin' ? 'bg-amber-50 text-amber-600' : 'bg-rose-50 text-rose-600')) }}">
                                    {{ $log->status }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-400 font-medium text-center py-4">Belum ada riwayat absensi.</p>
                @endif
            </div>
        </div>

    </div>
</div>

<!-- Modal Check-in (SweetAlert/Filament-like design) -->
<div id="checkin-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden transition-opacity duration-300">
    <div class="bg-white rounded-3xl border border-slate-100 shadow-2xl max-w-xl w-full overflow-hidden flex flex-col max-h-[90vh]">
        <!-- Header -->
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-extrabold text-slate-800 text-lg" id="modal-class-title">Absen Kelas</h3>
                <p class="text-xs text-slate-400 font-medium font-sans">Lakukan pencatatan kehadiran menggunakan deteksi lokasi GPS.</p>
            </div>
            <button onclick="closeCheckInModal()" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 hover:text-slate-600 hover:bg-slate-100 flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Scrollable Body -->
        <div class="p-6 overflow-y-auto space-y-5 flex-1">
            <form action="{{ route('mahasiswa.absen') }}" method="POST" id="attendance-form" class="space-y-4">
                @csrf
                <input type="hidden" name="kelas_kuliah_id" id="modal_kelas_id">
                <input type="hidden" name="latitude" id="student_lat">
                <input type="hidden" name="longitude" id="student_lng">

                <!-- Location banner -->
                <div id="location-banner" class="p-4 bg-slate-50 border border-slate-150 rounded-2xl flex items-center justify-between gap-3 text-xs">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-slate-200 flex items-center justify-center text-slate-500 shrink-0 animate-pulse" id="location-icon">
                            <i class="fa-solid fa-spinner animate-spin"></i>
                        </div>
                        <div>
                            <p class="font-bold text-slate-700" id="location-title">Melakukan deteksi lokasi...</p>
                            <p class="text-slate-400 font-medium mt-0.5 font-sans" id="location-desc">Harap izinkan izin akses lokasi GPS di browser Anda.</p>
                        </div>
                    </div>
                </div>

                <!-- Status (Hanya Hadir - Sakit/Izin dikonfirmasi langsung ke Dosen) -->
                <input type="hidden" name="status" value="Hadir">
                <div class="p-3 bg-emerald-50 border border-emerald-100 rounded-xl flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500 flex items-center justify-center text-white shrink-0">
                        <i class="fa-solid fa-user-check text-sm"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-emerald-800">Status: Hadir</p>
                        <p class="text-[10px] text-emerald-600 font-medium">Untuk izin atau sakit, silakan konfirmasi langsung ke Dosen pengampu.</p>
                    </div>
                </div>

                <!-- Visual Geofence Map -->
                <div class="space-y-1.5 pt-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider font-semibold">Peta Pemantauan Geofence</label>
                    <div id="map-student" class="border border-slate-200 shadow-inner"></div>
                </div>

                <!-- Submit Button -->
                <button type="submit" id="submit-attendance-btn" disabled class="w-full py-3 bg-slate-200 text-slate-400 font-bold text-sm rounded-xl cursor-not-allowed transition-all shadow-sm">
                    Menunggu Deteksi Lokasi...
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Leaflet JS for Map -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    const classLat = {{ $classLat }};
    const classLon = {{ $classLon }};
    const allowedRadius = {{ $allowedRadius }};
    
    let map;
    let studentMarker;
    let classMarker;
    let geofenceCircle;
    let distanceLine;
    let gpsWatchId = null;

    // Open check-in modal
    function openCheckInModal(kelasId, namaKelas) {
        document.getElementById('modal_kelas_id').value = kelasId;
        document.getElementById('modal-class-title').textContent = "Absen Kelas: " + namaKelas;
        
        const modal = document.getElementById('checkin-modal');
        modal.classList.remove('hidden');
        
        // Timeout to load map tiles properly
        setTimeout(function() {
            initMap();
            requestLocation();
        }, 100);
    }

    // Close check-in modal
    function closeCheckInModal() {
        const modal = document.getElementById('checkin-modal');
        modal.classList.add('hidden');
        
        if (gpsWatchId) {
            navigator.geolocation.clearWatch(gpsWatchId);
            gpsWatchId = null;
        }

        // Reset elements
        disableSubmitButton("Menunggu Deteksi Lokasi...");
        const titleEl = document.getElementById('location-title');
        const descEl = document.getElementById('location-desc');
        const bannerEl = document.getElementById('location-banner');
        const iconEl = document.getElementById('location-icon');
        
        bannerEl.className = "p-4 bg-slate-50 border border-slate-150 rounded-2xl flex items-center justify-between gap-3 text-xs";
        iconEl.className = "w-9 h-9 rounded-xl bg-slate-200 flex items-center justify-center text-slate-500 shrink-0 animate-pulse";
        iconEl.innerHTML = '<i class="fa-solid fa-spinner animate-spin"></i>';
        titleEl.textContent = "Melakukan deteksi lokasi...";
        descEl.textContent = "Harap izinkan izin akses lokasi GPS di browser Anda.";
        
        document.getElementById('student_lat').value = "";
        document.getElementById('student_lng').value = "";

        // Destroy map instance to recreate it next time
        if (map) {
            map.remove();
            map = null;
            studentMarker = null;
            classMarker = null;
            geofenceCircle = null;
            distanceLine = null;
        }
    }

    // Initialize map centering on the class coordinates
    function initMap() {
        if (map) return;

        map = L.map('map-student').setView([classLat, classLon], 16);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Marker for Class Location (Blue Pin)
        classMarker = L.marker([classLat, classLon]).addTo(map)
            .bindPopup("<b>Lokasi Kelas</b><br>Koordinat pusat kelas absensi.").openPopup();

        // Circle representing Geofence (Emerald Theme)
        geofenceCircle = L.circle([classLat, classLon], {
            color: '#10b981',
            fillColor: '#a7f3d0',
            fillOpacity: 0.15,
            radius: allowedRadius
        }).addTo(map);
    }

    // Update Student Position on Map and UI
    function updateStudentLocationOnMap(lat, lon) {
        if(!map) return;

        if (studentMarker) {
            studentMarker.setLatLng([lat, lon]);
        } else {
            // Marker for Student Location (Red Pin)
            var redIcon = L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });
            studentMarker = L.marker([lat, lon], {icon: redIcon}).addTo(map)
                .bindPopup("<b>Lokasi Anda</b>").openPopup();
        }

        // Connect both pins with a dashed line
        if (distanceLine) {
            distanceLine.setLatLngs([[lat, lon], [classLat, classLon]]);
        } else {
            distanceLine = L.polyline([[lat, lon], [classLat, classLon]], {
                color: '#64748b',
                weight: 2,
                dashArray: '5, 10'
            }).addTo(map);
        }

        // Adjust map view to fit both markers
        var group = new L.featureGroup([classMarker, studentMarker]);
        map.fitBounds(group.getBounds().pad(0.15));
    }

    // Haversine distance calculator in Javascript for reactive client-side checks
    function getDistance(lat1, lon1, lat2, lon2) {
        const R = 6371000; // in meters
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                  Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                  Math.sin(dLon/2) * Math.sin(dLon/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return R * c;
    }

    // Geolocation detection handler
    function requestLocation() {
        if (!navigator.geolocation) {
            updateLocationUIStatus("error", "Geolokasi tidak didukung", "Peramban Anda tidak mendukung peninjauan GPS.");
            return;
        }

        gpsWatchId = navigator.geolocation.getCurrentPosition(
            (position) => {
                const lat = position.coords.latitude;
                const lon = position.coords.longitude;
                
                // Write coordinates to form hidden inputs
                document.getElementById('student_lat').value = lat;
                document.getElementById('student_lng').value = lon;

                const distance = getDistance(lat, lon, classLat, classLon);
                const isInside = distance <= allowedRadius;

                updateStudentLocationOnMap(lat, lon);
                handleHadirGeofenceUI(distance, isInside);
            },
            (error) => {
                let msg = "Akses GPS ditolak.";
                if (error.code === error.PERMISSION_DENIED) {
                    msg = "Izin akses lokasi ditolak oleh Anda.";
                } else if (error.code === error.POSITION_UNAVAILABLE) {
                    msg = "Sinyal GPS tidak tersedia.";
                } else if (error.code === error.TIMEOUT) {
                    msg = "Batas waktu permintaan deteksi habis.";
                }
                updateLocationUIStatus("error", "Deteksi Lokasi Gagal", msg + " Harap izinkan browser mengakses lokasi.");
                disableSubmitButton("Harap Izinkan Lokasi GPS");
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    }

    // Set UI states based on geofence check
    function handleHadirGeofenceUI(distance, isInside) {
        const titleEl = document.getElementById('location-title');
        const descEl = document.getElementById('location-desc');
        const bannerEl = document.getElementById('location-banner');
        const iconEl = document.getElementById('location-icon');

        if (isInside) {
            // Inside Class radius
            bannerEl.className = "p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 rounded-2xl flex items-center justify-between gap-3 text-xs";
            iconEl.className = "w-9 h-9 rounded-xl bg-emerald-500 flex items-center justify-center text-white shrink-0";
            iconEl.innerHTML = '<i class="fa-solid fa-location-dot"></i>';
            titleEl.textContent = "Lokasi Sesuai (Dalam Jangkauan)";
            descEl.textContent = `Anda berjarak ${Math.round(distance)} meter dari pusat kelas. Batas toleransi: ${allowedRadius} meter.`;
            enableSubmitButton("Kirim Presensi (Hadir)");
        } else {
            // Outside Class radius
            bannerEl.className = "p-4 bg-rose-50 border border-rose-100 text-rose-800 rounded-2xl flex items-center justify-between gap-3 text-xs";
            iconEl.className = "w-9 h-9 rounded-xl bg-rose-500 flex items-center justify-center text-white shrink-0";
            iconEl.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i>';
            titleEl.textContent = "Di Luar Jangkauan Kelas";
            descEl.textContent = `Jarak Anda ${Math.round(distance)} meter dari kelas. Jarak maksimal absensi yang diizinkan adalah ${allowedRadius} meter.`;
            disableSubmitButton("Anda Berada Di Luar Jangkauan Kelas");
        }
    }

    // Standard helper states for Geolocation status banners
    function updateLocationUIStatus(type, title, desc) {
        const titleEl = document.getElementById('location-title');
        const descEl = document.getElementById('location-desc');
        const bannerEl = document.getElementById('location-banner');
        const iconEl = document.getElementById('location-icon');

        if(type === "error") {
            bannerEl.className = "p-4 bg-rose-50 border border-rose-100 text-rose-800 rounded-2xl flex items-center justify-between gap-3 text-xs";
            iconEl.className = "w-9 h-9 rounded-xl bg-rose-500 flex items-center justify-center text-white shrink-0 font-bold";
            iconEl.innerHTML = '<i class="fa-solid fa-circle-xmark"></i>';
        }
        titleEl.textContent = title;
        descEl.textContent = desc;
    }

    function enableSubmitButton(text) {
        const btn = document.getElementById('submit-attendance-btn');
        btn.removeAttribute('disabled');
        btn.className = "w-full py-3 bg-brand-600 hover:bg-brand-500 text-white font-bold text-sm rounded-xl shadow-lg shadow-brand-600/20 transition-all cursor-pointer hover:-translate-y-0.5 active:translate-y-0";
        btn.textContent = text;
    }

    function disableSubmitButton(text) {
        const btn = document.getElementById('submit-attendance-btn');
        btn.setAttribute('disabled', 'true');
        btn.className = "w-full py-3 bg-slate-200 text-slate-400 font-bold text-sm rounded-xl cursor-not-allowed transition-all shadow-sm";
        btn.textContent = text;
    }
</script>
@endsection
