<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập — Madmun.Arthouse</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&family=Playfair+Display:wght@500;600&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { colors: { brand: {
            50:'#eefaf3',100:'#d6f4e4',200:'#b0e9cd',300:'#7bd7ad',400:'#45bd88',
            500:'#1fa06c',600:'#0e8057',700:'#0b6848',800:'#0a533a',900:'#084432',950:'#04281e'
        } }, fontFamily: { sans: ['"Be Vietnam Pro"','sans-serif'], display: ['"Playfair Display"','serif'] } } } }
    </script>
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}?v=2">
</head>
<body class="font-sans min-h-screen bg-[#f4f7f4]">
<div class="grid lg:grid-cols-2 min-h-screen">

    {{-- Brand panel --}}
    <div class="relative hidden lg:flex flex-col justify-between p-12 overflow-hidden bg-brand-950">
        <img src="{{ asset('images/art/hero.svg') }}" alt="" class="absolute inset-0 w-full h-full object-cover opacity-70">
        <div class="absolute inset-0 bg-gradient-to-br from-brand-950/95 via-brand-900/80 to-brand-800/60"></div>
        <div class="relative">
            <a href="{{ route('home') }}" class="flex items-center gap-3 text-white">
                <span class="w-10 h-10 rounded-xl bg-brand-600 flex items-center justify-center"><i class="bx bxs-palette text-2xl"></i></span>
                <span class="font-bold text-lg">Madmun.Arthouse</span>
            </a>
        </div>
        <div class="relative max-w-md">
            <p class="text-brand-300 text-xs font-bold uppercase tracking-[.25em]">Studio & school</p>
            <h1 class="font-display text-4xl leading-tight text-white mt-4">Nơi tác phẩm của bạn được nhìn thấy.</h1>
            <p class="text-brand-100/80 mt-4 leading-relaxed">Một không gian chung cho học viên, giáo viên và người điều hành: lịch học, điểm danh, học phí và gallery tác phẩm.</p>
            <div class="flex gap-6 mt-8 text-white">
                <div><p class="text-2xl font-bold">4</p><p class="text-xs text-brand-200/70 uppercase tracking-wider">Vai trò</p></div>
                <div><p class="text-2xl font-bold">100%</p><p class="text-xs text-brand-200/70 uppercase tracking-wider">Tác phẩm có nhận xét</p></div>
                <div><p class="text-2xl font-bold">24/7</p><p class="text-xs text-brand-200/70 uppercase tracking-wider">Theo dõi học phí</p></div>
            </div>
        </div>
        <p class="relative text-xs text-brand-200/50">© {{ date('Y') }} Madmun.Arthouse</p>
    </div>

    {{-- Form --}}
    <div class="flex items-center justify-center p-6 sm:p-10">
        <div class="w-full max-w-[420px]">
            <div class="lg:hidden flex items-center gap-2.5 mb-8">
                <span class="w-9 h-9 rounded-xl bg-brand-600 flex items-center justify-center"><i class="bx bxs-palette text-white text-xl"></i></span>
                <span class="font-bold">Madmun<span class="text-brand-600">.Arthouse</span></span>
            </div>

            <h2 class="text-2xl font-bold">Chào mừng trở lại</h2>
            <p class="text-sm text-[#7d8f87] mt-1">Đăng nhập để tiếp tục vào không gian làm việc của bạn.</p>

            @if (session('status'))
                <div class="mt-5 text-sm bg-brand-50 text-brand-700 border border-brand-100 rounded-xl px-4 py-3">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('login.submit') }}" class="mt-7 space-y-4">
                @csrf
                <div>
                    <label class="label" for="email">Email</label>
                    <div class="relative">
                        <i class="bx bx-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-[#9aaba3]"></i>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="ban@madmun.art"
                               class="input pl-10" required autofocus autocomplete="email">
                    </div>
                    @error('email')<p class="text-red-600 text-xs mt-1.5">{{ $message }}</p>@enderror
                </div>
                <div>
                    <div class="flex justify-between items-center mb-1.5">
                        <label class="label !mb-0" for="password">Mật khẩu</label>
                        <span class="text-xs text-[#9aaba3]">Quên mật khẩu? Liên hệ admin</span>
                    </div>
                    <div class="relative">
                        <i class="bx bx-lock-alt absolute left-3.5 top-1/2 -translate-y-1/2 text-[#9aaba3]"></i>
                        <input id="password" type="password" name="password" placeholder="••••••••" class="input pl-10 pr-10" required autocomplete="current-password">
                        <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-[#9aaba3] hover:text-brand-700" onclick="const p=document.getElementById('password');p.type=p.type==='password'?'text':'password'">
                            <i class="bx bx-show text-lg"></i>
                        </button>
                    </div>
                    @error('password')<p class="text-red-600 text-xs mt-1.5">{{ $message }}</p>@enderror
                </div>
                <label class="flex items-center gap-2 text-sm text-[#4b5f56] select-none">
                    <input type="checkbox" name="remember" class="rounded border-[#cfded7] text-brand-600 focus:ring-brand-400">
                    Ghi nhớ đăng nhập
                </label>
                <button class="btn btn-primary w-full !py-3">Đăng nhập <i class="bx bx-right-arrow-alt text-lg"></i></button>
            </form>

            <p class="text-center text-sm text-[#7d8f87] mt-5">
                Chưa có tài khoản?
                <a href="{{ route('register') }}" class="link-green">Đăng ký học viên</a>
            </p>

            <div class="mt-8 card card-pad !bg-[#f8fbf9]">
                <p class="text-[11px] uppercase tracking-wider font-bold text-[#7d8f87] mb-3">Tài khoản demo</p>
                <div class="space-y-2">
                    @foreach ([
                        ['admin@madmun.art', 'Quản trị viên', 'bxs-shield-alt-2'],
                        ['teacher@madmun.art', 'Giáo viên', 'bxs-user-voice'],
                        ['student@madmun.art', 'Học viên', 'bxs-user-detail'],
                    ] as [$mail, $role, $icon])
                        <button type="button" onclick="fillLogin('{{ $mail }}')"
                                class="w-full flex items-center gap-2.5 px-3 py-2 rounded-lg bg-white border border-[#e3ebe6] hover:border-brand-300 text-left transition">
                            <i class="bx {{ $icon }} text-brand-600"></i>
                            <span class="text-xs">
                                <span class="font-semibold text-[#10231c]">{{ $role }}</span>
                                <span class="text-[#7d8f87] block">{{ $mail }}</span>
                            </span>
                            <span class="ml-auto text-[10px] text-brand-600 font-semibold">Dùng</span>
                        </button>
                    @endforeach
                </div>
                <p class="text-[11px] text-[#9aaba3] mt-3">Mật khẩu chung: <b class="text-[#4b5f56]">password</b></p>
            </div>
        </div>
    </div>
</div>

<script>
    function fillLogin(email) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = 'password';
        document.getElementById('password').focus();
    }
</script>
</body>
</html>
