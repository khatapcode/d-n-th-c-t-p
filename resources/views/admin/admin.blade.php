<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .sidebar-link { transition: all 0.2s ease; }
        .sidebar-link:hover { background: rgba(255,255,255,0.1); transform: translateX(4px); }
        .sidebar-link.active { background: linear-gradient(90deg, #6366f1, #8b5cf6); box-shadow: 0 4px 15px rgba(99,102,241,0.4); }
        .card-hover { transition: all 0.2s ease; }
        .card-hover:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #1e1e2e; }
        ::-webkit-scrollbar-thumb { background: #6366f1; border-radius: 3px; }
    </style>
</head>
<body class="bg-slate-100 font-sans">

    <!-- HEADER -->
    <header class="fixed top-0 left-0 right-0 z-20 h-16 bg-gradient-to-r from-slate-900 to-slate-800 shadow-lg flex items-center px-6">
        <!-- Logo -->
        <div class="flex items-center gap-3 w-64">
            <div class="w-8 h-8 bg-indigo-500 rounded-lg flex items-center justify-center">
                <i class='bx bxs-graduation text-white text-lg'></i>
            </div>
            <span class="text-white font-bold text-lg tracking-wide">IT Courses</span>
            <span class="text-slate-400 text-xs ml-1">Admin</span>
        </div>

        <!-- Spacer -->
        <div class="flex-1"></div>

        <!-- User Info -->
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2 bg-slate-700 rounded-full px-4 py-2">
                <div class="w-7 h-7 bg-indigo-500 rounded-full flex items-center justify-center">
                    <i class='bx bxs-user text-white text-sm'></i>
                </div>
                <span class="text-white text-sm font-medium">Administrator</span>
            </div>
            <a href="{{ route('logout') }}" class="flex items-center gap-2 text-slate-400 hover:text-red-400 transition-colors px-3 py-2 rounded-lg hover:bg-slate-700">
                <i class='bx bx-log-out text-xl'></i>
                <span class="text-sm">Đăng xuất</span>
            </a>
        </div>
    </header>

    <!-- SIDEBAR -->
    <aside class="fixed left-0 top-16 h-full w-64 bg-gradient-to-b from-slate-900 to-slate-800 shadow-xl z-10 overflow-y-auto pb-20">
        <div class="p-4 mt-2">
            <!-- Dashboard Link -->
            <a href="{{ route('admin.index') }}"
               class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl mb-1 text-slate-300 {{ request()->is('admin') ? 'active text-white' : 'hover:text-white' }}">
                <i class='bx bxs-dashboard text-lg'></i>
                <span class="text-sm font-medium">Dashboard</span>
            </a>

            <!-- Section Label -->
            <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider mt-4 mb-2 px-4">Quản lý</p>

            <a href="{{ route('admin.user') }}"
               class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl mb-1 text-slate-300 {{ request()->is('admin/user*') ? 'active text-white' : 'hover:text-white' }}">
                <i class='bx bxs-user-detail text-lg'></i>
                <span class="text-sm font-medium">Người dùng</span>
            </a>

            <a href="{{ route('admin.category') }}"
               class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl mb-1 text-slate-300 {{ request()->is('admin/category*') ? 'active text-white' : 'hover:text-white' }}">
                <i class='bx bxs-category text-lg'></i>
                <span class="text-sm font-medium">Danh mục</span>
            </a>

            <a href="{{ route('admin.course') }}"
               class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl mb-1 text-slate-300 {{ request()->is('admin/course*') ? 'active text-white' : 'hover:text-white' }}">
                <i class='bx bxs-book-bookmark text-lg'></i>
                <span class="text-sm font-medium">Khóa học</span>
            </a>

            <a href="{{ route('admin.registerCourse') }}"
               class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl mb-1 text-slate-300 {{ request()->is('admin/registerCourse*') ? 'active text-white' : 'hover:text-white' }}">
                <i class='bx bxs-calendar-check text-lg'></i>
                <span class="text-sm font-medium">Đăng ký khóa học</span>
            </a>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="ml-64 pt-16 min-h-screen">
        <div class="p-6">
            @yield('content')
        </div>
    </main>

</body>
</html>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


