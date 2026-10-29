@extends('layouts.studio', ['portal' => 'teacher'])
@section('title', 'Hồ sơ giáo viên')
@section('content')
<div class="mb-6">
    <p class="eyebrow">Cá nhân</p>
    <h1 class="page-title mt-1.5">Hồ sơ giáo viên</h1>
    <p class="page-sub">Thông tin này hiển thị trên landing page của trung tâm.</p>
</div>

<div class="grid lg:grid-cols-[360px_1fr] gap-5">
    <div class="space-y-4">
        <div class="card card-pad text-center">
            <span class="avatar avatar-lg mx-auto">{{ mb_substr($user->name, 0, 1) }}</span>
            <h2 class="font-semibold mt-3">{{ $user->name }}</h2>
            <p class="text-xs text-[#9aaba3]">{{ $user->email }}</p>
            <div class="flex justify-center gap-2 mt-3">
                <span class="badge badge-green">Giáo viên</span>
                <span class="badge badge-gray">{{ $user->teachingClasses()->count() }} lớp</span>
            </div>
        </div>

        <form method="POST" action="{{ route('teacher.account.update') }}" class="card card-pad space-y-4">
            @csrf @method('PUT')
            <h2 class="section-title">Thông tin cá nhân</h2>
            <div>
                <label class="label">Họ tên</label>
                <input name="name" value="{{ $user->name }}" class="input" required>
            </div>
            <div>
                <label class="label">Số điện thoại</label>
                <input name="phone" value="{{ $user->phone }}" class="input">
            </div>
            <div>
                <label class="label">Địa chỉ</label>
                <input name="address" value="{{ $user->address }}" class="input">
            </div>
            <div>
                <label class="label">Giới thiệu (hiện trên landing)</label>
                <textarea name="bio" rows="3" class="input" placeholder="Chuyên môn, phong cách giảng dạy...">{{ $user->bio }}</textarea>
            </div>
            <div class="divider"></div>
            <div>
                <label class="label">Mật khẩu mới</label>
                <input type="password" name="password" placeholder="Để trống nếu không đổi" class="input">
            </div>
            <div>
                <label class="label">Xác nhận mật khẩu</label>
                <input type="password" name="password_confirmation" class="input">
            </div>
            <button class="btn btn-primary w-full">Lưu thay đổi</button>
        </form>
    </div>

    <div class="space-y-5">
        <form method="POST" action="{{ route('teacher.gallery.store') }}" enctype="multipart/form-data" class="card card-pad">
            @csrf
            <h2 class="section-title mb-3">Thêm tác phẩm tiêu biểu</h2>
            <div class="grid sm:grid-cols-2 gap-3">
                <div>
                    <label class="label">Tên tác phẩm</label>
                    <input name="title" class="input" placeholder="VD: Mùa thu Hà Nội" required>
                </div>
                <div>
                    <label class="label">Ảnh</label>
                    <input type="file" name="image" accept="image/*" class="input !py-2" required>
                </div>
                <div class="sm:col-span-2">
                    <label class="label">Mô tả</label>
                    <textarea name="description" rows="2" class="input" placeholder="Chất liệu, kích thước..."></textarea>
                </div>
            </div>
            <button class="btn btn-primary mt-4"><i class="bx bx-upload"></i> Thêm vào gallery</button>
        </form>

        <div class="card">
            <div class="px-5 py-4"><h2 class="section-title">Gallery của tôi ({{ $artworks->count() }})</h2></div>
            <div class="p-5 pt-0">
                @if ($artworks->isEmpty())
                    <div class="empty"><i class="bx bxs-image-add"></i>Bạn chưa có tác phẩm nào.</div>
                @else
                    <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-3">
                        @foreach ($artworks as $art)
                            <figure class="rounded-xl overflow-hidden border border-[#eef4f0]">
                                <img src="{{ asset('images/artworks/'.$art->image) }}" alt="{{ $art->title }}" class="w-full h-32 object-cover">
                                <figcaption class="p-3 flex items-center justify-between gap-2">
                                    <span class="text-xs font-medium truncate">{{ $art->title }}</span>
                                    <form method="POST" action="{{ route('teacher.gallery.destroy', $art) }}" onsubmit="return confirm('Xóa tác phẩm này?')">
                                        @csrf @method('DELETE')
                                        <button class="text-red-500 hover:text-red-700"><i class="bx bx-trash"></i></button>
                                    </form>
                                </figcaption>
                            </figure>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
