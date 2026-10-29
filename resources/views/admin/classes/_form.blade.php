@php
    $selected = $classItem ? $classItem->students->pluck('id')->all() : [];
@endphp

<div class="grid grid-cols-2 gap-3">
    <div>
        <label class="label">Mã lớp</label>
        <input name="code" value="{{ $classItem->code ?? '' }}" placeholder="VD: MC-2026-01" class="input" required>
    </div>
    <div>
        <label class="label">Sĩ số tối đa</label>
        <input type="number" name="max_students" value="{{ $classItem->max_students ?? 20 }}" min="1" class="input" required>
    </div>
</div>

<div>
    <label class="label">Tên lớp</label>
    <input name="name" value="{{ $classItem->name ?? '' }}" placeholder="Màu nước cơ bản — Tối thứ 3" class="input" required>
</div>

<div class="grid sm:grid-cols-2 gap-3">
    <div>
        <label class="label">Khóa học</label>
        <select name="course_id" class="input" required>
            @foreach ($courses as $course)
                <option value="{{ $course->id }}" @selected(($classItem->course_id ?? '') == $course->id)>{{ $course->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="label">Giáo viên</label>
        <select name="teacher_id" class="input" required>
            @foreach ($teachers as $teacher)
                <option value="{{ $teacher->id }}" @selected(($classItem->teacher_id ?? '') == $teacher->id)>{{ $teacher->name }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="grid sm:grid-cols-2 gap-3">
    <div>
        <label class="label">Lịch học</label>
        <input name="schedule" value="{{ $classItem->schedule ?? '' }}" placeholder="T3 & T5 · 18:00 – 19:30" class="input">
    </div>
    <div>
        <label class="label">Phòng</label>
        <input name="room" value="{{ $classItem->room ?? '' }}" placeholder="Studio A" class="input">
    </div>
</div>

<div class="grid sm:grid-cols-3 gap-3">
    <div>
        <label class="label">Trạng thái</label>
        <select name="status" class="input">
            @foreach (['upcoming' => 'Sắp mở', 'ongoing' => 'Đang học', 'completed' => 'Kết thúc'] as $k => $v)
                <option value="{{ $k }}" @selected(($classItem->status ?? 'upcoming') === $k)>{{ $v }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="label">Khai giảng</label>
        <input type="date" name="start_date" value="{{ optional($classItem?->start_date)->format('Y-m-d') }}" class="input">
    </div>
    <div>
        <label class="label">Kết thúc</label>
        <input type="date" name="end_date" value="{{ optional($classItem?->end_date)->format('Y-m-d') }}" class="input">
    </div>
</div>

<div>
    <label class="label">Thêm học viên vào lớp</label>
    <div class="max-h-40 overflow-y-auto border border-[#e3ebe6] rounded-xl p-2.5 space-y-1.5 bg-[#fbfdfb]">
        @forelse ($students as $student)
            <label class="flex items-center gap-2.5 text-sm px-1.5 py-1 rounded-lg hover:bg-white cursor-pointer">
                <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" @checked(in_array($student->id, $selected))
                       class="rounded border-[#cfded7] text-brand-600 focus:ring-brand-400">
                {{ $student->name }}
                <span class="text-[11px] text-[#9aaba3] ml-auto">{{ $student->email }}</span>
            </label>
        @empty
            <p class="text-xs text-[#9aaba3] px-1.5 py-1">Chưa có học viên nào.</p>
        @endforelse
    </div>
</div>
