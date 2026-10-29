@extends('layouts.admin')
@section('title', 'Lớp học')
@section('content')
<div class="flex flex-wrap items-end justify-between gap-3 mb-6">
    <div>
        <p class="eyebrow">Đào tạo</p>
        <h1 class="page-title mt-1.5">Quản lý lớp học</h1>
        <p class="page-sub">Sĩ số, lịch học, giáo viên phụ trách và yêu cầu đăng ký của học viên.</p>
    </div>
    <button onclick="openModal('createClass')" class="btn btn-primary"><i class="bx bx-plus text-lg"></i> Thêm lớp</button>
</div>

@if ($pendingTotal > 0)
    <div class="mb-5 flex flex-wrap items-center gap-3 bg-amber-50 border border-amber-200 text-amber-900 rounded-2xl px-4 py-3 text-sm">
        <i class="bx bxs-bell-ring text-xl"></i>
        <span>Có <b>{{ $pendingTotal }}</b> yêu cầu đăng ký đang chờ duyệt.</span>
        <a href="#pending" class="ml-auto font-semibold underline underline-offset-2">Xem danh sách</a>
    </div>
@endif

<div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-4" id="pending">
    @forelse ($classes as $class)
        @php $fill = min(100, ($class->students_count / max(1, $class->max_students)) * 100); @endphp
        <div class="card card-hover overflow-hidden flex flex-col">
            <div class="p-5 flex-1">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <p class="text-[11px] font-mono text-[#9aaba3]">{{ $class->code }}</p>
                        <h2 class="font-semibold text-[15px] mt-0.5 leading-snug">{{ $class->name }}</h2>
                        <p class="text-xs text-[#7d8f87] mt-0.5">{{ $class->course->name ?? '—' }}</p>
                    </div>
                    <span class="badge {{ $class->status === 'ongoing' ? 'badge-green' : ($class->status === 'completed' ? 'badge-gray' : 'badge-blue') }}">
                        {{ $class->statusLabel() }}
                    </span>
                </div>

                <div class="mt-4 space-y-1.5 text-xs text-[#4b5f56]">
                    <p class="flex items-center gap-2"><i class="bx bx-time-five text-brand-500"></i>{{ $class->schedule ?? 'Chưa xếp lịch' }}</p>
                    <p class="flex items-center gap-2"><i class="bx bx-map text-brand-500"></i>{{ $class->room ?? 'Chưa có phòng' }}</p>
                    <p class="flex items-center gap-2"><i class="bx bx-user-voice text-brand-500"></i>{{ $class->teacher->name ?? 'Chưa phân công' }}</p>
                </div>

                <div class="mt-4">
                    <div class="flex justify-between text-[11px] text-[#7d8f87] mb-1">
                        <span>Sĩ số</span><span class="font-semibold text-[#10231c]">{{ $class->students_count }}/{{ $class->max_students }}</span>
                    </div>
                    <div class="h-1.5 rounded-full bg-[#edf2ef] overflow-hidden">
                        <div class="h-full bg-brand-500 rounded-full" style="width: {{ $fill }}%"></div>
                    </div>
                </div>

                @if ($class->pending_students_count > 0)
                    <p class="mt-3 inline-flex items-center gap-1.5 text-[11px] font-semibold text-amber-700 bg-amber-50 rounded-lg px-2 py-1">
                        <i class="bx bxs-bell"></i> {{ $class->pending_students_count }} chờ duyệt
                    </p>
                @endif
            </div>

            <div class="px-5 py-3 border-t border-[#f0f4f1] flex items-center gap-1.5">
                <a href="{{ route('admin.classes.show', $class) }}" class="btn btn-soft btn-sm">Quản lý lớp</a>
                <button onclick="openModal('editClass{{ $class->id }}')" class="btn btn-ghost btn-sm !px-2.5" title="Sửa">
                    <i class="bx bx-edit-alt text-base"></i>
                </button>
                <form method="POST" action="{{ route('admin.classes.destroy', $class) }}" class="ml-auto" onsubmit="return confirm('Xóa lớp {{ $class->code }}? Học phí và điểm danh của lớp cũng sẽ bị xóa.')">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger btn-sm !px-2.5" title="Xóa"><i class="bx bx-trash text-base"></i></button>
                </form>
            </div>
        </div>

        <div id="editClass{{ $class->id }}" class="hidden fixed inset-0 z-50 bg-black/40 backdrop-blur-sm items-start justify-center overflow-y-auto p-4 sm:p-6">
            <form method="POST" action="{{ route('admin.classes.update', $class) }}" class="bg-white rounded-2xl w-full max-w-2xl my-6 overflow-hidden">
                @csrf @method('PUT')
                <div class="flex items-center justify-between px-6 py-4 border-b border-[#eef4f0]">
                    <h3 class="font-semibold">Sửa lớp {{ $class->code }}</h3>
                    <button type="button" onclick="closeModal('editClass{{ $class->id }}')" class="text-[#9aaba3] hover:text-[#10231c]"><i class="bx bx-x text-2xl"></i></button>
                </div>
                <div class="p-6 space-y-4">
                    @include('admin.classes._form', ['classItem' => $class, 'courses' => $courses, 'teachers' => $teachers, 'students' => $students])
                </div>
                <div class="flex justify-end gap-2 px-6 py-4 bg-[#fbfdfb] border-t border-[#eef4f0]">
                    <button type="button" onclick="closeModal('editClass{{ $class->id }}')" class="btn btn-ghost">Hủy</button>
                    <button class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    @empty
        <div class="col-span-full card empty">
            <i class="bx bxs-chalkboard"></i>
            Chưa có lớp học nào. Bấm “Thêm lớp” để tạo lớp đầu tiên.
        </div>
    @endforelse
</div>

<div class="mt-5">{{ $classes->links() }}</div>

{{-- Modal tạo lớp --}}
<div id="createClass" class="hidden fixed inset-0 z-50 bg-black/40 backdrop-blur-sm items-start justify-center overflow-y-auto p-4 sm:p-6">
    <form method="POST" action="{{ route('admin.classes.store') }}" class="bg-white rounded-2xl w-full max-w-2xl my-6 overflow-hidden">
        @csrf
        <div class="flex items-center justify-between px-6 py-4 border-b border-[#eef4f0]">
            <h3 class="font-semibold">Thêm lớp học</h3>
            <button type="button" onclick="closeModal('createClass')" class="text-[#9aaba3] hover:text-[#10231c]"><i class="bx bx-x text-2xl"></i></button>
        </div>
        <div class="p-6 space-y-4">
            @include('admin.classes._form', ['classItem' => null, 'courses' => $courses, 'teachers' => $teachers, 'students' => $students])
        </div>
        <div class="flex justify-end gap-2 px-6 py-4 bg-[#fbfdfb] border-t border-[#eef4f0]">
            <button type="button" onclick="closeModal('createClass')" class="btn btn-ghost">Hủy</button>
            <button class="btn btn-primary">Tạo lớp</button>
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
