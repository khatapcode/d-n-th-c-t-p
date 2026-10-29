<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body class="min-h-screen bg-slate-100">
    <div class="grid grid-cols-1 md:grid-cols-2 min-h-screen">
        <div class="order-2 md:order-1 hidden md:flex flex-col justify-center items-center bg-gradient-to-br from-slate-900 to-indigo-950 text-white p-10">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-10 h-10 bg-indigo-500 rounded-xl flex items-center justify-center">
                    <i class="bx bxs-graduation text-2xl"></i>
                </div>
                <span class="font-bold text-2xl">IT Courses</span>
            </div>
            <img class="w-full max-w-md px-2" src="{{ asset('images/login+signup/login.svg') }}" alt="">
            <p class="mt-8 text-slate-300 text-center max-w-sm">Đăng nhập để đăng ký khóa học và theo dõi tiến độ học tập của bạn.</p>
        </div>
        <div class="flex items-center px-6 py-10 lg:p-20 order-1 md:order-2">
            <form action="{{ route('login.submit') }}" method="post" class="flex flex-col w-full max-w-md mx-auto bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
                @csrf
                <p class="text-3xl font-bold text-center text-slate-800 mb-2">Đăng nhập</p>
                <p class="text-center text-slate-500 text-sm mb-7">Chào mừng bạn quay lại IT Courses</p>
                <label class="text-sm font-medium text-slate-700">Email*</label>
                <input type="email" name="email" value="{{ old('email') }}" class="mt-1 border border-slate-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="abc@gmail.com">
                @if ($errors->has('email'))
                    <div class="text-red-500 text-sm mt-1">{{ $errors->first('email') }}</div>
                @endif
                
                <label class="text-sm font-medium text-slate-700 mt-5">Mật khẩu*</label>
                <input type="password" name="password" class="mt-1 border border-slate-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="******">
                @if ($errors->has('password'))
                    <div class="text-red-500 text-sm mt-1">{{ $errors->first('password') }}</div>
                @endif
                
                <button class="bg-indigo-500 hover:bg-indigo-600 rounded-xl mt-6 py-3 transition-all active:scale-95">
                    <p class="font-semibold text-white">Đăng nhập ngay</p>
                </button>
                <p class="text-center mt-6 text-slate-600 text-sm">
                    Vui lòng <a href="{{ route('signup') }}" class="underline font-semibold text-indigo-600">đăng ký</a> khi chưa có tài khoản !
                </p>
            </form>
        </div>
    </div>
</body>
</html>
