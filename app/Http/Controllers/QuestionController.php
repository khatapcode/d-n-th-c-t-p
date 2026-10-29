<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Lesson;
use App\Models\Questions;

class QuestionController extends Controller
{
    public function showQuestion($lesson_id) {
        // Lấy tất cả các bài học của khóa học cụ thể
        $questions = Questions::where('lesson_id', $lesson_id)->paginate(5);

        // Lấy thông tin của khóa học
        $lessons = Lesson::findOrFail($lesson_id);

        return view('admin.course.lesson.question.index', compact('questions', 'lessons'));
    }

    public function createQuesForm(Lesson $lesson) {
        return view('admin.course.lesson.question.create', compact('lesson'));
    }

    public function store(Request $request, Lesson $lesson) {
        $messages = [
            'question_name.required' => 'Nội dung câu hỏi không được để trống !!',
            'question_a.required' => 'Câu trả lời 1 không được để trống !!',
            'question_b.required' => 'Câu trả lời 2 không được để trống !!',
            'question_c.required' => 'Câu trả lời 3 không được để trống !!',
            'question_d.required' => 'Câu trả lời 4 không được để trống !!',
        ];

        $request->validate([
            'question_name' => 'required|string',
            'question_a' => 'required|string',
            'question_b' => 'required|string',
            'question_c' => 'required|string',
            'question_d' => 'required|string',
        ], $messages);

        Questions::create([
            'question_name' => $request->input('question_name'),
            'question_a' => $request->input('question_a'),
            'question_b' => $request->input('question_b'),
            'question_c' => $request->input('question_c'),
            'question_d' => $request->input('question_d'),
            'question_correct' => $request->input('question_correct'),
            'lesson_id' => $request->input('lesson_id'),
        ]);

        return redirect()->route('admin.createquestion', ['lesson' => $lesson->lesson_id])->with('success', 'Thêm câu hỏi thành công');
    }

    public function editQuessForm(Questions $question) {
        return view('admin.course.lesson.question.edit', compact('question'));
    }

    public function update(Request $request, Questions $question) {
        $messages = [
            'question_name.required' => 'Nội dung câu hỏi không được để trống !!',
            'question_a.required' => 'Câu trả lời 1 không được để trống !!',
            'question_b.required' => 'Câu trả lời 2 không được để trống !!',
            'question_c.required' => 'Câu trả lời 3 không được để trống !!',
            'question_d.required' => 'Câu trả lời 4 không được để trống !!',
        ];

        $data = $request->validate([
            'question_name' => 'required|string',
            'question_a' => 'required|string',
            'question_b' => 'required|string',
            'question_c' => 'required|string',
            'question_d' => 'required|string',
            'question_correct' => 'required|string',
        ], $messages);

        $question->update($data);

        return redirect()->route('admin.editquestion', ['question' => $question->question_id])->with('success', 'Sửa câu hỏi thành công');;
    }

    public function destroy(Questions $question, Lesson $lesson) {
        $question->delete();
        return redirect()->route('admin.question', ['lesson' => $question->lesson_id]);
    }

}