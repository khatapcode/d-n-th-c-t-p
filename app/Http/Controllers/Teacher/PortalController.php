<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ArtClass;
use App\Models\Artwork;
use App\Models\ArtworkComment;
use App\Models\Attendance;
use App\Models\SalaryPayment;
use App\Models\User;
use App\Services\EnrollmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PortalController extends Controller
{
    public function __construct(private EnrollmentService $enrollments)
    {
    }
    public function account()
    {
        $user = auth()->user();
        $artworks = $user->artworks()->latest()->get();
        return view('teacher.account', compact('user', 'artworks'));
    }

    public function updateAccount(Request $request)
    {
        $user = auth()->user();
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:6|confirmed',
        ]);
        $password = $data['password'] ?? null;
        unset($data['password']);
        $user->fill($data);
        if (!empty($password)) {
            $user->password = Hash::make($password);
        }
        $user->save();

        return back()->with('success', 'Đã cập nhật thông tin cá nhân.');
    }

    public function storeArtwork(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|max:4096',
        ]);

        $file = $request->file('image');
        $name = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images/artworks'), $name);

        Artwork::create([
            'user_id' => auth()->id(),
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'image' => $name,
            'status' => 'approved',
            'reviewer_id' => auth()->id(),
        ]);

        return back()->with('success', 'Đã thêm tác phẩm vào gallery.');
    }

    public function deleteArtwork(Artwork $artwork)
    {
        abort_unless($artwork->user_id === auth()->id(), 403);
        $artwork->delete();
        return back()->with('success', 'Đã xóa tác phẩm.');
    }

    public function classes()
    {
        $classes = ArtClass::with(['course'])
            ->withCount(['students', 'pendingStudents'])
            ->where('teacher_id', auth()->id())
            ->latest()
            ->get();

        return view('teacher.classes', compact('classes'));
    }

    public function classShow(ArtClass $class)
    {
        abort_unless($class->teacher_id === auth()->id(), 403);
        $class->load(['course', 'students' => fn ($q) => $q->orderBy('name'), 'pendingStudents']);
        $today = now()->toDateString();
        $attendances = Attendance::where('art_class_id', $class->id)
            ->whereDate('session_date', $today)
            ->pluck('status', 'student_id');

        $studentArtworks = Artwork::with('owner')
            ->whereIn('user_id', $class->students->pluck('id'))
            ->latest()
            ->get();

        return view('teacher.class-show', compact('class', 'attendances', 'today', 'studentArtworks'));
    }

    public function approveEnrollment(ArtClass $class, User $student)
    {
        abort_unless($class->teacher_id === auth()->id(), 403);
        $invoice = $this->enrollments->approve($class, $student);

        return back()->with('success', 'Đã duyệt ' . $student->name . ' vào lớp.'
            . ($invoice ? ' Hóa đơn ' . $invoice->code . ' đã được tạo.' : ''));
    }

    public function rejectEnrollment(Request $request, ArtClass $class, User $student)
    {
        abort_unless($class->teacher_id === auth()->id(), 403);
        $this->enrollments->reject($class, $student, $request->input('note'));

        return back()->with('success', 'Đã từ chối yêu cầu của ' . $student->name . '.');
    }

    public function attendance(Request $request, ArtClass $class)
    {
        abort_unless($class->teacher_id === auth()->id(), 403);
        $date = $request->input('session_date', now()->toDateString());
        foreach ($request->input('status', []) as $studentId => $status) {
            Attendance::updateOrCreate(
                [
                    'art_class_id' => $class->id,
                    'student_id' => $studentId,
                    'session_date' => $date,
                ],
                [
                    'status' => $status,
                    'note' => $request->input("note.$studentId"),
                ]
            );
        }

        return back()->with('success', 'Đã lưu điểm danh.');
    }

    public function commentArtwork(Request $request, Artwork $artwork)
    {
        $data = $request->validate([
            'comment' => 'required|string|max:1000',
        ]);
        ArtworkComment::create([
            'artwork_id' => $artwork->id,
            'user_id' => auth()->id(),
            'comment' => $data['comment'],
        ]);

        return back()->with('success', 'Đã gửi nhận xét tác phẩm.');
    }

    public function galleryIndex()
    {
        $artworks = Artwork::with(['owner', 'comments.user'])->latest()->get();
        $counts = [
            'pending' => $artworks->where('status', 'pending')->count(),
            'approved' => $artworks->where('status', 'approved')->count(),
            'revision' => $artworks->where('status', 'revision')->count(),
            'rejected' => $artworks->where('status', 'rejected')->count(),
        ];

        $myStudentIds = User::whereHas('enrolledClasses', fn ($q) => $q->where('art_classes.teacher_id', auth()->id()))
            ->pluck('id')
            ->all();

        return view('teacher.gallery', compact('artworks', 'counts', 'myStudentIds'));
    }

    public function approveArtwork(Artwork $artwork)
    {
        abort_unless(
            $artwork->owner?->enrolledClasses()->where('art_classes.teacher_id', auth()->id())->exists(),
            403,
            'Tác phẩm không thuộc lớp bạn phụ trách.'
        );

        $artwork->update([
            'status' => 'approved',
            'reviewer_id' => auth()->id(),
            'review_note' => null,
        ]);

        return back()->with('success', 'Đã duyệt tác phẩm "' . $artwork->title . '".');
    }

    public function salary()
    {
        $teacher = auth()->user();
        $payments = SalaryPayment::where('teacher_id', $teacher->id)->orderByDesc('period_year')->orderByDesc('period_month')->get();
        $monthHours = $payments->where('period_year', now()->year)->where('period_month', now()->month)->sum('teaching_hours');
        if ($monthHours == 0) {
            $monthHours = $teacher->teachingClasses()->where('status', 'ongoing')->count() * 16;
        }
        $estimated = ($teacher->hourly_rate ?? 0) * $monthHours;

        return view('teacher.salary', compact('teacher', 'payments', 'monthHours', 'estimated'));
    }
}
