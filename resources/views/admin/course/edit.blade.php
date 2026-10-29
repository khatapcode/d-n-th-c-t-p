@extends('admin.admin')

@section('content')

@php
    $selectedCategoryId = $course->category_id;
@endphp

{{-- Page Header --}}
<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('admin.course') }}" class="w-9 h-9 bg-white rounded-xl shadow-sm border border-slate-200 flex items-center justify-center hover:bg-slate-50 transition-colors">
        <i class='bx bx-arrow-back text-slate-500 text-lg'></i>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Chỉnh sửa khóa học</h1>
        <p class="text-slate-500 text-sm mt-0.5">Khóa học / Chỉnh sửa</p>
    </div>
</div>

{{-- Form Card --}}
<div class="max-w-3xl bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
    <form action="{{ route('admin.course.update', $course->course_id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="space-y-5">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Tên khóa học</label>
                <input type="text" name="course_name" value="{{ old('course_name', $course->course_name) }}"
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                       placeholder="Nhập tên khóa học...">
                @if ($errors->has('course_name'))
                    <p class="text-red-500 text-xs mt-1">{{ $errors->first('course_name') }}</p>
                @endif
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Hình ảnh khóa học</label>
                @if($course->course_img)
                    <div class="mb-3">
                        <img src="{{ asset('images/courses/' . $course->course_img) }}"
                             alt="Ảnh hiện tại" class="w-32 h-24 object-cover rounded-xl border border-slate-200">
                        <p class="text-xs text-slate-400 mt-1">Ảnh hiện tại</p>
                    </div>
                @endif
                <div class="border-2 border-dashed border-slate-200 rounded-xl p-4 hover:border-indigo-400 transition-colors">
                    <input type="file" name="course_img"
                           class="w-full text-sm text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>
                @if ($errors->has('course_img'))
                    <p class="text-red-500 text-xs mt-1">{{ $errors->first('course_img') }}</p>
                @endif
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Mô tả khóa học</label>
                <textarea id="course_description" name="course_description"
                          class="w-full px-4 py-3 rounded-xl border border-slate-200 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all min-h-[120px]">
                    {{ old('course_description', $course->course_description) }}
                </textarea>
                @if ($errors->has('course_description'))
                    <p class="text-red-500 text-xs mt-1">{{ $errors->first('course_description') }}</p>
                @endif
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Danh mục</label>
                <select name="category_id"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    @foreach ($cates as $cate)
                        <option value="{{ $cate->category_id }}" {{ $cate->category_id == $selectedCategoryId ? 'selected' : '' }}>
                            {{ $cate->category_name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        @if(session('success'))
            <div class="flex items-center gap-2 p-3 bg-emerald-50 border border-emerald-200 rounded-xl mt-5">
                <i class='bx bx-check-circle text-emerald-600'></i>
                <p class="text-emerald-700 text-sm">{{ session('success') }}</p>
            </div>
        @endif

        <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-slate-100">
            <a href="{{ route('admin.course') }}"
               class="px-5 py-2.5 text-sm font-medium text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                Hủy
            </a>
            <button type="submit"
                    class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition-colors shadow-sm">
                <i class='bx bx-save mr-1'></i> Lưu thay đổi
            </button>
        </div>
    </form>
</div>

<!-- CKEditor 5 -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.0/classic/ckeditor.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        ClassicEditor
            .create(document.querySelector('#course_description'))
            .catch(error => {
                console.error(error);
            });
    });
</script>

@stop()
