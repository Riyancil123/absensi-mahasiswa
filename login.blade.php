<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Portal Presensi Kampus</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes pageFadeIn {
            from {
                opacity: 0;
                transform: translateY(6px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .page-transition {
            animation: pageFadeIn 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }
    </style>
</head>
<body class="bg-gradient-to-tr from-slate-950 via-slate-900 to-emerald-950 min-h-screen flex items-center justify-center p-4 antialiased font-sans">

    <!-- Glowing Background blobs (Green Theme) -->
    <div class="fixed top-0 left-0 w-80 h-80 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none -mt-20 -ml-20"></div>
    <div class="fixed bottom-0 right-0 w-96 h-96 bg-emerald-600/10 rounded-full blur-3xl pointer-events-none -mb-32 -mr-32"></div>

    <div class="relative w-full max-w-md bg-slate-900/60 backdrop-blur-xl border border-slate-800/80 p-8 rounded-3xl shadow-2xl space-y-6 page-transition">
        
        <!-- App Identity -->
        <div class="text-center space-y-2">
            <div class="inline-flex bg-gradient-to-tr from-emerald-500 to-teal-600 p-3.5 rounded-2xl text-white shadow-xl shadow-emerald-500/20">
                <i class="fa-solid fa-graduation-cap fa-2xl"></i>
            </div>
            <h1 class="text-2xl font-extrabold tracking-tight text-white">Portal Presensi Kampus</h1>
            <p class="text-xs text-slate-400 font-medium font-sans">Lakukan autentikasi menggunakan NIM atau NPP Anda</p>
        </div>

        <!-- Session Alert Notification -->
        @if (session('success'))
            <div class="p-3.5 bg-emerald-500/15 border border-emerald-500/20 text-emerald-400 text-xs rounded-xl flex items-center gap-2">
                <i class="fa-solid fa-circle-check"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="p-3.5 bg-rose-500/15 border border-rose-500/20 text-rose-400 text-xs rounded-xl flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        @endif

        @error('login_error')
            <div class="p-3.5 bg-rose-500/15 border border-rose-500/20 text-rose-400 text-xs rounded-xl flex items-center gap-2 animate-bounce">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span class="font-medium">{{ $message }}</span>
            </div>
        @enderror

        <!-- Login Form -->
        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Username (NIM / NPP) -->
            <div class="space-y-1.5">
                <label for="username" class="text-xs font-bold text-slate-400 uppercase tracking-wider font-semibold">Username (NIM / NPP)</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-500">
                        <i class="fa-solid fa-user"></i>
                    </span>
                    <input type="text" name="username" id="username" value="{{ old('username') }}" placeholder="Masukkan NIM (Mahasiswa) atau NPP (Dosen)..." class="w-full pl-10 pr-4 py-3 bg-slate-950/40 border border-slate-800 focus:border-emerald-500 focus:ring-emerald-500 focus:ring-1 rounded-xl text-sm text-white focus:outline-none focus:bg-slate-950/80 transition-all placeholder-slate-600" required>
                </div>
                @error('username')
                    <p class="text-[11px] font-semibold text-rose-400 flex items-center gap-1 mt-1"><i class="fa-solid fa-circle-exclamation text-[10px]"></i>{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="space-y-1.5">
                <label for="password" class="text-xs font-bold text-slate-400 uppercase tracking-wider font-semibold">Kata Sandi (Password)</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-500">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input type="password" name="password" id="password" placeholder="••••••••" class="w-full pl-10 pr-4 py-3 bg-slate-950/40 border border-slate-800 focus:border-emerald-500 focus:ring-emerald-500 focus:ring-1 rounded-xl text-sm text-white focus:outline-none focus:bg-slate-950/80 transition-all placeholder-slate-600" required>
                </div>
                @error('password')
                    <p class="text-[11px] font-semibold text-rose-400 flex items-center gap-1 mt-1"><i class="fa-solid fa-circle-exclamation text-[10px]"></i>{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button (Green Theme) -->
            <button type="submit" class="w-full py-3 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-sm rounded-xl shadow-lg shadow-emerald-600/20 transition-all hover:-translate-y-0.5 active:translate-y-0 mt-2">
                Masuk Ke Sistem
            </button>
        </form>
    </div>
</body>
</html>
