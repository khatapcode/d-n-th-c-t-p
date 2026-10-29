@extends('layouts.public')
@section('title', $course->name . ' — Madmun.Arthouse')
@section('description', Str::limit(strip_tags($course->description), 150))

@section('content')
@php
    $levels = ['beginner' => 'Cơ bản', 'intermediate' => 'Trung cấp', 'advanced' => 'Nâng cao'];
@endphp

<div class="bg-white border-b border-[#eef4f0]">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-3 text-xs text-[#8b9c93] flex items-center gap-2">
        <a href="{{ route('home') }}" class="hover:text-brand-700">Trang chủ</a>
        <i class="bx bx-chevron-right"></i>
        <a href="{{ route('home') }}#courses" class="hover:text-brand-700">Khóa học</a>
        <i class="bx bx-chevron-right"></i>
        <span class="text-[#10231c] font-medium">{{ $course->name }}</span>
    </div>
</div>

<section class="max-w-6xl mx-auto px-4 sm:px-6 py-10 lg:py-14">
    <div class="grid lg:grid-cols-[1.15fr_.85fr] gap-10">
        <div>
            <span class="badge badge-green">{{ $levels[$course->level] ?? $course->level }}</span>
            <h1 class="font-display text-3xl sm:text-4xl lg:text-[2.75rem] leading-tight mt-4">{{ $course->name }}</h1>
            <p class="text-[#4b5f56] mt-4 leading-relaxed">{{ $course->description }}</p>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mt-7">
                @foreach ([
                    ['bx-time-five', $course->duration_hours . ' giờ', 'Thời lượng'],
                    ['bx-layer', $course->classes->count() . ' lớp', 'Đang mở'],
                    ['bx-money', number_format($course->tuition_fee) . 'đ', 'Học phí'],
                ] as [$icon, $value, $label])
                    <div class="card card-pad !p-4">
                        <i class="bx {{ $icon }} text-brand-600 text-xl"></i>
                        <p class="font-bold mt-2">{{ $value }}</p>
                        <p class="text-[11px] uppercase tracking-wider text-[#9aaba3]">{{ $label }}</p>
                    </div>
                @endforeach
            </div>

            <div class="mt-10">
                <h2 class="section-title mb-4">Lịch khai giảng</h2>
                <div class="space-y-3">
                    @forelse ($course->classes as $class)
                        @php $left = max(0, $class->max_students - $class->students_count); @endphp
                        <div class="card card-pad !p-4 flex flex-wrap items-center gap-4">
                            <div class="w-11 h-11 rounded-xl bg-brand-50 text-brand-700 flex items-center justify-center flex-shrink-0">
                                <i class="bx bxs-calendar text-xl"></i>
                            </div>
                            <div class="flex-1 min-w-[140px]">
                                <p class="font-semibold text-sm">{{ $class->name }} <span class="text-[11px] font-mono text-[#9aaba3]">{{ $class->code }}</span></p>
                                <p class="text-xs text-[#7d8f87] mt-0.5">
                                    {{ $class->schedule ?? 'Lịch linh hoạt' }} · {{ $class->teacher->name ?? 'Đang cập nhật' }}
                                </p>
                            </div>
                            <div class="text-xs text-[#7d8f87]">
                                Khai giảng<br><b class="text-[#10231c]">{{ optional($class->start_date)->format('d/m/Y') ?? '—' }}</b>
                            </div>
                            <span class="badge {{ $left > 0 ? 'badge-green' : 'badge-red' }}">{{ $left > 0 ? 'Còn '.$left.' chỗ' : 'Hết chỗ' }}</span>
                        </div>
                    @empty
                        <div class="card empty"><i class="bx bxs-calendar-x"></i>Khóa này chưa mở lớp mới. Để lại liên hệ để nhận thông báo.</div>
                    @endforelse
                </div>
            </div>

            <div class="mt-10">
                <h2 class="section-title mb-4">Bạn sẽ học được gì</h2>
                <div class="grid sm:grid-cols-2 gap-3">
                    @foreach ([
                        'Nền tảng hình khối, ánh sáng và bố cục',
                        'Kỹ thuật chất liệu và cách pha màu',
                        'Quy trình hoàn thiện một tác phẩm từ phác thảo',
                        'Nhận xét 1-1 từ giáo viên mỗi buổi',
                    ] as $item)
                        <div class="flex gap-2.5 text-sm text-[#4b5f56]">
                            <i class="bx bxs-check-circle text-brand-500 text-lg flex-shrink-0"></i> {{ $item }}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <aside class="lg:sticky lg:top-24 self-start space-y-5">
            <div class="rounded-2xl overflow-hidden border border-[#e3ebe6] bg-brand-100">
                @if ($course->image)
                    <img src="{{ asset('images/courses/'.$course->image) }}" alt="{{ $course->name }}" class="w-full h-60 object-cover">
                @endif
            </div>
            <div class="card card-pad">
                <p class="text-[11px] uppercase tracking-wider text-[#9aaba3]">Học phí trọn khóa</p>
                <p class="text-3xl font-bold text-brand-700 mt-1">{{ number_format($course->tuition_fee) }}đ</p>
                <p class="text-xs text-[#7d8f87] mt-1">{{ $course->duration_hours }} giờ học · có thể chia 2 đợt</p>
                <div class="divider my-4"></div>
                @auth
                    @if (auth()->user()->isStudent())
                        <a href="{{ route('student.enroll.index') }}" class="btn btn-primary w-full">Chọn lớp & đăng ký</a>
                    @else
                        <a href="{{ route(auth()->user()->homeRoute()) }}" class="btn btn-primary w-full">Vào không gian làm việc</a>
                    @endif
                @else
                    <a href="{{ route('register') }}" class="btn btn-primary w-full">Đăng ký học ngay</a>
                    <a href="{{ route('login') }}" class="btn btn-ghost w-full mt-2">Đã có tài khoản</a>
                @endauth
                <p class="text-[11px] text-[#9aaba3] text-center mt-3">Buổi học thử đầu tiên miễn phí</p>
            </div>

            @if ($artworks->isNotEmpty())
                <div class="card card-pad">
                    <p class="section-title mb-3">Tác phẩm học viên</p>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach ($artworks as $art)
                            <img src="{{ asset('images/artworks/'.$art->image) }}" alt="{{ $art->title }}" class="w-full h-24 object-cover rounded-lg">
                        @endforeach
                    </div>
                </div>
            @endif
        </aside>
    </div>
</section>

@if ($others->isNotEmpty())
<section class="bg-[#f4f8f5] py-16">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <h2 class="font-display text-2xl mb-6">Khóa học khác</h2>
        <div class="grid sm:grid-cols-3 gap-4">
            @foreach ($others as $other)
                <a href="{{ route('course.show', $other) }}" class="card card-hover overflow-hidden">
                    <img src="{{ asset('images/courses/'.$other->image) }}" alt="" class="w-full h-32 object-cover">
                    <div class="p-4">
                        <p class="font-semibold text-sm">{{ $other->name }}</p>
                        <p class="text-xs text-brand-700 font-semibold mt-1">{{ number_format($other->tuition_fee) }}đ</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
