@extends('layouts.admin')
@section('title', 'Phòng trưng bày')
@section('content')
@php
    $statusMeta = [
        'pending' => ['Chờ duyệt', 'badge-amber'],
        'approved' => ['Đã duyệt', 'badge-green'],
        'revision' => ['Cần sửa', 'badge-blue'],
        'rejected' => ['Từ chối', 'badge-red'],
    ];
@endphp

<div class="flex flex-wrap items-end justify-between gap-3 mb-6">
    <div>
        <p class="eyebrow">Vận hành</p>
        <h1 class="page-title mt-1.5">Phòng trưng bày</h1>
        <p class="page-sub">Duyệt tác phẩm học viên trước khi hiển thị trên trang chủ.</p>
    </div>
    <div class="flex gap-1.5 flex-wrap">
        @foreach (['' => 'Tất cả', 'pending' => 'Chờ duyệt', 'approved' => 'Đã duyệt', 'revision' => 'Cần sửa', 'rejected' => 'Từ chối'] as $key => $label)
            <a href="{{ route('admin.gallery.index', $key ? ['status' => $key] : []) }}"
               class="btn btn-sm {{ ($status ?: '') === $key ? 'btn-primary' : 'btn-ghost' }}">{{ $label }}</a>
        @endforeach
    </div>
</div>

<div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-4">
    @forelse ($artworks as $art)
        @php [$label, $tone] = $statusMeta[$art->status] ?? [$art->status, 'badge-gray']; @endphp
        <article class="card overflow-hidden flex flex-col">
            <div class="relative h-48 bg-brand-100">
                <img src="{{ asset('images/artworks/'.$art->image) }}" alt="{{ $art->title }}" class="w-full h-full object-cover">
                <span class="absolute top-2.5 left-2.5 badge !bg-white/95 {{ $tone }}">{{ $label }}</span>
            </div>
            <div class="p-4 flex-1 flex flex-col">
                <p class="font-semibold">{{ $art->title }}</p>
                <div class="flex items-center gap-2 mt-1 text-[11px] text-[#9aaba3]">
                    <span class="avatar !w-5 !h-5 !text-[9px]">{{ mb_substr($art->owner->name ?? '?', 0, 1) }}</span>
                    {{ $art->owner->name ?? '—' }} · {{ $art->created_at->format('d/m/Y') }}
                </div>
                @if ($art->description)
                    <p class="text-xs text-[#7d8f87] mt-2 line-clamp-2">{{ $art->description }}</p>
                @endif
                @if ($art->review_note)
                    <p class="text-[11px] text-brand-700 mt-2 bg-brand-50 rounded-lg px-2 py-1.5">
                        <i class="bx bx-comment"></i> {{ $art->review_note }}
                    </p>
                @endif

                <form method="POST" action="{{ route('admin.gallery.review', $art) }}" class="mt-3 space-y-2 mt-auto">
                    @csrf @method('PUT')
                    <div class="flex gap-1.5">
                        <select name="status" class="input !py-1.5 !text-xs">
                            <option value="approved" @selected($art->status === 'approved')>Duyệt</option>
                            <option value="pending" @selected($art->status === 'pending')>Chờ duyệt</option>
                            <option value="revision" @selected($art->status === 'revision')>Yêu cầu sửa</option>
                            <option value="rejected" @selected($art->status === 'rejected')>Từ chối</option>
                        </select>
                        <button class="btn btn-primary btn-sm flex-shrink-0">Lưu</button>
                    </div>
                    <input name="review_note" value="{{ $art->review_note }}" placeholder="Nhận xét gửi học viên..." class="input !py-1.5 !text-xs">
                </form>
            </div>
        </article>
    @empty
        <div class="col-span-full card empty">
            <i class="bx bxs-image"></i>
            Không có tác phẩm nào{{ $status ? ' ở trạng thái này' : '' }}.
        </div>
    @endforelse
</div>

<div class="mt-5">{{ $artworks->links() }}</div>
@endsection
