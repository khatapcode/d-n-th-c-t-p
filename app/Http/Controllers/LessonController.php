<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Courses;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function showLesson($course_id) {
        // Lấy tất cả các bài học của khóa học cụ thể
        $lessons = Lesson::where('course_id', $course_id)->paginate(10);

        // Lấy thông tin của khóa học
        $course = Courses::findOrFail($course_id);

        return view('admin.course.lesson.detail', compact('lessons', 'course'));
    }

    public function createLessForm(Courses $course) {
        return view('admin.course.lesson.create', compact('course'));
    }

    public function store(Request $request, Courses $course) {
        $messages = [
            'lesson_name.required' => 'Tên bài học không được để trống !!',
            'lesson_video.required' => 'Video bài học là bắt buộc !!',
            'lesson_video.mimes' => 'Video phải có định dạng: mp4, avi, mkv !!',
            'lesson_video.max' => 'Dung lượng video không được vượt quá 50MB !!',
        ];
    
        // Validate các trường nhập liệu
        $request->validate([
            'lesson_name' => 'required|string',
            'lesson_video' => 'required|mimes:mp4,avi,mkv|max:51200',
        ], $messages);

        if ($request->hasFile('lesson_video')) {
            $video = $request->file('lesson_video');
            $videoName = time() . '.' . $video->getClientOriginalExtension();
            $video->move(public_path('videos/lessons'), $videoName);
        }

        // Chuyển đổi giờ, phút, giây thành định dạng time
        $duration = sprintf('%02d:%02d:%02d', $request->hours, $request->minutes, $request->seconds);

        // // Tạo mới bài học
        Lesson::create([
            'lesson_name' => $request->input('lesson_name'),
            'lesson_video' => $videoName,
            'lesson_duration' => $duration,
            'course_id' => $request->input('course_id'),
        ]);

        return redirect()->route('admin.createlesson', ['course' => $course->course_id])->with('success', 'Thêm bài học thành công');
    }
    
    public function editLessForm(Lesson $lesson) {
        return view('admin.course.lesson.edit', compact('lesson'));
    }

    
    public function update(Request $request, Lesson $lesson) {
        $messages = [
            'lesson_name.required' => 'Tên bài học không được để trống !!',
            'lesson_video.required' => 'Video bài học là bắt buộc !!',
            'lesson_video.mimes' => 'Video phải có định dạng: mp4, avi, mkv !!',
            'lesson_video.max' => 'Dung lượng video không được vượt quá 50MB !!',
        ];
    
        // Validate các trường nhập liệu
        $data = $request->validate([
            'lesson_name' => 'required|string',
            'lesson_video' => 'mimes:mp4,avi,mkv|max:51200',
        ], $messages);

        if ($request->hasFile('lesson_video')) {
            $video = $request->file('lesson_video');
            $videoName = time() . '.' . $video->getClientOriginalExtension();
            $video->move(public_path('videos/lessons'), $videoName);
            $data['lesson_video'] = $videoName;
        }

        // Chuyển đổi giờ, phút, giây thành định dạng time
        $duration = sprintf('%02d:%02d:%02d', $request->hours, $request->minutes, $request->seconds);
        $data['lesson_duration'] = $duration;

        $lesson->update($data);

        return redirect()->route('admin.editlesson', ['lesson' => $lesson->lesson_id])->with('success', 'Sửa bài học thành công');
    }

    public function destroy(Lesson $lesson, Courses $course) {
        $lesson->delete();
        return redirect()->route('admin.lesson', ['course' => $lesson->course_id]);
    }
}
