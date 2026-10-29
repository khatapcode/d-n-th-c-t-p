@php
    $portal = $portal ?? 'admin';
    $roleLabel = [
        'admin' => 'Quản trị viên',
        'teacher' => 'Giáo viên',
        'student' => 'Học viên',
    ][$portal] ?? 'Thành viên';

    $pendingEnrollments = \Illuminate\Support\Facades\DB::table('class_student')->where('status', 'pending')->count();
    $pendingArtworks = \App\Models\Artwork::where('status', 'pending')->count();
    $unpaidInvoices = \App\Models\Invoice::whereIn('status', ['unpaid', 'pending'])->count();
    $unreadMessages = \App\Models\Contact::where('is_read', false)->count();

    if ($portal === 'admin') {
        $navigation = [
            ['label' => null, 'items' => [
                ['route' => 'admin.dashboard', 'match' => 'admin.dashboard', 'icon' => 'bxs-dashboard', 'label' => 'Tổng quan', 'badge' => null],
            ]],
            ['label' => 'Đào tạo', 'items' => [
                ['route' => 'admin.courses.index', 'match' => 'admin.courses.*', 'icon' => 'bxs-book-content', 'label' => 'Khóa học', 'badge' => null],
                ['route' => 'admin.classes.index', 'match' => 'admin.classes.*', 'icon' => 'bxs-chalkboard', 'label' => 'Lớp học', 'badge' => $pendingEnrollments],
                ['route' => 'admin.students.index', 'match' => 'admin.students.*', 'icon' => 'bxs-user-detail', 'label' => 'Học viên', 'badge' => null],
                ['route' => 'admin.teachers.index', 'match' => 'admin.teachers.*', 'icon' => 'bxs-user-voice', 'label' => 'Giáo viên', 'badge' => null],
            ]],
            ['label' => 'Vận hành', 'items' => [
                ['route' => 'admin.gallery.index', 'match' => 'admin.gallery.*', 'icon' => 'bxs-image-alt', 'label' => 'Phòng trưng bày', 'badge' => $pendingArtworks],
                ['route' => 'admin.finance.index', 'match' => 'admin.finance.*', 'icon' => 'bxs-wallet', 'label' => 'Tài chính', 'badge' => $unpaidInvoices],
                ['route' => 'admin.messages.index', 'match' => 'admin.messages.*', 'icon' => 'bxs-envelope', 'label' => 'Hộp thư liên hệ', 'badge' => $unreadMessages],
            ]],
            ['label' => 'Hệ thống', 'items' => [
                ['route' => 'admin.admins.index', 'match' => 'admin.admins.*', 'icon' => 'bxs-shield-alt-2', 'label' => 'Tài khoản admin', 'badge' => null],
                ['route' => 'admin.settings.index', 'match' => 'admin.settings.*', 'icon' => 'bxs-cog', 'label' => 'Cài đặt', 'badge' => null],
            ]],
        ];
    } elseif ($portal === 'teacher') {
        $navigation = [
            ['label' => null, 'items' => [
                ['route' => 'teacher.classes', 'match' => 'teacher.classes*', 'icon' => 'bxs-chalkboard', 'label' => 'Lớp tôi dạy', 'badge' => null],
                ['route' => 'teacher.gallery.index', 'match' => 'teacher.gallery.*', 'icon' => 'bxs-image-alt', 'label' => 'Tác phẩm học viên', 'badge' => $pendingArtworks],
                ['route' => 'teacher.salary', 'match' => 'teacher.salary', 'icon' => 'bxs-wallet', 'label' => 'Lương & thu nhập', 'badge' => null],
            ]],
            ['label' => 'Cá nhân', 'items' => [
                ['route' => 'teacher.account', 'match' => 'teacher.account*', 'icon' => 'bxs-user-circle', 'label' => 'Hồ sơ của tôi', 'badge' => null],
            ]],
        ];
    } else {
        $navigation = [
            ['label' => null, 'items' => [
                ['route' => 'student.classes', 'match' => 'student.classes', 'icon' => 'bxs-chalkboard', 'label' => 'Lớp của tôi', 'badge' => null],
                ['route' => 'student.enroll.index', 'match' => 'student.enroll.*', 'icon' => 'bxs-compass', 'label' => 'Đăng ký lớp mới', 'badge' => null],
            ]],
            ['label' => 'Cá nhân', 'items' => [
                ['route' => 'student.account', 'match' => 'student.account*', 'icon' => 'bxs-user-circle', 'label' => 'Hồ sơ & tác phẩm', 'badge' => null],
            ]],
        ];
    }
@endphp

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bảng điều khiển') — Madmun.Arthouse</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
                    fontFamily: { sans: ['"Be Vietnam Pro"', 'sans-serif'] }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}?v=2">
    @yield('head')
</head>
<body class="font-sans min-h-screen">

{{-- Mobile overlay --}}
<div id="sidebarOverlay" class="fixed inset-0 bg-black/40 z-30 hidden lg:hidden" onclick="toggleSidebar()"></div>

<div class="min-h-screen lg:flex">

    {{-- Sidebar --}}
    <aside id="sidebar" class="panel-sidebar fixed inset-y-0 left-0 z-40 w-[264px] flex flex-col -translate-x-full lg:translate-x-0 lg:static transition-transform duration-200 ease-out">

        <div class="h-[68px] flex items-center gap-3 px-5 border-b border-white/10 flex-shrink-0">
            <a href="{{ route('home') }}" class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand-400 to-brand-600 flex items-center justify-center shadow-lg shadow-brand-900/40">
                <i class="bx bxs-palette text-white text-xl"></i>
            </a>
            <div class="leading-tight">
                <p class="text-white font-bold text-[15px]">Madmun<span class="text-brand-300">.Arthouse</span></p>
                <p class="text-[10px] uppercase tracking-[.18em] text-brand-300/80">{{ $roleLabel }}</p>
            </div>
            <button class="lg:hidden ml-auto text-white/60 hover:text-white" onclick="toggleSidebar()">
                <i class="bx bx-x text-2xl"></i>
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto px-3 py-4">
            @foreach ($navigation as $group)
                @if ($group['label'])
                    <p class="nav-group-label">{{ $group['label'] }}</p>
                @endif
                @foreach ($group['items'] as $item)
                    @php $isActive = request()->routeIs($item['match']); @endphp
                    <a href="{{ route($item['route']) }}" class="nav-item {{ $isActive ? 'active' : '' }}">
                        <i class="bx {{ $item['icon'] }}"></i>
                        <span>{{ $item['label'] }}</span>
                        @if ($item['badge'] > 0)
                            <span class="nav-badge">{{ $item['badge'] > 99 ? '99+' : $item['badge'] }}</span>
                        @endif
                    </a>
                @endforeach
            @endforeach
        </nav>

        <div class="p-3 border-t border-white/10">
            <a href="{{ route('home') }}" class="nav-item !text-brand-200/80 hover:!text-white">
                <i class="bx bx-home-alt"></i>
                <span>Về trang chủ</span>
            </a>
        </div>
    </aside>

    {{-- Main column --}}
    <div class="flex-1 min-w-0 flex flex-col">

        <header class="sticky top-0 z-20 bg-white/90 backdrop-blur border-b border-[#e3ebe6]">
            <div class="h-[68px] px-4 sm:px-6 lg:px-8 flex items-center gap-3">
                <button class="lg:hidden w-9 h-9 rounded-lg bg-brand-50 text-brand-700 flex items-center justify-center" onclick="toggleSidebar()">
                    <i class="bx bx-menu text-xl"></i>
                </button>

                <div class="hidden sm:flex items-center gap-2 text-xs text-[#7d8f87]">
                    <i class="bx bxs-map-pin text-brand-500"></i>
                    <span>Không gian làm việc</span>
                    <i class="bx bx-chevron-right"></i>
                    <span class="font-semibold text-[#10231c]">{{ $roleLabel }}</span>
                </div>

                <div class="flex-1"></div>

                <details class="relative group">
                    <summary class="list-none cursor-pointer flex items-center gap-2.5 pl-1.5 pr-3 py-1.5 rounded-full hover:bg-[#f4f7f4] transition">
                        <span class="avatar">{{ mb_substr(auth()->user()->name, 0, 1) }}</span>
                        <span class="hidden sm:block leading-tight text-left">
                            <span class="block text-[13px] font-semibold text-[#10231c]">{{ auth()->user()->name }}</span>
                            <span class="block text-[10px] uppercase tracking-wider text-[#7d8f87]">{{ $roleLabel }}</span>
                        </span>
                        <i class="bx bx-chevron-down text-[#7d8f87]"></i>
                    </summary>
                    <div class="absolute right-0 mt-2 w-56 card p-2 z-30">
                        <a href="{{ route(auth()->user()->homeRoute()) }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-[#4b5f56] hover:bg-[#f4f7f4]">
                            <i class="bx bx-user-circle text-lg"></i> Trang cá nhân
                        </a>
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-[#4b5f56] hover:bg-[#f4f7f4]">
                                <i class="bx bx-cog text-lg"></i> Cài đặt tài khoản
                            </a>
                        @endif
                        <div class="divider my-1"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-red-600 hover:bg-red-50">
                                <i class="bx bx-log-out text-lg"></i> Đăng xuất
                            </button>
                        </form>
                    </div>
                </details>
            </div>
        </header>

        <main class="flex-1 px-4 sm:px-6 lg:px-8 py-6 lg:py-8 w-full max-w-[1440px]">
            @if (session('success'))
                <div class="flash flash-success" id="flashSuccess">
                    <i class="bx bxs-check-circle text-lg mt-0.5"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if ($errors->any())
                <div class="flash flash-error" id="flashError">
                    <i class="bx bxs-error-circle text-lg mt-0.5"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            @yield('content')
        </main>

        <footer class="px-6 lg:px-8 pb-6 text-[11px] text-[#9aaba3] flex flex-wrap gap-2 justify-between">
            <span>© {{ date('Y') }} Madmun.Arthouse — Hệ thống quản lý trung tâm nghệ thuật.</span>
            <span>Phiên bản 2.0 · {{ $roleLabel }}</span>
        </footer>
    </div>
</div>

<script>
    function toggleSidebar() {
        const bar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const hidden = bar.classList.contains('-translate-x-full');
        bar.classList.toggle('-translate-x-full', !hidden);
        overlay.classList.toggle('hidden', !hidden);
        document.body.style.overflow = hidden ? 'hidden' : '';
    }
    setTimeout(() => {
        ['flashSuccess', 'flashError'].forEach(id => {
            const el = document.getElementById(id);
            if (el) { el.style.transition = 'opacity .4s'; el.style.opacity = 0; setTimeout(() => el.remove(), 400); }
        });
    }, 4000);
    document.addEventListener('click', (e) => {
        document.querySelectorAll('details[open]').forEach(d => {
            if (!d.contains(e.target)) d.removeAttribute('open');
        });
    });
</script>
@yield('scripts')
</body>
</html>
