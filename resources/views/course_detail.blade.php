@extends('admin')

@section('title', 'Nội dung khóa học')

@section('main')

<div class="mb-6">
    <p class="text-indigo-600 text-xs font-semibold uppercase tracking-wider">Bài giảng</p>
    <h1 class="text-2xl lg:text-3xl font-bold text-slate-800 mt-1">Khóa học {{$course->course_name}}</h1>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 lg:p-6">
    <p class="font-semibold text-lg text-slate-800 mb-4">
        Nội dung khóa học
    </p>
    <div>
        <input type="hidden" value="{{$stt=1}}">
        @foreach ($lessons as $lesson)
            <div class="flex items-center gap-3 p-3 mt-3 bg-slate-50 hover:bg-indigo-50/60 border border-slate-100 rounded-xl transition-colors">
                <button class="w-9 h-9 shrink-0 bg-indigo-500 hover:bg-indigo-600 text-white font-bold rounded-lg toggleButton" data-lesson-id="{{$lesson->lesson_id}}">+</button>
                <p class="font-semibold text-slate-800">Bài {{$stt++}}: {{$lesson->lesson_name}}</p>
                <p class="ms-auto mr-1 flex items-center font-medium text-slate-500 text-sm">
                    <i class="bx bx-time mr-1 text-indigo-500"></i> {{$lesson->lesson_duration}}
                </p>
            </div>
            <div id="myVideo-{{$lesson->lesson_id}}" class="w-full hidden">
                <div class="w-full flex justify-center items-center bg-slate-900 rounded-xl mt-2 p-3">
                    <video class="w-full max-w-3xl rounded-lg" width="750" height="50" src="{{ asset('videos/lessons/' . $lesson->lesson_video) }}" controls></video>
                </div>
                <a class="inline-flex items-center gap-1 mt-3 mb-2 text-indigo-600 hover:text-indigo-800 font-medium" href="{{ route('home.test', $lesson->lesson_id) }}">
                    <i class="bx bx-edit"></i> Câu hỏi củng cố
                </a>
            </div>
        @endforeach
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.toggleButton').forEach(button => {
            button.addEventListener('click', () => {
                const lessonId = button.getAttribute('data-lesson-id');
                const video = document.getElementById(`myVideo-${lessonId}`);
                
                if (video.classList.contains('hidden')) {
                    video.classList.remove('hidden');
                    button.textContent = '-';
                } else {
                    video.classList.add('hidden');
                    button.textContent = '+';
                }
            });
        });
    });
</script>

@stop()
