@extends('admin.admin')

@section('content')

@php
    $perPage = $courses->perPage();
    $currentPage = $courses->currentPage();
    $stt = ($currentPage - 1) * $perPage + 1;
@endphp

{{-- Page Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Quản lý khóa học</h1>
        <p class="text-slate-500 text-sm mt-1">Danh sách tất cả khóa học trong hệ thống</p>
    </div>
    <a href="{{ route('admin.createcourse') }}"
       class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition-colors shadow-sm">
        <i class='bx bx-plus text-lg'></i>
        Tạo khóa học mới
    </a>
</div>

{{-- Filter & Search --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-5">
    <div class="flex flex-wrap gap-4 items-center">
        <form method="GET" action="{{ route('admin.course') }}" class="flex items-center gap-2">
            <label class="text-sm font-medium text-slate-600">Danh mục:</label>
            <select name="category_id" class="px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">Tất cả</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->category_id }}" @if($category->category_id == $category_id) selected @endif>
                        {{ $category->category_name }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="px-3 py-2 bg-slate-100 text-slate-600 text-sm rounded-lg hover:bg-slate-200 transition-colors">
                <i class='bx bx-filter-alt'></i> Lọc
            </button>
        </form>

        <form method="GET" action="{{ route('admin.course') }}" class="flex items-center gap-2 ml-auto">
            <div class="relative">
                <input type="text" name="key" placeholder="Tìm kiếm khóa học..."
                       class="pl-9 pr-4 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 w-64">
                <i class='bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400'></i>
            </div>
            <button type="submit" class="px-3 py-2 bg-indigo-50 text-indigo-600 text-sm rounded-lg hover:bg-indigo-100 transition-colors font-medium">
                Tìm kiếm
            </button>
        </form>
    </div>

    @if ($key)
        <div class="mt-3 pt-3 border-t border-slate-100">
            <span class="text-sm text-slate-500">Kết quả cho: <span class="font-semibold text-slate-700">"{{ $key }}"</span></span>
        </div>
    @endif
</div>

{{-- Table Card --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200">
                    <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-4">STT</th>
                    <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-4">Khóa học</th>
                    <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-4">Ảnh</th>
                    <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-4">Mô tả</th>
                    <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-4">Danh mục</th>
                    <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-4">Ngày tạo</th>
                    <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-4">Ngày cập nhật</th>
                    <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-4">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($courses as $course)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-sm font-medium text-slate-600">{{ $stt++ }}</td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-medium text-slate-800">{{ $course->course_name }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <img src="{{ asset('images/courses/' . $course->course_img) }}"
                                 alt="{{ $course->course_name }}"
                                 class="w-16 h-12 object-cover rounded-lg border border-slate-200">
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-slate-500 truncate max-w-[160px]">{{ $course->course_description }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 bg-violet-100 text-violet-700 text-xs font-medium rounded-full">
                                {{ $course->category->category_name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ ($course->created_at)->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ ($course->updated_at)->format('d/m/Y') }}</td>
                        <td class="px-6 py-4">
                            <form class="delete-form" action="{{ route('admin.course.delete', $course->course_id) }}" method="POST">
                                @csrf @method('DELETE')
                                <div class="flex items-center gap-1.5">
                                    <a href="{{ route('admin.lesson', $course->course_id) }}"
                                       class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-blue-50 text-blue-700 text-xs font-medium rounded-lg hover:bg-blue-100 transition-colors">
                                        <i class='bx bx-show text-sm'></i> Bài học
                                    </a>
                                    <a href="{{ route('admin.editcourse', $course->course_id) }}"
                                       class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-amber-50 text-amber-700 text-xs font-medium rounded-lg hover:bg-amber-100 transition-colors">
                                        <i class='bx bx-edit text-sm'></i> Sửa
                                    </a>
                                    <button type="button"
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-red-50 text-red-700 text-xs font-medium rounded-lg hover:bg-red-100 transition-colors btn-delete">
                                        <i class='bx bx-trash text-sm'></i> Xóa
                                    </button>
                                </div>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Pagination --}}
<div class="mt-5">
    {{ $courses->links('vendor.pagination.tailwind') }}
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteForms = document.querySelectorAll('.delete-form');
        deleteForms.forEach(form => {
            const deleteButton = form.querySelector('.btn-delete');
            deleteButton.addEventListener('click', function (event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Bạn có chắc chắn muốn xóa khóa học này?',
                    text: "Bạn sẽ không thể khôi phục lại dữ liệu này!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#6366f1',
                    cancelButtonColor: '#ef4444',
                    confirmButtonText: 'Xác nhận',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                })
            });
        });
    });
</script>

@stop()
