@extends('admin.admin')

@section('content')

{{-- Page Header --}}
<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('admin.user') }}" class="w-9 h-9 bg-white rounded-xl shadow-sm border border-slate-200 flex items-center justify-center hover:bg-slate-50 transition-colors">
        <i class='bx bx-arrow-back text-slate-500 text-lg'></i>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Chỉnh sửa người dùng</h1>
        <p class="text-slate-500 text-sm mt-0.5">Người dùng / Chỉnh sửa thông tin</p>
    </div>
</div>

{{-- Form Card --}}
<div class="max-w-2xl bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
    <form action="{{ route('admin.user.update', $user->id) }}" method="POST">
        @csrf @method('PUT')

        <div class="space-y-5">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Tên người dùng</label>
                <input type="text" name="name" value="{{ $user->name }}" required
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                       placeholder="Nhập tên người dùng...">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Email (tên tài khoản)</label>
                <input type="text" name="email" value="{{ $user->email }}" readonly
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 text-slate-500 text-sm bg-slate-50 cursor-not-allowed"
                       placeholder="Email...">
                <p class="text-xs text-slate-400 mt-1">Email không thể thay đổi</p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Mật khẩu</label>
                <input type="text" name="password" value="{{ $user->password }}" required
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                       placeholder="Nhập mật khẩu...">
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-slate-100">
            <a href="{{ route('admin.user') }}"
               class="px-5 py-2.5 text-sm font-medium text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                Hủy
            </a>
            <button type="submit"
                    class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition-colors shadow-sm">
                <i class='bx bx-save mr-1'></i> Lưu thay đổi
            </button>
        </div>
    </form>
</div>

@stop()
