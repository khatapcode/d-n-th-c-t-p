@extends('layouts.admin')
@section('title', 'Lớp ' . $class->code)
@section('content')
@php $fill = min(100, ($class->students->count() / max(1, $class->max_students)) * 100); @endphp

<div class="flex flex-wrap items-start justify-between gap-4 mb-6">
    <div>
        <a href="{{ route('admin.classes.index') }}" class="inline-flex items-center gap-1 text-xs text-[#7d8f87] hover:text-brand-700 mb-2">
            <i class="bx bx-left-arrow-alt"></i> Danh sách lớp
        </a>
        <div class="flex items-center gap-3">
            <h1 class="page-title">{{ $class->name }}</h1>
            <span class="badge {{ $class->status === 'ongoing' ? 'badge-green' : ($class->status === 'completed' ? 'badge-gray' : 'badge-blue') }}">{{ $class->statusLabel() }}</span>
        </div>
        <p class="page-sub">{{ $class->code }} · {{ $class->course->name ?? '—' }} · {{ $class->schedule ?? 'Chưa xếp lịch' }}</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.finance.index') }}" class="btn btn-ghost"><i class="bx bxs-wallet text-lg"></i> Tài chính lớp</a>
        <a href="{{ route('admin.courses.index') }}" class="btn btn-ghost"><i class="bx bxs-book-content text-lg"></i> Khóa học</a>
    </div>
</div>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
    <div class="card card-pad">
        <p class="text-[11px] uppercase tracking-wider text-[#9aaba3]">Sĩ số</p>
        <p class="text-2xl font-bold mt-1">{{ $class->students->count() }}<span class="text-sm font-medium text-[#9aaba3]">/{{ $class->max_students }}</span></p>
        <div class="h-1.5 rounded-full bg-[#edf2ef] mt-2 overflow-hidden"><div class="h-full bg-brand-500" style="width: {{ $fill }}%"></div></div>
    </div>
    <div class="card card-pad">
        <p class="text-[11px] uppercase tracking-wider text-[#9aaba3]">Giáo viên</p>
        <p class="text-sm font-semibold mt-2">{{ $class->teacher->name ?? 'Chưa phân công' }}</p>
        <p class="text-[11px] text-[#9aaba3] mt-1">{{ $class->teacher->email ?? '' }}</p>
    </div>
    <div class="card card-pad">
        <p class="text-[11px] uppercase tracking-wider text-[#9aaba3]">Thời gian</p>
        <p class="text-sm font-semibold mt-2">{{ optional($class->start_date)->format('d/m/Y') ?? '—' }} → {{ optional($class->end_date)->format('d/m/Y') ?? '—' }}</p>
        <p class="text-[11px] text-[#9aaba3] mt-1">{{ $class->room ?? 'Chưa có phòng' }}</p>
    </div>
    <div class="card card-pad">
        <p class="text-[11px] uppercase tracking-wider text-[#9aaba3]">Học phí khóa</p>
        <p class="text-sm font-semibold mt-2 text-brand-700">{{ number_format($class->course->tuition_fee ?? 0) }}đ</p>
        <p class="text-[11px] text-[#9aaba3] mt-1">{{ $class->invoices->where('status', 'paid')->count() }}/{{ $class->invoices->count() }} hóa đơn đã thu</p>
    </div>
</div>

<div class="grid xl:grid-cols-3 gap-5">
    <div class="xl:col-span-2 space-y-5">
        {{-- Pending --}}
        <div class="card">
            <div class="flex items-center justify-between px-5 py-4">
                <h2 class="section-title flex items-center gap-2">
                    Yêu cầu chờ duyệt
                    @if ($class->pendingStudents->count())
                        <span class="badge badge-amber">{{ $class->pendingStudents->count() }}</span>
                    @endif
                </h2>
                <span class="text-[11px] text-[#9aaba3]">Còn {{ $class->seatsLeft() }} chỗ trống</span>
            </div>
            @if ($class->pendingStudents->isEmpty())
                <div class="empty !py-8"><i class="bx bxs-check-shield"></i>Không có yêu cầu nào đang chờ.</div>
            @else
                <div class="divide-y divide-[#f0f4f1]">
                    @foreach ($class->pendingStudents as $student)
                        <div class="px-5 py-3.5 flex flex-wrap items-center gap-3">
                            <span class="avatar">{{ mb_substr($student->name, 0, 1) }}</span>
                            <div class="min-w-0">
                                <p class="text-sm font-medium">{{ $student->name }}</p>
                                <p class="text-[11px] text-[#9aaba3]">{{ $student->email }} · gửi {{ $student->pivot->created_at?->diffForHumans() ?? 'vừa xong' }}</p>
                            </div>
                            <div class="ml-auto flex gap-2">
                                <form method="POST" action="{{ route('admin.classes.enrollments.approve', [$class, $student]) }}">
                                    @csrf
                                    <button class="btn btn-primary btn-sm" @disabled($class->isFull())>
                                        <i class="bx bx-check text-base"></i> Duyệt
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.classes.enrollments.reject', [$class, $student]) }}">
                                    @csrf
                                    <button class="btn btn-danger btn-sm">Từ chối</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Roster --}}
        <div class="card overflow-hidden">
            <div class="px-5 py-4 flex items-center justify-between">
                <h2 class="section-title">Danh sách học viên ({{ $class->students->count() }})</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Học viên</th>
                            <th>Ngày vào lớp</th>
                            <th>Học phí</th>
                            <th class="text-right"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($class->students as $student)
                            @php
                                $invoices = $class->invoices->where('student_id', $student->id);
                                $unpaid = $invoices->whereIn('status', ['unpaid', 'pending'])->count();
                            @endphp
                            <tr>
                                <td>
                                    <div class="flex items-center gap-2.5">
                                        <span class="avatar !w-8 !h-8 !text-xs">{{ mb_substr($student->name, 0, 1) }}</span>
                                        <div>
                                            <p class="font-medium">{{ $student->name }}</p>
                                            <p class="text-[11px] text-[#9aaba3]">{{ $student->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-[#4b5f56] text-xs">{{ optional($student->pivot->enrolled_at)->format('d/m/Y') ?? '—' }}</td>
                                <td>
                                    @if ($invoices->isEmpty())
                                        <span class="badge badge-gray">Chưa có hóa đơn</span>
                                    @elseif ($unpaid)
                                        <span class="badge badge-amber">Còn {{ $unpaid }} chưa thu</span>
                                    @else
                                        <span class="badge badge-green">Đã thu đủ</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <div class="inline-flex gap-1.5">
                                        <a href="{{ route('admin.students.show', $student) }}" class="btn btn-ghost btn-sm">Hồ sơ</a>
                                        <form method="POST" action="{{ route('admin.classes.students.remove', [$class, $student]) }}" onsubmit="return confirm('Xóa {{ $student->name }} khỏi lớp?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-danger btn-sm">Xóa</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="empty !py-8"><i class="bx bxs-user-x"></i>Lớp chưa có học viên nào.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Invoices --}}
        <div class="card overflow-hidden">
            <div class="px-5 py-4 flex items-center justify-between">
                <h2 class="section-title">Học phí của lớp</h2>
                <a href="{{ route('admin.finance.index') }}" class="text-xs link-green">Quản lý</a>
            </div>
            <div class="overflow-x-auto">
                <table class="table">
                    <thead><tr><th>Mã</th><th>Học viên</th><th>Số tiền</th><th>Trạng thái</th></tr></thead>
                    <tbody>
                        @forelse ($class->invoices->sortByDesc('created_at') as $invoice)
                            <tr>
                                <td class="font-mono text-xs text-[#7d8f87]">{{ $invoice->code }}</td>
                                <td>{{ $invoice->student->name ?? '—' }}</td>
                                <td class="font-semibold">{{ number_format($invoice->amount) }}đ</td>
                                <td>
                                    @if ($invoice->status === 'paid') <span class="badge badge-green">Đã thu</span>
                                    @elseif ($invoice->status === 'pending') <span class="badge badge-amber">Chờ xác nhận</span>
                                    @else <span class="badge badge-red">Chưa thu</span> @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="empty !py-8"><i class="bx bxs-receipt"></i>Chưa có hóa đơn nào cho lớp.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <aside class="space-y-5">
        {{-- Add student --}}
        <div class="card card-pad">
            <h2 class="section-title mb-3">Thêm học viên vào lớp</h2>
            <p class="text-xs text-[#7d8f99] mb-3">Học viên được thêm trực tiếp sẽ ở trạng thái đã duyệt và tự tạo hóa đơn học phí.</p>
            <form method="POST" action="{{ route('admin.classes.students.add', $class) }}" class="space-y-3">
                @csrf
                <select name="student_id" class="input" required>
                    <option value="">— Chọn học viên —</option>
                    @foreach ($allStudents as $student)
                        <option value="{{ $student->id }}">{{ $student->name }} · {{ $student->email }}</option>
                    @endforeach
                </select>
                <button class="btn btn-primary w-full" @disabled($class->isFull() || $allStudents->isEmpty())>
                    <i class="bx bx-user-plus text-lg"></i> Thêm & tạo học phí
                </button>
                @if ($class->isFull())
                    <p class="text-[11px] text-red-600">Lớp đã đủ sĩ số.</p>
                @endif
            </form>
        </div>

        {{-- Teacher --}}
        <div class="card card-pad">
            <h2 class="section-title mb-3">Giáo viên phụ trách</h2>
            @if ($class->teacher)
                <div class="flex items-center gap-3">
                    <span class="avatar avatar-lg">{{ mb_substr($class->teacher->name, 0, 1) }}</span>
                    <div>
                        <p class="font-medium text-sm">{{ $class->teacher->name }}</p>
                        <p class="text-[11px] text-[#9aaba3]">{{ $class->teacher->email }}</p>
                        <p class="text-[11px] text-[#9aaba3]">{{ $class->teacher->phone ?: 'Chưa có SĐT' }}</p>
                    </div>
                </div>
            @else
                <p class="text-sm text-[#9aaba3]">Chưa phân công giáo viên.</p>
            @endif
            <div class="divider my-4"></div>
            <p class="text-xs text-[#7d8f87] leading-relaxed">
                Giáo viên có thể điểm danh, duyệt yêu cầu vào lớp và duyệt tác phẩm của học viên trong lớp này.
            </p>
        </div>
    </aside>
</div>
@endsection
