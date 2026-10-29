@extends('layouts.studio', ['portal' => 'teacher'])
@section('title', 'Tác phẩm học viên')
@section('content')
<div class="flex flex-wrap items-end justify-between gap-3 mb-6">
    <div>
        <p class="eyebrow">Gallery</p>
        <h1 class="page-title mt-1.5">Tác phẩm học viên</h1>
        <p class="page-sub">Duyệt tác phẩm và để lại nhận xét để học viên tiến bộ nhanh hơn.</p>
    </div>
    <div class="flex gap-2 text-xs">
        <span class="badge badge-green">{{ $counts['approved'] }} đã duyệt</span>
        <span class="badge badge-amber">{{ $counts['pending'] }} chờ</span>
        <span class="badge badge-blue">{{ $counts['revision'] }} cần sửa</span>
    </div>
</div>

<div class="flex flex-wrap gap-2 mb-5">
    @foreach (['all' => 'Tất cả', 'pending' => 'Chờ duyệt', 'approved' => 'Đã duyệt', 'revision' => 'Cần sửa', 'rejected' => 'Từ chối'] as $key => $label)
        <button data-filter="{{ $key }}" onclick="filterArtworks('{{ $key }}')"
                class="filter-btn btn btn-sm {{ $key === 'all' ? 'btn-primary' : 'btn-ghost' }}">{{ $label }}</button>
    @endforeach
</div>

<div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-4" id="artworkGrid">
    @forelse ($artworks as $art)
        <div class="card overflow-hidden artwork-item" data-status="{{ $art->status }}">
            <div class="relative h-48 bg-brand-100">
                <img src="{{ asset('images/artworks/'.$art->image) }}" alt="{{ $art->title }}" class="w-full h-full object-cover">
                <span class="absolute top-2.5 left-2.5 badge !bg-white/95 {{ $art->status === 'approved' ? 'badge-green' : ($art->status === 'pending' ? 'badge-amber' : ($art->status === 'revision' ? 'badge-blue' : 'badge-red')) }}">
                    {{ ['approved' => 'Đã duyệt', 'pending' => 'Chờ duyệt', 'revision' => 'Cần sửa', 'rejected' => 'Từ chối'][$art->status] ?? $art->status }}
                </span>
            </div>
            <div class="p-4">
                <p class="font-semibold text-sm">{{ $art->title }}</p>
                <p class="text-[11px] text-[#9aaba3]">{{ $art->owner->name ?? '' }} · {{ $art->created_at->format('d/m/Y') }}</p>
                @if ($art->description)
                    <p class="text-xs text-[#7d8f87] mt-2 line-clamp-2">{{ $art->description }}</p>
                @endif

                @if ($art->comments->isNotEmpty())
                    <div class="mt-2.5 space-y-1">
                        @foreach ($art->comments as $cmt)
                            <p class="text-[11px] text-[#4b5f56] bg-[#f8fbf9] rounded-lg px-2 py-1">
                                <b>{{ $cmt->user->name ?? 'GV' }}:</b> {{ $cmt->comment }}
                            </p>
                        @endforeach
                    </div>
                @endif

                @if ($art->status === 'pending' && in_array($art->user_id, $myStudentIds))
                    <form method="POST" action="{{ route('teacher.artworks.approve', $art) }}" class="mt-3">
                        @csrf
                        <button class="btn btn-primary btn-sm w-full"><i class="bx bx-check-circle"></i> Duyệt tác phẩm</button>
                    </form>
                @endif

                <form method="POST" action="{{ route('teacher.artworks.comment', $art) }}" class="flex gap-2 mt-2">
                    @csrf
                    <input name="comment" placeholder="Nhận xét..." class="input !py-1.5 !text-xs" required>
                    <button class="btn btn-soft btn-sm">Gửi</button>
                </form>
            </div>
        </div>
    @empty
        <div class="col-span-full card empty"><i class="bx bxs-image"></i>Chưa có tác phẩm nào.</div>
    @endforelse
</div>

<div id="emptyFilter" class="hidden card empty mt-4"><i class="bx bx-search-alt"></i>Không có tác phẩm ở trạng thái này.</div>
@endsection

@section('scripts')
<script>
function filterArtworks(status) {
    let visible = 0;
    document.querySelectorAll('.artwork-item').forEach(el => {
        const match = status === 'all' || el.dataset.status === status;
        el.style.display = match ? '' : 'none';
        if (match) visible++;
    });
    document.getElementById('emptyFilter').classList.toggle('hidden', visible > 0);
    document.querySelectorAll('.filter-btn').forEach(btn => {
        const active = btn.dataset.filter === status;
        btn.classList.toggle('btn-primary', active);
        btn.classList.toggle('btn-ghost', !active);
    });
}
</script>
@endsection
