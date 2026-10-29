<?php

namespace App\Http\Controllers;

use App\Models\RegisterCourses;
use Illuminate\Http\Request;

class RegisterCourseController extends Controller
{
    public function index(Request $request) {
        // Lấy giá trị của rc_status và key từ request
        $rc_status = $request->input('rc_status');
        $key = $request->input('key');
    
        // Bắt đầu query với RegisterCourses và các liên kết cần thiết
        $query = RegisterCourses::with(['user', 'course.category']);
    
        // Nếu có giá trị của rc_status, thêm điều kiện vào query
        if ($rc_status) {
            $query->where('rc_status', $rc_status);
        }
    
        // Nếu có giá trị của key, thêm điều kiện tìm kiếm theo tên người dùng
        if ($key) {
            $query->whereHas('user', function($q) use ($key) {
                $q->where('name', 'like', '%' . $key . '%');
            });
        }
    
        $registerCourses = $query->paginate(10, ['rc_id', 'rc_status', 'user_id', 'course_id', 'created_at']);
    
        return view('admin.registerCourse.index', compact('registerCourses', 'rc_status', 'key'));
    }

    public function editRCForm(RegisterCourses $registerCourses, $rc_id) {
        $registerCourses = RegisterCourses::with(['user', 'course.category'])
        ->where('rc_id', $rc_id)
        ->first(['rc_id', 'rc_status', 'user_id', 'course_id', 'created_at']);

        return view('admin.registerCourse.edit', compact('registerCourses', 'rc_id'));
    }

    public function update(Request $request, $rc_id) {
        $validatedData = $request->validate([
            'rc_status' => 'string|max:255',
        ]);
        // Tìm bản ghi cần cập nhật
        $registerCourses = RegisterCourses::findOrFail($rc_id);
        $registerCourses->update($validatedData);
        return redirect()->route('admin.editRC', $rc_id)->with('success', 'Duyệt đăng ký thành công');
    }

    public function destroy($rc_id) {
        // Tìm bản ghi cần xóa
        $registerCourse = RegisterCourses::findOrFail($rc_id);
        $registerCourse->delete();
        return redirect()->route('admin.registerCourse');
    }
}
