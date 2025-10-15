@extends('layouts.admin')

@section('title', __('messages.exam_questions'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title mb-0">{{ __('messages.exam_questions') }}</h3>
                            <p class="text-muted mb-0">
                                {{ __('messages.exam') }}: {{ app()->getLocale() == 'ar' ? $exam->title_ar : $exam->title_en }}
                            </p>
                        </div>
                        <div class="btn-group">
                            <a href="{{ route('exams.exam_questions.create', $exam) }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> {{ __('messages.add_question') }}
                            </a>
                            <a href="{{ route('exams.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> {{ __('messages.back_to_exams') }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Exam Info -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-question"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ __('messages.total_questions') }}</span>
                                    <span class="info-box-number">{{ $exam->questions->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-star"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ __('messages.total_grade') }}</span>
                                    <span class="info-box-number">{{ number_format($exam->total_grade, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-percentage"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ __('messages.passing_grade') }}</span>
                                    <span class="info-box-number">{{ number_format($exam->passing_grade, 2) }}%</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary"><i class="fas fa-clock"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ __('messages.duration') }}</span>
                                    <span class="info-box-number">
                                        {{ $exam->duration_minutes ? $exam->duration_minutes . ' ' . __('messages.minutes') : __('messages.unlimited') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filters -->
                    <form method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <select name="type" class="form-control">
                                    <option value="">{{ __('messages.all_types') }}</option>
                                    <option value="multiple_choice" {{ request('type') == 'multiple_choice' ? 'selected' : '' }}>
                                        {{ __('messages.multiple_choice') }}
                                    </option>
                                    <option value="true_false" {{ request('type') == 'true_false' ? 'selected' : '' }}>
                                        {{ __('messages.true_false') }}
                                    </option>
                                    <option value="essay" {{ request('type') == 'essay' ? 'selected' : '' }}>
                                        {{ __('messages.essay') }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="{{ __('messages.search_questions') }}" 
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fas fa-search"></i> {{ __('messages.filter') }}
                                </button>
                                <a href="{{ route('exams.exam_questions.index', $exam) }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times"></i> {{ __('messages.clear') }}
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Questions Table -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.order') }}</th>
                                    <th>{{ __('messages.title') }}</th>
                                    <th>{{ __('messages.question') }}</th>
                                    <th>{{ __('messages.type') }}</th>
                                    <th>{{ __('messages.grade') }}</th>
                                    <th>{{ __('messages.created_at') }}</th>
                                    <th>{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($questions as $question)
                                    <tr>
                                        <td>
                                            <span class="badge bg-secondary">{{ $question->pivot->order }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ app()->getLocale() == 'ar' ? $question->title_ar : $question->title_en }}</strong>
                                        </td>
                                        <td>
                                            <p class="mb-0">
                                                {{ Str::limit(app()->getLocale() == 'ar' ? $question->question_ar : $question->question_en, 100) }}
                                            </p>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $question->type)) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">{{ number_format($question->pivot->grade, 2) }}</span>
                                        </td>
                                        <td>{{ $question->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('exams.exam_questions.show', [$exam, $question]) }}" 
                                                   class="btn btn-sm btn-info"
                                                   title="{{ __('messages.view') }}">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                <a href="{{ route('exams.exam_questions.edit', [$exam, $question]) }}" 
                                                   class="btn btn-sm btn-warning"
                                                   title="{{ __('messages.edit') }}">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                <form action="{{ route('exams.exam_questions.destroy', [$exam, $question]) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('{{ __('messages.confirm_delete_question') }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-danger"
                                                            title="{{ __('messages.delete') }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <div class="py-4">
                                                <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">{{ __('messages.no_questions_found') }}</h5>
                                                <p class="text-muted">{{ __('messages.start_adding_questions_to_exam') }}</p>
                                                <a href="{{ route('exams.exam_questions.create', $exam) }}" class="btn btn-primary">
                                                    <i class="fas fa-plus"></i> {{ __('messages.add_first_question') }}
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($questions->hasPages())
                    <div class="d-flex justify-content-center">
                        {{ $questions->appends(request()->query())->links() }}
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
</style>
@endsection