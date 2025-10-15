{{-- resources/views/exams/show.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>{{ __('messages.exam_details') }}: {{ $exam->name }}</h4>
                    <div>
                        <a href="{{ route('exams.edit', $exam) }}" class="btn btn-warning btn-sm">
                            {{ __('messages.edit') }}
                        </a>
                        <a href="{{ route('questions.index', $exam) }}" class="btn btn-primary btn-sm">
                            {{ __('messages.manage_questions') }}
                        </a>
                        <a href="{{ route('exams.attempts', $exam) }}" class="btn btn-info btn-sm">
                            {{ __('messages.view_attempts') }}
                        </a>
                        <a href="{{ route('exams.index') }}" class="btn btn-secondary btn-sm">
                            {{ __('messages.back') }}
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="row">
                        <!-- Exam Information -->
                        <div class="col-md-8">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="25%">{{ __('messages.exam_name') }}</th>
                                    <td>{{ $exam->name }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.description') }}</th>
                                    <td>{{ $exam->description ?? __('messages.no_description') }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.category') }}</th>
                                    <td>{{ $exam->category->name ?? __('messages.no_category') }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.duration') }}</th>
                                    <td>{{ $exam->duration_minutes }} {{ __('messages.minutes') }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.start_time') }}</th>
                                    <td>{{ $exam->start_time->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.end_time') }}</th>
                                    <td>{{ $exam->end_time->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.total_grade') }}</th>
                                    <td>{{ $exam->total_grade }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.pass_grade') }}</th>
                                    <td>{{ $exam->pass_grade }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.max_attempts') }}</th>
                                    <td>{{ $exam->max_attempts }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.status') }}</th>
                                    <td>
                                        @if($exam->isAvailable())
                                            <span class="badge bg-success">{{ __('messages.active') }}</span>
                                        @elseif($exam->start_time > now())
                                            <span class="badge bg-warning">{{ __('messages.upcoming') }}</span>
                                        @elseif($exam->end_time < now())
                                            <span class="badge bg-secondary">{{ __('messages.expired') }}</span>
                                        @else
                                            <span class="badge bg-danger">{{ __('messages.inactive') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <!-- Exam Statistics -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6>{{ __('messages.exam_statistics') }}</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-12 mb-3">
                                            <h4 class="text-primary">{{ $exam->questions->count() }}</h4>
                                            <small>{{ __('messages.total_questions') }}</small>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <h4 class="text-info">{{ $exam->attempts->count() }}</h4>
                                            <small>{{ __('messages.total_attempts') }}</small>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <h4 class="text-success">{{ $exam->attempts->where('status', 'completed')->count() }}</h4>
                                            <small>{{ __('messages.completed_attempts') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($exam->instructions)
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h6>{{ __('messages.exam_instructions') }}</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="instructions-content">
                                            @if(is_array($exam->instructions))
                                                @foreach($exam->instructions as $instruction)
                                                    <p>{{ $instruction }}</p>
                                                @endforeach
                                            @else
                                                {!! nl2br(e($exam->instructions)) !!}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Questions Section -->
                    <div class="mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>{{ __('messages.exam_questions') }} ({{ $exam->questions->count() }})</h5>
                            <a href="{{ route('questions.create', $exam) }}" class="btn btn-success btn-sm">
                                {{ __('messages.add_question') }}
                            </a>
                        </div>

                        @if($exam->questions->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th width="8%">{{ __('messages.order') }}</th>
                                            <th width="40%">{{ __('messages.question') }}</th>
                                            <th width="15%">{{ __('messages.type') }}</th>
                                            <th width="10%">{{ __('messages.grade') }}</th>
                                            <th width="10%">{{ __('messages.image') }}</th>
                                            <th width="17%">{{ __('messages.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($exam->questions as $question)
                                            <tr>
                                                <td>
                                                    <span class="badge bg-primary">{{ $question->order }}</span>
                                                </td>
                                                <td>
                                                    <div class="question-preview">
                                                        {{ Str::limit($question->question_text, 80) }}
                                                    </div>
                                                </td>
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
                                                    @if($question->question_image)
                                                        <i class="fas fa-image text-success" title="{{ __('messages.has_image') }}"></i>
                                                    @else
                                                        <i class="fas fa-times text-muted" title="{{ __('messages.no_image') }}"></i>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <a href="{{ route('questions.show', [$exam, $question]) }}" 
                                                           class="btn btn-info" title="{{ __('messages.view') }}">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('questions.edit', [$exam, $question]) }}" 
                                                           class="btn btn-warning" title="{{ __('messages.edit') }}">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('questions.destroy', [$exam, $question]) }}" 
                                                              method="POST" style="display: inline;">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="btn btn-danger" 
                                                                    title="{{ __('messages.delete') }}"
                                                                    onclick="return confirm('{{ __('messages.confirm_delete') }}')">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle"></i>
                                {{ __('messages.no_questions_found') }}
                                <br>
                                <a href="{{ route('questions.create', $exam) }}" class="btn btn-primary mt-2">
                                    {{ __('messages.add_first_question') }}
                                </a>
                            </div>
                        @endif
                    </div>

                
                </div>
            </div>
        </div>
    </div>
</div>
@endsection