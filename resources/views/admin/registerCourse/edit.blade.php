@extends('admin.admin')

@section('content')

{{-- Page Header --}}
<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('admin.registerCourse') }}" class="w-9 h-9 bg-white rounded-xl shadow-sm border border-slate-200 flex items-center justify-center hover:bg-slate-50 transition-colors">
        <i class='bx bx-arrow-back text-slate-500 text-lg'></i>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Duyệt đăng ký khóa học</h1>
        <p class="text-slate-500 text-sm mt-0.5">Đăng ký / Duyệt</p>
    </div>
</div>

{{-- Info + Form Cards --}}
<div class="max-w-2xl space-y-5">

    {{-- Info Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-4">Thông tin đăng ký</h2>
        <div class="space-y-4">
            <div class="flex items-center justify-between py-3 border-b border-slate-100">
                <span class="text-sm text-slate-500 font-medium">Họ tên người đăng ký</span>
                <span class="text-sm font-semibold text-slate-800">{{ $registerCourses->user->name }}</span>
            </div>
            <div class="flex items-center justify-between py-3 border-b border-slate-100">
                <span class="text-sm text-slate-500 font-medium">Tên khóa học</span>
                <span class="text-sm font-semibold text-slate-800">{{ $registerCourses->course->course_name }}</span>
            </div>
            <div class="flex items-center justify-between py-3">
                <span class="text-sm text-slate-500 font-medium">Thuộc danh mục</span>
                <span class="inline-flex items-center px-2.5 py-1 bg-violet-100 text-violet-700 text-xs font-medium rounded-full">
                    {{ $registerCourses->course->category->category_name }}
                </span>
            </div>
        </div>
    </div>

    {{-- Status Form Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-4">Cập nhật trạng thái</h2>
        <form action="{{ route('admin.RC.update', $registerCourses->rc_id) }}" method="post">
            @csrf @method('PUT')

            <div class="mb-5">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Trạng thái đăng ký</label>
                <select name="rc_status"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="chưa" {{ $registerCourses->rc_status == 'chưa' ? 'selected' : '' }}>🟡 Mới đăng ký</option>
                    <option value="rồi" {{ $registerCourses->rc_status == 'rồi' ? 'selected' : '' }}>🟢 Đã duyệt</option>
                </select>
            </div>

            @if(session('success'))
                <div class="flex items-center gap-2 p-3 bg-emerald-50 border border-emerald-200 rounded-xl mb-4">
                    <i class='bx bx-check-circle text-emerald-600'></i>
                    <p class="text-emerald-700 text-sm">{{ session('success') }}</p>
                </div>
            @endif

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.registerCourse') }}"
                   class="px-5 py-2.5 text-sm font-medium text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                    Hủy
                </a>
                <button type="submit"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition-colors shadow-sm">
                    <i class='bx bx-check mr-1'></i> Xác nhận
                </button>
            </div>
        </form>
    </div>
</div>

@stop()
