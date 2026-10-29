@extends('layouts.admin')
@section('title', 'Khóa học')
@section('content')
<div class="flex flex-wrap items-end justify-between gap-3 mb-6">
    <div>
        <p class="eyebrow">Đào tạo</p>
        <h1 class="page-title mt-1.5">Quản lý khóa học</h1>
        <p class="page-sub">Chương trình học, cấp độ, học phí và số lớp đang vận hành.</p>
    </div>
    <button onclick="openModal('createCourse')" class="btn btn-primary"><i class="bx bx-plus text-lg"></i> Thêm khóa học</button>
</div>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="table">
            <thead>
                <tr>
                    <th>Khóa học</th>
                    <th>Cấp độ</th>
                    <th>Thời lượng</th>
                    <th>Học phí</th>
                    <th class="text-center">Lớp đang mở</th>
                    <th class="text-center">Trạng thái</th>
                    <th class="text-right"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($courses as $course)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                @if ($course->image)
                                    <img src="{{ asset('images/courses/'.$course->image) }}" class="w-12 h-12 rounded-xl object-cover" alt="">
                                @else
                                    <div class="w-12 h-12 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center"><i class="bx bxs-book-content text-xl"></i></div>
                                @endif
                                <div class="min-w-0">
                                    <p class="font-medium flex items-center gap-1.5">
                                        {{ $course->name }}
                                        @if ($course->is_featured)
                                            <i class="bx bxs-star text-amber-400 text-sm" title="Nổi bật"></i>
                                        @endif
                                    </p>
                                    <p class="text-xs text-[#9aaba3] line-clamp-1 max-w-xs">{{ $course->description }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-green">{{ ['beginner' => 'Cơ bản', 'intermediate' => 'Trung cấp', 'advanced' => 'Nâng cao'][$course->level] ?? $course->level }}</span>
                        </td>
                        <td class="text-[#4b5f56]">{{ $course->duration_hours }} giờ</td>
                        <td class="font-semibold">{{ number_format($course->tuition_fee) }}đ</td>
                        <td class="text-center">{{ $course->active_classes_count }}</td>
                        <td class="text-center">
                            <span class="badge {{ $course->status === 'active' ? 'badge-green' : 'badge-gray' }}">
                                {{ $course->status === 'active' ? 'Đang mở' : 'Ngưng' }}
                            </span>
                        </td>
                        <td class="text-right">
                            <div class="inline-flex gap-1.5">
                                <button onclick="openModal('editCourse{{ $course->id }}')" class="btn btn-ghost btn-sm">Sửa</button>
                                <form method="POST" action="{{ route('admin.courses.destroy', $course) }}" onsubmit="return confirm('Xóa khóa học {{ $course->name }}?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Xóa</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="empty"><i class="bx bxs-book-open"></i>Chưa có khóa học nào.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-5">{{ $courses->links() }}</div>

@foreach ($courses as $course)
    <div id="editCourse{{ $course->id }}" class="hidden fixed inset-0 z-50 bg-black/40 backdrop-blur-sm items-start justify-center overflow-y-auto p-4 sm:p-6">
        <form method="POST" action="{{ route('admin.courses.update', $course) }}" enctype="multipart/form-data" class="bg-white rounded-2xl w-full max-w-xl my-6 overflow-hidden">
            @csrf @method('PUT')
            <div class="flex items-center justify-between px-6 py-4 border-b border-[#eef4f0]">
                <h3 class="font-semibold">Sửa khóa học</h3>
                <button type="button" onclick="closeModal('editCourse{{ $course->id }}')" class="text-[#9aaba3] hover:text-[#10231c]"><i class="bx bx-x text-2xl"></i></button>
            </div>
            <div class="p-6 space-y-4">
                @include('admin.courses._form', ['course' => $course])
            </div>
            <div class="flex justify-end gap-2 px-6 py-4 bg-[#fbfdfb] border-t border-[#eef4f0]">
                <button type="button" onclick="closeModal('editCourse{{ $course->id }}')" class="btn btn-ghost">Hủy</button>
                <button class="btn btn-primary">Lưu thay đổi</button>
            </div>
        </form>
    </div>
@endforeach

<div id="createCourse" class="hidden fixed inset-0 z-50 bg-black/40 backdrop-blur-sm items-start justify-center overflow-y-auto p-4 sm:p-6">
    <form method="POST" action="{{ route('admin.courses.store') }}" enctype="multipart/form-data" class="bg-white rounded-2xl w-full max-w-xl my-6 overflow-hidden">
        @csrf
        <div class="flex items-center justify-between px-6 py-4 border-b border-[#eef4f0]">
            <h3 class="font-semibold">Thêm khóa học</h3>
            <button type="button" onclick="closeModal('createCourse')" class="text-[#9aaba3] hover:text-[#10231c]"><i class="bx bx-x text-2xl"></i></button>
        </div>
        <div class="p-6 space-y-4">
            @include('admin.courses._form', ['course' => null])
        </div>
        <div class="flex justify-end gap-2 px-6 py-4 bg-[#fbfdfb] border-t border-[#eef4f0]">
            <button type="button" onclick="closeModal('createCourse')" class="btn btn-ghost">Hủy</button>
            <button class="btn btn-primary">Tạo khóa học</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
function openModal(id) { const el = document.getElementById(id); el.classList.remove('hidden'); el.classList.add('flex'); document.body.style.overflow = 'hidden'; }
function closeModal(id) { const el = document.getElementById(id); el.classList.add('hidden'); el.classList.remove('flex'); document.body.style.overflow = ''; }
</script>
@endsection
