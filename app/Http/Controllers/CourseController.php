<?php

namespace App\Http\Controllers;

use App\Models\Courses;
use App\Models\Category;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    // Hiển thị các khóa học
    public function showCourse(Request $request) {
        $category_id = $request->input('category_id'); // Lấy giá trị category_id từ request
        $key = $request->input('key'); // Lấy giá trị key từ request
        $categories = Category::all(); // Lấy tất cả danh mục

        // Tìm kiếm theo từ khóa
        if ($key) {
            $courses = Courses::orderBy('course_id', 'ASC')
                ->where('course_name', 'like', '%' . $key . '%')
                ->paginate(5);
        } else {
            // Lọc khóa học dựa trên category_id
            if ($category_id) {
                $courses = Courses::with('category')
                    ->where('category_id', $category_id)
                    ->orderBy('course_id', 'ASC')
                    ->paginate(5);
            } else {
                // Hiển thị tất cả
                $courses = Courses::with('category')
                    ->orderBy('course_id', 'ASC')
                    ->paginate(5);
            }
        }

        return view('admin.course.index', compact('courses', 'categories', 'category_id', 'key'));
    }

    // Hiển thị form thêm khóa học
    public function createCourseForm() {
        $cates = Category::orderBy('category_id', 'ASC')->paginate(5);
        return view('admin.course.create', compact('cates'));
    }

    // Thêm khóa học
    public function store(Request $request) {
        $messages = [
            'course_name.required' => 'Tên khóa học không được để trống !!',
            'course_name.max' => 'Tên khóa học không được vượt quá 255 ký tự !!',
            'course_img.required' => 'Hình ảnh khóa học là bắt buộc !!',
            'course_img.image' => 'Hình ảnh không phải là tệp hình ảnh !!',
            'course_img.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, svg !!',
            'course_img.max' => 'Dung lượng hình ảnh không được vượt quá 2MB !!',
            'course_description.required' => 'Mô tả khóa học không được để trống !!',
            // 'category_id.required' => 'Danh mục là bắt buộc !!'
        ];

        $request->validate([
            'course_name' => 'required|string|max:255',
            'course_img' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'course_description' => 'required|string',
            // 'category_id' => 'required|exists:categories,id',
        ], $messages);

        // Xử lý hình ảnh
        if ($request->hasFile('course_img')) {
            $image = $request->file('course_img');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/courses'), $imageName);
        }

        // Thêm mới khóa học
        Courses::create([
            'course_name' => $request->input('course_name'),
            'course_img' => $imageName,
            'course_description' => $request->input('course_description'),
            'category_id' => $request->input('category_id'),
        ]);

        return redirect()->route('admin.createcourse')->with('success', 'Thêm khóa học thành công');
    }

    // Hiển thị form sửa khóa học
    public function editCourseForm(Courses $course) {
        $cates = Category::orderBy('category_id')->paginate(25);
        return view('admin.course.edit', compact('course', 'cates'));
    }

    // Sửa khóa học
    public function update(Request $request, Courses $course) {
        $messages = [
            'course_name.required' => 'Tên khóa học không được để trống !!',
            'course_name.max' => 'Tên khóa học không được vượt quá 255 ký tự !!',
            'course_img.image' => 'Hình ảnh không phải là tệp hình ảnh !!',
            'course_img.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, svg !!',
            'course_img.max' => 'Dung lượng hình ảnh không được vượt quá 2MB !!',
            'course_description.required' => 'Mô tả khóa học không được để trống !!',
        ];

        $data = $request->validate([
            'course_name' => 'required|string|max:255',
            'course_img' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'course_description' => 'required|string',
            'category_id' => 'required|exists:categories,category_id',
        ], $messages);

        if ($request->hasFile('course_img')) {
            $image = $request->file('course_img');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/courses'), $imageName);
            $data['course_img'] = $imageName;
        }

        $course->update($data);

        return redirect()->route('admin.editcourse', $course->course_id)->with('success', 'Sửa khóa học thành công');;
    }

    // Xóa khóa học
    public function destroy(Courses $course) {
        $course->delete();
        return redirect()->route('admin.course');
    }
    
}
