@extends('layouts.admin')
@section('title', 'Tổng quan')
@section('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
@endsection

@section('content')
<div class="flex flex-wrap items-end justify-between gap-4 mb-7">
    <div>
        <p class="eyebrow">Bảng điều khiển</p>
        <h1 class="page-title mt-1.5">Xin chào, {{ auth()->user()->name }} 👋</h1>
        <p class="page-sub">Tổng quan hoạt động của trung tâm · {{ now()->translatedFormat('l, d/m/Y') }}</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.students.index') }}" class="btn btn-ghost"><i class="bx bxs-user-plus text-lg"></i> Thêm học viên</a>
        <a href="{{ route('admin.finance.index') }}" class="btn btn-primary"><i class="bx bxs-receipt text-lg"></i> Tạo hóa đơn</a>
    </div>
</div>

{{-- Stat cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-5">
    @php
        $cards = [
            ['Học viên', $stats['students'], 'bxs-user-detail', route('admin.students.index'), 'bg-brand-50 text-brand-700', $stats['teachers'] . ' giáo viên'],
            ['Lớp đang mở', $stats['classes'], 'bxs-chalkboard', route('admin.classes.index'), 'bg-sky-50 text-sky-700', $stats['courses'] . ' khóa học'],
            ['Doanh thu', number_format($stats['revenue']) . 'đ', 'bxs-wallet', route('admin.finance.index'), 'bg-amber-50 text-amber-700', 'Chưa thu: ' . number_format($stats['unpaid']) . 'đ'],
            ['Chờ xử lý', $stats['pending_enrollments'] + $stats['pending_payments'] + $stats['unread_messages'], 'bxs-bell-ring', route('admin.classes.index'), 'bg-rose-50 text-rose-600', $stats['pending_enrollments'] . ' đăng ký · ' . $stats['pending_payments'] . ' thanh toán'],
        ];
    @endphp
    @foreach ($cards as [$label, $value, $icon, $link, $tone, $hint])
        <a href="{{ $link }}" class="card card-pad card-hover">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-medium text-[#7d8f87] uppercase tracking-wider">{{ $label }}</p>
                    <p class="text-[26px] font-bold mt-1.5 leading-none">{{ $value }}</p>
                    <p class="text-[11px] text-[#9aaba3] mt-2">{{ $hint }}</p>
                </div>
                <span class="w-11 h-11 rounded-xl flex items-center justify-center {{ $tone }}">
                    <i class="bx {{ $icon }} text-xl"></i>
                </span>
            </div>
        </a>
    @endforeach
</div>

<div class="grid xl:grid-cols-3 gap-4 mb-5">
    {{-- Revenue chart --}}
    <div class="xl:col-span-2 card card-pad">
        <div class="flex flex-wrap justify-between items-start gap-3 mb-4">
            <div>
                <h2 class="section-title">Doanh thu năm {{ date('Y') }}</h2>
                <p class="text-xs text-[#9aaba3] mt-1">Chỉ tính hóa đơn đã được xác nhận thanh toán</p>
            </div>
            <div class="text-right">
                <p class="text-sm font-bold text-brand-700">{{ number_format($stats['revenue']) }}đ</p>
                <p class="text-[11px] text-[#9aaba3]">Tổng đã thu</p>
            </div>
        </div>
        <div class="h-[240px]">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    {{-- Pending enrollments --}}
    <div class="card card-pad flex flex-col">
        <div class="flex items-center justify-between mb-3">
            <h2 class="section-title">Đăng ký chờ duyệt</h2>
            <a href="{{ route('admin.classes.index') }}" class="text-xs link-green">Xem lớp</a>
        </div>
        <div class="space-y-2.5 flex-1">
            @forelse ($pendingEnrollments as $class)
                <div class="rounded-xl border border-[#eef4f0] p-3">
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-[13px] font-semibold truncate">{{ $class->name }}</p>
                        <span class="badge badge-amber">{{ $class->pending_students_count }} chờ</span>
                    </div>
                    <p class="text-[11px] text-[#8b9c93] mt-1 truncate">
                        {{ $class->pendingStudents->pluck('name')->take(3)->implode(', ') }}
                    </p>
                    <a href="{{ route('admin.classes.show', $class) }}" class="mt-2 inline-flex items-center gap-1 text-[11px] font-semibold text-brand-700 hover:gap-2 transition-all">
                        Duyệt ngay <i class="bx bx-right-arrow-alt"></i>
                    </a>
                </div>
            @empty
                <div class="empty !py-8">
                    <i class="bx bxs-check-shield"></i>
                    Không có yêu cầu nào đang chờ.
                </div>
            @endforelse
        </div>
        @if ($stats['pending_payments'] > 0)
            <a href="{{ route('admin.finance.index', ['status' => 'pending']) }}" class="mt-3 flex items-center gap-2 text-xs bg-amber-50 text-amber-800 rounded-xl px-3 py-2.5">
                <i class="bx bxs-error-circle text-base"></i>
                {{ $stats['pending_payments'] }} hóa đơn chờ xác nhận chuyển khoản
            </a>
        @endif
    </div>
</div>

<div class="grid xl:grid-cols-3 gap-4">
    {{-- Classes --}}
    <div class="xl:col-span-2 card">
        <div class="flex items-center justify-between px-5 py-4">
            <h2 class="section-title">Lớp đang diễn ra</h2>
            <a href="{{ route('admin.classes.index') }}" class="text-xs link-green">Tất cả lớp</a>
        </div>
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Lớp</th>
                        <th>Khóa học</th>
                        <th>Giáo viên</th>
                        <th class="text-center">Sĩ số</th>
                        <th class="text-right"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($currentClasses as $class)
                        <tr>
                            <td>
                                <p class="font-medium">{{ $class->name }}</p>
                                <p class="text-[11px] font-mono text-[#9aaba3]">{{ $class->code }}</p>
                            </td>
                            <td class="text-[#4b5f56]">{{ $class->course->name ?? '—' }}</td>
                            <td class="text-[#4b5f56]">{{ $class->teacher->name ?? '—' }}</td>
                            <td class="text-center">
                                <div class="inline-flex items-center gap-2">
                                    <span class="text-xs font-semibold">{{ $class->students_count }}/{{ $class->max_students }}</span>
                                    <span class="hidden sm:block w-16 h-1.5 rounded-full bg-[#edf2ef] overflow-hidden">
                                        <span class="block h-full bg-brand-500" style="width: {{ min(100, ($class->students_count / max(1, $class->max_students)) * 100) }}%"></span>
                                    </span>
                                </div>
                            </td>
                            <td class="text-right">
                                <a href="{{ route('admin.classes.show', $class) }}" class="btn btn-ghost btn-sm">Chi tiết</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="empty !py-8"><i class="bx bxs-chalkboard"></i>Chưa có lớp nào đang diễn ra.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="space-y-4">
        {{-- Messages --}}
        <div class="card card-pad">
            <div class="flex items-center justify-between mb-3">
                <h2 class="section-title">Liên hệ mới</h2>
                <a href="{{ route('admin.messages.index') }}" class="text-xs link-green">Hộp thư</a>
            </div>
            <div class="space-y-3">
                @forelse ($recentMessages as $msg)
                    <div class="text-sm">
                        <div class="flex items-center gap-2">
                            <span class="avatar !w-7 !h-7 !text-[11px]">{{ mb_substr($msg->name, 0, 1) }}</span>
                            <p class="font-medium truncate">{{ $msg->name }}</p>
                            @unless ($msg->is_read)
                                <span class="w-1.5 h-1.5 rounded-full bg-brand-500 ml-auto"></span>
                            @endunless
                        </div>
                        <p class="text-xs text-[#8b9c93] mt-1 line-clamp-2">{{ $msg->message }}</p>
                    </div>
                @empty
                    <p class="text-xs text-[#9aaba3]">Chưa có liên hệ nào.</p>
                @endforelse
            </div>
        </div>

        {{-- Top courses --}}
        <div class="card card-pad">
            <h2 class="section-title mb-3 flex items-center gap-2">
                <i class="bx bxs-trophy text-amber-500"></i> Khóa học đông học viên
            </h2>
            @forelse ($topCourses as $i => $course)
                <div class="flex justify-between items-center text-sm py-2 border-b border-[#f0f4f1] last:border-0">
                    <div class="flex items-center gap-2 min-w-0">
                        <span class="w-5 h-5 rounded-md bg-brand-50 text-brand-700 flex items-center justify-center text-[10px] font-bold flex-shrink-0">{{ $i + 1 }}</span>
                        <span class="truncate">{{ $course->name }}</span>
                    </div>
                    <span class="badge badge-green flex-shrink-0">{{ $course->student_count }} HV</span>
                </div>
            @empty
                <p class="text-xs text-[#9aaba3]">Chưa có dữ liệu.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
const ctx = document.getElementById('revenueChart');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json($chartLabels),
        datasets: [{
            label: 'Doanh thu',
            data: @json($chartValues),
            borderColor: '#0e8057',
            backgroundColor: (context) => {
                const g = context.chart.ctx.createLinearGradient(0, 0, 0, 240);
                g.addColorStop(0, 'rgba(31,160,108,.28)');
                g.addColorStop(1, 'rgba(31,160,108,0)');
                return g;
            },
            fill: true,
            tension: .42,
            borderWidth: 2.5,
            pointBackgroundColor: '#0e8057',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 4,
            pointHoverRadius: 6,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#04281e',
                padding: 10,
                cornerRadius: 10,
                callbacks: {
                    label: (c) => ' ' + new Intl.NumberFormat('vi-VN').format(c.parsed.y) + 'đ'
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                border: { display: false },
                ticks: {
                    color: '#9aaba3',
                    font: { size: 10 },
                    callback: (v) => new Intl.NumberFormat('vi-VN', { notation: 'compact' }).format(v) + 'đ'
                },
                grid: { color: 'rgba(16,35,28,.05)' }
            },
            x: {
                border: { display: false },
                ticks: { color: '#9aaba3', font: { size: 10 } },
                grid: { display: false }
            }
        }
    }
});
</script>
@endsection
