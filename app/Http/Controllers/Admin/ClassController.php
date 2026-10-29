<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArtClass;
use App\Models\Course;
use App\Models\User;
use App\Services\EnrollmentService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClassController extends Controller
{
    public function __construct(private EnrollmentService $enrollments)
    {
    }

    public function index()
    {
        $classes = ArtClass::with(['course', 'teacher'])
            ->withCount(['students', 'pendingStudents'])
            ->latest()
            ->paginate(10);

        $courses = Course::where('status', 'active')->orderBy('name')->get();
        $teachers = User::where('role', User::ROLE_TEACHER)->where('is_active', true)->orderBy('name')->get();
        $students = User::where('role', User::ROLE_STUDENT)->where('is_active', true)->orderBy('name')->get();
        $pendingTotal = ArtClass::withCount('pendingStudents')->get()->sum('pending_students_count');

        return view('admin.classes.index', compact('classes', 'courses', 'teachers', 'students', 'pendingTotal'));
    }

    public function show(ArtClass $class)
    {
        $class->load(['course', 'teacher', 'students' => fn ($q) => $q->orderBy('name'), 'pendingStudents']);

        $invoices = $class->invoices()->with('student')->latest()->get();
        $allStudents = User::where('role', User::ROLE_STUDENT)
            ->where('is_active', true)
            ->whereNotIn('id', $class->members()->pluck('users.id'))
            ->orderBy('name')
            ->get();

        return view('admin.classes.show', compact('class', 'invoices', 'allStudents'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $studentIds = $data['student_ids'] ?? [];
        unset($data['student_ids']);

        $class = ArtClass::create($data);
        $this->syncStudents($class, $studentIds);

        return back()->with('success', 'Đã tạo lớp học ' . $class->code . '.');
    }

    public function update(Request $request, ArtClass $class)
    {
        $data = $this->validated($request, $class->id);
        $studentIds = $data['student_ids'] ?? [];
        unset($data['student_ids']);

        $class->update($data);
        $this->syncStudents($class, $studentIds);

        return back()->with('success', 'Đã cập nhật lớp học.');
    }

    public function destroy(ArtClass $class)
    {
        $class->delete();

        return back()->with('success', 'Đã xóa lớp học.');
    }

    /** Thêm nhanh một học viên đã có vào lớp (duyệt thẳng). */
    public function addStudent(Request $request, ArtClass $class)
    {
        $data = $request->validate([
            'student_id' => 'required|exists:users,id',
        ]);

        if ($class->isFull()) {
            return back()->withErrors(['enrollment' => 'Lớp đã đủ học viên.']);
        }

        $student = User::where('role', User::ROLE_STUDENT)->findOrFail($data['student_id']);

        $class->members()->syncWithoutDetaching([
            $student->id => ['status' => 'approved', 'enrolled_at' => now(), 'decided_at' => now()],
        ]);

        $invoice = $this->enrollments->createTuitionInvoice($class, $student);

        return back()->with('success', 'Đã thêm ' . $student->name . ' vào lớp.'
            . ($invoice ? ' Học phí ' . number_format($invoice->amount) . 'đ đã được tạo.' : ''));
    }

    public function approveEnrollment(ArtClass $class, User $student)
    {
        $invoice = $this->enrollments->approve($class, $student);

        return back()->with('success', 'Đã duyệt ' . $student->name . ' vào lớp.'
            . ($invoice ? ' Đã tạo hóa đơn ' . $invoice->code . '.' : ''));
    }

    public function rejectEnrollment(Request $request, ArtClass $class, User $student)
    {
        $this->enrollments->reject($class, $student, $request->input('note'));

        return back()->with('success', 'Đã từ chối yêu cầu của ' . $student->name . '.');
    }

    public function removeStudent(ArtClass $class, User $student)
    {
        $class->members()->detach($student->id);

        return back()->with('success', 'Đã xóa ' . $student->name . ' khỏi lớp.');
    }

    private function syncStudents(ArtClass $class, array $studentIds): void
    {
        $payload = [];
        foreach ($studentIds as $id) {
            $payload[$id] = ['status' => 'approved', 'enrolled_at' => now(), 'decided_at' => now()];
        }

        $class->members()->syncWithoutDetaching($payload);
    }

    private function validated(Request $request, $id = null): array
    {
        return $request->validate([
            'code' => ['required', 'string', 'max:50', Rule::unique('art_classes', 'code')->ignore($id)],
            'name' => 'required|string|max:255',
            'course_id' => 'required|exists:art_courses,id',
            'teacher_id' => 'required|exists:users,id',
            'schedule' => 'nullable|string|max:255',
            'room' => 'nullable|string|max:100',
            'status' => 'required|in:upcoming,ongoing,completed',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'max_students' => 'required|integer|min:1',
            'student_ids' => 'nullable|array',
        ]);
    }
}
