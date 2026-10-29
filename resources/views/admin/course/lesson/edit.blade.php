@extends('admin.admin')

@section('content')

{{-- Page Header --}}
<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('admin.lesson', $lesson->course_id) }}" class="w-9 h-9 bg-white rounded-xl shadow-sm border border-slate-200 flex items-center justify-center hover:bg-slate-50 transition-colors">
        <i class='bx bx-arrow-back text-slate-500 text-lg'></i>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Chỉnh sửa bài học</h1>
        <p class="text-slate-500 text-sm mt-0.5">Khóa học / Bài học / Chỉnh sửa</p>
    </div>
</div>

{{-- Form Card --}}
<div class="max-w-2xl bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
    <form action="{{ route('admin.lesson.update', $lesson->lesson_id) }}" method="post" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="space-y-5">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Tên bài học</label>
                <input type="text" name="lesson_name" value="{{ old('lesson_name', $lesson->lesson_name) }}"
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                       placeholder="Nhập tên bài học...">
                @if ($errors->has('lesson_name'))
                    <p class="text-red-500 text-xs mt-1">{{ $errors->first('lesson_name') }}</p>
                @endif
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Video bài học</label>
                @if($lesson->lesson_video)
                    <div class="mb-3">
                        <video class="w-full max-w-xs h-32 rounded-xl object-cover border border-slate-200 bg-slate-100"
                               src="{{ asset('videos/lessons/' . $lesson->lesson_video) }}" controls></video>
                        <p class="text-xs text-slate-400 mt-1">Video hiện tại</p>
                    </div>
                @endif
                <div class="border-2 border-dashed border-slate-200 rounded-xl p-4 hover:border-indigo-400 transition-colors">
                    <input type="file" name="lesson_video"
                           class="w-full text-sm text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>
                <input type="hidden" name="course_id" value="{{ $lesson->lesson_id }}">
                @if ($errors->has('lesson_video'))
                    <p class="text-red-500 text-xs mt-1">{{ $errors->first('lesson_video') }}</p>
                @endif
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Thời lượng video</label>
                <div class="flex items-center gap-3">
                    <div class="flex-1">
                        <input type="number" name="hours" min="0" max="23" required
                               value="{{ old('hours', \Carbon\Carbon::parse($lesson->lesson_duration)->format('H')) }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 text-slate-800 text-sm text-center focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                               placeholder="Giờ">
                        <p class="text-xs text-slate-400 text-center mt-1">Giờ</p>
                    </div>
                    <span class="text-slate-400 text-xl font-bold mb-4">:</span>
                    <div class="flex-1">
                        <input type="number" name="minutes" min="0" max="59" required
                               value="{{ old('minutes', \Carbon\Carbon::parse($lesson->lesson_duration)->format('i')) }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 text-slate-800 text-sm text-center focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                               placeholder="Phút">
                        <p class="text-xs text-slate-400 text-center mt-1">Phút</p>
                    </div>
                    <span class="text-slate-400 text-xl font-bold mb-4">:</span>
                    <div class="flex-1">
                        <input type="number" name="seconds" min="0" max="59" required
                               value="{{ old('seconds', \Carbon\Carbon::parse($lesson->lesson_duration)->format('s')) }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 text-slate-800 text-sm text-center focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                               placeholder="Giây">
                        <p class="text-xs text-slate-400 text-center mt-1">Giây</p>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="flex items-center gap-2 p-3 bg-emerald-50 border border-emerald-200 rounded-xl mt-5">
                <i class='bx bx-check-circle text-emerald-600'></i>
                <p class="text-emerald-700 text-sm">{{ session('success') }}</p>
            </div>
        @endif

        <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-slate-100">
            <a href="{{ route('admin.lesson', $lesson->course_id) }}"
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
