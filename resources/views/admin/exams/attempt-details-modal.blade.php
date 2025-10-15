{{-- resources/views/exams/attempt-details-modal.blade.php --}}
<div class="container-fluid">
    <div class="row">
        <!-- Student Information -->
        <div class="col-md-6">
            <h6>{{ __('messages.student_information') }}</h6>
            <table class="table table-sm table-bordered">
                <tr>
                    <th width="35%">{{ __('messages.name') }}</th>
                    <td>{{ $attempt->user->name }}</td>
                </tr>
                <tr>
                    <th>{{ __('messages.phone') }}</th>
                    <td>{{ $attempt->user->phone }}</td>
                </tr>
                <tr>
                    <th>{{ __('messages.school') }}</th>
                    <td>{{ $attempt->user->school_name }}</td>
                </tr>
                <tr>
                    <th>{{ __('messages.field') }}</th>
                    <td>{{ $attempt->user->field->name ?? __('messages.no_field') }}</td>
                </tr>
            </table>
        </div>

        <!-- Attempt Information -->
        <div class="col-md-6">
            <h6>{{ __('messages.attempt_information') }}</h6>
            <table class="table table-sm table-bordered">
                <tr>
                    <th width="35%">{{ __('messages.status') }}</th>
                    <td>
                        @switch($attempt->status)
                            @case('completed')
                                <span class="badge bg-success">{{ __('messages.completed') }}</span>
                                @break
                            @case('in_progress')
                                <span class="badge bg-warning">{{ __('messages.in_progress') }}</span>
                                @break
                            @case('abandoned')
                                <span class="badge bg-secondary">{{ __('messages.abandoned') }}</span>
                                @break
                            @case('time_up')
                                <span class="badge bg-danger">{{ __('messages.time_up') }}</span>
                                @break
                        @endswitch
                    </td>
                </tr>
                <tr>
                    <th>{{ __('messages.started_at') }}</th>
                    <td>{{ $attempt->started_at->format('Y-m-d H:i:s') }}</td>
                </tr>
                <tr>
                    <th>{{ __('messages.submitted_at') }}</th>
                    <td>
                        @if($attempt->submitted_at)
                            {{ $attempt->submitted_at->format('Y-m-d H:i:s') }}
                        @else
                            <span class="text-muted">{{ __('messages.not_submitted') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>{{ __('messages.duration') }}</th>
                    <td>
                        @if($attempt->submitted_at)
                            @php
                                $duration = $attempt->started_at->diffInMinutes($attempt->submitted_at);
                            @endphp
                            {{ floor($duration / 60) }}h {{ $duration % 60 }}m
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Scores and Results -->
    @if($attempt->status === 'completed')
        <div class="row mt-3">
            <div class="col-md-12">
                <h6>{{ __('messages.exam_results') }}</h6>
                <div class="row">
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="text-primary">{{ number_format($attempt->score, 2) }}</h5>
                                <small class="text-muted">{{ __('messages.total_score') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="text-info">{{ number_format($attempt->percentage, 1) }}%</h5>
                                <small class="text-muted">{{ __('messages.percentage') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="text-secondary">{{ $attempt->exam->pass_grade }}</h5>
                                <small class="text-muted">{{ __('messages.pass_grade') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                @if($attempt->score >= $attempt->exam->pass_grade)
                                    <h5 class="text-success">{{ __('messages.passed') }}</h5>
                                @else
                                    <h5 class="text-danger">{{ __('messages.failed') }}</h5>
                                @endif
                                <small class="text-muted">{{ __('messages.result') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Question-by-Question Breakdown -->
        <div class="row mt-3">
            <div class="col-md-12">
                <h6>{{ __('messages.question_breakdown') }}</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-striped">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="15%">{{ __('messages.type') }}</th>
                                <th width="8%">{{ __('messages.grade') }}</th>
                                <th width="10%">{{ __('messages.awarded') }}</th>
                                <th width="12%">{{ __('messages.status') }}</th>
                                <th width="50%">{{ __('messages.user_answer') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attempt->exam->questions->sortBy('order') as $question)
                                @php
                                    $answer = $attempt->questionAnswers->where('question_id', $question->id)->first();
                                @endphp
                                <tr>
                                    <td>{{ $question->order }}</td>
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
                                    <td>{{ $question->grade }}</td>
                                    <td>
                                        @if($answer)
                                            <strong class="{{ $answer->is_correct ? 'text-success' : 'text-danger' }}">
                                                {{ number_format($answer->awarded_grade, 2) }}
                                            </strong>
                                        @else
                                            <span class="text-muted">0</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($answer)
                                            @if($answer->is_correct === true)
                                                <span class="badge bg-success">{{ __('messages.correct') }}</span>
                                            @elseif($answer->is_correct === false)
                                                <span class="badge bg-danger">{{ __('messages.incorrect') }}</span>
                                            @elseif($question->type === 'essay')
                                                <span class="badge bg-warning">{{ __('messages.manual_grading') }}</span>
                                            @else
                                                <span class="badge bg-secondary">{{ __('messages.unanswered') }}</span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">{{ __('messages.unanswered') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($answer && $answer->user_answer)
                                            @if($question->type === 'multiple_choice')
                                                @php
                                                    $selectedOption = is_array($answer->user_answer) ? $answer->user_answer[0] : $answer->user_answer;
                                                    $optionText = isset($question->options[$selectedOption]) ? $question->options[$selectedOption] : $selectedOption;
                                                @endphp
                                                <strong>{{ chr(65 + $selectedOption) }}.</strong> {{ $optionText }}
                                            @elseif($question->type === 'true_false')
                                                <strong>{{ ucfirst($answer->user_answer) }}</strong>
                                            @elseif($question->type === 'essay')
                                                <div class="text-truncate" title="{{ $answer->user_answer }}">
                                                    {{ Str::limit($answer->user_answer, 100) }}
                                                </div>
                                                @if($answer->feedback)
                                                    <small class="text-info">
                                                        <i class="fas fa-comment"></i> {{ __('messages.feedback_available') }}
                                                    </small>
                                                @endif
                                            @else
                                                {{ $answer->user_answer }}
                                            @endif
                                        @else
                                            <span class="text-muted">{{ __('messages.not_answered') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- Notes Section -->
    @if($attempt->notes)
        <div class="row mt-3">
            <div class="col-md-12">
                <h6>{{ __('messages.admin_notes') }}</h6>
                <div class="alert alert-info">
                    {!! nl2br(e($attempt->notes)) !!}
                </div>
            </div>
        </div>
    @endif

    <!-- Action Buttons -->
    <div class="row mt-3">
        <div class="col-md-12 text-end">
            @if($attempt->status === 'completed')
                <a href="{{ route('exam.results', $attempt) }}" class="btn btn-primary btn-sm" target="_blank">
                    <i class="fas fa-external-link-alt"></i> {{ __('messages.view_full_results') }}
                </a>
            @endif
            
            @if($attempt->exam->questions()->where('type', 'essay')->exists())
                <a href="{{ route('exam.grade-essays', $attempt) }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-pen"></i> {{ __('messages.grade_essays') }}
                </a>
            @endif
            
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                {{ __('messages.close') }}
            </button>
        </div>
    </div>
</div>