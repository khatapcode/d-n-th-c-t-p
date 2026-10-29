@extends('admin.admin')

@section('content')

@php
    $perPage = $questions->perPage();
    $currentPage = $questions->currentPage();
    $stt = ($currentPage - 1) * $perPage + 1;
@endphp

{{-- Page Header --}}
<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('admin.lesson', $lessons->course_id) }}" class="w-9 h-9 bg-white rounded-xl shadow-sm border border-slate-200 flex items-center justify-center hover:bg-slate-50 transition-colors">
        <i class='bx bx-arrow-back text-slate-500 text-lg'></i>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Danh sách câu hỏi</h1>
        <p class="text-slate-500 text-sm mt-0.5">Khóa học / Bài học / Câu hỏi</p>
    </div>
    <div class="ml-auto">
        <a href="{{ route('admin.createquestion', $lessons->lesson_id) }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition-colors shadow-sm">
            <i class='bx bx-plus text-lg'></i>
            Tạo câu hỏi mới
        </a>
    </div>
</div>

{{-- Table Card --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200">
                    <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-4">STT</th>
                    <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-4">Nội dung câu hỏi</th>
                    <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-4">Các đáp án</th>
                    <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-4">Đáp án đúng</th>
                    <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-4">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($questions as $question)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-sm font-medium text-slate-600">{{ $stt++ }}</td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-slate-800 max-w-xs">{!! $question->question_name !!}</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2 text-sm text-slate-600">
                                    <span class="w-5 h-5 bg-blue-100 text-blue-700 rounded text-xs font-bold flex items-center justify-center shrink-0">A</span>
                                    {{ $question->question_a }}
                                </div>
                                <div class="flex items-center gap-2 text-sm text-slate-600">
                                    <span class="w-5 h-5 bg-purple-100 text-purple-700 rounded text-xs font-bold flex items-center justify-center shrink-0">B</span>
                                    {{ $question->question_b }}
                                </div>
                                <div class="flex items-center gap-2 text-sm text-slate-600">
                                    <span class="w-5 h-5 bg-orange-100 text-orange-700 rounded text-xs font-bold flex items-center justify-center shrink-0">C</span>
                                    {{ $question->question_c }}
                                </div>
                                <div class="flex items-center gap-2 text-sm text-slate-600">
                                    <span class="w-5 h-5 bg-rose-100 text-rose-700 rounded text-xs font-bold flex items-center justify-center shrink-0">D</span>
                                    {{ $question->question_d }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-100 text-emerald-700 text-xs font-medium rounded-full">
                                <i class='bx bx-check text-sm'></i>
                                {{ $question->question_correct }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <form action="{{ route('admin.question.delete', $question->question_id) }}" method="POST" class="delete-form flex items-center gap-1.5">
                                @csrf @method('DELETE')
                                <a href="{{ route('admin.editquestion', $question->question_id) }}"
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
    {{ $questions->links('vendor.pagination.tailwind') }}
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteForms = document.querySelectorAll('.delete-form');
        deleteForms.forEach(form => {
            const deleteButton = form.querySelector('.btn-delete');
            deleteButton.addEventListener('click', function (event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Bạn có chắc chắn muốn xóa câu hỏi này?',
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
