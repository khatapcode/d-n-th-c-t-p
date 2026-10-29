@extends('layouts.studio', ['portal' => 'teacher'])
@section('title', $class->name)
@section('content')
<div class="mb-6">
    <a href="{{ route('teacher.classes') }}" class="inline-flex items-center gap-1 text-xs text-[#7d8f87] hover:text-brand-700 mb-2">
        <i class="bx bx-left-arrow-alt"></i> Lớp tôi dạy
    </a>
    <div class="flex flex-wrap items-center gap-3">
        <h1 class="page-title">{{ $class->name }}</h1>
        <span class="badge {{ $class->status === 'ongoing' ? 'badge-green' : 'badge-blue' }}">{{ $class->statusLabel() }}</span>
    </div>
    <p class="page-sub">{{ $class->code }} · {{ $class->schedule ?? 'Chưa xếp lịch' }} · {{ $class->room ?? '' }} · {{ $class->students->count() }} học viên</p>
</div>

@if ($class->pendingStudents->isNotEmpty())
    <div class="card mb-5 overflow-hidden">
        <div class="px-5 py-4 flex items-center justify-between">
            <h2 class="section-title flex items-center gap-2">
                Yêu cầu chờ duyệt <span class="badge badge-amber">{{ $class->pendingStudents->count() }}</span>
            </h2>
            <span class="text-[11px] text-[#9aaba3]">Còn {{ $class->seatsLeft() }} chỗ</span>
        </div>
        <div class="divide-y divide-[#f0f4f1]">
            @foreach ($class->pendingStudents as $student)
                <div class="px-5 py-3 flex flex-wrap items-center gap-3">
                    <span class="avatar !w-8 !h-8 !text-xs">{{ mb_substr($student->name, 0, 1) }}</span>
                    <div>
                        <p class="text-sm font-medium">{{ $student->name }}</p>
                        <p class="text-[11px] text-[#9aaba3]">{{ $student->email }}</p>
                    </div>
                    <div class="ml-auto flex gap-2">
                        <form method="POST" action="{{ route('teacher.classes.enrollments.approve', [$class, $student]) }}">
                            @csrf
                            <button class="btn btn-primary btn-sm" @disabled($class->isFull())><i class="bx bx-check"></i> Duyệt</button>
                        </form>
                        <form method="POST" action="{{ route('teacher.classes.enrollments.reject', [$class, $student]) }}">
                            @csrf
                            <button class="btn btn-danger btn-sm">Từ chối</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

<div class="grid xl:grid-cols-2 gap-5">
    {{-- Attendance --}}
    <div class="card card-pad">
        <div class="flex items-center justify-between mb-4">
            <h2 class="section-title">Điểm danh</h2>
            <span class="text-[11px] text-[#9aaba3]">{{ \Carbon\Carbon::parse($today)->format('d/m/Y') }}</span>
        </div>
        @if ($class->students->isEmpty())
            <div class="empty !py-8"><i class="bx bxs-user-x"></i>Lớp chưa có học viên.</div>
        @else
            <form method="POST" action="{{ route('teacher.classes.attendance', $class) }}">
                @csrf
                <div class="mb-3">
                    <label class="label">Ngày điểm danh</label>
                    <input type="date" name="session_date" value="{{ $today }}" class="input !py-2" required>
                </div>
                <div class="space-y-1.5">
                    @foreach ($class->students as $student)
                        <div class="flex items-center justify-between gap-3 py-2 border-b border-[#f0f4f1] last:border-0">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <span class="avatar !w-8 !h-8 !text-xs">{{ mb_substr($student->name, 0, 1) }}</span>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium truncate">{{ $student->name }}</p>
                                    <input name="note[{{ $student->id }}]" placeholder="Ghi chú (không bắt buộc)" class="text-[11px] text-[#7d8f87] bg-transparent border-0 p-0 focus:ring-0 w-full">
                                </div>
                            </div>
                            <select name="status[{{ $student->id }}]" class="input !py-1.5 !text-xs !w-auto">
                                @foreach (['present' => 'Có mặt', 'absent' => 'Vắng', 'leave' => 'Nghỉ phép'] as $k => $v)
                                    <option value="{{ $k }}" @selected(($attendances[$student->id] ?? 'present') === $k)>{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endforeach
                </div>
                <button class="btn btn-primary w-full mt-4"><i class="bx bx-save text-base"></i> Lưu điểm danh</button>
            </form>
        @endif
    </div>

    {{-- Artworks --}}
    <div class="card card-pad">
        <div class="flex items-center justify-between mb-4">
            <h2 class="section-title">Tác phẩm của học viên</h2>
            <a href="{{ route('teacher.gallery.index') }}" class="text-xs link-green">Tất cả</a>
        </div>
        <div class="space-y-3 max-h-[620px] overflow-y-auto pr-1">
            @forelse ($studentArtworks as $art)
                <div class="border border-[#eef4f0] rounded-xl overflow-hidden">
                    <div class="flex gap-3 p-3">
                        <img src="{{ asset('images/artworks/'.$art->image) }}" alt="" class="w-20 h-20 rounded-lg object-cover flex-shrink-0">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium truncate">{{ $art->title }}</p>
                            <p class="text-[11px] text-[#9aaba3]">{{ $art->owner->name ?? '' }} · {{ $art->created_at->format('d/m/Y') }}</p>
                            <div class="flex items-center gap-2 mt-1.5">
                                @if ($art->status === 'approved')
                                    <span class="badge badge-green">Đã duyệt</span>
                                @elseif ($art->status === 'pending')
                                    <span class="badge badge-amber">Chờ duyệt</span>
                                    <form method="POST" action="{{ route('teacher.artworks.approve', $art) }}">
                                        @csrf
                                        <button class="text-[11px] font-semibold text-brand-700 hover:underline">Duyệt ngay</button>
                                    </form>
                                @elseif ($art->status === 'revision')
                                    <span class="badge badge-blue">Cần sửa</span>
                                @else
                                    <span class="badge badge-red">Từ chối</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if ($art->comments->isNotEmpty())
                        <div class="px-3 pb-2 space-y-1">
                            @foreach ($art->comments->take(2) as $cmt)
                                <p class="text-[11px] text-[#4b5f56] bg-[#f8fbf9] rounded-lg px-2 py-1">
                                    <b>{{ $cmt->user->name ?? 'GV' }}:</b> {{ $cmt->comment }}
                                </p>
                            @endforeach
                        </div>
                    @endif
                    <form method="POST" action="{{ route('teacher.artworks.comment', $art) }}" class="flex gap-2 px-3 pb-3">
                        @csrf
                        <input name="comment" placeholder="Nhận xét cho học viên..." class="input !py-1.5 !text-xs" required>
                        <button class="btn btn-soft btn-sm">Gửi</button>
                    </form>
                </div>
            @empty
                <div class="empty !py-8"><i class="bx bxs-image"></i>Chưa có tác phẩm nào từ lớp này.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
