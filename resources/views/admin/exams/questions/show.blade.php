@extends('layouts.admin')

@section('title', __('messages.view_exam_question'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title">{{ __('messages.view_exam_question') }}</h3>
                            <p class="text-muted mb-0">
                                {{ __('messages.exam') }}: {{ app()->getLocale() == 'ar' ? $exam->title_ar : $exam->title_en }}
                            </p>
                        </div>
                        <div class="btn-group">
                            <a href="{{ route('exams.exam_questions.index', $exam) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
                            </a>
                            <a href="{{ route('exams.exam_questions.edit', [$exam, $question]) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> {{ __('messages.edit') }}
                            </a>
                            <form action="{{ route('exams.exam_questions.destroy', [$exam, $question]) }}" 
                                  method="POST" 
                                  class="d-inline"
                                  onsubmit="return confirm('{{ __('messages.confirm_delete_question') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash"></i> {{ __('messages.delete') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Question Info Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary"><i class="fas fa-hashtag"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ __('messages.order_in_exam') }}</span>
                                    <span class="info-box-number">{{ $examQuestion->pivot->order ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-star"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ __('messages.grade_in_exam') }}</span>
                                    <span class="info-box-number">{{ number_format($examQuestion->pivot->grade ?? $question->grade, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-question-circle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ __('messages.question_type') }}</span>
                                    <span class="info-box-number">{{ ucfirst(str_replace('_', ' ', $question->type)) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-clock"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ __('messages.created_at') }}</span>
                                    <span class="info-box-number">{{ $question->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Question Details -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">{{ __('messages.question_details') }}</h5>
                                </div>
                                <div class="card-body">
                                    <!-- Question Titles -->
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label font-weight-bold">{{ __('messages.title_en') }}</label>
                                                <div class="bg-light p-3 rounded">{{ $question->title_en }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label font-weight-bold">{{ __('messages.title_ar') }}</label>
                                                <div class="bg-light p-3 rounded" dir="rtl">{{ $question->title_ar }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Question Text -->
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label font-weight-bold">{{ __('messages.question_en') }}</label>
                                                <div class="bg-light p-3 rounded" style="min-height: 100px;">
                                                    {!! nl2br(e($question->question_en)) !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label font-weight-bold">{{ __('messages.question_ar') }}</label>
                                                <div class="bg-light p-3 rounded" style="min-height: 100px;" dir="rtl">
                                                    {!! nl2br(e($question->question_ar)) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Additional Info -->
                                    <div class="row mb-4">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label font-weight-bold">{{ __('messages.course') }}</label>
                                                <div class="bg-light p-2 rounded">
                                                    @if($question->course)
                                                        <span class="badge badge-info">{{ app()->getLocale() == 'ar' ? $question->course->name_ar : $question->course->name_en }}</span>
                                                    @else
                                                        <span class="text-muted">{{ __('messages.no_course_assigned') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label font-weight-bold">{{ __('messages.default_grade') }}</label>
                                                <div class="bg-light p-2 rounded">
                                                    <span class="badge badge-success">{{ number_format($question->grade, 2) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label font-weight-bold">{{ __('messages.created_by') }}</label>
                                                <div class="bg-light p-2 rounded">
                                                    @if($question->creator)
                                                        <span class="badge badge-secondary">{{ $question->creator->name }}</span>
                                                    @elseif($question->created_by_admin)
                                                        <span class="badge badge-primary">{{ __('messages.admin') }}</span>
                                                    @else
                                                        <span class="text-muted">{{ __('messages.unknown') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Answer Options/Details -->
                    @if($question->type === 'multiple_choice' && $question->options->count() > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">{{ __('messages.answer_options') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach($question->options->sortBy('order') as $option)
                                        <div class="col-md-6 mb-3">
                                            <div class="option-display p-3 border rounded {{ $option->is_correct ? 'border-success bg-light-success' : '' }}">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-2">
                                                            <span class="badge badge-secondary">{{ chr(65 + $loop->index) }}</span>
                                                            @if($option->is_correct)
                                                                <span class="badge badge-success ml-2">{{ __('messages.correct') }}</span>
                                                            @endif
                                                        </h6>
                                                        <div class="mb-2">
                                                            <strong>{{ __('messages.english') }}:</strong> {{ $option->option_en }}
                                                        </div>
                                                        <div dir="rtl">
                                                            <strong>{{ __('messages.arabic') }}:</strong> {{ $option->option_ar }}
                                                        </div>
                                                    </div>
                                                    @if($option->is_correct)
                                                        <i class="fas fa-check-circle text-success fa-lg"></i>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @elseif($question->type === 'true_false' && $question->options->count() > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">{{ __('messages.correct_answer') }}</h5>
                                </div>
                                <div class="card-body">
                                    @php
                                        $trueOption = $question->options->where('option_en', 'True')->first();
                                        $falseOption = $question->options->where('option_en', 'False')->first();
                                        $correctAnswer = $trueOption && $trueOption->is_correct ? 'true' : 'false';
                                    @endphp
                                    <div class="text-center">
                                        @if($correctAnswer === 'true')
                                            <span class="badge badge-success badge-lg p-3">
                                                <i class="fas fa-check-circle fa-2x"></i><br>
                                                {{ __('messages.true') }}
                                            </span>
                                        @else
                                            <span class="badge badge-danger badge-lg p-3">
                                                <i class="fas fa-times-circle fa-2x"></i><br>
                                                {{ __('messages.false') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @elseif($question->type === 'essay')
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">{{ __('messages.essay_question') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i>
                                        {{ __('messages.essay_question_note') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Explanations -->
                    @if($question->explanation_en || $question->explanation_ar)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">{{ __('messages.explanations') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @if($question->explanation_en)
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label font-weight-bold">{{ __('messages.explanation_en') }}</label>
                                                <div class="bg-light p-3 rounded">
                                                    {!! nl2br(e($question->explanation_en)) !!}
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @if($question->explanation_ar)
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label font-weight-bold">{{ __('messages.explanation_ar') }}</label>
                                                <div class="bg-light p-3 rounded" dir="rtl">
                                                    {!! nl2br(e($question->explanation_ar)) !!}
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.info-box {
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    background-color: #fff;
    padding: 15px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
}

.info-box-icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    color: white;
    font-size: 1.5rem;
    margin-right: 15px;
}

.info-box-content {
    flex: 1;
}

.info-box-text {
    display: block;
    font-size: 0.9rem;
    color: #6c757d;
    margin-bottom: 5px;
}

.info-box-number {
    display: block;
    font-size: 1.5rem;
    font-weight: bold;
    color: #495057;
}

.option-display {
    transition: all 0.3s ease;
}

.option-display:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.bg-light-success {
    background-color: #d4edda !important;
}

.badge-lg {
    font-size: 1.2rem;
    padding: 1rem;
}

.timeline {
    position: relative;
    margin: 0 0 30px 0;
    padding: 0;
    list-style: none;
}

.timeline:before {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    width: 4px;
    background: #ddd;
    left: 31px;
    margin: 0;
    border-radius: 2px;
}

.timeline > div {
    position: relative;
    margin: 0 0 15px 0;
}

.timeline > div > .timeline-item {
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
    border-radius: 3px;
    margin-top: 0;
    background: #fff;
    color: #444;
    margin-left: 60px;
    margin-right: 15px;
    padding: 0;
    position: relative;
}

.timeline > div > .fas,
.timeline > div > .far {
    width: 30px;
    height: 30px;
    font-size: 15px;
    line-height: 30px;
    position: absolute;
    color: #666;
    background: #d2d6de;
    border-radius: 50%;
    text-align: center;
    left: 18px;
    top: 0;
}

.timeline > div > .timeline-item > .time {
    color: #999;
    float: right;
    padding: 10px;
    font-size: 12px;
}

.timeline > div > .timeline-item > .timeline-header {
    margin: 0;
    color: #555;
    border-bottom: 1px solid #f4f4f4;
    padding: 10px;
    font-size: 16px;
    line-height: 1.1;
}

.timeline > div > .timeline-item > .timeline-body {
    padding: 10px;
}

.time-label {
    font-weight: 600;
    color: #fff;
    margin-bottom: 15px;
    padding: 5px 10px;
    background-color: #666;
    border-radius: 4px;
    display: inline-block;
}

.bg-green {
    background-color: #00a65a !important;
}

.bg-yellow {
    background-color: #f39c12 !important;
}

.bg-gray {
    background-color: #d2d6de !important;
}
</style>
@endsection