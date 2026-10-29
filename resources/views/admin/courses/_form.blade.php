<div>
    <label class="label">Tên khóa học</label>
    <input name="name" value="{{ old('name', $course->name ?? '') }}" placeholder="VD: Màu nước căn bản" class="input" required>
</div>

<div class="grid sm:grid-cols-3 gap-3">
    <div>
        <label class="label">Cấp độ</label>
        <select name="level" class="input">
            @foreach (['beginner' => 'Cơ bản', 'intermediate' => 'Trung cấp', 'advanced' => 'Nâng cao'] as $k => $v)
                <option value="{{ $k }}" @selected(old('level', $course->level ?? '') === $k)>{{ $v }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="label">Thời lượng (giờ)</label>
        <input type="number" name="duration_hours" value="{{ old('duration_hours', $course->duration_hours ?? 24) }}" min="1" class="input" required>
    </div>
    <div>
        <label class="label">Học phí (đ)</label>
        <input type="number" name="tuition_fee" value="{{ old('tuition_fee', $course->tuition_fee ?? 0) }}" min="0" class="input" required>
    </div>
</div>

<div>
    <label class="label">Mô tả</label>
    <textarea name="description" rows="3" class="input" placeholder="Nội dung, kết quả đầu ra, chất liệu...">{{ old('description', $course->description ?? '') }}</textarea>
</div>

<div class="grid sm:grid-cols-2 gap-3">
    <div>
        <label class="label">Trạng thái</label>
        <select name="status" class="input">
            <option value="active" @selected(($course->status ?? 'active') === 'active')>Đang mở</option>
            <option value="inactive" @selected(($course->status ?? '') === 'inactive')>Ngưng mở</option>
        </select>
    </div>
    <div>
        <label class="label">Ảnh khóa học</label>
        <input type="file" name="image" accept="image/*" class="input !py-2 text-xs">
        @if (!empty($course?->image))
            <img src="{{ asset('images/courses/'.$course->image) }}" class="h-14 mt-2 rounded-lg object-cover" alt="">
        @endif
    </div>
</div>

<label class="flex items-center gap-2.5 text-sm text-[#4b5f56] bg-[#fbfdfb] border border-[#e3ebe6] rounded-xl px-3 py-2.5 cursor-pointer">
    <input type="checkbox" name="is_featured" value="1" @checked($course->is_featured ?? false)
           class="rounded border-[#cfded7] text-brand-600 focus:ring-brand-400">
    Hiển thị nổi bật trên trang chủ
</label>
