@extends('layouts.studio', ['portal' => 'student'])
@section('title', 'Hồ sơ & tác phẩm')
@section('content')
@php
    $statusMeta = [
        'approved' => ['Đã duyệt', 'badge-green'],
        'pending' => ['Chờ duyệt', 'badge-amber'],
        'revision' => ['Cần chỉnh sửa', 'badge-blue'],
        'rejected' => ['Từ chối', 'badge-red'],
    ];
@endphp

<div class="mb-6">
    <p class="eyebrow">Cá nhân</p>
    <h1 class="page-title mt-1.5">Hồ sơ & tác phẩm</h1>
    <p class="page-sub">Thông tin liên hệ và gallery cá nhân của bạn.</p>
</div>

<div class="grid lg:grid-cols-[360px_1fr] gap-5">
    <div class="space-y-4">
        <div class="card card-pad text-center">
            <span class="avatar avatar-lg mx-auto">{{ mb_substr($user->name, 0, 1) }}</span>
            <h2 class="font-semibold mt-3">{{ $user->name }}</h2>
            <p class="text-xs text-[#9aaba3]">{{ $user->email }}</p>
            <div class="flex justify-center gap-2 mt-3">
                <span class="badge badge-green">Học viên</span>
                <span class="badge {{ $user->is_active ? 'badge-green' : 'badge-red' }}">{{ $user->is_active ? 'Đang hoạt động' : 'Đã khóa' }}</span>
            </div>
        </div>

        <form method="POST" action="{{ route('student.account.update') }}" class="card card-pad space-y-4">
            @csrf @method('PUT')
            <h2 class="section-title">Thông tin cá nhân</h2>
            <div>
                <label class="label">Họ tên</label>
                <input name="name" value="{{ $user->name }}" class="input" required>
            </div>
            <div>
                <label class="label">Số điện thoại</label>
                <input name="phone" value="{{ $user->phone }}" placeholder="090..." class="input">
            </div>
            <div>
                <label class="label">Địa chỉ</label>
                <input name="address" value="{{ $user->address }}" placeholder="Quận, thành phố" class="input">
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
        <form method="POST" action="{{ route('student.gallery.store') }}" enctype="multipart/form-data" class="card card-pad">
            @csrf
            <div class="flex items-center justify-between mb-3">
                <h2 class="section-title">Gửi tác phẩm mới</h2>
                <span class="text-[11px] text-[#9aaba3]">Tác phẩm chờ giáo viên duyệt</span>
            </div>
            <div class="grid sm:grid-cols-[1fr_1fr] gap-3">
                <div>
                    <label class="label">Tên tác phẩm</label>
                    <input name="title" placeholder="VD: Chiều trên phố" class="input" required>
                </div>
                <div>
                    <label class="label">Ảnh</label>
                    <input type="file" name="image" accept="image/*" class="input !py-2" required>
                </div>
                <div class="sm:col-span-2">
                    <label class="label">Mô tả / chất liệu</label>
                    <textarea name="description" rows="2" placeholder="Màu nước trên giấy 300gsm..." class="input"></textarea>
                </div>
            </div>
            <button class="btn btn-primary mt-4"><i class="bx bx-upload text-base"></i> Gửi duyệt</button>
        </form>

        <div class="card">
            <div class="px-5 py-4 flex items-center justify-between">
                <h2 class="section-title">Gallery của tôi ({{ $artworks->count() }})</h2>
                <span class="text-[11px] text-[#9aaba3]">{{ $approved->count() }} đã duyệt</span>
            </div>
            <div class="p-5 pt-0">
                @if ($artworks->isEmpty())
                    <div class="empty"><i class="bx bxs-image-add"></i>Bạn chưa gửi tác phẩm nào.</div>
                @else
                    <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-3">
                        @foreach ($artworks as $art)
                            @php [$label, $tone] = $statusMeta[$art->status] ?? [$art->status, 'badge-gray']; @endphp
                            <figure class="rounded-xl overflow-hidden border border-[#eef4f0] bg-white">
                                <div class="relative h-36">
                                    <img src="{{ asset('images/artworks/'.$art->image) }}" alt="{{ $art->title }}" class="w-full h-full object-cover">
                                    <span class="absolute top-2 left-2 badge {{ $tone }} !bg-white/95">{{ $label }}</span>
                                </div>
                                <figcaption class="p-3">
                                    <p class="text-sm font-medium truncate">{{ $art->title }}</p>
                                    <p class="text-[11px] text-[#9aaba3]">{{ $art->created_at->format('d/m/Y') }}</p>
                                    @if ($art->review_note)
                                        <p class="text-[11px] mt-1.5 text-[#4b5f56] bg-[#f8fbf9] rounded-lg px-2 py-1.5">
                                            <i class="bx bx-comment-detail text-brand-500"></i> {{ $art->review_note }}
                                        </p>
                                    @endif
                                    @if ($art->comments->count())
                                        <p class="text-[11px] text-brand-700 mt-1.5"><i class="bx bx-message-rounded-dots"></i> {{ $art->comments->count() }} nhận xét</p>
                                    @endif
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
