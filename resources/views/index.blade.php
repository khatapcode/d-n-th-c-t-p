@extends('admin')

@section('title', 'Trang chủ')

@section('main')

<div class="mb-8 rounded-2xl bg-gradient-to-r from-slate-900 to-indigo-900 p-6 lg:p-8 text-white shadow-sm overflow-hidden relative">
    <div class="relative z-10 max-w-2xl">
        <p class="text-indigo-300 text-sm font-medium mb-2">Nền tảng học lập trình</p>
        <h1 class="text-2xl lg:text-3xl font-bold">Học IT theo lộ trình rõ ràng</h1>
        <p class="text-slate-300 text-sm mt-2">Chọn danh mục, đăng ký khóa học và luyện tập với bài kiểm tra củng cố sau mỗi bài học.</p>
    </div>
    <div class="hidden lg:flex absolute right-8 top-1/2 -translate-y-1/2 w-20 h-20 bg-indigo-500/30 rounded-2xl items-center justify-center">
        <i class="bx bxs-book-bookmark text-5xl text-indigo-200"></i>
    </div>
</div>

@foreach ($cates as $cate)
    <section class="mb-10">
        <div class="flex items-end justify-between mb-4">
            <div>
                <p class="text-indigo-600 text-xs font-semibold uppercase tracking-wider">Danh mục</p>
                <h2 class="text-xl lg:text-2xl font-bold text-slate-800 mt-1">{{ $cate->category_name }}</h2>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
            @foreach ($cate->courses as $course)
                <article class="card-hover bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
                    <a href="{{ route('home.course', $course->course_id) }}" class="block aspect-video overflow-hidden bg-slate-200">
                        <img class="w-full h-full object-cover" src="{{ asset('images/courses/' . $course->course_img) }}" alt="{{ $course->course_name }}">
                    </a>
                    <div class="p-4 flex flex-col flex-1">
                        <a href="{{ route('home.course', $course->course_id) }}">
                            <h3 class="font-semibold text-slate-800 leading-snug line-clamp-2">{{ $course->course_name }}</h3>
                        </a>
                        <div class="mt-auto pt-4 flex justify-end">
                            <a href="{{ route('home.course', $course->course_id) }}" class="inline-flex items-center gap-1 px-4 py-1.5 rounded-lg bg-indigo-500 hover:bg-indigo-600 text-sm text-white font-medium transition-colors">
                                Xem ngay <i class="bx bx-right-arrow-alt"></i>
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
@endforeach

@stop()
