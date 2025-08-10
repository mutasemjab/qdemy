@extends('layouts.admin')

@section('title', __('messages.questions'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.questions') }}</h3>
                    @can('question-add')
                        <a href="{{ route('questions.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> {{ __('messages.add_question') }}
                        </a>
                    @endcan
                </div>

                <div class="card-body">
                

                    <!-- Filters -->
                    <form method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <select name="course_id" class="form-select">
                                    <option value="">{{ __('messages.all_courses') }}</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}" 
                                                {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                            {{ $course->title_en }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="type" class="form-select">
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
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="{{ __('messages.search_questions') }}" 
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fas fa-search"></i> {{ __('messages.filter') }}
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Questions Table -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('messages.title') }}</th>
                                    <th>{{ __('messages.course') }}</th>
                                    <th>{{ __('messages.type') }}</th>
                                    <th>{{ __('messages.grade') }}</th>
                                    <th>{{ __('messages.created_by') }}</th>
                                    <th>{{ __('messages.created_at') }}</th>
                                    <th>{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($questions as $question)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <strong>{{ $question->title_en }}</strong><br>
                                            <small class="text-muted">{{ $question->title_ar }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $question->course->title_en }}</span>
                                        </td>
                                        <td>
                                            @if($question->type === 'multiple_choice')
                                                <span class="badge bg-primary">
                                                    <i class="fas fa-list"></i> {{ __('messages.multiple_choice') }}
                                                </span>
                                            @elseif($question->type === 'true_false')
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-circle"></i> {{ __('messages.true_false') }}
                                                </span>
                                            @else
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-edit"></i> {{ __('messages.essay') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $question->grade }}</span>
                                        </td>
                                        <td>
                                            @if($question->creator)
                                                {{ $question->creator->name }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $question->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @can('question-table')
                                                    <a href="{{ route('questions.show', $question) }}" 
                                                       class="btn btn-sm btn-info"
                                                       title="{{ __('messages.view') }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                @endcan
                                                
                                                @can('question-edit')
                                                    <a href="{{ route('questions.edit', $question) }}" 
                                                       class="btn btn-sm btn-warning"
                                                       title="{{ __('messages.edit') }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endcan
                                                
                                                @can('question-delete')
                                                    <form action="{{ route('questions.destroy', $question) }}" 
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
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            {{ __('messages.no_questions_found') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $questions->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection