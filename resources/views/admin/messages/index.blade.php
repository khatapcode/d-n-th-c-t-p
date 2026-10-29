@extends('layouts.admin')
@section('title', 'Hộp thư liên hệ')
@section('content')
<div class="flex flex-wrap items-end justify-between gap-3 mb-6">
    <div>
        <p class="eyebrow">Vận hành</p>
        <h1 class="page-title mt-1.5">Hộp thư liên hệ</h1>
        <p class="page-sub">Tin nhắn gửi từ form liên hệ trên landing page.</p>
    </div>
    <div class="flex gap-1.5">
        <a href="{{ route('admin.messages.index') }}" class="btn btn-sm {{ $filter === 'all' ? 'btn-primary' : 'btn-ghost' }}">Tất cả</a>
        <a href="{{ route('admin.messages.index', ['filter' => 'unread']) }}" class="btn btn-sm {{ $filter === 'unread' ? 'btn-primary' : 'btn-ghost' }}">
            Chưa xử lý
            @if ($unread > 0)<span class="nav-badge !ml-0">{{ $unread }}</span>@endif
        </a>
    </div>
</div>

<div class="space-y-3">
    @forelse ($messages as $message)
        <div class="card card-pad {{ $message->is_read ? '' : '!border-brand-200 !bg-brand-50/30' }}">
            <div class="flex flex-wrap items-start gap-3">
                <span class="avatar">{{ mb_substr($message->name, 0, 1) }}</span>
                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <p class="font-semibold text-sm">{{ $message->name }}</p>
                        @unless ($message->is_read)
                            <span class="badge badge-green">Mới</span>
                        @endunless
                        <span class="text-[11px] text-[#9aaba3] ml-auto">{{ $message->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-[11px] text-[#7d8f87] mt-0.5">
                        <a href="mailto:{{ $message->email }}" class="hover:text-brand-700">{{ $message->email }}</a>
                        @if ($message->phone) · {{ $message->phone }} @endif
                    </p>
                    <p class="text-sm text-[#4b5f56] mt-2 whitespace-pre-line">{{ $message->message }}</p>
                    <div class="flex flex-wrap gap-2 mt-3">
                        <a href="mailto:{{ $message->email }}" class="btn btn-soft btn-sm"><i class="bx bx-reply"></i> Phản hồi</a>
                        <form method="POST" action="{{ route('admin.messages.toggle', $message) }}">
                            @csrf
                            <button class="btn btn-ghost btn-sm">
                                <i class="bx {{ $message->is_read ? 'bx-envelope' : 'bx-check-double' }}"></i>
                                {{ $message->is_read ? 'Đánh dấu chưa xử lý' : 'Đánh dấu đã xử lý' }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.messages.destroy', $message) }}" onsubmit="return confirm('Xóa liên hệ này?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm"><i class="bx bx-trash"></i> Xóa</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="card empty">
            <i class="bx bxs-inbox"></i>
            {{ $filter === 'unread' ? 'Không còn tin nhắn nào chưa xử lý.' : 'Chưa có liên hệ nào.' }}
        </div>
    @endforelse
</div>

<div class="mt-5">{{ $messages->links() }}</div>
@endsection
