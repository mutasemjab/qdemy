{{-- resources/views/questions/index.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4>{{ __('messages.exam_questions') }}: {{ $exam->name }}</h4>
                        <small class="text-muted">{{ __('messages.total_grade') }}: {{ $exam->total_grade }}</small>
                    </div>
                    <div>
                        <a href="{{ route('questions.create', $exam) }}" class="btn btn-primary">
                            {{ __('messages.add_question') }}
                        </a>
                        <a href="{{ route('exams.show', $exam) }}" class="btn btn-secondary">
                            {{ __('messages.back_to_exam') }}
                        </a>
                    </div>
                </div>

                <div class="card-body">
                  

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.order') }}</th>
                                    <th>{{ __('messages.question') }}</th>
                                    <th>{{ __('messages.type') }}</th>
                                    <th>{{ __('messages.grade') }}</th>
                                    <th>{{ __('messages.image') }}</th>
                                    <th>{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody id="questions-table">
                                @forelse($questions as $question)
                                    <tr data-id="{{ $question->id }}">
                                        <td>
                                            <span class="badge bg-primary">{{ $question->order }}</span>
                                        </td>
                                        <td>
                                            <div class="question-preview">
                                                {{ Str::limit($question->question_text, 100) }}
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
                                                <i class="fas fa-image text-success"></i>
                                            @else
                                                <i class="fas fa-times text-muted"></i>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('questions.show', [$exam, $question]) }}" class="btn btn-sm btn-info">
                                                    {{ __('messages.view') }}
                                                </a>
                                                <a href="{{ route('questions.edit', [$exam, $question]) }}" class="btn btn-sm btn-warning">
                                                    {{ __('messages.edit') }}
                                                </a>
                                                <form action="{{ route('questions.duplicate', [$exam, $question]) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-secondary">
                                                        {{ __('messages.duplicate') }}
                                                    </button>
                                                </form>
                                                <form action="{{ route('questions.destroy', [$exam, $question]) }}" method="POST" style="display: inline;">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" 
                                                            onclick="return confirm('{{ __('messages.confirm_delete') }}')">
                                                        {{ __('messages.delete') }}
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">{{ __('messages.no_questions_found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $questions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
