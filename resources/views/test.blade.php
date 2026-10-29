@extends('admin')

@section('title', 'Câu hỏi củng cố')

@section('main')

<div class="mb-6">
    <p class="text-indigo-600 text-xs font-semibold uppercase tracking-wider">Bài kiểm tra</p>
    <h2 class="text-2xl font-bold text-slate-800 mt-1">Câu hỏi củng cố: {{ $lesson->lesson_name }}</h2>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 lg:p-8">
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 p-4 rounded-xl mb-6">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('home.test.submit', $lesson->lesson_id) }}" method="post">
        @csrf
        @foreach ($questions as $key => $question)
            @php
                $result = collect(session('resultDetails'))->firstWhere('question_id', $question->question_id);
            @endphp
            <fieldset id="group-{{ $key }}" class="mb-6 p-5 border border-slate-100 rounded-2xl bg-slate-50/70">
                <div class="flex items-start text-slate-800">
                    <p class="text-md font-semibold mr-1">
                        {{ $key + 1 }}.
                    </p>
                    {!! $question->question_name !!}
                    <p class="ml-1">?</p>
                </div>
                <div class="ml-4 mt-3 space-y-2">
                    @foreach (['a', 'b', 'c', 'd'] as $option)
                        <div class="form-check">
                            <label class="flex items-center gap-2 rounded-xl bg-white border border-slate-100 px-3 py-2 cursor-pointer hover:border-indigo-200 @if(session('resultDetails')) {{ $result['userAnswer'] === $question->{'question_' . $option} ? ($result['isCorrect'] ? 'text-emerald-600 border-emerald-200 bg-emerald-50' : 'text-red-500 border-red-200 bg-red-50') : ($question->{'question_' . $option} === $result['correctAnswer'] ? 'text-emerald-600 border-emerald-200 bg-emerald-50' : '') }} @endif" for="answer_{{ $question->id }}_{{ $option }}">
                                <input id="answer_{{ $question->id }}_{{ $option }}" name="group-{{ $key }}" value="{{ $question->{'question_' . $option} }}" class="form-radio h-4 w-4 text-indigo-600" type="radio" @if(session('resultDetails') && $result['userAnswer'] === $question->{'question_' . $option}) checked @endif>
                                <span>{{ $question->{'question_' . $option} }}</span>
                            </label>
                        </div>
                    @endforeach
                    
                    @if($result && !$result['isCorrect'])
                        <div class="mt-2 text-emerald-600 text-sm font-medium">
                            <p>Đáp án đúng: {{ $result['correctAnswer'] }}</p>
                        </div>
                    @endif
                </div>
            </fieldset>
        @endforeach
        @if (session('success'))
            <div class="flex items-center justify-end">
                <a href="{{route('home.test', $question->lesson_id)}}" class="bg-slate-500 hover:bg-slate-700 text-white font-semibold py-2.5 px-5 rounded-xl transition-colors">
                    Làm lại
                </a>
            </div>
        @else
            <div class="flex items-center justify-end">
                <button type="submit" class="bg-indigo-500 hover:bg-indigo-600 text-white font-semibold py-2.5 px-5 rounded-xl transition-colors">
                    Nộp bài
                </button>
            </div>
        @endif
    </form>
</div>

@stop
