@extends('layouts.admin')
@section('title', 'Học viên')
@section('content')
<div class="flex justify-between mb-6 gap-3">
    <div>
        <p class="eyebrow">Đào tạo</p>
        <h1 class="page-title mt-1.5">Quản lý học viên</h1>
        <p class="page-sub">Hồ sơ, lớp đang học và tình trạng học phí của từng học viên.</p>
    </div>
    <div class="flex gap-2 items-start">
        <form method="GET" class="flex gap-2">
            <input name="q" value="{{ request('q') }}" placeholder="Tìm tên/email..." class="border rounded-xl px-3 py-2 text-sm w-52">
            <button class="px-3 py-2 bg-stone-100 rounded-xl text-sm hover:bg-stone-200">
                <i class="bx bx-search"></i>
            </button>
        </form>
        <button onclick="openModal('createStudent')" class="px-4 py-2 bg-brand-600 text-white rounded-xl text-sm font-medium flex items-center gap-1">
            <i class="bx bx-plus"></i> Thêm học viên
        </button>
    </div>
</div>

<div class="bg-white rounded-2xl border border-stone-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-stone-50 text-xs uppercase text-stone-500">
            <tr>
                <th class="text-left px-4 py-3">Học viên</th>
                <th class="text-center">Lớp đang học</th>
                <th class="text-center">Học phí</th>
                <th class="text-center">Trạng thái</th>
                <th class="text-right px-4"></th>
            </tr>
        </thead>
        <tbody>
        @forelse ($students as $student)
            @php
                $unpaid = $student->invoices->where('status', 'unpaid')->count();
                $pending = $student->invoices->where('status', 'pending')->count();
            @endphp
            <tr class="border-t border-stone-50 hover:bg-stone-50/50">
                <td class="px-4 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-brand-100 text-brand-700 flex items-center justify-center font-semibold text-sm">
                            {{ mb_substr($student->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-medium">{{ $student->name }}</p>
                            <p class="text-xs text-stone-400">{{ $student->email }}</p>
                        </div>
                    </div>
                </td>
                <td class="text-center">
                    <span class="px-2 py-0.5 bg-stone-100 text-stone-700 rounded-full text-xs">
                        {{ $student->enrolledClasses->count() }} lớp
                    </span>
                </td>
                <td class="text-center">
                    <span class="px-2 py-0.5 rounded-full text-xs {{ $unpaid ? 'bg-red-50 text-red-700' : ($pending ? 'bg-amber-50 text-amber-700' : 'bg-emerald-50 text-emerald-700') }}">
                        {{ $unpaid ? 'Còn ' . $unpaid . ' chưa nộp' : ($pending ? $pending . ' chờ xác nhận' : 'Đã nộp') }}
                    </span>
                </td>
                <td class="text-center">
                    <span class="text-xs {{ $student->is_active ? 'text-emerald-600' : 'text-red-500' }}">
                        {{ $student->is_active ? '● Hoạt động' : '● Bị khóa' }}
                    </span>
                </td>
                <td class="px-4 text-right">
                    <a class="text-brand-700 text-sm hover:underline" href="{{ route('admin.students.show', $student) }}">
                        Hồ sơ <i class="bx bx-chevron-right"></i>
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="px-4 py-10 text-center text-stone-400">
                    <i class="bx bxs-user-x text-4xl block mb-2"></i>
                    {{ request('q') ? 'Không tìm thấy học viên nào.' : 'Chưa có học viên nào.' }}
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
    <div class="p-4">{{ $students->links() }}</div>
</div>

{{-- Modal tạo học viên --}}
<div id="createStudent" class="hidden fixed inset-0 z-40 bg-black/40 items-center justify-center p-4">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-xl">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-lg">Thêm học viên mới</h3>
            <button onclick="closeModal('createStudent')" class="text-stone-400 hover:text-stone-600">
                <i class="bx bx-x text-2xl"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.students.store') }}" class="space-y-3">
            @csrf
            <div>
                <label class="text-sm font-medium">Họ tên *</label>
                <input name="name" placeholder="Nguyễn Văn A" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm" required>
            </div>
            <div>
                <label class="text-sm font-medium">Email *</label>
                <input type="email" name="email" placeholder="email@example.com" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm" required>
            </div>
            <div>
                <label class="text-sm font-medium">Số điện thoại</label>
                <input name="phone" placeholder="0901234567" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm">
            </div>
            <div>
                <label class="text-sm font-medium">Mật khẩu *</label>
                <input type="password" name="password" placeholder="Tối thiểu 6 ký tự" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm" required>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeModal('createStudent')" class="px-4 py-2 text-stone-600 hover:bg-stone-100 rounded-xl text-sm">Hủy</button>
                <button class="px-5 py-2 bg-brand-600 text-white rounded-xl text-sm font-medium">Tạo tài khoản</button>
            </div>
        </form>
    </div>
</div>
@endsection
@section('scripts')
<script>
function openModal(id){const el=document.getElementById(id);el.classList.remove('hidden');el.classList.add('flex')}
function closeModal(id){const el=document.getElementById(id);el.classList.add('hidden');el.classList.remove('flex')}
</script>
@endsection
