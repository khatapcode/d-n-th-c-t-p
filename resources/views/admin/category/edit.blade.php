@extends('admin.admin')

@section('content')

{{-- Page Header --}}
<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('admin.category') }}" class="w-9 h-9 bg-white rounded-xl shadow-sm border border-slate-200 flex items-center justify-center hover:bg-slate-50 transition-colors">
        <i class='bx bx-arrow-back text-slate-500 text-lg'></i>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Chỉnh sửa danh mục</h1>
        <p class="text-slate-500 text-sm mt-0.5">Danh mục / Chỉnh sửa</p>
    </div>
</div>

{{-- Form Card --}}
<div class="max-w-2xl bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
    <form action="{{ route('admin.category.update', $category->category_id) }}" method="POST">
        @csrf @method('PUT')

        <div class="mb-5">
            <label for="category_name" class="block text-sm font-semibold text-slate-700 mb-2">Tên danh mục</label>
            <input type="text" id="category_name" name="category_name"
                   value="{{ old('category_name', $category->category_name) }}"
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                   placeholder="Nhập tên danh mục...">
        </div>

        @if(session('success'))
            <div class="flex items-center gap-2 p-3 bg-emerald-50 border border-emerald-200 rounded-xl mb-4">
                <i class='bx bx-check-circle text-emerald-600'></i>
                <p class="text-emerald-700 text-sm">{{ session('success') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="flex items-start gap-2 p-3 bg-red-50 border border-red-200 rounded-xl mb-4">
                <i class='bx bx-error-circle text-red-600 mt-0.5'></i>
                <div>
                    @foreach ($errors->all() as $error)
                        <p class="text-red-600 text-sm">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-slate-100">
            <a href="{{ route('admin.category') }}"
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
