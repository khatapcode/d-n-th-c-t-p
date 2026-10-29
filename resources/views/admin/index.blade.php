@extends('admin.admin')

@section('content')

{{-- Page Header --}}
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Dashboard</h1>
    <p class="text-slate-500 text-sm mt-1">Chào mừng bạn quay lại, Administrator!</p>
</div>

{{-- Stats Cards --}}
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
    <div class="card-hover bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-500 text-sm">Người dùng</p>
                <p class="text-2xl font-bold text-slate-800 mt-1">Quản lý</p>
            </div>
            <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                <i class='bx bxs-user text-2xl text-indigo-600'></i>
            </div>
        </div>
        <a href="{{ route('admin.user') }}" class="inline-flex items-center gap-1 text-indigo-600 text-sm mt-3 hover:underline">
            Xem danh sách <i class='bx bx-right-arrow-alt'></i>
        </a>
    </div>

    <div class="card-hover bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-500 text-sm">Danh mục</p>
                <p class="text-2xl font-bold text-slate-800 mt-1">Quản lý</p>
            </div>
            <div class="w-12 h-12 bg-violet-100 rounded-xl flex items-center justify-center">
                <i class='bx bxs-category text-2xl text-violet-600'></i>
            </div>
        </div>
        <a href="{{ route('admin.category') }}" class="inline-flex items-center gap-1 text-violet-600 text-sm mt-3 hover:underline">
            Xem danh sách <i class='bx bx-right-arrow-alt'></i>
        </a>
    </div>

    <div class="card-hover bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-500 text-sm">Khóa học</p>
                <p class="text-2xl font-bold text-slate-800 mt-1">Quản lý</p>
            </div>
            <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                <i class='bx bxs-book-bookmark text-2xl text-emerald-600'></i>
            </div>
        </div>
        <a href="{{ route('admin.course') }}" class="inline-flex items-center gap-1 text-emerald-600 text-sm mt-3 hover:underline">
            Xem danh sách <i class='bx bx-right-arrow-alt'></i>
        </a>
    </div>

    <div class="card-hover bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-500 text-sm">Đăng ký</p>
                <p class="text-2xl font-bold text-slate-800 mt-1">Quản lý</p>
            </div>
            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                <i class='bx bxs-calendar-check text-2xl text-amber-600'></i>
            </div>
        </div>
        <a href="{{ route('admin.registerCourse') }}" class="inline-flex items-center gap-1 text-amber-600 text-sm mt-3 hover:underline">
            Xem danh sách <i class='bx bx-right-arrow-alt'></i>
        </a>
    </div>
</div>

{{-- Quick Access --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
    <h2 class="text-lg font-semibold text-slate-800 mb-4">Truy cập nhanh</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="{{ route('admin.createcate') }}" class="flex flex-col items-center gap-2 p-4 rounded-xl bg-violet-50 hover:bg-violet-100 transition-colors text-violet-700">
            <i class='bx bx-plus-circle text-2xl'></i>
            <span class="text-sm font-medium text-center">Thêm danh mục</span>
        </a>
        <a href="{{ route('admin.createcourse') }}" class="flex flex-col items-center gap-2 p-4 rounded-xl bg-emerald-50 hover:bg-emerald-100 transition-colors text-emerald-700">
            <i class='bx bx-plus-circle text-2xl'></i>
            <span class="text-sm font-medium text-center">Thêm khóa học</span>
        </a>
        <a href="{{ route('admin.user') }}" class="flex flex-col items-center gap-2 p-4 rounded-xl bg-indigo-50 hover:bg-indigo-100 transition-colors text-indigo-700">
            <i class='bx bxs-user-detail text-2xl'></i>
            <span class="text-sm font-medium text-center">Quản lý người dùng</span>
        </a>
        <a href="{{ route('admin.registerCourse') }}" class="flex flex-col items-center gap-2 p-4 rounded-xl bg-amber-50 hover:bg-amber-100 transition-colors text-amber-700">
            <i class='bx bxs-bell text-2xl'></i>
            <span class="text-sm font-medium text-center">Duyệt đăng ký</span>
        </a>
    </div>
</div>

@stop()
