<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SalaryPayment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = User::where('role', User::ROLE_TEACHER)
            ->with(['teachingClasses.students', 'salaryPayments'])
            ->latest()
            ->paginate(10);

        return view('admin.teachers.index', compact('teachers'));
    }

    public function show(User $teacher)
    {
        abort_unless($teacher->isTeacher(), 404);
        $teacher->load(['teachingClasses.course', 'teachingClasses.students', 'salaryPayments']);

        $hours = $teacher->salaryPayments->where('period_year', now()->year)->where('period_month', now()->month)->sum('teaching_hours');
        if ($hours == 0) {
            $hours = $teacher->teachingClasses->where('status', 'ongoing')->count() * 16;
        }
        $estimatedSalary = ($teacher->hourly_rate ?? 0) * $hours;

        return view('admin.teachers.show', compact('teacher', 'hours', 'estimatedSalary'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string|max:20',
            'hourly_rate' => 'nullable|integer|min:0',
            'bio' => 'nullable|string',
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'] ?? null,
            'hourly_rate' => $data['hourly_rate'] ?? 150000,
            'bio' => $data['bio'] ?? null,
            'role' => User::ROLE_TEACHER,
        ]);

        return back()->with('success', 'Đã thêm giáo viên.');
    }

    public function paySalary(Request $request, User $teacher)
    {
        abort_unless($teacher->isTeacher(), 404);
        $data = $request->validate([
            'period_month' => 'required|integer|min:1|max:12',
            'period_year' => 'required|integer|min:2020',
            'teaching_hours' => 'required|numeric|min:0',
            'amount' => 'required|integer|min:0',
        ]);

        SalaryPayment::create([
            'teacher_id' => $teacher->id,
            'period_month' => $data['period_month'],
            'period_year' => $data['period_year'],
            'teaching_hours' => $data['teaching_hours'],
            'amount' => $data['amount'],
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        return back()->with('success', 'Đã ghi nhận thanh toán lương.');
    }

    public function update(Request $request, User $teacher)
    {
        abort_unless($teacher->isTeacher(), 404);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $teacher->id,
            'phone' => 'nullable|string|max:20',
            'hourly_rate' => 'nullable|integer|min:0',
            'bio' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'password' => 'nullable|string|min:6',
        ]);
        $teacher->name = $data['name'];
        $teacher->email = $data['email'];
        $teacher->phone = $data['phone'] ?? null;
        $teacher->hourly_rate = $data['hourly_rate'] ?? $teacher->hourly_rate;
        $teacher->bio = $data['bio'] ?? $teacher->bio;
        $teacher->is_active = $request->boolean('is_active', $teacher->is_active);
        if (!empty($data['password'])) {
            $teacher->password = Hash::make($data['password']);
        }
        $teacher->save();
        return back()->with('success', 'Đã cập nhật giáo viên.');
    }

    public function destroy(User $teacher)
    {
        abort_unless($teacher->isTeacher(), 404);
        $teacher->delete();
        return back()->with('success', 'Đã xóa giáo viên.');
    }
}
