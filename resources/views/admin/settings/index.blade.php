@extends('layouts.admin')
@section('title', 'Cài đặt')
@section('content')
<div class="mb-6">
    <p class="eyebrow">Hệ thống</p>
    <h1 class="page-title mt-1.5">Cài đặt tài khoản</h1>
    <p class="page-sub">Thông tin cá nhân và mô hình phân quyền của hệ thống.</p>
</div>

<div class="grid lg:grid-cols-[1.4fr_1fr] gap-5">
    <form method="POST" action="{{ route('admin.settings.update') }}" class="card card-pad space-y-4">
        @csrf @method('PUT')
        <h2 class="section-title">Thông tin quản trị viên</h2>
        <div class="grid sm:grid-cols-2 gap-3">
            <div>
                <label class="label">Họ tên</label>
                <input name="name" value="{{ $user->name }}" class="input" required>
            </div>
            <div>
                <label class="label">Email</label>
                <input type="email" name="email" value="{{ $user->email }}" class="input" required>
            </div>
        </div>
        <div>
            <label class="label">Số điện thoại</label>
            <input name="phone" value="{{ $user->phone }}" class="input">
        </div>
        <div class="divider"></div>
        <div class="grid sm:grid-cols-2 gap-3">
            <div>
                <label class="label">Mật khẩu mới</label>
                <input type="password" name="password" placeholder="Để trống nếu không đổi" class="input">
            </div>
            <div>
                <label class="label">Xác nhận mật khẩu</label>
                <input type="password" name="password_confirmation" class="input">
            </div>
        </div>
        <button class="btn btn-primary">Lưu thay đổi</button>
    </form>

    <div class="space-y-4">
        <div class="card card-pad">
            <h2 class="section-title mb-3">Vai trò hệ thống</h2>
            <div class="space-y-2.5">
                @foreach ([
                    ['GUEST', 'Khách', 'Xem landing, gửi liên hệ, đăng ký tài khoản'],
                    ['STUDENT', 'Học viên', 'Đăng ký lớp, xem điểm danh, học phí, gửi tác phẩm'],
                    ['TEACHER', 'Giáo viên', 'Điểm danh, duyệt học viên/tác phẩm, xem lương'],
                    ['ADMIN', 'Quản trị viên', 'Toàn quyền: khóa học, lớp, tài chính, hệ thống'],
                ] as [$code, $name, $desc])
                    <div class="rounded-xl border border-[#eef4f0] p-3">
                        <div class="flex items-center gap-2">
                            <span class="badge {{ $code === 'ADMIN' ? 'badge-green' : 'badge-gray' }}">{{ $code }}</span>
                            <span class="text-sm font-medium">{{ $name }}</span>
                        </div>
                        <p class="text-[11px] text-[#7d8f87] mt-1.5">{{ $desc }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="card card-pad">
            <h2 class="section-title mb-2">Bảo mật</h2>
            <ul class="text-xs text-[#7d8f87] space-y-1.5">
                <li class="flex gap-2"><i class="bx bxs-lock-alt text-brand-500"></i> Mọi route /admin/* yêu cầu đăng nhập và role ADMIN.</li>
                <li class="flex gap-2"><i class="bx bxs-lock-alt text-brand-500"></i> /teacher/* và /student/* tương ứng đúng role.</li>
                <li class="flex gap-2"><i class="bx bxs-lock-alt text-brand-500"></i> Mật khẩu được hash bằng bcrypt.</li>
            </ul>
        </div>
    </div>
</div>
@endsection
