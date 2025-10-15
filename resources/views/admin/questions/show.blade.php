{{-- resources/views/questions/show.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>{{ __('messages.question_details') }}</h4>
                    <div>
                        <a href="{{ route('questions.edit', [$exam, $question]) }}" class="btn btn-warning btn-sm">
                            {{ __('messages.edit') }}
                        </a>
                        <a href="{{ route('questions.index', $exam) }}" class="btn btn-secondary btn-sm">
                            {{ __('messages.back') }}
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <!-- Question Information -->
                        <div class="col-md-8">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="25%">{{ __('messages.exam') }}</th>
                                    <td>
                                        <a href="{{ route('exams.show', $exam) }}">{{ $exam->name }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.question_type') }}</th>
                                    <td>
                                        @switch($question->type)
                                            @case('multiple_choice')
                                                <span class="badge bg-info">{{ __('messages.multiple_choice') }}</span>
                                                @break
                                            @case('true_false')
                                                <span class="badge bg-success">{{ __('messages.true_false') }}</span>
                                                @break
                                            @case('essay')
                                                <span class="badge bg-warning">{{ __('messages.essay') }}</span>
                                                @break
                                            @case('fill_blank')
                                                <span class="badge bg-secondary">{{ __('messages.fill_blank') }}</span>
                                                @break
                                        @endswitch
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.grade') }}</th>
                                    <td>{{ $question->grade }} {{ __('messages.points') }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.order') }}</th>
                                    <td>{{ $question->order }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.required') }}</th>
                                    <td>
                                        @if($question->is_required)
                                            <span class="badge bg-danger">{{ __('messages.required') }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ __('messages.optional') }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.created_at') }}</th>
                                    <td>{{ $question->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>

                        <!-- Question Statistics (if needed) -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6>{{ __('messages.question_statistics') }}</h6>
                                </div>
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <h4 class="text-primary">{{ $question->answers->count() }}</h4>
                                        <small>{{ __('messages.total_answers') }}</small>
                                    </div>
                                    @if($question->type !== 'essay')
                                        <div class="mb-3">
                                            <h4 class="text-success">{{ $question->answers->where('is_correct', true)->count() }}</h4>
                                            <small>{{ __('messages.correct_answers') }}</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Question Content -->
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ __('messages.question_content') }}</h5>
                        </div>
                        <div class="card-body">
                            <!-- Question Text -->
                            <div class="mb-3">
                                <h6>{{ __('messages.question_text') }}:</h6>
                                <div class="p-3 bg-light rounded">
                                    {!! nl2br(e($question->question_text)) !!}
                                </div>
                            </div>

                            <!-- Question Image -->
                            @if($question->question_image)
                                <div class="mb-3">
                                    <h6>{{ __('messages.question_image') }}:</h6>
                                    <div class="text-center">
                                        <img src="{{ asset('assets/admin/uploads/' . $question->question_image) }}" 
                                             alt="Question Image" class="img-fluid rounded border" style="max-height: 400px;">
                                    </div>
                                </div>
                            @endif

                            <!-- Answer Options/Correct Answers -->
                            @if($question->type === 'multiple_choice')
                                <div class="mb-3">
                                    <h6>{{ __('messages.answer_options') }}:</h6>
                                    <div class="list-group">
                                        @foreach($question->options as $index => $option)
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <span>{{ chr(65 + $index) }}. {{ $option }}</span>
                                                @if(in_array($index, $question->correct_answers ?? []))
                                                    <span class="badge bg-success">{{ __('messages.correct') }}</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @elseif($question->type === 'true_false')
                                <div class="mb-3">
                                    <h6>{{ __('messages.correct_answer') }}:</h6>
                                    <div class="p-3 bg-light rounded">
                                        @if(isset($question->correct_answers[0]))
                                            @if($question->correct_answers[0] === 'true')
                                                <span class="badge bg-success fs-6">{{ __('messages.true') }}</span>
                                            @else
                                                <span class="badge bg-danger fs-6">{{ __('messages.false') }}</span>
                                            @endif
                                        @else
                                            <span class="text-muted">{{ __('messages.no_correct_answer_set') }}</span>
                                        @endif
                                    </div>
                                </div>
                            @elseif($question->type === 'essay')
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    {{ __('messages.essay_question_manual_grading') }}
                                </div>
                            @elseif($question->type === 'fill_blank')
                                @if($question->correct_answers)
                                    <div class="mb-3">
                                        <h6>{{ __('messages.correct_answers') }}:</h6>
                                        <div class="p-3 bg-light rounded">
                                            @foreach($question->correct_answers as $answer)
                                                <span class="badge bg-success me-2">{{ $answer }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endif

                            <!-- Explanation -->
                            @if($question->explanation)
                                <div class="mb-3">
                                    <h6>{{ __('messages.explanation') }}:</h6>
                                    <div class="p-3 bg-info bg-opacity-10 rounded border-start border-info border-4">
                                        {!! nl2br(e($question->explanation)) !!}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Navigation -->
                    <div class="mt-4">
                        <div class="row">
                            <div class="col-md-6">
                                @php
                                    $prevQuestion = $exam->questions()
                                        ->where('order', '<', $question->order)
                                        ->orderBy('order', 'desc')
                                        ->first();
                                @endphp
                                @if($prevQuestion)
                                    <a href="{{ route('questions.show', [$exam, $prevQuestion]) }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left"></i> {{ __('messages.previous_question') }}
                                    </a>
                                @endif
                            </div>
                            <div class="col-md-6 text-end">
                                @php
                                    $nextQuestion = $exam->questions()
                                        ->where('order', '>', $question->order)
                                        ->orderBy('order', 'asc')
                                        ->first();
                                @endphp
                                @if($nextQuestion)
                                    <a href="{{ route('questions.show', [$exam, $nextQuestion]) }}" class="btn btn-outline-secondary">
                                        {{ __('messages.next_question') }} <i class="fas fa-arrow-right"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection