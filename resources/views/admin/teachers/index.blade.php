@extends('layouts.admin')
@section('title', 'Giáo viên')
@section('content')
<div class="flex justify-between mb-6">
    <div>
        <p class="eyebrow">Đào tạo</p>
        <h1 class="page-title mt-1.5">Quản lý giáo viên</h1>
        <p class="page-sub">Hồ sơ, lớp đang dạy và lương tạm tính của giáo viên.</p>
    </div>
    <button onclick="openModal('createTeacher')" class="px-4 py-2 bg-brand-600 text-white rounded-xl text-sm font-medium flex items-center gap-1">
        <i class="bx bx-plus"></i> Thêm giáo viên
    </button>
</div>

<div class="grid md:grid-cols-2 xl:grid-cols-3 gap-5 mb-6">
    @forelse ($teachers as $teacher)
        @php
            $ongoingClasses = $teacher->teachingClasses->where('status', 'ongoing')->count();
            $totalStudents = $teacher->teachingClasses->sum(fn($c) => $c->students->count());
            $monthSalary = $teacher->salaryPayments->where('period_year', date('Y'))->where('period_month', date('n'))->first();
        @endphp
        <div class="bg-white rounded-2xl border border-stone-100 p-5 card-hover">
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-brand-100 text-brand-700 flex items-center justify-center font-bold text-lg">
                        {{ mb_substr($teacher->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="font-semibold">{{ $teacher->name }}</p>
                        <p class="text-xs text-stone-400">{{ $teacher->email }}</p>
                    </div>
                </div>
                <span class="text-xs {{ $teacher->is_active ? 'text-emerald-600' : 'text-red-400' }}">
                    {{ $teacher->is_active ? '●' : '○' }}
                </span>
            </div>

            @if ($teacher->bio)
                <p class="text-sm text-stone-500 mb-3 line-clamp-2">{{ $teacher->bio }}</p>
            @endif

            <div class="grid grid-cols-3 gap-2 text-center text-xs bg-stone-50 rounded-xl p-3 mb-3">
                <div>
                    <p class="font-bold text-stone-800">{{ $ongoingClasses }}</p>
                    <p class="text-stone-400">Lớp dạy</p>
                </div>
                <div>
                    <p class="font-bold text-stone-800">{{ $totalStudents }}</p>
                    <p class="text-stone-400">Học viên</p>
                </div>
                <div>
                    <p class="font-bold text-brand-700">{{ number_format(($teacher->hourly_rate ?? 0) / 1000) }}K</p>
                    <p class="text-stone-400">/giờ</p>
                </div>
            </div>

            @if ($monthSalary)
                <div class="flex justify-between items-center text-xs py-2 border-t border-stone-50">
                    <span class="text-stone-500">Lương T{{ date('n') }}:</span>
                    <span class="font-semibold {{ $monthSalary->status === 'paid' ? 'text-emerald-600' : 'text-brand-600' }}">
                        {{ number_format($monthSalary->amount) }}đ
                        {{ $monthSalary->status === 'paid' ? '✓' : '⏳' }}
                    </span>
                </div>
            @endif

            <a href="{{ route('admin.teachers.show', $teacher) }}"
               class="mt-3 block text-center text-sm text-brand-700 hover:underline">
                Xem hồ sơ chi tiết →
            </a>
        </div>
    @empty
        <div class="col-span-3 py-16 text-center text-stone-400">
            <i class="bx bxs-user-voice text-5xl block mb-3"></i>
            Chưa có giáo viên nào.
        </div>
    @endforelse
</div>

<div class="text-sm text-stone-400 px-1">
    Tổng: {{ $teachers->total() }} giáo viên · Trang {{ $teachers->links() }}
</div>

{{-- Modal tạo giáo viên --}}
<div id="createTeacher" class="hidden fixed inset-0 z-40 bg-black/40 items-center justify-center p-4">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-xl">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-lg">Thêm giáo viên mới</h3>
            <button onclick="closeModal('createTeacher')" class="text-stone-400 hover:text-stone-600">
                <i class="bx bx-x text-2xl"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.teachers.store') }}" class="space-y-3">
            @csrf
            <div>
                <label class="text-sm font-medium">Họ tên *</label>
                <input name="name" placeholder="Nguyễn Thị B" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm" required>
            </div>
            <div>
                <label class="text-sm font-medium">Email *</label>
                <input type="email" name="email" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm" required>
            </div>
            <div>
                <label class="text-sm font-medium">Số điện thoại</label>
                <input name="phone" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm">
            </div>
            <div>
                <label class="text-sm font-medium">Lương/giờ (đ)</label>
                <input type="number" name="hourly_rate" value="150000" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm">
            </div>
            <div>
                <label class="text-sm font-medium">Hồ sơ / Bio</label>
                <textarea name="bio" rows="2" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm" placeholder="Chuyên ngành, kinh nghiệm..."></textarea>
            </div>
            <div>
                <label class="text-sm font-medium">Mật khẩu *</label>
                <input type="password" name="password" placeholder="Tối thiểu 6 ký tự" class="mt-1 w-full border rounded-xl px-3 py-2.5 text-sm" required>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeModal('createTeacher')" class="px-4 py-2 text-stone-600 hover:bg-stone-100 rounded-xl text-sm">Hủy</button>
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
