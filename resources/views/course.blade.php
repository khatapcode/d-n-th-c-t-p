@extends('admin')

@section('title', 'Khóa học ' . $course->course_name)

@section('main')

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-8">
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-0">
        <div class="lg:col-span-2 bg-slate-900">
            <img class="w-full h-full object-cover min-h-[220px] max-h-[360px] lg:max-h-none opacity-95" src="{{ asset('images/courses/' . $course->course_img) }}" alt="{{ $course->course_name }}">
        </div>
        <div class="lg:col-span-3 p-6 lg:p-8">
            <p class="text-indigo-600 text-xs font-semibold uppercase tracking-wider mb-2">Khóa học</p>
            <div class="flex flex-col sm:flex-row sm:items-start gap-4">
                <h1 class="font-bold text-2xl lg:text-3xl text-slate-800 flex-1">{{ $course->course_name }}</h1>
                @auth
                @if (isset($rc_status))
                    @if ($rc_status == 'chưa')
                        <button class="shrink-0 font-semibold border border-amber-200 text-amber-700 bg-amber-50 rounded-xl px-5 py-2">
                            Chờ xác nhận
                        </button>
                    @elseif ($rc_status == 'rồi')
                        <a href="{{ route('home.course_detail', $course->course_id) }}" class="shrink-0 inline-flex items-center justify-center font-semibold text-white bg-emerald-500 hover:bg-emerald-600 rounded-xl px-5 py-2 transition-colors">
                            Học ngay
                        </a>
                    @endif
                @else
                    <form class="shrink-0" action="{{ route('home.registerCourse') }}" method="post">
                        @csrf
                        <input type="hidden" name="rc_status" value="chưa">
                        <input type="hidden" name="course_id" value="{{$course->course_id}}">
                        <button type="submit" class="font-semibold text-white bg-indigo-500 hover:bg-indigo-600 rounded-xl py-2 px-5 transition-colors">
                            Đăng ký ngay
                        </button>
                    </form>
                @endif
                @else
                    <button class="shrink-0 font-semibold text-white bg-indigo-500 hover:bg-indigo-600 rounded-xl px-5 py-2 transition-colors" onclick="showLoginAlert();">
                        Đăng ký ngay
                    </button>
                @endauth
            </div>

            <div class="mt-4 prose max-w-none text-slate-600 leading-7">
                {!! $course->course_description !!}
            </div>

            <p class="mt-5 inline-flex items-center gap-2 text-sm text-slate-700 bg-slate-50 border border-slate-100 rounded-xl px-4 py-2">
                <i class="bx bxs-time text-indigo-500 text-lg"></i>
                <span><b>Tổng thời lượng của khóa học:</b> {{$totalDuration}}</span>
            </p>
        </div>
    </div>
</div>

<div>
    <div class="mb-4">
        <p class="text-indigo-600 text-xs font-semibold uppercase tracking-wider">Gợi ý</p>
        <h2 class="text-xl font-bold text-slate-800 mt-1">Khóa học liên quan</h2>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
        @foreach ($relatedCourses as $relatedCourse)
            <article class="card-hover bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
                <a href="{{ route('home.course', $relatedCourse->course_id) }}" class="block aspect-video overflow-hidden bg-slate-200">
                    <img class="w-full h-full object-cover" src="{{ asset('images/courses/' . $relatedCourse->course_img) }}" alt="">
                </a>
                <div class="p-4 flex flex-col flex-1">
                    <a href="{{ route('home.course', $relatedCourse->course_id) }}">
                        <p class="font-semibold text-slate-800 leading-snug line-clamp-2">{{ $relatedCourse->course_name }}</p>
                    </a>
                    <div class="mt-auto pt-4 flex justify-end">
                        <a href="{{ route('home.course', $relatedCourse->course_id) }}" class="inline-flex items-center gap-1 px-4 py-1.5 rounded-lg bg-indigo-500 hover:bg-indigo-600 text-sm text-white font-medium transition-colors">
                            Xem ngay <i class="bx bx-right-arrow-alt"></i>
                        </a>
                    </div>
                </div>
            </article>
        @endforeach
    </div>
</div>

<script>
    function showLoginAlert() {
        Swal.fire({
            title: 'Vui lòng đăng nhập',
            html: 'Bạn cần <a href="{{ route('login') }}" class="text-indigo-500 underline">đăng nhập</a> để đăng ký khóa học.',
            icon: 'warning',
            showConfirmButton: false,
            showCancelButton: true,
            cancelButtonText: 'Đóng'
        });
    }
</script>

@stop()
