@extends('layouts.studio', ['portal' => 'teacher'])
@section('title', 'Lớp tôi dạy')
@section('content')
@php $totalPending = $classes->sum('pending_students_count'); @endphp

<div class="flex flex-wrap items-end justify-between gap-3 mb-6">
    <div>
        <p class="eyebrow">Giáo viên</p>
        <h1 class="page-title mt-1.5">Lớp tôi dạy</h1>
        <p class="page-sub">{{ $classes->count() }} lớp · {{ $totalPending }} yêu cầu đang chờ bạn duyệt.</p>
    </div>
    <a href="{{ route('teacher.gallery.index') }}" class="btn btn-ghost"><i class="bx bxs-image-alt text-lg"></i> Tác phẩm học viên</a>
</div>

<div class="grid md:grid-cols-2 xl:grid-cols-3 gap-4">
    @forelse ($classes as $class)
        <a href="{{ route('teacher.classes.show', $class) }}" class="card card-hover overflow-hidden block">
            <div class="h-1.5 bg-gradient-to-r from-brand-500 to-brand-300"></div>
            <div class="p-5">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <p class="text-[11px] font-mono text-[#9aaba3]">{{ $class->code }}</p>
                        <h2 class="font-semibold mt-0.5 leading-snug">{{ $class->name }}</h2>
                        <p class="text-xs text-[#7d8f87]">{{ $class->course->name ?? '' }}</p>
                    </div>
                    <span class="badge {{ $class->status === 'ongoing' ? 'badge-green' : 'badge-blue' }}">{{ $class->statusLabel() }}</span>
                </div>

                <div class="space-y-1.5 text-xs text-[#4b5f56] mt-4">
                    <p class="flex items-center gap-2"><i class="bx bx-time-five text-brand-500"></i>{{ $class->schedule ?? '—' }}</p>
                    <p class="flex items-center gap-2"><i class="bx bx-map text-brand-500"></i>{{ $class->room ?? '—' }}</p>
                    <p class="flex items-center gap-2"><i class="bx bx-group text-brand-500"></i>{{ $class->students_count }}/{{ $class->max_students }} học viên</p>
                </div>

                <div class="mt-4 flex items-center justify-between">
                    @if ($class->pending_students_count > 0)
                        <span class="badge badge-amber"><i class="bx bxs-bell"></i> {{ $class->pending_students_count }} chờ duyệt</span>
                    @else
                        <span class="text-[11px] text-[#9aaba3]">Không có yêu cầu mới</span>
                    @endif
                    <span class="text-brand-700 text-sm"><i class="bx bx-right-arrow-alt text-xl"></i></span>
                </div>
            </div>
        </a>
    @empty
        <div class="col-span-full card empty"><i class="bx bxs-chalkboard"></i>Bạn chưa được phân công lớp nào.</div>
    @endforelse
</div>
@endsection
