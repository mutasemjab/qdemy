@extends('layouts.admin')

@section('title', __('messages.exams'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.exams') }}</h3>
                    @can('exam-add')
                        <a href="{{ route('exams.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> {{ __('messages.add_exam') }}
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
                                <select name="status" class="form-select">
                                    <option value="">{{ __('messages.all_statuses') }}</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>
                                        {{ __('messages.active') }}
                                    </option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                                        {{ __('messages.inactive') }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="{{ __('messages.search_exams') }}" 
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fas fa-search"></i> {{ __('messages.filter') }}
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Exams Table -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('messages.title') }}</th>
                                    <th>{{ __('messages.course') }}</th>
                                    <th>{{ __('messages.questions') }}</th>
                                    <th>{{ __('messages.total_grade') }}</th>
                                    <th>{{ __('messages.duration') }}</th>
                                    <th>{{ __('messages.status') }}</th>
                                    <th>{{ __('messages.created_at') }}</th>
                                    <th>{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($exams as $exam)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <strong>{{ $exam->title_en }}</strong><br>
                                            <small class="text-muted">{{ $exam->title_ar }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $exam->course->title_en }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $exam->questions->count() }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">{{ $exam->total_grade }}</span>
                                        </td>
                                        <td>
                                            @if($exam->duration_minutes)
                                                {{ $exam->formatted_duration }}
                                            @else
                                                <span class="text-muted">{{ __('messages.unlimited') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($exam->is_active)
                                                <span class="badge bg-success">{{ __('messages.active') }}</span>
                                            @else
                                                <span class="badge bg-danger">{{ __('messages.inactive') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $exam->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @can('exam-table')
                                                    <a href="{{ route('exams.show', $exam) }}" 
                                                       class="btn btn-sm btn-info"
                                                       title="{{ __('messages.view') }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                @endcan

                                                @can('exam-edit')
                                                    <a href="{{ route('exams.questions.manage', $exam) }}" 
                                                       class="btn btn-sm btn-success"
                                                       title="{{ __('messages.manage_questions') }}">
                                                        <i class="fas fa-list"></i>
                                                    </a>
                                                @endcan

                                                @can('exam-table')
                                                    <a href="{{ route('exams.results', $exam) }}" 
                                                       class="btn btn-sm btn-primary"
                                                       title="{{ __('messages.results') }}">
                                                        <i class="fas fa-chart-bar"></i>
                                                    </a>
                                                @endcan
                                                
                                                @can('exam-edit')
                                                    <a href="{{ route('exams.edit', $exam) }}" 
                                                       class="btn btn-sm btn-warning"
                                                       title="{{ __('messages.edit') }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endcan
                                                
                                                @can('exam-delete')
                                                    <form action="{{ route('exams.destroy', $exam) }}" 
                                                          method="POST" 
                                                          class="d-inline"
                                                          onsubmit="return confirm('{{ __('messages.confirm_delete_exam') }}')">
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
                                        <td colspan="9" class="text-center">
                                            {{ __('messages.no_exams_found') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $exams->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection