<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArtClass;
use App\Models\Contact;
use App\Models\Course;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'courses' => Course::count(),
            'classes' => ArtClass::whereIn('status', ['upcoming', 'ongoing'])->count(),
            'teachers' => User::where('role', User::ROLE_TEACHER)->count(),
            'students' => User::where('role', User::ROLE_STUDENT)->count(),
            'revenue' => Invoice::where('status', 'paid')->sum('amount'),
            'unpaid' => Invoice::where('status', 'unpaid')->sum('amount'),
            'pending_payments' => Invoice::where('status', 'pending')->count(),
            'pending_enrollments' => DB::table('class_student')->where('status', 'pending')->count(),
            'unread_messages' => Contact::where('is_read', false)->count(),
        ];

        $revenueByMonth = Invoice::query()
            ->selectRaw('MONTH(COALESCE(paid_at, created_at)) as month, SUM(amount) as total')
            ->where('status', 'paid')
            ->whereYear(DB::raw('COALESCE(paid_at, created_at)'), now()->year)
            ->groupBy('month')
            ->pluck('total', 'month');

        $chartLabels = [];
        $chartValues = [];
        for ($m = 1; $m <= 12; $m++) {
            $chartLabels[] = 'T' . $m;
            $chartValues[] = (int) ($revenueByMonth[$m] ?? 0);
        }

        $recentInvoices = Invoice::with(['student', 'artClass'])->latest()->take(6)->get();
        $currentClasses = ArtClass::with(['teacher', 'course'])
            ->withCount('students')
            ->where('status', 'ongoing')
            ->take(5)
            ->get();

        $pendingEnrollments = ArtClass::with(['course', 'pendingStudents'])
            ->withCount('pendingStudents')
            ->has('pendingStudents')
            ->take(5)
            ->get();

        $recentMessages = Contact::latest()->take(4)->get();

        $coursePerf = Course::with(['classes.students'])->withCount('classes')->get()->map(function ($course) {
            $course->student_count = $course->classes->sum(fn ($class) => $class->students->count());
            return $course;
        });

        $topCourses = $coursePerf->sortByDesc('student_count')->take(3)->values();
        $improveCourses = $coursePerf->sortBy('student_count')->take(3)->values();

        return view('admin.dashboard', compact(
            'stats', 'chartLabels', 'chartValues', 'recentInvoices', 'currentClasses',
            'topCourses', 'improveCourses', 'pendingEnrollments', 'recentMessages'
        ));
    }
}
