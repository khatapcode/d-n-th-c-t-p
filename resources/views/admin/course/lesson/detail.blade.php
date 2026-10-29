@extends('admin.admin')

@section('content')

@php
    $perPage = $lessons->perPage();
    $currentPage = $lessons->currentPage();
    $stt = ($currentPage - 1) * $perPage + 1;
@endphp

{{-- Page Header --}}
<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('admin.course') }}" class="w-9 h-9 bg-white rounded-xl shadow-sm border border-slate-200 flex items-center justify-center hover:bg-slate-50 transition-colors">
        <i class='bx bx-arrow-back text-slate-500 text-lg'></i>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Danh sách bài học</h1>
        <p class="text-slate-500 text-sm mt-0.5">Khóa học / Bài học</p>
    </div>
    <div class="ml-auto">
        <a href="{{ route('admin.createlesson', $course->course_id) }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition-colors shadow-sm">
            <i class='bx bx-plus text-lg'></i>
            Tạo bài học mới
        </a>
    </div>
</div>

{{-- Table Card --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200">
                    <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-4">Bài</th>
                    <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-4">Tên bài học</th>
                    <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-4">Video</th>
                    <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-4">Thời lượng</th>
                    <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-4">Câu hỏi</th>
                    <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-4">Ngày tạo</th>
                    <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-4">Ngày cập nhật</th>
                    <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-4">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($lessons as $lesson)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center justify-center w-7 h-7 bg-indigo-100 text-indigo-700 text-xs font-bold rounded-full">{{ $stt++ }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-medium text-slate-800">{{ $lesson->lesson_name }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <video class="w-40 h-24 rounded-lg object-cover bg-slate-100 border border-slate-200"
                                   src="{{ asset('videos/lessons/' . $lesson->lesson_video) }}" controls></video>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1 text-sm text-slate-600">
                                <i class='bx bx-time-five text-slate-400'></i>
                                {{ $lesson->lesson_duration }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.question', $lesson->lesson_id) }}"
                               class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-700 text-xs font-medium rounded-lg hover:bg-blue-100 transition-colors">
                                <i class='bx bx-question-mark text-sm'></i> Xem câu hỏi
                            </a>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ ($lesson->created_at)->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ ($lesson->updated_at)->format('d/m/Y') }}</td>
                        <td class="px-6 py-4">
                            <form action="{{ route('admin.lesson.delete', $lesson->lesson_id) }}" method="POST" class="delete-form flex items-center gap-1.5">
                                @csrf @method('DELETE')
                                <a href="{{ route('admin.editlesson', $lesson->lesson_id) }}"
                                   class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-amber-50 text-amber-700 text-xs font-medium rounded-lg hover:bg-amber-100 transition-colors">
                                    <i class='bx bx-edit text-sm'></i> Sửa
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
    {{ $lessons->links('vendor.pagination.tailwind') }}
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteForms = document.querySelectorAll('.delete-form');
        deleteForms.forEach(form => {
            const deleteButton = form.querySelector('.btn-delete');
            deleteButton.addEventListener('click', function (event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Bạn có chắc chắn muốn xóa bài học này?',
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
