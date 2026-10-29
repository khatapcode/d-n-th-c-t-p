<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', User::ROLE_STUDENT)->with(['enrolledClasses', 'invoices']);
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($builder) use ($q) {
                $builder->where('name', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%");
            });
        }
        $students = $query->latest()->paginate(10)->withQueryString();

        return view('admin.students.index', compact('students'));
    }

    public function show(User $student)
    {
        abort_unless($student->isStudent(), 404);
        $student->load([
            'enrolledClasses.course',
            'enrolledClasses.teacher',
            'invoices.artClass',
            'attendances.artClass',
        ]);

        $feeStatus = match (true) {
            $student->invoices->where('status', 'unpaid')->count() > 0 => 'Chưa nộp',
            $student->invoices->where('status', 'pending')->count() > 0 => 'Chờ xác nhận',
            default => 'Đã nộp',
        };

        return view('admin.students.show', compact('student', 'feeStatus'));
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
            'role' => User::ROLE_STUDENT,
        ]);

        return back()->with('success', 'Đã thêm học viên.');
    }

    public function update(Request $request, User $student)
    {
        abort_unless($student->isStudent(), 404);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->id,
            'phone' => 'nullable|string|max:20',
            'is_active' => 'nullable|boolean',
            'password' => 'nullable|string|min:6',
        ]);
        $student->name = $data['name'];
        $student->email = $data['email'];
        $student->phone = $data['phone'] ?? null;
        $student->is_active = $request->boolean('is_active', $student->is_active);
        if (!empty($data['password'])) {
            $student->password = Hash::make($data['password']);
        }
        $student->save();
        return back()->with('success', 'Đã cập nhật học viên.');
    }

    public function destroy(User $student)
    {
        abort_unless($student->isStudent(), 404);
        $student->delete();
        return back()->with('success', 'Đã xóa học viên.');
    }
}
