@extends('layouts.admin')
@section('title', 'Admin')
@section('content')
<div class="flex justify-between mb-6">
    <h1 class="text-2xl font-bold">Quản lý Admin</h1>
    <button onclick="openModal('createAdmin')" class="px-4 py-2 bg-brand-600 text-white rounded-xl text-sm">+ Thêm admin</button>
</div>
<div class="bg-white rounded-2xl border overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-stone-50 text-xs uppercase text-stone-500"><tr><th class="text-left px-4 py-3">Tên</th><th>Email</th><th>SĐT</th><th>Trạng thái</th><th></th></tr></thead>
        <tbody>
        @foreach ($admins as $admin)
            <tr class="border-t">
                <td class="px-4 py-3 font-medium">{{ $admin->name }}</td>
                <td>{{ $admin->email }}</td>
                <td>{{ $admin->phone }}</td>
                <td>{{ $admin->is_active ? 'Hoạt động' : 'Khóa' }}</td>
                <td class="px-4 text-right">
                    <button onclick="openModal('editAdmin{{ $admin->id }}')" class="text-brand-700">Sửa</button>
                    <form class="inline" method="POST" action="{{ route('admin.admins.destroy', $admin) }}" onsubmit="return confirm('Xóa admin này?')">@csrf @method('DELETE')<button class="text-red-600 ml-2">Xóa</button></form>
                </td>
            </tr>
            <div id="editAdmin{{ $admin->id }}" class="hidden fixed inset-0 z-40 bg-black/40 items-center justify-center p-4">
                <form method="POST" action="{{ route('admin.admins.update', $admin) }}" class="bg-white rounded-2xl p-6 w-full max-w-md space-y-3">
                    @csrf @method('PUT')
                    <h3 class="font-bold">Sửa admin</h3>
                    <input name="name" value="{{ $admin->name }}" class="w-full border rounded-xl px-3 py-2" required>
                    <input type="email" name="email" value="{{ $admin->email }}" class="w-full border rounded-xl px-3 py-2" required>
                    <input name="phone" value="{{ $admin->phone }}" class="w-full border rounded-xl px-3 py-2">
                    <input type="password" name="password" placeholder="Mật khẩu mới (trống = giữ)" class="w-full border rounded-xl px-3 py-2">
                    <label class="text-sm"><input type="checkbox" name="is_active" value="1" @checked($admin->is_active)> Đang hoạt động</label>
                    <div class="flex justify-end gap-2"><button type="button" onclick="closeModal('editAdmin{{ $admin->id }}')">Hủy</button><button class="px-4 py-2 bg-brand-600 text-white rounded-xl">Lưu</button></div>
                </form>
            </div>
        @endforeach
        </tbody>
    </table>
    <div class="p-4">{{ $admins->links() }}</div>
</div>
<div id="createAdmin" class="hidden fixed inset-0 z-40 bg-black/40 items-center justify-center p-4">
    <form method="POST" action="{{ route('admin.admins.store') }}" class="bg-white rounded-2xl p-6 w-full max-w-md space-y-3">
        @csrf
        <h3 class="font-bold">Thêm admin</h3>
        <input name="name" placeholder="Họ tên" class="w-full border rounded-xl px-3 py-2" required>
        <input type="email" name="email" placeholder="Email" class="w-full border rounded-xl px-3 py-2" required>
        <input name="phone" placeholder="SĐT" class="w-full border rounded-xl px-3 py-2">
        <input type="password" name="password" placeholder="Mật khẩu" class="w-full border rounded-xl px-3 py-2" required>
        <div class="flex justify-end gap-2"><button type="button" onclick="closeModal('createAdmin')">Hủy</button><button class="px-4 py-2 bg-brand-600 text-white rounded-xl">Tạo</button></div>
    </form>
</div>
@endsection
@section('scripts')
<script>
function openModal(id){const el=document.getElementById(id);el.classList.remove('hidden');el.classList.add('flex')}
function closeModal(id){const el=document.getElementById(id);el.classList.add('hidden');el.classList.remove('flex')}
</script>
@endsection
