@extends('layouts.studio', ['portal' => 'student'])
@section('title', 'Đăng ký lớp')
@section('content')
<div class="mb-6">
    <p class="eyebrow">Tuyển sinh</p>
    <h1 class="page-title mt-1.5">Đăng ký lớp mới</h1>
    <p class="page-sub">Gửi yêu cầu giữ chỗ — giáo viên hoặc admin sẽ duyệt và tạo học phí cho bạn.</p>
</div>

<div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-4">
    @forelse ($classes as $class)
        @php
            $myStatus = $myEnrollments[$class->id] ?? null;
            $left = max(0, $class->max_students - $class->students_count);
            $full = $left === 0;
        @endphp
        <div class="card card-hover overflow-hidden flex flex-col">
            <div class="p-5 flex-1">
                <div class="flex items-start justify-between">
                    <span class="badge {{ $full ? 'badge-red' : 'badge-green' }}">{{ $full ? 'Hết chỗ' : 'Còn ' . $left . ' chỗ' }}</span>
                    <span class="text-[11px] font-mono text-[#9aaba3]">{{ $class->code }}</span>
                </div>
                <h2 class="font-semibold mt-3 leading-snug">{{ $class->name }}</h2>
                <p class="text-xs text-[#7d8f87] mt-0.5">{{ $class->course->name ?? '' }}</p>

                <div class="mt-4 space-y-1.5 text-xs text-[#4b5f56]">
                    <p class="flex items-center gap-2"><i class="bx bx-user-voice text-brand-500"></i>{{ $class->teacher->name ?? 'Đang cập nhật' }}</p>
                    <p class="flex items-center gap-2"><i class="bx bx-time-five text-brand-500"></i>{{ $class->schedule ?? 'Lịch linh hoạt' }}</p>
                    <p class="flex items-center gap-2"><i class="bx bx-map text-brand-500"></i>{{ $class->room ?? '—' }}</p>
                    <p class="flex items-center gap-2"><i class="bx bx-calendar text-brand-500"></i>Khai giảng {{ optional($class->start_date)->format('d/m/Y') ?? '—' }}</p>
                </div>

                <div class="mt-4 flex items-center justify-between text-xs">
                    <span class="text-[#9aaba3]">Học phí</span>
                    <span class="font-bold text-brand-700">{{ number_format($class->course->tuition_fee ?? 0) }}đ</span>
                </div>
            </div>

            <div class="px-5 py-3 border-t border-[#f0f4f1]">
                @if ($myStatus === 'approved')
                    <span class="badge badge-green w-full justify-center !py-2"><i class="bx bx-check-circle"></i> Bạn đã ở trong lớp</span>
                @elseif ($myStatus === 'pending')
                    <div class="flex gap-2">
                        <span class="badge badge-amber flex-1 justify-center !py-2">Chờ duyệt</span>
                        <form method="POST" action="{{ route('student.classes.cancel', $class) }}">
                            @csrf @method('DELETE')
                            <button class="btn btn-ghost btn-sm">Hủy</button>
                        </form>
                    </div>
                @elseif ($myStatus === 'rejected')
                    <span class="badge badge-red w-full justify-center !py-2">Yêu cầu trước bị từ chối</span>
                @else
                    <form method="POST" action="{{ route('student.classes.enroll', $class) }}">
                        @csrf
                        <button class="btn btn-primary w-full" @disabled($full)>
                            <i class="bx bx-plus-circle text-base"></i> Đăng ký lớp này
                        </button>
                    </form>
                @endif
            </div>
        </div>
    @empty
        <div class="col-span-full card empty">
            <i class="bx bxs-calendar-x"></i>
            Hiện chưa có lớp nào mở đăng ký. Vui lòng quay lại sau.
        </div>
    @endforelse
</div>

<div class="mt-6 flex items-start gap-2.5 text-xs text-[#7d8f87] bg-white border border-[#e3ebe6] rounded-2xl px-4 py-3">
    <i class="bx bxs-info-circle text-brand-500 text-base"></i>
    <p>Lưu ý: mỗi lớp có sĩ số giới hạn. Sau khi được duyệt, học phí sẽ xuất hiện trong mục “Lớp của tôi”. Bạn có thể báo nghỉ từng buổi tại đó.</p>
</div>
@endsection
