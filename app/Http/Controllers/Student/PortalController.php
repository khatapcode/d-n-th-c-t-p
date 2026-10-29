<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ArtClass;
use App\Models\Artwork;
use App\Models\Attendance;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PortalController extends Controller
{
    public function account()
    {
        $user = auth()->user();
        $artworks = $user->artworks()->latest()->get();
        $approved = $artworks->where('status', 'approved');

        return view('student.account', compact('user', 'artworks', 'approved'));
    }

    public function updateAccount(Request $request)
    {
        $user = auth()->user();
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
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
            'status' => 'pending',
        ]);

        return back()->with('success', 'Đã gửi tác phẩm. Chờ giáo viên/admin duyệt.');
    }

    public function classes()
    {
        $student = auth()->user();
        $classes = $student->enrolledClasses()->with(['course', 'teacher'])->get();
        $invoices = $student->invoices()->with('artClass')->latest()->get();
        $attendances = Attendance::with('artClass')
            ->where('student_id', $student->id)
            ->orderByDesc('session_date')
            ->get();

        $attendanceRate = $attendances->where('status', 'present')->count();
        $attendanceTotal = $attendances->whereIn('status', ['present', 'absent', 'leave'])->count();

        return view('student.classes', compact(
            'student', 'classes', 'invoices', 'attendances', 'attendanceRate', 'attendanceTotal'
        ));
    }

    /** Danh mục lớp đang mở để học viên đăng ký. */
    public function catalog()
    {
        $student = auth()->user();

        $classes = ArtClass::with(['course', 'teacher'])
            ->withCount(['students', 'pendingStudents'])
            ->open()
            ->orderBy('start_date')
            ->get();

        $myEnrollments = $student->enrolledClasses()
            ->withPivot('status')
            ->get()
            ->mapWithKeys(fn ($c) => [$c->id => $c->pivot->status]);

        return view('student.enroll', compact('classes', 'myEnrollments'));
    }

    public function requestEnrollment(ArtClass $class)
    {
        abort_unless(in_array($class->status, ['upcoming', 'ongoing']), 404);

        $existing = $class->members()->where('users.id', auth()->id())->first();
        $status = $existing?->pivot->status;

        if ($status === 'approved') {
            return back()->with('success', 'Bạn đã là học viên của lớp này.');
        }

        if ($class->isFull()) {
            return back()->withErrors(['enrollment' => 'Lớp đã đủ học viên, vui lòng chọn lớp khác.']);
        }

        $class->members()->syncWithoutDetaching([
            auth()->id() => ['status' => 'pending', 'note' => null, 'decided_at' => null],
        ]);

        return back()->with('success', 'Đã gửi yêu cầu tham gia lớp ' . $class->name . '. Vui lòng chờ duyệt.');
    }

    public function cancelEnrollment(ArtClass $class)
    {
        $class->members()->detach(auth()->id());

        return back()->with('success', 'Đã hủy yêu cầu đăng ký lớp ' . $class->name . '.');
    }

    /** Học viên báo đã chuyển khoản để admin xác nhận. */
    public function requestPayment(Request $request, Invoice $invoice)
    {
        abort_unless($invoice->student_id === auth()->id(), 403);
        abort_unless($invoice->status === 'unpaid', 400);

        $data = $request->validate([
            'method' => 'required|in:cash,bank,momo,vnpay',
            'paid_note' => 'nullable|string|max:255',
        ]);

        $invoice->update([
            'status' => 'pending',
            'method' => $data['method'],
            'paid_note' => $data['paid_note'] ?? null,
        ]);

        return back()->with('success', 'Đã báo thanh toán hóa đơn ' . $invoice->code . '. Admin sẽ xác nhận sớm.');
    }

    public function requestLeave(Request $request, ArtClass $class)
    {
        abort_unless($class->students()->where('users.id', auth()->id())->exists(), 403);

        $data = $request->validate([
            'session_date' => 'required|date',
            'note' => 'nullable|string|max:255',
        ]);

        Attendance::updateOrCreate(
            [
                'art_class_id' => $class->id,
                'student_id' => auth()->id(),
                'session_date' => $data['session_date'],
            ],
            [
                'status' => 'leave',
                'note' => $data['note'] ?? 'Báo nghỉ',
            ]
        );

        return back()->with('success', 'Đã gửi báo nghỉ.');
    }
}
