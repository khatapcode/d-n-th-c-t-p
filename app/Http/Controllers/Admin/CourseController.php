<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::withCount(['classes as active_classes_count' => function ($q) {
            $q->where('status', 'ongoing');
        }])->latest()->paginate(10);

        return view('admin.courses.index', compact('courses'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        unset($data['image']);
        $data['image'] = $this->storeImage($request);
        $data['is_featured'] = $request->boolean('is_featured');
        Course::create($data);

        return back()->with('success', 'Đã thêm khóa học.');
    }

    public function update(Request $request, Course $course)
    {
        $data = $this->validated($request);
        unset($data['image']);
        $data['is_featured'] = $request->boolean('is_featured');
        if ($request->hasFile('image')) {
            $data['image'] = $this->storeImage($request);
        }
        $course->update($data);

        return back()->with('success', 'Đã cập nhật khóa học.');
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return back()->with('success', 'Đã xóa khóa học.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'duration_hours' => 'required|integer|min:1',
            'level' => 'required|in:beginner,intermediate,advanced',
            'tuition_fee' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|max:2048',
        ]);
    }

    private function storeImage(Request $request): ?string
    {
        if (!$request->hasFile('image')) {
            return null;
        }
        $file = $request->file('image');
        $name = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images/courses'), $name);
        return $name;
    }
}
