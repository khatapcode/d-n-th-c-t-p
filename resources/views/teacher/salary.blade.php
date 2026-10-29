@extends('layouts.studio', ['portal' => 'teacher'])
@section('title', 'Lương & thu nhập')
@section('content')
<div class="mb-6">
    <p class="eyebrow">Thu nhập</p>
    <h1 class="page-title mt-1.5">Lương & thu nhập</h1>
    <p class="page-sub">Lương tạm tính dựa trên giờ dạy và đơn giá của bạn. Admin chi trả vào đầu tháng sau.</p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="card card-pad">
        <p class="text-[11px] uppercase tracking-wider text-[#9aaba3]">Đơn giá giờ dạy</p>
        <p class="text-2xl font-bold mt-1">{{ number_format($teacher->hourly_rate ?? 0) }}đ<span class="text-xs font-medium text-[#9aaba3]">/giờ</span></p>
    </div>
    <div class="card card-pad">
        <p class="text-[11px] uppercase tracking-wider text-[#9aaba3]">Giờ dạy tháng {{ now()->month }}</p>
        <p class="text-2xl font-bold mt-1">{{ $monthHours }}<span class="text-xs font-medium text-[#9aaba3]"> giờ</span></p>
    </div>
    <div class="card card-pad !bg-brand-700 !border-brand-700 text-white">
        <p class="text-[11px] uppercase tracking-wider text-brand-200">Lương tạm tính</p>
        <p class="text-2xl font-bold mt-1">{{ number_format($estimated) }}đ</p>
        <p class="text-[11px] text-brand-200/80 mt-1">Chưa gồm thưởng / khấu trừ</p>
    </div>
</div>

<div class="card overflow-hidden">
    <div class="px-5 py-4"><h2 class="section-title">Bảng lương theo kỳ</h2></div>
    <div class="overflow-x-auto">
        <table class="table">
            <thead>
                <tr>
                    <th>Kỳ lương</th>
                    <th>Giờ dạy</th>
                    <th>Số tiền</th>
                    <th>Trạng thái</th>
                    <th>Ngày chi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($payments as $pay)
                    <tr>
                        <td class="font-medium">Tháng {{ $pay->period_month }}/{{ $pay->period_year }}</td>
                        <td>{{ $pay->teaching_hours }} giờ</td>
                        <td class="font-semibold">{{ number_format($pay->amount) }}đ</td>
                        <td>
                            <span class="badge {{ $pay->status === 'paid' ? 'badge-green' : 'badge-amber' }}">
                                {{ $pay->status === 'paid' ? 'Đã nhận' : 'Chờ chi' }}
                            </span>
                        </td>
                        <td class="text-xs text-[#7d8f87]">{{ $pay->paid_at ? \Carbon\Carbon::parse($pay->paid_at)->format('d/m/Y') : '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="empty !py-10"><i class="bx bxs-wallet"></i>Chưa có kỳ lương nào được ghi nhận.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
