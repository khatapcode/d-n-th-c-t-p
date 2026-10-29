@extends('admin.admin')

@section('content')

{{-- Page Header --}}
<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('admin.question', $lesson->lesson_id) }}" class="w-9 h-9 bg-white rounded-xl shadow-sm border border-slate-200 flex items-center justify-center hover:bg-slate-50 transition-colors">
        <i class='bx bx-arrow-back text-slate-500 text-lg'></i>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Thêm câu hỏi</h1>
        <p class="text-slate-500 text-sm mt-0.5">Khóa học / Bài học / Câu hỏi / Thêm mới</p>
    </div>
</div>

{{-- Form Card --}}
<div class="max-w-3xl bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
    <form action="{{ route('admin.question.store', $lesson->lesson_id) }}" method="post">
        @csrf

        {{-- Question Content --}}
        <div class="mb-6">
            <label class="block text-sm font-semibold text-slate-700 mb-2">Nội dung câu hỏi</label>
            <textarea id="question_name" name="question_name"
                      class="w-full px-4 py-3 rounded-xl border border-slate-200 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all min-h-[100px]"
                      placeholder="Nhập nội dung câu hỏi...">{{ old('question_name') }}</textarea>
            @if ($errors->has('question_name'))
                <p class="text-red-500 text-xs mt-1">{{ $errors->first('question_name') }}</p>
            @endif
        </div>

        {{-- Answers --}}
        <div class="space-y-4 mb-6">
            <p class="text-sm font-semibold text-slate-700">Các câu trả lời</p>

            @foreach([
                ['key' => 'a', 'label' => 'A', 'color' => 'blue'],
                ['key' => 'b', 'label' => 'B', 'color' => 'purple'],
                ['key' => 'c', 'label' => 'C', 'color' => 'orange'],
                ['key' => 'd', 'label' => 'D', 'color' => 'rose'],
            ] as $ans)
            <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-xl border border-slate-200 hover:border-indigo-300 transition-colors">
                <span class="w-8 h-8 bg-{{ $ans['color'] }}-100 text-{{ $ans['color'] }}-700 rounded-lg text-sm font-bold flex items-center justify-center shrink-0">
                    {{ $ans['label'] }}
                </span>
                <input type="text" name="question_{{ $ans['key'] }}" id="question_{{ $ans['key'] }}"
                       value="{{ old('question_' . $ans['key']) }}"
                       class="flex-1 px-3 py-2 rounded-lg border border-slate-200 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white"
                       placeholder="Nhập câu trả lời {{ $ans['label'] }}...">
                <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer shrink-0">
                    <input type="radio" name="question_correct" value="" required id="radio_{{ $ans['key'] }}" class="w-4 h-4 accent-indigo-600">
                    <span>Đáp án đúng</span>
                </label>
                @if ($errors->has('question_' . $ans['key']))
                    <p class="text-red-500 text-xs">{{ $errors->first('question_' . $ans['key']) }}</p>
                @endif
            </div>
            @endforeach
        </div>

        @if(session('success'))
            <div class="flex items-center gap-2 p-3 bg-emerald-50 border border-emerald-200 rounded-xl mb-5">
                <i class='bx bx-check-circle text-emerald-600'></i>
                <p class="text-emerald-700 text-sm">{{ session('success') }}</p>
            </div>
        @endif

        <input type="hidden" name="lesson_id" value="{{ $lesson->lesson_id }}">

        <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
            <a href="{{ route('admin.question', $lesson->lesson_id) }}"
               class="px-5 py-2.5 text-sm font-medium text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                Hủy
            </a>
            <button type="submit"
                    class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition-colors shadow-sm">
                <i class='bx bx-plus mr-1'></i> Thêm câu hỏi
            </button>
        </div>
    </form>
</div>

<!-- CKEditor 5 -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.0/classic/ckeditor.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        ClassicEditor
            .create(document.querySelector('#question_name'))
            .catch(error => {
                console.error(error);
        });

        const questionA = document.getElementById('question_a');
        const questionB = document.getElementById('question_b');
        const questionC = document.getElementById('question_c');
        const questionD = document.getElementById('question_d');

        const radioA = document.getElementById('radio_a');
        const radioB = document.getElementById('radio_b');
        const radioC = document.getElementById('radio_c');
        const radioD = document.getElementById('radio_d');

        questionA.addEventListener('input', () => { radioA.value = questionA.value; });
        questionB.addEventListener('input', () => { radioB.value = questionB.value; });
        questionC.addEventListener('input', () => { radioC.value = questionC.value; });
        questionD.addEventListener('input', () => { radioD.value = questionD.value; });
    });
</script>

@stop()
