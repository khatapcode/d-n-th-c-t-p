@extends('admin')

@section('title', 'Kết quả tìm kiếm')

@section('main')

<div class="mb-6">
    <p class="text-indigo-600 text-xs font-semibold uppercase tracking-wider">Tìm kiếm</p>
    <h2 class="text-2xl font-bold text-slate-800 mt-1">Kết quả tìm kiếm cho từ khóa: <span class="text-indigo-600">{{ $query }}</span></h2>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
    @if($courses->count() > 0)
        @foreach($courses as $course)
            <article class="card-hover bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
                <a href="{{ route('home.course', $course->course_id) }}" class="block aspect-video overflow-hidden bg-slate-200">
                    <img class="w-full h-full object-cover" src="{{ asset('images/courses/' . $course->course_img) }}" alt="{{ $course->course_name }}">
                </a>
                <div class="p-4 flex flex-col flex-1">
                    <a href="{{ route('home.course', $course->course_id) }}">
                        <p class="font-semibold text-slate-800 leading-snug line-clamp-2">{{ $course->course_name }}</p>
                    </a>
                    <div class="flex items-center gap-1 pt-4 text-sm text-slate-500">
                        <i class="bx bxs-time text-indigo-500"></i>
                        <p>...</p>
                    </div>
                </div>
            </article>
        @endforeach
    @else
        <div class="col-span-full bg-white rounded-2xl border border-slate-100 p-10 text-center text-slate-500">
            Không tìm thấy kết quả nào.
        </div>
    @endif
</div>

@stop()
