@extends('layouts.admin')
@section('title', $teacher->name)
@section('content')
<div class="flex items-center gap-4 mb-6">
    <a href="{{ route('admin.teachers.index') }}" class="text-sm text-brand-700 flex items-center gap-1">
        <i class="bx bx-arrow-back"></i> Danh sách
    </a>
    <h1 class="text-2xl font-bold">{{ $teacher->name }}</h1>
    <span class="px-2 py-0.5 rounded-full text-xs {{ $teacher->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
        {{ $teacher->is_active ? 'Đang hoạt động' : 'Đã khóa' }}
    </span>
</div>

<div class="grid lg:grid-cols-3 gap-5">
    {{-- Cột trái: Hồ sơ + Sửa --}}
    <div class="space-y-5">
        <div class="bg-white rounded-2xl border border-stone-100 p-6">
            <div class="w-16 h-16 rounded-2xl bg-brand-100 text-brand-800 flex items-center justify-center font-bold text-2xl mb-4">
                {{ mb_substr($teacher->name, 0, 1) }}
            </div>
            <p class="text-stone-600 text-sm">{{ $teacher->bio }}</p>
            <div class="mt-4 space-y-1.5 text-sm">
                <p><span class="text-stone-400">Email:</span> {{ $teacher->email }}</p>
                <p><span class="text-stone-400">SĐT:</span> {{ $teacher->phone ?? '—' }}</p>
                <p><span class="text-stone-400">Lương/giờ:</span> {{ number_format($teacher->hourly_rate ?? 0) }}đ</p>
                <p><span class="text-stone-400">Tháng này:</span> <b>{{ $hours }} giờ</b> · Tạm tính: <b>{{ number_format($estimatedSalary) }}đ</b></p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-stone-100 p-5">
            <h3 class="font-semibold mb-3">Chỉnh sửa thông tin</h3>
            <form method="POST" action="{{ route('admin.teachers.update', $teacher) }}" class="space-y-2">
                @csrf @method('PUT')
                <input name="name" value="{{ $teacher->name }}" class="w-full border rounded-xl px-3 py-2 text-sm" required>
                <input type="email" name="email" value="{{ $teacher->email }}" class="w-full border rounded-xl px-3 py-2 text-sm" required>
                <input name="phone" value="{{ $teacher->phone }}" placeholder="SĐT" class="w-full border rounded-xl px-3 py-2 text-sm">
                <input type="number" name="hourly_rate" value="{{ $teacher->hourly_rate }}" placeholder="Lương/giờ" class="w-full border rounded-xl px-3 py-2 text-sm">
                <textarea name="bio" placeholder="Hồ sơ" class="w-full border rounded-xl px-3 py-2 text-sm">{{ $teacher->bio }}</textarea>
                <input type="password" name="password" placeholder="Mật khẩu mới (trống = giữ)" class="w-full border rounded-xl px-3 py-2 text-sm">
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="is_active" value="1" @checked($teacher->is_active)> Đang hoạt động
                </label>
                <button class="w-full py-2 bg-brand-600 text-white rounded-xl text-sm">Lưu thay đổi</button>
            </form>
            <form method="POST" action="{{ route('admin.teachers.destroy', $teacher) }}" class="mt-2" onsubmit="return confirm('Xóa giáo viên này?')">
                @csrf @method('DELETE')
                <button class="w-full py-2 bg-red-50 text-red-600 rounded-xl text-sm">Xóa giáo viên</button>
            </form>
        </div>
    </div>

    {{-- Cột giữa: Lớp đang dạy + Chi lương --}}
    <div class="space-y-5">
        <div class="bg-white rounded-2xl border border-stone-100 p-5">
            <h2 class="font-semibold mb-3">Lớp đang dạy</h2>
            @forelse ($teacher->teachingClasses as $class)
                <div class="py-2.5 border-b border-stone-50 text-sm">
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="font-mono text-brand-700 text-xs">{{ $class->code }}</span>
                            <p class="font-medium">{{ $class->name }}</p>
                            <p class="text-stone-400 text-xs">{{ $class->schedule }} · {{ $class->room }}</p>
                        </div>
                        <span class="text-xs px-1.5 py-0.5 rounded bg-stone-100 text-stone-600">
                            {{ $class->students->count() }} HV
                        </span>
                    </div>
                </div>
            @empty
                <p class="text-sm text-stone-400">Chưa được phân lớp.</p>
            @endforelse
        </div>

        <div class="bg-white rounded-2xl border border-stone-100 p-5">
            <h2 class="font-semibold mb-3">Chi lương tháng này</h2>
            <form method="POST" action="{{ route('admin.teachers.salary', $teacher) }}" class="space-y-2">
                @csrf
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="text-xs text-stone-500">Tháng</label>
                        <input type="number" name="period_month" value="{{ date('n') }}" min="1" max="12" class="w-full border rounded-xl px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="text-xs text-stone-500">Năm</label>
                        <input type="number" name="period_year" value="{{ date('Y') }}" min="2020" class="w-full border rounded-xl px-3 py-2 text-sm">
                    </div>
                </div>
                <div>
                    <label class="text-xs text-stone-500">Số giờ dạy</label>
                    <input type="number" step="0.5" name="teaching_hours" value="{{ $hours }}" class="w-full border rounded-xl px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="text-xs text-stone-500">Số tiền (đ)</label>
                    <input type="number" name="amount" value="{{ $estimatedSalary }}" class="w-full border rounded-xl px-3 py-2 text-sm">
                </div>
                <button class="w-full py-2 bg-brand-600 text-white rounded-xl text-sm">Xác nhận chi lương</button>
            </form>
        </div>
    </div>

    {{-- Cột phải: Lịch sử lương --}}
    <div class="bg-white rounded-2xl border border-stone-100 p-5">
        <h2 class="font-semibold mb-3">Lịch sử nhận lương</h2>
        @forelse ($teacher->salaryPayments->sortByDesc('period_year')->sortByDesc('period_month') as $pay)
            <div class="flex justify-between py-2.5 border-b border-stone-50 text-sm">
                <div>
                    <p class="font-medium">T{{ $pay->period_month }}/{{ $pay->period_year }}</p>
                    <p class="text-xs text-stone-400">{{ $pay->teaching_hours }} giờ</p>
                </div>
                <div class="text-right">
                    <p class="font-semibold">{{ number_format($pay->amount) }}đ</p>
                    <span class="text-xs {{ $pay->status === 'paid' ? 'text-emerald-600' : 'text-brand-600' }}">
                        {{ $pay->status === 'paid' ? '✓ Đã chi' : '⏳ Chờ chi' }}
                    </span>
                </div>
            </div>
        @empty
            <p class="text-sm text-stone-400">Chưa có lịch sử lương.</p>
        @endforelse
    </div>
</div>
@endsection
