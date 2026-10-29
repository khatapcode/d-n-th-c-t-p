@extends('layouts.admin')
@section('title', 'Tài chính')
@section('content')
@php
    $statusMeta = [
        'unpaid' => ['Chưa thanh toán', 'badge-red'],
        'pending' => ['Chờ xác nhận', 'badge-amber'],
        'paid' => ['Đã thanh toán', 'badge-green'],
    ];
    $tabs = ['' => 'Tất cả', 'unpaid' => 'Chưa thu', 'pending' => 'Chờ xác nhận', 'paid' => 'Đã thu'];
@endphp

<div class="flex flex-wrap items-end justify-between gap-3 mb-6">
    <div>
        <p class="eyebrow">Vận hành</p>
        <h1 class="page-title mt-1.5">Tài chính</h1>
        <p class="page-sub">Học phí học viên, xác nhận thanh toán và lương giáo viên.</p>
    </div>
    <button onclick="openModal('createInvoice')" class="btn btn-primary"><i class="bx bx-plus text-lg"></i> Tạo hóa đơn</button>
</div>

<div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-5">
    <div class="card card-pad">
        <div class="flex items-center justify-between">
            <p class="text-[11px] uppercase tracking-wider text-[#9aaba3]">Đã thu</p>
            <span class="w-8 h-8 rounded-lg bg-brand-50 text-brand-700 flex items-center justify-center"><i class="bx bxs-check-circle"></i></span>
        </div>
        <p class="text-xl font-bold mt-2 text-brand-700">{{ number_format($summary['paid']) }}đ</p>
    </div>
    <div class="card card-pad">
        <div class="flex items-center justify-between">
            <p class="text-[11px] uppercase tracking-wider text-[#9aaba3]">Chưa thu</p>
            <span class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center"><i class="bx bxs-time-five"></i></span>
        </div>
        <p class="text-xl font-bold mt-2">{{ number_format($summary['unpaid']) }}đ</p>
    </div>
    <div class="card card-pad !border-amber-200 !bg-amber-50/40">
        <div class="flex items-center justify-between">
            <p class="text-[11px] uppercase tracking-wider text-amber-700">Chờ xác nhận</p>
            <span class="w-8 h-8 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center"><i class="bx bxs-error-circle"></i></span>
        </div>
        <p class="text-xl font-bold mt-2 text-amber-800">{{ number_format($summary['pending']) }}đ</p>
        <p class="text-[11px] text-amber-700">{{ $summary['pending_count'] }} hóa đơn học viên đã báo thanh toán</p>
    </div>
    <div class="card card-pad">
        <div class="flex items-center justify-between">
            <p class="text-[11px] uppercase tracking-wider text-[#9aaba3]">Lương chờ chi</p>
            <span class="w-8 h-8 rounded-lg bg-sky-50 text-sky-700 flex items-center justify-center"><i class="bx bxs-wallet"></i></span>
        </div>
        <p class="text-xl font-bold mt-2">{{ number_format($summary['salary_pending']) }}đ</p>
        <p class="text-[11px] text-[#9aaba3] mt-1">Đã chi: {{ number_format($summary['salary_paid']) }}đ</p>
    </div>
</div>

<div class="card overflow-hidden mb-5">
    <div class="px-5 pt-4 pb-3 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="section-title">Hóa đơn học phí</h2>
            <p class="text-xs text-[#9aaba3] mt-0.5">Học viên báo chuyển khoản → bạn xác nhận tại đây.</p>
        </div>
        <div class="flex gap-1.5 flex-wrap">
            @foreach ($tabs as $key => $label)
                <a href="{{ route('admin.finance.index', $key ? ['status' => $key] : []) }}"
                   class="btn btn-sm {{ ($status ?: '') === $key ? 'btn-primary' : 'btn-ghost' }}">{{ $label }}</a>
            @endforeach
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="table">
            <thead>
                <tr>
                    <th>Mã</th>
                    <th>Học viên</th>
                    <th>Lớp</th>
                    <th>Số tiền</th>
                    <th>Trạng thái</th>
                    <th class="text-right"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($invoices as $inv)
                    @php [$label, $tone] = $statusMeta[$inv->status] ?? [$inv->status, 'badge-gray']; @endphp
                    <tr>
                        <td class="font-mono text-xs text-[#7d8f87]">{{ $inv->code }}</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <span class="avatar !w-7 !h-7 !text-[11px]">{{ mb_substr($inv->student->name ?? '?', 0, 1) }}</span>
                                <span class="font-medium text-sm">{{ $inv->student->name ?? '—' }}</span>
                            </div>
                        </td>
                        <td class="text-xs text-[#4b5f56]">{{ $inv->artClass->name ?? '—' }}</td>
                        <td class="font-semibold">{{ number_format($inv->amount) }}đ</td>
                        <td>
                            <span class="badge {{ $tone }}">{{ $label }}</span>
                            @if ($inv->status === 'pending')
                                <p class="text-[10px] text-[#9aaba3] mt-1">{{ ['bank' => 'Chuyển khoản', 'momo' => 'MoMo', 'vnpay' => 'VNPay', 'cash' => 'Tiền mặt'][$inv->method] ?? $inv->method }}{{ $inv->paid_note ? ' · ' . $inv->paid_note : '' }}</p>
                            @elseif ($inv->paid_at)
                                <p class="text-[10px] text-[#9aaba3] mt-1">{{ $inv->paid_at->format('d/m/Y') }}</p>
                            @endif
                        </td>
                        <td class="text-right">
                            <div class="inline-flex gap-1.5">
                                @if ($inv->status !== 'paid')
                                    <form method="POST" action="{{ route('admin.finance.invoice.confirm', $inv) }}">
                                        @csrf
                                        <button class="btn btn-primary btn-sm"><i class="bx bx-check"></i> Xác nhận</button>
                                    </form>
                                @endif
                                @if ($inv->status === 'pending')
                                    <form method="POST" action="{{ route('admin.finance.invoice.reject', $inv) }}">
                                        @csrf
                                        <button class="btn btn-ghost btn-sm">Hoàn tác</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="empty"><i class="bx bxs-receipt"></i>Không có hóa đơn nào ở trạng thái này.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3">{{ $invoices->links() }}</div>
</div>

<div class="card overflow-hidden">
    <div class="px-5 py-4">
        <h2 class="section-title">Lương giáo viên</h2>
        <p class="text-xs text-[#9aaba3] mt-0.5">Bảng lương được tạo từ trang giáo viên.</p>
    </div>
    <div class="overflow-x-auto">
        <table class="table">
            <thead>
                <tr>
                    <th>Giáo viên</th>
                    <th>Kỳ lương</th>
                    <th>Giờ dạy</th>
                    <th>Số tiền</th>
                    <th>Trạng thái</th>
                    <th class="text-right"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($salaries as $pay)
                    <tr>
                        <td class="font-medium">{{ $pay->teacher->name ?? '—' }}</td>
                        <td class="text-[#4b5f56]">Tháng {{ $pay->period_month }}/{{ $pay->period_year }}</td>
                        <td>{{ $pay->teaching_hours }} giờ</td>
                        <td class="font-semibold">{{ number_format($pay->amount) }}đ</td>
                        <td><span class="badge {{ $pay->status === 'paid' ? 'badge-green' : 'badge-amber' }}">{{ $pay->status === 'paid' ? 'Đã chi' : 'Chờ chi' }}</span></td>
                        <td class="text-right">
                            @if ($pay->status !== 'paid')
                                <form method="POST" action="{{ route('admin.finance.salary.confirm', $pay) }}">
                                    @csrf
                                    <button class="btn btn-soft btn-sm"><i class="bx bx-wallet"></i> Chi lương</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="empty"><i class="bx bxs-wallet"></i>Chưa có dữ liệu lương.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3">{{ $salaries->links() }}</div>
</div>

{{-- Modal tạo hóa đơn --}}
<div id="createInvoice" class="hidden fixed inset-0 z-50 bg-black/40 backdrop-blur-sm items-center justify-center p-4">
    <form method="POST" action="{{ route('admin.finance.invoice.store') }}" class="bg-white rounded-2xl w-full max-w-md overflow-hidden">
        @csrf
        <div class="flex items-center justify-between px-6 py-4 border-b border-[#eef4f0]">
            <h3 class="font-semibold">Tạo hóa đơn học phí</h3>
            <button type="button" onclick="closeModal('createInvoice')" class="text-[#9aaba3] hover:text-[#10231c]"><i class="bx bx-x text-2xl"></i></button>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label class="label">Học viên</label>
                <select name="student_id" class="input" required>
                    <option value="">— Chọn học viên —</option>
                    @foreach ($students as $s)
                        <option value="{{ $s->id }}">{{ $s->name }} · {{ $s->email }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="label">Lớp học (tuỳ chọn)</label>
                <select name="art_class_id" class="input">
                    <option value="">— Không gắn lớp —</option>
                    @foreach ($artClasses as $cl)
                        <option value="{{ $cl->id }}">{{ $cl->code }} — {{ $cl->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="label">Số tiền (đ)</label>
                <input type="number" name="amount" min="0" class="input" placeholder="2500000" required>
            </div>
            <div>
                <label class="label">Ghi chú</label>
                <input name="note" class="input" placeholder="Học phí tháng 11...">
            </div>
        </div>
        <div class="flex justify-end gap-2 px-6 py-4 bg-[#fbfdfb] border-t border-[#eef4f0]">
            <button type="button" onclick="closeModal('createInvoice')" class="btn btn-ghost">Hủy</button>
            <button class="btn btn-primary">Tạo hóa đơn</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
function openModal(id) { const el = document.getElementById(id); el.classList.remove('hidden'); el.classList.add('flex'); }
function closeModal(id) { const el = document.getElementById(id); el.classList.add('hidden'); el.classList.remove('flex'); }
</script>
@endsection
