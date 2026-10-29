<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Courses;
use App\Models\Lesson;
use App\Models\RegisterCourses;
use App\Models\Questions;
use Illuminate\Support\Facades\DB;

use Auth;

class HomeController extends Controller
{
    public function index() {
        $cates = Category::with('courses')->orderBy('category_id', 'ASC')->paginate(10);

        return view('index', compact('cates'));
    }

    public function showCourse($course_id) {
        // Lấy khóa học hiện tại theo ID
        $course = Courses::findOrFail($course_id);

        // Lấy các khóa học cùng danh mục nhưng không bao gồm khóa học hiện tại
        $relatedCourses = Courses::where('category_id', $course->category_id)
                                ->where('course_id', '!=', $course_id)
                                ->inRandomOrder()
                                ->take(4)
                                ->get();
                            
        $rc_status = RegisterCourses::where('course_id', $course_id)
                                ->where('user_id', Auth::id())
                                ->value('rc_status'); // Lấy rc_status cho người dùng hiện tại   
                                
        // Tính tổng thời gian của tất cả các bài học trong khóa học
        $totalDuration = Lesson::where('course_id', $course_id)
                           ->select(DB::raw('SEC_TO_TIME(SUM(TIME_TO_SEC(lesson_duration))) AS total_duration'))
                           ->value('total_duration');

        // Trả về view course và truyền dữ liệu khóa học cùng khóa học liên quan
        return view('course', compact('course', 'relatedCourses', 'rc_status', 'totalDuration'));
    }

    public function showCourseDetail($course_id) {
        // Lấy tất cả các bài học của khóa học cụ thể
        $lessons = Lesson::where('course_id', $course_id)->paginate(10);

        // Lấy thông tin của khóa học
        $course = Courses::findOrFail($course_id);
        // Trả về view course và truyền dữ liệu khóa học cùng khóa học liên quan
        return view('course_detail', compact('lessons', 'course'));
    }

    public function registerCourse(Request $request) {
        // Lấy user_id của người dùng đang đăng nhập
        $userId = Auth::id();

        // Tạo mới bản ghi
        RegisterCourses::create([
            'rc_status' => $request->input('rc_status'),
            'course_id' => $request->input('course_id'),
            'user_id' => $userId,
        ]);

        // Chuyển hướng đến route GET với tham số course_id
        return redirect()->route('home.course', ['course_id' => $request->input('course_id')]);
    }

    public function showTest($lesson_id) {
        // Lấy danh sách câu hỏi theo lesson_id
        $questions = Questions::where('lesson_id', $lesson_id)->paginate(10);

        $lesson = Lesson::findOrFail($lesson_id);

        // Trả về view với danh sách câu hỏi
        return view('test', compact('questions', 'lesson'));
    }

    public function submitTest(Request $request, $lesson_id)
    {
        $correctAnswers = 0;
        $questions = Questions::where('lesson_id', $lesson_id)->get();
        $totalQuestions = $questions->count();
        $resultDetails = [];

        if ($totalQuestions > 0) {
            foreach ($questions as $key => $question) {
                // Lấy tên input của câu hỏi
                $answerKey = 'group-' . $key;
                $userAnswer = $request->input($answerKey, null);
                
                // Kiểm tra nếu người dùng đã chọn câu trả lời cho câu hỏi này
                $isCorrect = $userAnswer === $question->question_correct;
                if ($isCorrect) {
                    $correctAnswers++;
                }

                // Lưu trữ chi tiết kết quả
                $resultDetails[] = [
                    'question_id' => $question->question_id,
                    'userAnswer' => $userAnswer,
                    'correctAnswer' => $question->question_correct,
                    'isCorrect' => $isCorrect
                ];
            }

            $message = 'Bài kiểm tra đã được nộp. Số câu làm đúng của bạn là: ' . $correctAnswers . '/' . $totalQuestions . ' câu';
        } else {
            $message = 'Không có câu hỏi nào trong bài kiểm tra này.';
        }

        // Chuyển các biến ra view
        return redirect()->route('home.test', $lesson_id)->with([
            'success' => $message,
            'resultDetails' => $resultDetails
        ]);
    }
    
    public function searchCourses(Request $request) {
        $query = $request->input('query');
        $courses = Courses::where('course_name', 'LIKE', "%{$query}%")->get();

        return response()->json($courses);
    }

    public function searchResults(Request $request) {
        $query = $request->input('query');
        $courses = Courses::where('course_name', 'LIKE', "%{$query}%")->get();

        return view('search_result', compact('courses', 'query'));
    }
 
}
