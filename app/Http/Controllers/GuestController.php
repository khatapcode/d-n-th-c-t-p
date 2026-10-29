<?php

namespace App\Http\Controllers;

use App\Models\ArtClass;
use App\Models\Artwork;
use App\Models\Contact;
use App\Models\Course;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function home()
    {
        $courses = Course::where('status', 'active')->where('is_featured', true)->take(6)->get();
        if ($courses->isEmpty()) {
            $courses = Course::where('status', 'active')->latest()->take(6)->get();
        }

        $teachers = User::where('role', User::ROLE_TEACHER)->where('is_active', true)->take(6)->get();
        $artworks = Artwork::where('status', 'approved')->latest()->take(8)->get();
        $reviews = Review::where('is_visible', true)->latest()->take(6)->get();
        $openClasses = ArtClass::with(['course', 'teacher'])->withCount('students')->open()->orderBy('start_date')->take(4)->get();

        $numbers = [
            'students' => User::where('role', User::ROLE_STUDENT)->count(),
            'teachers' => User::where('role', User::ROLE_TEACHER)->count(),
            'classes' => ArtClass::whereIn('status', ['upcoming', 'ongoing'])->count(),
            'artworks' => Artwork::where('status', 'approved')->count(),
        ];

        return view('guest.home', compact('courses', 'teachers', 'artworks', 'reviews', 'openClasses', 'numbers'));
    }

    public function course(Course $course)
    {
        abort_unless($course->status === 'active', 404);

        $course->load(['classes' => fn ($q) => $q->open()->with(['teacher'])->withCount('students')->orderBy('start_date')]);
        $others = Course::where('status', 'active')->where('id', '!=', $course->id)->take(3)->get();
        $artworks = Artwork::where('status', 'approved')->latest()->take(4)->get();

        return view('guest.course', compact('course', 'others', 'artworks'));
    }

    public function contact(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string|max:2000',
        ]);

        Contact::create($data);

        return back()->with('success', 'Đã gửi liên hệ. Madmun.Arthouse sẽ phản hồi sớm.');
    }
}
