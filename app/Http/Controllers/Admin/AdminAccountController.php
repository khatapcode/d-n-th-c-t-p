<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminAccountController extends Controller
{
    public function index()
    {
        $admins = User::where('role', User::ROLE_ADMIN)->latest()->paginate(10);
        return view('admin.admins.index', compact('admins'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string|max:20',
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'] ?? null,
            'role' => User::ROLE_ADMIN,
        ]);

        return back()->with('success', 'Đã thêm tài khoản admin.');
    }

    public function update(Request $request, User $admin)
    {
        abort_unless($admin->isAdmin(), 404);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $admin->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6',
            'is_active' => 'nullable|boolean',
        ]);

        $admin->name = $data['name'];
        $admin->email = $data['email'];
        $admin->phone = $data['phone'] ?? null;
        $admin->is_active = $request->boolean('is_active', $admin->is_active);
        if (!empty($data['password'])) {
            $admin->password = Hash::make($data['password']);
        }
        $admin->save();

        return back()->with('success', 'Đã cập nhật admin.');
    }

    public function destroy(User $admin)
    {
        abort_unless($admin->isAdmin(), 404);
        if ($admin->id === auth()->id()) {
            return back()->withErrors(['error' => 'Không thể xóa tài khoản đang đăng nhập.']);
        }
        $admin->delete();
        return back()->with('success', 'Đã xóa admin.');
    }
}
