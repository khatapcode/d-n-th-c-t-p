<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'IT Courses')</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .sidebar-link { transition: all 0.2s ease; }
        .sidebar-link:hover { background: rgba(255,255,255,0.1); transform: translateX(4px); }
        .sidebar-link.active { background: linear-gradient(90deg, #6366f1, #8b5cf6); box-shadow: 0 4px 15px rgba(99,102,241,0.4); }
        .card-hover { transition: all 0.2s ease; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 12px 28px rgba(15,23,42,0.12); }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #1e1e2e; }
        ::-webkit-scrollbar-thumb { background: #6366f1; border-radius: 3px; }
        #search-results:empty { display: none; }
    </style>
</head>
<body class="bg-slate-100 font-sans min-h-screen">

    <header class="fixed top-0 left-0 right-0 z-20 h-16 bg-gradient-to-r from-slate-900 to-slate-800 shadow-lg flex items-center px-4 lg:px-6 gap-4">
        <div class="flex items-center gap-3 w-auto lg:w-64 shrink-0">
            <div class="w-8 h-8 bg-indigo-500 rounded-lg flex items-center justify-center">
                <i class="bx bxs-graduation text-white text-lg"></i>
            </div>
            <a href="{{ route('home.index') }}" class="text-white font-bold text-lg tracking-wide">IT Courses</a>
        </div>

        <div class="flex-1 flex justify-center">
            <div class="relative w-full max-w-xl">
                <i class="bx bx-search text-lg absolute z-10 left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input id="search" class="w-full bg-slate-700/70 border border-slate-600 rounded-xl py-2 pl-10 pr-4 text-sm text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" type="text" placeholder="Tìm kiếm khóa học,...">
                <div id="search-results" class="absolute left-0 right-0 mt-2 bg-white rounded-xl shadow-xl border border-slate-100 overflow-hidden z-30 max-h-72 overflow-y-auto"></div>
            </div>
        </div>

        <div class="flex items-center gap-2 lg:gap-3 shrink-0">
            @if (Auth::check())
                <div class="flex items-center gap-2 bg-slate-700 rounded-full px-3 py-1.5">
                    <div class="w-7 h-7 bg-indigo-500 rounded-full flex items-center justify-center">
                        <i class="bx bxs-user text-white text-sm"></i>
                    </div>
                    <span class="hidden md:inline text-white text-sm font-medium max-w-[140px] truncate">{{ Auth::user()->name }}</span>
                </div>
                <a href="{{ route('logout') }}" class="flex items-center gap-2 text-slate-400 hover:text-red-400 transition-colors px-2 py-2 rounded-lg hover:bg-slate-700">
                    <i class="bx bx-log-out text-xl"></i>
                    <span class="hidden lg:inline text-sm">Đăng xuất</span>
                </a>
            @else
                <a href="{{ route('signup') }}" class="hidden sm:inline-flex items-center px-3 py-1.5 rounded-lg text-sm text-slate-200 hover:text-white hover:bg-slate-700 transition-colors">
                    Đăng ký
                </a>
                <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-1.5 rounded-lg text-sm font-medium text-white bg-indigo-500 hover:bg-indigo-400 transition-colors">
                    Đăng nhập
                </a>
            @endif
        </div>
    </header>

    <aside class="fixed left-0 top-16 h-[calc(100vh-4rem)] w-20 lg:w-64 bg-gradient-to-b from-slate-900 to-slate-800 shadow-xl z-10 overflow-y-auto">
        <div class="p-3 lg:p-4 mt-2">
            <p class="hidden lg:block text-slate-500 text-xs font-semibold uppercase tracking-wider mb-2 px-4">Khám phá</p>
            <a href="{{ route('home.index') }}"
               class="sidebar-link flex flex-col lg:flex-row items-center lg:gap-3 px-2 lg:px-4 py-3 rounded-xl mb-1 text-slate-300 {{ request()->routeIs('home.index') ? 'active text-white' : 'hover:text-white' }}">
                <i class="bx bxs-home text-xl"></i>
                <span class="text-[11px] lg:text-sm font-medium">Trang chủ</span>
            </a>
        </div>
    </aside>

    <main class="ml-20 lg:ml-64 pt-16 min-h-screen flex flex-col">
        <div class="p-4 lg:p-6 flex-1">
            @yield('main')
        </div>

        <footer class="bg-gradient-to-r from-slate-900 to-slate-800 text-slate-300 mt-10">
            <div class="px-6 lg:px-10 py-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 bg-indigo-500 rounded-lg flex items-center justify-center">
                            <i class="bx bxs-graduation text-white"></i>
                        </div>
                        <p class="font-bold text-xl text-white">IT Courses</p>
                    </div>
                    <p class="text-sm text-slate-400 leading-relaxed">Nền tảng học lập trình với các khóa học theo danh mục, bài giảng và câu hỏi củng cố.</p>
                </div>
                <div>
                    <p class="text-white font-semibold mb-3">Liên kết</p>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('home.index') }}" class="hover:text-indigo-400 transition-colors">Trang chủ</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-indigo-400 transition-colors">Đăng nhập</a></li>
                        <li><a href="{{ route('signup') }}" class="hover:text-indigo-400 transition-colors">Đăng ký</a></li>
                    </ul>
                </div>
                <div>
                    <p class="text-white font-semibold mb-3">Hỗ trợ</p>
                    <ul class="space-y-2 text-sm text-slate-400">
                        <li>Hướng dẫn học tập</li>
                        <li>Chính sách bảo mật</li>
                        <li>Điều khoản sử dụng</li>
                    </ul>
                </div>
                <div>
                    <p class="text-white font-semibold mb-3">Mạng xã hội</p>
                    <ul class="flex gap-3 text-2xl">
                        <li><i class="bx bxl-facebook-circle hover:text-indigo-400 cursor-pointer"></i></li>
                        <li><i class="bx bxl-instagram-alt hover:text-indigo-400 cursor-pointer"></i></li>
                        <li><i class="bx bxl-tiktok hover:text-indigo-400 cursor-pointer"></i></li>
                        <li><i class="bx bxl-github hover:text-indigo-400 cursor-pointer"></i></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-slate-700 px-6 py-4 text-center text-sm text-slate-500">
                © 2024 All rights reserved.
            </div>
        </footer>
    </main>

</body>
</html>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        function searchCourses() {
            var query = $('#search').val();
            if (query) {
                $.ajax({
                    url: '{{ route("home.search.courses") }}',
                    type: 'GET',
                    data: {query: query},
                    success: function(data) {
                        var results = $('#search-results');
                        results.empty();

                        if (data.length > 0) {
                            $.each(data, function(index, course) {
                                results.append('<a href="{{ url("/course") }}/' + course.course_id + '" class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-700">' + course.course_name + '</a>');
                            });
                        } else {
                            results.append('<div class="px-4 py-3 text-sm text-slate-500 font-medium">Không tìm thấy kết quả</div>');
                        }
                    }
                });
            } else {
                $('#search-results').empty();
            }
        }

        $('#search').on('input', function() {
            searchCourses();
        });

        $('#search').on('keypress', function(e) {
            if (e.which == 13) { // Kiểm tra nếu phím Enter được nhấn
                e.preventDefault(); // Ngăn chặn hành động mặc định của phím Enter
                var query = $(this).val();
                if (query) {
                    window.location.href = '{{ route("home.search.results") }}?query=' + encodeURIComponent(query);
                }
            }
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
