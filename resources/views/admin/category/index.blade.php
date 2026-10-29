@extends('admin.admin')

@section('content')

@php
    use Carbon\Carbon;
    $perPage = $cates->perPage();
    $currentPage = $cates->currentPage();
    $stt = ($currentPage - 1) * $perPage + 1;
@endphp

{{-- Page Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Quản lý danh mục</h1>
        <p class="text-slate-500 text-sm mt-1">Danh sách tất cả danh mục khóa học</p>
    </div>
    <a href="{{ route('admin.createcate') }}"
       class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition-colors shadow-sm">
        <i class='bx bx-plus text-lg'></i>
        Tạo danh mục mới
    </a>
</div>

{{-- Table Card --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200">
                    <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-4">STT</th>
                    <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-4">Tên danh mục</th>
                    <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-4">Ngày tạo</th>
                    <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-4">Ngày cập nhật</th>
                    <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-4">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($cates as $cate)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-sm font-medium text-slate-600">{{ $stt++ }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-violet-100 rounded-lg flex items-center justify-center shrink-0">
                                    <i class='bx bxs-category text-violet-600 text-sm'></i>
                                </div>
                                <span class="text-sm font-medium text-slate-800">{{ $cate->category_name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ Carbon::parse($cate->created_at)->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ Carbon::parse($cate->updated_at)->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4">
                            <form action="{{ route('admin.category.delete', $cate->category_id) }}" method="POST" class="delete-form flex items-center gap-2">
                                @csrf @method('DELETE')
                                <a href="{{ route('admin.editcate', $cate->category_id) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 bg-amber-50 text-amber-700 text-xs font-medium rounded-lg hover:bg-amber-100 transition-colors">
                                    <i class='bx bx-edit text-sm'></i> Sửa
                                </a>
                                <button type="button"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-50 text-red-700 text-xs font-medium rounded-lg hover:bg-red-100 transition-colors btn-delete">
                                    <i class='bx bx-trash text-sm'></i> Xóa
                                </button>
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
    {{ $cates->links('vendor.pagination.tailwind') }}
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteForms = document.querySelectorAll('.delete-form');
        deleteForms.forEach(form => {
            const deleteButton = form.querySelector('.btn-delete');
            deleteButton.addEventListener('click', function (event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Bạn có chắc chắn muốn xóa danh mục này?',
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
