<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body class="min-h-screen bg-slate-100">
    <div class="grid grid-cols-1 md:grid-cols-2 min-h-screen">
        <div class="flex items-center px-6 py-10 lg:p-20">
            <div class="w-full max-w-md mx-auto bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
                <form action="{{ route('signup.submit') }}" method="post" class="flex flex-col">
                    @csrf
                    <p class="text-3xl font-bold text-center text-slate-800 mb-2">Đăng ký</p>
                    <p class="text-center text-slate-500 text-sm mb-7">Tạo tài khoản để bắt đầu học</p>
                    <label class="text-sm font-medium text-slate-700">Họ và tên*</label>
                    <input type="text" name="name" value="{{old('name')}}"  class="mt-1 border border-slate-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Nguyễn Văn A">
                    @if ($errors->has('name'))
                        <div class="text-red-500 text-sm mt-1">{{ $errors->first('name') }}</div>
                    @endif
                    
                    <label class="text-sm font-medium text-slate-700 mt-5">Email*</label>
                    <input type="email" name="email" value="{{old('email')}}" class="mt-1 border border-slate-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="abc@gmail.com">
                    @if ($errors->has('email'))
                        <div class="text-red-500 text-sm mt-1">{{ $errors->first('email') }}</div>
                    @endif

                    <label class="text-sm font-medium text-slate-700 mt-5">Mật khẩu*</label>
                    <input type="password" name="password" class="mt-1 border border-slate-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="******">
                    @if ($errors->has('password'))
                        <div class="text-red-500 text-sm mt-1">{{ $errors->first('password') }}</div>
                    @endif

                    @if(session('success'))
                        <div class="text-emerald-600 text-sm mt-3">{{ session('success') }}</div>
                    @endif

                    <button class="bg-indigo-500 hover:bg-indigo-600 font-semibold text-white rounded-xl mt-6 py-3 transition-all active:scale-95">
                        <p>Đăng ký ngay</p>
                    </button>
                </form>
                <a href="{{ route('login') }}" class="block text-center border border-slate-200 font-semibold text-slate-600 rounded-xl mt-3 py-3 transition-all hover:border-indigo-500 hover:text-indigo-600">
                    Đăng nhập
                </a>
            </div>
        </div>
        <div class="hidden md:flex flex-col justify-center items-center bg-gradient-to-br from-slate-900 to-indigo-950 text-white p-10">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-10 h-10 bg-indigo-500 rounded-xl flex items-center justify-center">
                    <i class="bx bxs-graduation text-2xl"></i>
                </div>
                <span class="font-bold text-2xl">IT Courses</span>
            </div>
            <img class="w-full max-w-md px-2" src="{{ asset('images/login+signup/signup.svg') }}" alt="">
            <p class="mt-8 text-slate-300 text-center max-w-sm">Tham gia cộng đồng học viên và khám phá các khóa học lập trình.</p>
        </div>
    </div>
</body>
</html>
