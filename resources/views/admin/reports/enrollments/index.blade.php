@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">{{ __('messages.course_enrollment_reports') }}</h1>
        <div>
            <a href="{{ route('reports.enrollments.export') }}?{{ http_build_query(request()->all()) }}" class="btn btn-success">
                <i class="fas fa-file-excel"></i> {{ __('messages.export_excel') }}
            </a>
            <a href="{{ route('reports.enrollments.print') }}?{{ http_build_query(request()->all()) }}" class="btn btn-secondary" target="_blank">
                <i class="fas fa-print"></i> {{ __('messages.print') }}
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">{{ __('messages.total_enrollments') }}</h5>
                    <h2 class="mb-0">{{ $statistics['total_enrollments'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">{{ __('messages.unique_students') }}</h5>
                    <h2 class="mb-0">{{ $statistics['unique_students'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">{{ __('messages.unique_courses') }}</h5>
                    <h2 class="mb-0">{{ $statistics['unique_courses'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">{{ __('messages.total_revenue') }}</h5>
                    <h2 class="mb-0">{{ number_format($statistics['total_revenue'], 2) }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">{{ __('messages.average_enrollment_per_course') }}</h6>
                    <h3 class="mb-0">{{ $statistics['average_enrollment_per_course'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">{{ __('messages.enrollments_this_month') }}</h6>
                    <h3 class="mb-0">{{ $statistics['enrollments_this_month'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">{{ __('messages.revenue_this_month') }}</h6>
                    <h3 class="mb-0">{{ number_format($statistics['revenue_this_month'], 2) }}</h3>
                </div>
            </div>
        </div>
    </div>

    @if($statistics['most_popular_course'])
    <div class="alert alert-info">
        <strong>{{ __('messages.most_popular_course') }}:</strong> 
        {{ app()->getLocale() == 'ar' ? $statistics['most_popular_course']->title_ar : $statistics['most_popular_course']->title_en }}
    </div>
    @endif

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">{{ __('messages.filters') }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('reports.enrollments.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="course_id">{{ __('messages.course') }}</label>
                        <select name="course_id" id="course_id" class="form-control">
                            <option value="">{{ __('messages.all_courses') }}</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                    {{ app()->getLocale() == 'ar' ? $course->title_ar : $course->title_en }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="student_id">{{ __('messages.student') }}</label>
                        <select name="student_id" id="student_id" class="form-control">
                            <option value="">{{ __('messages.all_students') }}</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="teacher_id">{{ __('messages.teacher') }}</label>
                        <select name="teacher_id" id="teacher_id" class="form-control">
                            <option value="">{{ __('messages.all_teachers') }}</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="subject_id">{{ __('messages.subject') }}</label>
                        <select name="subject_id" id="subject_id" class="form-control">
                            <option value="">{{ __('messages.all_subjects') }}</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                    {{ app()->getLocale() == 'ar' ? $subject->name_ar : $subject->name_en }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="from_date">{{ __('messages.from_date') }}</label>
                        <input type="date" name="from_date" id="from_date" class="form-control" value="{{ request('from_date') }}">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="to_date">{{ __('messages.to_date') }}</label>
                        <input type="date" name="to_date" id="to_date" class="form-control" value="{{ request('to_date') }}">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="payment_status">{{ __('messages.payment_status') }}</label>
                        <select name="payment_status" id="payment_status" class="form-control">
                            <option value="">{{ __('messages.all') }}</option>
                            <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>{{ __('messages.paid') }}</option>
                            <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>{{ __('messages.unpaid') }}</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="search">{{ __('messages.search') }}</label>
                        <input type="text" name="search" id="search" class="form-control" placeholder="{{ __('messages.search_placeholder') }}" value="{{ request('search') }}">
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> {{ __('messages.apply_filters') }}
                    </button>
                    <a href="{{ route('reports.enrollments.index') }}" class="btn btn-secondary">
                        <i class="fas fa-redo"></i> {{ __('messages.reset') }}
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Enrollments Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">{{ __('messages.enrollment_list') }}</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('messages.id') }}</th>
                            <th>{{ __('messages.student') }}</th>
                            <th>{{ __('messages.email') }}</th>
                            <th>{{ __('messages.phone') }}</th>
                            <th>{{ __('messages.course') }}</th>
                            <th>{{ __('messages.teacher') }}</th>
                            <th>{{ __('messages.subject') }}</th>
                            <th>{{ __('messages.enrollment_date') }}</th>
                            <th>{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($enrollments as $enrollment)
                        <tr>
                            <td>{{ $enrollment->id }}</td>
                            <td>{{ $enrollment->user->name }}</td>
                            <td>{{ $enrollment->user->email }}</td>
                            <td>{{ $enrollment->user->phone }}</td>
                            <td>{{ app()->getLocale() == 'ar' ? $enrollment->course->title_ar : $enrollment->course->title_en }}</td>
                            <td>{{ $enrollment->course->teacher->name }}</td>
                            <td>{{ app()->getLocale() == 'ar' ? $enrollment->course->subject->name_ar : $enrollment->course->subject->name_en }}</td>
                            <td>{{ $enrollment->created_at->format('Y-m-d') }}</td>
                            <td>
                                <a href="{{ route('reports.enrollments.show-student', $enrollment->user->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> {{ __('messages.view_student') }}
                                </a>
                                <a href="{{ route('reports.enrollments.show-course', $enrollment->course->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> {{ __('messages.view_course') }}
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">{{ __('messages.no_enrollments_found') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $enrollments->links() }}
            </div>
        </div>
    </div>
</div>
@endsection