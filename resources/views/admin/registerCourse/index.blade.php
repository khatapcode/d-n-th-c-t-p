@extends('admin.admin')

@section('content')

{{-- Page Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Quản lý đăng ký khóa học</h1>
        <p class="text-slate-500 text-sm mt-1">Danh sách tất cả đăng ký trong hệ thống</p>
    </div>
</div>

{{-- Filter & Search --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-5">
    <div class="flex flex-wrap gap-4 items-center">
        <form method="GET" action="" class="flex items-center gap-2">
            <label class="text-sm font-medium text-slate-600">Trạng thái:</label>
            <select name="rc_status" class="px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">Tất cả</option>
                <option value="chưa" {{ request('rc_status') == 'chưa' ? 'selected' : '' }}>Mới đăng ký</option>
                <option value="rồi" {{ request('rc_status') == 'rồi' ? 'selected' : '' }}>Đã duyệt</option>
            </select>
            <button type="submit" class="px-3 py-2 bg-slate-100 text-slate-600 text-sm rounded-lg hover:bg-slate-200 transition-colors">
                <i class='bx bx-filter-alt'></i> Lọc
            </button>
        </form>

        <form method="GET" action="" class="flex items-center gap-2 ml-auto">
            <div class="relative">
                <input type="text" name="key" placeholder="Tìm theo tên người dùng..."
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
                    <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-4">Người đăng ký</th>
                    <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-4">Khóa học</th>
                    <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-4">Danh mục</th>
                    <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-4">Trạng thái</th>
                    <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-4">Ngày đăng ký</th>
                    <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-4">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($registerCourses as $registerCourse)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center shrink-0">
                                    <span class="text-indigo-600 text-xs font-bold">{{ strtoupper(substr($registerCourse->user->name, 0, 1)) }}</span>
                                </div>
                                <span class="text-sm font-medium text-slate-800">{{ $registerCourse->user->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-slate-700 font-medium">{{ $registerCourse->course->course_name }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 bg-violet-100 text-violet-700 text-xs font-medium rounded-full">
                                {{ $registerCourse->course->category->category_name }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($registerCourse->rc_status == 'chưa')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-100 text-amber-700 text-xs font-medium rounded-full">
                                    <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span> Mới đăng ký
                                </span>
                            @elseif($registerCourse->rc_status == 'rồi')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-100 text-emerald-700 text-xs font-medium rounded-full">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Đã duyệt
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ ($registerCourse->created_at)->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4">
                            <form action="{{ route('admin.RC.delete', $registerCourse->rc_id) }}" method="POST" class="delete-form flex items-center gap-1.5">
                                @csrf @method('DELETE')
                                <a href="{{ route('admin.editRC', $registerCourse->rc_id) }}"
                                   class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-amber-50 text-amber-700 text-xs font-medium rounded-lg hover:bg-amber-100 transition-colors">
                                    <i class='bx bx-edit text-sm'></i> Duyệt
                                </a>
                                <button type="button"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-red-50 text-red-700 text-xs font-medium rounded-lg hover:bg-red-100 transition-colors btn-delete">
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
    {{ $registerCourses->links('vendor.pagination.tailwind') }}
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteForms = document.querySelectorAll('.delete-form');
        deleteForms.forEach(form => {
            const deleteButton = form.querySelector('.btn-delete');
            deleteButton.addEventListener('click', function (event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Bạn có chắc chắn muốn xóa?',
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
