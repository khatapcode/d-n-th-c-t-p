@extends('layouts.studio', ['portal' => 'student'])
@section('title', 'Lớp của tôi')
@section('content')
@php
    $unpaidTotal = $invoices->whereIn('status', ['unpaid', 'pending'])->sum('amount');
    $statusMeta = [
        'unpaid' => ['Chưa thanh toán', 'badge-red'],
        'pending' => ['Chờ xác nhận', 'badge-amber'],
        'paid' => ['Đã thanh toán', 'badge-green'],
    ];
@endphp

<div class="flex flex-wrap items-end justify-between gap-3 mb-6">
    <div>
        <p class="eyebrow">Học viên</p>
        <h1 class="page-title mt-1.5">Lớp của tôi</h1>
        <p class="page-sub">Theo dõi lịch học, điểm danh và học phí của bạn.</p>
    </div>
    <a href="{{ route('student.enroll.index') }}" class="btn btn-primary"><i class="bx bx-plus text-lg"></i> Đăng ký lớp mới</a>
</div>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="card card-pad">
        <p class="text-[11px] uppercase tracking-wider text-[#9aaba3]">Lớp đang học</p>
        <p class="text-2xl font-bold mt-1">{{ $classes->count() }}</p>
    </div>
    <div class="card card-pad">
        <p class="text-[11px] uppercase tracking-wider text-[#9aaba3]">Tỷ lệ có mặt</p>
        <p class="text-2xl font-bold mt-1">{{ $attendanceTotal ? round($attendanceRate / $attendanceTotal * 100) : 0 }}%</p>
        <p class="text-[11px] text-[#9aaba3]">{{ $attendanceTotal }} buổi đã ghi nhận</p>
    </div>
    <div class="card card-pad">
        <p class="text-[11px] uppercase tracking-wider text-[#9aaba3]">Học phí còn lại</p>
        <p class="text-2xl font-bold mt-1 {{ $unpaidTotal ? 'text-rose-600' : 'text-brand-700' }}">{{ number_format($unpaidTotal) }}đ</p>
    </div>
    <div class="card card-pad">
        <p class="text-[11px] uppercase tracking-wider text-[#9aaba3]">Tác phẩm đã duyệt</p>
        <p class="text-2xl font-bold mt-1">{{ $student->artworks()->where('status', 'approved')->count() }}</p>
        <a href="{{ route('student.account') }}" class="text-[11px] link-green">Xem gallery →</a>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-5">
    <div class="space-y-4">
        <h2 class="section-title">Lớp đã đăng ký</h2>
        @forelse ($classes as $class)
            <div class="card card-pad">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <p class="text-[11px] font-mono text-[#9aaba3]">{{ $class->code }}</p>
                        <h3 class="font-semibold mt-0.5">{{ $class->name }}</h3>
                        <p class="text-xs text-[#7d8f87]">{{ $class->course->name ?? '' }}</p>
                    </div>
                    <span class="badge {{ $class->status === 'ongoing' ? 'badge-green' : 'badge-blue' }}">{{ $class->statusLabel() }}</span>
                </div>
                <div class="grid grid-cols-2 gap-3 mt-4 text-xs text-[#4b5f56]">
                    <p class="flex items-center gap-2"><i class="bx bx-user-voice text-brand-500 text-base"></i>{{ $class->teacher->name ?? 'Đang cập nhật' }}</p>
                    <p class="flex items-center gap-2"><i class="bx bx-time-five text-brand-500 text-base"></i>{{ $class->schedule ?? '—' }}</p>
                    <p class="flex items-center gap-2"><i class="bx bx-map text-brand-500 text-base"></i>{{ $class->room ?? '—' }}</p>
                    <p class="flex items-center gap-2"><i class="bx bx-calendar text-brand-500 text-base"></i>{{ optional($class->start_date)->format('d/m/Y') ?? '—' }}</p>
                </div>

                <details class="mt-4 group">
                    <summary class="list-none cursor-pointer text-xs font-semibold text-brand-700 inline-flex items-center gap-1">
                        <i class="bx bx-calendar-x text-base"></i> Báo nghỉ một buổi
                        <i class="bx bx-chevron-down group-open:rotate-180 transition"></i>
                    </summary>
                    <form method="POST" action="{{ route('student.classes.leave', $class) }}" class="mt-3 grid sm:grid-cols-[1fr_1.4fr_auto] gap-2">
                        @csrf
                        <input type="date" name="session_date" value="{{ date('Y-m-d') }}" class="input !py-2 text-xs" required>
                        <input name="note" placeholder="Lý do nghỉ" class="input !py-2 text-xs">
                        <button class="btn btn-soft btn-sm">Gửi</button>
                    </form>
                </details>
            </div>
        @empty
            <div class="card empty">
                <i class="bx bxs-chalkboard"></i>
                Bạn chưa tham gia lớp nào.
                <a href="{{ route('student.enroll.index') }}" class="link-green block mt-2">Xem lớp đang mở →</a>
            </div>
        @endforelse
    </div>

    <div class="space-y-5">
        <div class="card overflow-hidden">
            <div class="px-5 py-4">
                <h2 class="section-title">Học phí</h2>
                <p class="text-xs text-[#9aaba3] mt-0.5">Báo thanh toán sau khi chuyển khoản để admin xác nhận.</p>
            </div>
            <div class="divide-y divide-[#f0f4f1]">
                @forelse ($invoices as $inv)
                    @php [$label, $tone] = $statusMeta[$inv->status] ?? [$inv->status, 'badge-gray']; @endphp
                    <div class="px-5 py-3.5">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <p class="text-sm font-medium">{{ $inv->artClass->name ?? 'Học phí chung' }}</p>
                                <p class="text-[11px] font-mono text-[#9aaba3]">{{ $inv->code }} · {{ $inv->created_at->format('d/m/Y') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold">{{ number_format($inv->amount) }}đ</p>
                                <span class="badge {{ $tone }}">{{ $label }}</span>
                            </div>
                        </div>

                        @if ($inv->status === 'unpaid')
                            <details class="mt-2.5">
                                <summary class="list-none cursor-pointer text-xs font-semibold text-brand-700 inline-flex items-center gap-1">
                                    <i class="bx bx-transfer text-base"></i> Tôi đã chuyển khoản
                                </summary>
                                <form method="POST" action="{{ route('student.invoices.pay', $inv) }}" class="mt-2 grid sm:grid-cols-[1fr_1.4fr_auto] gap-2">
                                    @csrf
                                    <select name="method" class="input !py-2 text-xs">
                                        <option value="bank">Chuyển khoản</option>
                                        <option value="momo">MoMo</option>
                                        <option value="vnpay">VNPay</option>
                                        <option value="cash">Tiền mặt</option>
                                    </select>
                                    <input name="paid_note" placeholder="Mã giao dịch / ghi chú" class="input !py-2 text-xs">
                                    <button class="btn btn-soft btn-sm">Gửi</button>
                                </form>
                            </details>
                        @elseif ($inv->status === 'pending')
                            <p class="text-[11px] text-amber-700 mt-2">Đã báo thanh toán{{ $inv->paid_note ? ' · ' . $inv->paid_note : '' }}. Chờ admin xác nhận.</p>
                        @else
                            <p class="text-[11px] text-brand-700 mt-2">Đã xác nhận {{ optional($inv->paid_at)->format('d/m/Y') }}</p>
                        @endif
                    </div>
                @empty
                    <div class="empty"><i class="bx bxs-receipt"></i>Chưa có hóa đơn nào.</div>
                @endforelse
            </div>
        </div>

        <div class="card overflow-hidden">
            <div class="px-5 py-4"><h2 class="section-title">Lịch sử điểm danh</h2></div>
            <div class="overflow-x-auto max-h-[340px] overflow-y-auto">
                <table class="table">
                    <thead><tr><th>Ngày</th><th>Lớp</th><th>Trạng thái</th></tr></thead>
                    <tbody>
                        @forelse ($attendances as $att)
                            <tr>
                                <td class="text-xs">{{ $att->session_date->format('d/m/Y') }}</td>
                                <td class="text-xs text-[#4b5f56]">{{ $att->artClass->name ?? '—' }}</td>
                                <td>
                                    @if ($att->status === 'present') <span class="badge badge-green">Có mặt</span>
                                    @elseif ($att->status === 'absent') <span class="badge badge-red">Vắng</span>
                                    @else <span class="badge badge-amber">Nghỉ phép</span> @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="empty !py-8"><i class="bx bxs-calendar"></i>Chưa có buổi điểm danh.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
