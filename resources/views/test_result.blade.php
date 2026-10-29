@extends('admin')

@section('title', 'Kết quả kiểm tra')

@section('main')

<div class="mb-6">
    <p class="text-indigo-600 text-xs font-semibold uppercase tracking-wider">Kết quả</p>
    <h2 class="text-2xl font-bold text-slate-800 mt-1">Kết quả kiểm tra: {{ $lesson->lesson_name }}</h2>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 lg:p-8">
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 p-4 rounded-xl mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-6 bg-indigo-50 border border-indigo-100 text-indigo-800 p-4 rounded-xl">
        {{ $message }}
    </div>

    @foreach ($results as $key => $result)
        <fieldset id="group-{{ $key }}">
            <div class="mb-6 p-5 border border-slate-100 rounded-2xl bg-slate-50/70">
                <div class="flex items-start text-slate-800">
                    <p class="text-md font-semibold mr-1">
                        {{ $key + 1 }}.
                    </p>
                    {!! $result['question']->question_name !!}
                    <p class="ml-1">?</p>
                </div>
                <div class="ml-4 mt-3 space-y-2">
                    @foreach (['a', 'b', 'c', 'd'] as $option)
                        @php
                            $optionValue = 'question_' . $option;
                            $isCorrectAnswer = $result['question']->$optionValue === $result['question']->question_correct;
                            $isUserAnswer = $result['user_answer'] === $result['question']->$optionValue;
                            $answerClass = $isCorrectAnswer ? 'bg-emerald-100 border-emerald-200' : ($isUserAnswer && !$isCorrectAnswer ? 'bg-red-100 border-red-200' : 'bg-white border-slate-100');
                        @endphp
                        <div class="form-check rounded-xl border px-3 py-2 {{ $answerClass }}">
                            <input disabled name="group-{{ $key }}" value="{{ $result['question']->$optionValue }}" class="form-radio h-4 w-4 text-indigo-600" type="radio" {{ $isUserAnswer ? 'checked' : '' }}>
                            <label class="ml-2" for="answer_{{ $result['question']->id }}_{{ $option }}">{{ $result['question']->$optionValue }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
        </fieldset>
    @endforeach
</div>

@stop
