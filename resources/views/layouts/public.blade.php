<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Madmun.Arthouse — Trung tâm nghệ thuật')</title>
    <meta name="description" content="@yield('description', 'Madmun.Arthouse — lớp học hội họa, màu nước, sơn dầu và digital art với giáo viên kèm cặp và gallery trưng bày tác phẩm học viên.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#eefaf3', 100: '#d6f4e4', 200: '#b0e9cd', 300: '#7bd7ad',
                            400: '#45bd88', 500: '#1fa06c', 600: '#0e8057', 700: '#0b6848',
                            800: '#0a533a', 900: '#084432', 950: '#04281e'
                        }
                    },
                    fontFamily: {
                        sans: ['"Be Vietnam Pro"', 'sans-serif'],
                        display: ['"Playfair Display"', 'serif']
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}?v=2">
    @yield('head')
</head>
<body class="font-sans text-[#10231c] bg-[#fbfdfb]">

<header class="sticky top-0 z-40 bg-white/85 backdrop-blur-md border-b border-[#eaf1ec]">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 h-[72px] flex items-center justify-between gap-4">
        <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
            <span class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand-500 to-brand-700 flex items-center justify-center shadow-lg shadow-brand-600/25 group-hover:scale-105 transition">
                <i class="bx bxs-palette text-white text-xl"></i>
            </span>
            <span class="leading-tight">
                <span class="block font-bold text-[15px] tracking-tight">Madmun<span class="text-brand-600">.Arthouse</span></span>
                <span class="block text-[9px] uppercase tracking-[.2em] text-[#8b9c93]">Art studio & school</span>
            </span>
        </a>

        <nav class="hidden md:flex items-center gap-1 text-sm text-[#4b5f56]">
            <a href="{{ route('home') }}#intro" class="px-3 py-2 rounded-lg hover:bg-brand-50 hover:text-brand-700 transition">Giới thiệu</a>
            <a href="{{ route('home') }}#courses" class="px-3 py-2 rounded-lg hover:bg-brand-50 hover:text-brand-700 transition">Khóa học</a>
            <a href="{{ route('home') }}#teachers" class="px-3 py-2 rounded-lg hover:bg-brand-50 hover:text-brand-700 transition">Giáo viên</a>
            <a href="{{ route('home') }}#gallery" class="px-3 py-2 rounded-lg hover:bg-brand-50 hover:text-brand-700 transition">Gallery</a>
            <a href="{{ route('home') }}#contact" class="px-3 py-2 rounded-lg hover:bg-brand-50 hover:text-brand-700 transition">Liên hệ</a>
        </nav>

        <div class="flex items-center gap-2">
            @auth
                <a href="{{ route(auth()->user()->homeRoute()) }}" class="btn btn-primary btn-sm">
                    <i class="bx bx-grid-alt"></i> Vào không gian làm việc
                </a>
            @else
                <a href="{{ route('login') }}" class="hidden sm:inline-flex btn btn-ghost btn-sm">Đăng nhập</a>
                <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Đăng ký học</a>
            @endauth
            <button class="md:hidden w-9 h-9 rounded-lg border border-[#e3ebe6] flex items-center justify-center text-[#4b5f56]" onclick="document.getElementById('mobileNav').classList.toggle('hidden')">
                <i class="bx bx-menu text-xl"></i>
            </button>
        </div>
    </div>

    <div id="mobileNav" class="hidden md:hidden border-t border-[#eaf1ec] bg-white px-4 py-3 space-y-1 text-sm">
        <a href="{{ route('home') }}#intro" class="block px-3 py-2 rounded-lg hover:bg-brand-50">Giới thiệu</a>
        <a href="{{ route('home') }}#courses" class="block px-3 py-2 rounded-lg hover:bg-brand-50">Khóa học</a>
        <a href="{{ route('home') }}#teachers" class="block px-3 py-2 rounded-lg hover:bg-brand-50">Giáo viên</a>
        <a href="{{ route('home') }}#gallery" class="block px-3 py-2 rounded-lg hover:bg-brand-50">Gallery</a>
        <a href="{{ route('home') }}#contact" class="block px-3 py-2 rounded-lg hover:bg-brand-50">Liên hệ</a>
        @guest
            <a href="{{ route('login') }}" class="block px-3 py-2 rounded-lg hover:bg-brand-50">Đăng nhập</a>
        @endguest
    </div>
</header>

@yield('content')

<footer class="bg-[#04281e] text-[#a9c2b7] mt-20">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-14 grid gap-10 md:grid-cols-4">
        <div class="md:col-span-2">
            <div class="flex items-center gap-2.5">
                <span class="w-9 h-9 rounded-xl bg-brand-600 flex items-center justify-center"><i class="bx bxs-palette text-white text-xl"></i></span>
                <p class="text-white font-bold text-lg">Madmun.Arthouse</p>
            </div>
            <p class="text-sm mt-4 max-w-sm leading-relaxed">Trung tâm nghệ thuật dành cho người muốn tạo ra tác phẩm thật: hình họa nền tảng, màu nước, sơn dầu và digital art.</p>
            <div class="flex gap-2 mt-5">
                <a href="#" class="w-9 h-9 rounded-full bg-white/5 hover:bg-white/10 flex items-center justify-center"><i class="bx bxl-facebook"></i></a>
                <a href="#" class="w-9 h-9 rounded-full bg-white/5 hover:bg-white/10 flex items-center justify-center"><i class="bx bxl-instagram"></i></a>
                <a href="#" class="w-9 h-9 rounded-full bg-white/5 hover:bg-white/10 flex items-center justify-center"><i class="bx bxl-youtube"></i></a>
            </div>
        </div>
        <div>
            <p class="text-white text-sm font-semibold mb-3">Khám phá</p>
            <ul class="space-y-2 text-sm">
                <li><a href="{{ route('home') }}#courses" class="hover:text-white">Khóa học</a></li>
                <li><a href="{{ route('home') }}#teachers" class="hover:text-white">Giáo viên</a></li>
                <li><a href="{{ route('home') }}#gallery" class="hover:text-white">Gallery học viên</a></li>
                <li><a href="{{ route('register') }}" class="hover:text-white">Đăng ký học</a></li>
            </ul>
        </div>
        <div>
            <p class="text-white text-sm font-semibold mb-3">Liên hệ</p>
            <ul class="space-y-2 text-sm">
                <li class="flex gap-2"><i class="bx bx-map text-brand-400"></i> 123 Nguyễn Huệ, Quận 1, TP.HCM</li>
                <li class="flex gap-2"><i class="bx bx-phone text-brand-400"></i> 090 123 4567</li>
                <li class="flex gap-2"><i class="bx bx-envelope text-brand-400"></i> hello@madmun.art</li>
                <li class="flex gap-2"><i class="bx bx-time text-brand-400"></i> T2 – T7, 8:00 – 21:00</li>
            </ul>
        </div>
    </div>
    <div class="border-t border-white/10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-5 text-xs flex flex-col sm:flex-row justify-between gap-2">
            <span>© {{ date('Y') }} Madmun.Arthouse. Bảo lưu mọi quyền.</span>
            <span>Thiết kế & vận hành bởi Madmun Studio</span>
        </div>
    </div>
</footer>
</body>
</html>
