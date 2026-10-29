@extends('layouts.admin')
@section('title', $student->name)
@section('content')
<div class="flex items-center gap-4 mb-6">
    <a href="{{ route('admin.students.index') }}" class="text-sm text-brand-700 flex items-center gap-1">
        <i class="bx bx-arrow-back"></i> Danh sách
    </a>
    <h1 class="text-2xl font-bold">{{ $student->name }}</h1>
    <span class="px-2 py-0.5 rounded-full text-xs {{ $student->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
        {{ $student->is_active ? 'Đang học' : 'Đã khóa' }}
    </span>
</div>

<div class="grid lg:grid-cols-3 gap-5 mb-6">
    <div class="lg:col-span-1 bg-white rounded-2xl border border-stone-100 p-6">
        <div class="w-16 h-16 rounded-2xl bg-brand-100 text-brand-800 flex items-center justify-center font-bold text-2xl mb-4">
            {{ mb_substr($student->name, 0, 1) }}
        </div>
        <p class="text-sm text-stone-500">Email</p>
        <p class="font-medium">{{ $student->email }}</p>
        <p class="text-sm text-stone-500 mt-2">SĐT</p>
        <p class="font-medium">{{ $student->phone ?? '—' }}</p>
        <p class="text-sm text-stone-500 mt-2">Địa chỉ</p>
        <p class="font-medium">{{ $student->address ?? '—' }}</p>
        <p class="text-sm text-stone-500 mt-2">Tình trạng học phí</p>
        <span class="px-2 py-0.5 rounded-full text-xs {{ $feeStatus === 'Đã nộp' ? 'bg-emerald-50 text-emerald-700' : 'bg-brand-50 text-brand-700' }}">
            {{ $feeStatus }}
        </span>

        <div class="mt-5 pt-5 border-t">
            <h3 class="font-semibold text-sm mb-3">Chỉnh sửa thông tin</h3>
            <form method="POST" action="{{ route('admin.students.update', $student) }}" class="space-y-2">
                @csrf @method('PUT')
                <input name="name" value="{{ $student->name }}" class="w-full border rounded-xl px-3 py-2 text-sm" required>
                <input type="email" name="email" value="{{ $student->email }}" class="w-full border rounded-xl px-3 py-2 text-sm" required>
                <input name="phone" value="{{ $student->phone }}" placeholder="SĐT" class="w-full border rounded-xl px-3 py-2 text-sm">
                <input type="password" name="password" placeholder="Mật khẩu mới (trống = giữ)" class="w-full border rounded-xl px-3 py-2 text-sm">
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="is_active" value="1" @checked($student->is_active)> Đang hoạt động
                </label>
                <div class="flex gap-2">
                    <button class="flex-1 py-2 bg-brand-600 text-white rounded-xl text-sm">Lưu</button>
                    <form method="POST" action="{{ route('admin.students.destroy', $student) }}" onsubmit="return confirm('Xóa học viên?')">
                        @csrf @method('DELETE')
                        <button class="py-2 px-3 bg-red-50 text-red-600 rounded-xl text-sm">Xóa</button>
                    </form>
                </div>
            </form>
        </div>
    </div>

    <div class="lg:col-span-2 space-y-5">
        <div class="bg-white rounded-2xl border border-stone-100 p-5">
            <h2 class="font-semibold mb-3">Lớp đang học</h2>
            @forelse ($student->enrolledClasses as $class)
                <div class="flex items-center justify-between py-2.5 border-b border-stone-50 text-sm">
                    <div>
                        <span class="font-mono text-brand-700 text-xs">{{ $class->code }}</span>
                        <p class="font-medium mt-0.5">{{ $class->name }}</p>
                        <p class="text-stone-500 text-xs">GV: {{ $class->teacher->name ?? '—' }} · {{ $class->schedule }}</p>
                    </div>
                    <span class="px-2 py-0.5 text-xs rounded-full
                        {{ $class->status === 'ongoing' ? 'bg-emerald-50 text-emerald-700' : ($class->status === 'upcoming' ? 'bg-sky-50 text-sky-700' : 'bg-stone-100 text-stone-500') }}">
                        {{ ['ongoing' => 'Đang học', 'upcoming' => 'Sắp mở', 'completed' => 'Kết thúc'][$class->status] ?? $class->status }}
                    </span>
                </div>
            @empty
                <p class="text-sm text-stone-400 py-2">Chưa đăng ký lớp nào.</p>
            @endforelse
        </div>

        <div class="bg-white rounded-2xl border border-stone-100 p-5">
            <h2 class="font-semibold mb-3">Lịch sử thanh toán học phí</h2>
            @forelse ($student->invoices as $inv)
                <div class="flex justify-between text-sm py-2.5 border-b border-stone-50">
                    <div>
                        <p class="font-mono text-xs text-stone-500">{{ $inv->code }}</p>
                        <p class="font-medium">{{ $inv->artClass->name ?? '—' }}</p>
                        @if ($inv->paid_at)
                            <p class="text-xs text-stone-400">Ngày nộp: {{ $inv->paid_at->format('d/m/Y') }}</p>
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="font-semibold">{{ number_format($inv->amount) }}đ</p>
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $inv->status === 'paid' ? 'bg-emerald-50 text-emerald-700' : 'bg-brand-50 text-brand-700' }}">
                            {{ $inv->status === 'paid' ? 'Đã nộp' : 'Chưa nộp' }}
                        </span>
                    </div>
                </div>
            @empty
                <p class="text-sm text-stone-400 py-2">Chưa có hóa đơn.</p>
            @endforelse
        </div>

        <div class="bg-white rounded-2xl border border-stone-100 p-5">
            <h2 class="font-semibold mb-3">Lịch sử điểm danh</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="text-xs uppercase text-stone-500">
                        <tr>
                            <th class="text-left py-2">Ngày</th>
                            <th class="text-left">Lớp</th>
                            <th class="text-left">Trạng thái</th>
                            <th class="text-left">Ghi chú</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($student->attendances as $att)
                        <tr class="border-t border-stone-50">
                            <td class="py-2">{{ $att->session_date->format('d/m/Y') }}</td>
                            <td>{{ $att->artClass->name ?? '—' }}</td>
                            <td>
                                <span class="px-1.5 py-0.5 rounded text-xs
                                    {{ $att->status === 'present' ? 'bg-emerald-50 text-emerald-700' : ($att->status === 'absent' ? 'bg-red-50 text-red-600' : 'bg-brand-50 text-brand-700') }}">
                                    {{ ['present' => 'Có mặt', 'absent' => 'Vắng', 'leave' => 'Nghỉ phép'][$att->status] ?? $att->status }}
                                </span>
                            </td>
                            <td class="text-stone-400">{{ $att->note }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="py-4 text-stone-400">Chưa có dữ liệu điểm danh.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
