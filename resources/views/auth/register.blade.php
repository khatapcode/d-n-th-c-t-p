<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký học viên — Madmun.Arthouse</title>
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
<div class="grid lg:grid-cols-[1fr_520px] min-h-screen">

    <div class="relative hidden lg:flex flex-col justify-between p-12 overflow-hidden bg-brand-950">
        <img src="{{ asset('images/courses/course-3.svg') }}" alt="" class="absolute inset-0 w-full h-full object-cover opacity-70">
        <div class="absolute inset-0 bg-gradient-to-tr from-brand-950/95 via-brand-950/70 to-brand-800/40"></div>
        <a href="{{ route('home') }}" class="relative flex items-center gap-3 text-white">
            <span class="w-10 h-10 rounded-xl bg-brand-600 flex items-center justify-center"><i class="bx bxs-palette text-2xl"></i></span>
            <span class="font-bold text-lg">Madmun.Arthouse</span>
        </a>
        <div class="relative max-w-md">
            <p class="text-brand-300 text-xs font-bold uppercase tracking-[.25em]">Bắt đầu hành trình</p>
            <h1 class="font-display text-4xl leading-tight text-white mt-4">Mỗi tác phẩm đều bắt đầu từ một nét vẽ đầu tiên.</h1>
            <ul class="mt-8 space-y-3 text-sm text-brand-100/85">
                <li class="flex gap-2.5"><i class="bx bxs-check-circle text-brand-400 text-lg"></i> Chọn lớp phù hợp trình độ và lịch rảnh</li>
                <li class="flex gap-2.5"><i class="bx bxs-check-circle text-brand-400 text-lg"></i> Theo dõi điểm danh, học phí minh bạch</li>
                <li class="flex gap-2.5"><i class="bx bxs-check-circle text-brand-400 text-lg"></i> Nhận nhận xét từ giáo viên trên từng tác phẩm</li>
            </ul>
        </div>
        <p class="relative text-xs text-brand-200/50">© {{ date('Y') }} Madmun.Arthouse</p>
    </div>

    <div class="flex items-center justify-center p-6 sm:p-10">
        <div class="w-full max-w-[420px] py-8">
            <a href="{{ route('home') }}" class="lg:hidden flex items-center gap-2.5 mb-8">
                <span class="w-9 h-9 rounded-xl bg-brand-600 flex items-center justify-center"><i class="bx bxs-palette text-white text-xl"></i></span>
                <span class="font-bold">Madmun<span class="text-brand-600">.Arthouse</span></span>
            </a>

            <h2 class="text-2xl font-bold">Tạo tài khoản học viên</h2>
            <p class="text-sm text-[#7d8f87] mt-1">Đăng ký xong bạn có thể gửi yêu cầu vào lớp ngay.</p>

            <form method="POST" action="{{ route('register.submit') }}" class="mt-7 space-y-4">
                @csrf
                <div>
                    <label class="label" for="name">Họ và tên</label>
                    <input id="name" name="name" value="{{ old('name') }}" placeholder="Nguyễn Minh Anh" class="input" required autofocus>
                    @error('name')<p class="text-red-600 text-xs mt-1.5">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="label" for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="ban@example.com" class="input" required>
                    @error('email')<p class="text-red-600 text-xs mt-1.5">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="label" for="phone">Số điện thoại <span class="font-normal text-[#9aaba3]">(không bắt buộc)</span></label>
                    <input id="phone" name="phone" value="{{ old('phone') }}" placeholder="090 123 4567" class="input">
                    @error('phone')<p class="text-red-600 text-xs mt-1.5">{{ $message }}</p>@enderror
                </div>
                <div class="grid sm:grid-cols-2 gap-3">
                    <div>
                        <label class="label" for="password">Mật khẩu</label>
                        <input id="password" type="password" name="password" placeholder="Tối thiểu 6 ký tự" class="input" required autocomplete="new-password">
                        @error('password')<p class="text-red-600 text-xs mt-1.5">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="label" for="password_confirmation">Nhập lại</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" placeholder="••••••" class="input" required autocomplete="new-password">
                    </div>
                </div>
                <button class="btn btn-primary w-full !py-3">Tạo tài khoản <i class="bx bx-right-arrow-alt text-lg"></i></button>
            </form>

            <p class="text-center text-sm text-[#7d8f87] mt-5">
                Đã có tài khoản? <a href="{{ route('login') }}" class="link-green">Đăng nhập</a>
            </p>
        </div>
    </div>
</div>
</body>
</html>
