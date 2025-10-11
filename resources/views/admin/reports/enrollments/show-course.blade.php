@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">{{ __('messages.course_enrollment_report') }}</h1>
        <a href="{{ route('reports.enrollments.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
        </a>
    </div>

    <!-- Course Information -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">{{ __('messages.course_information') }}</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>{{ __('messages.course_title') }}:</strong> {{ app()->getLocale() == 'ar' ? $course->title_ar : $course->title_en }}</p>
                    <p><strong>{{ __('messages.teacher') }}:</strong> {{ $course->teacher->name }}</p>
                    <p><strong>{{ __('messages.subject') }}:</strong> {{ app()->getLocale() == 'ar' ? $course->subject->name_ar : $course->subject->name_en }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>{{ __('messages.created_date') }}:</strong> {{ $course->created_at->format('Y-m-d') }}</p>
                    <p><strong>{{ __('messages.status') }}:</strong> 
                        @if($course->is_active)
                            <span class="badge bg-success">{{ __('messages.active') }}</span>
                        @else
                            <span class="badge bg-secondary">{{ __('messages.inactive') }}</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">{{ __('messages.total_enrollments') }}</h5>
                    <h2 class="mb-0">{{ $courseStats['total_enrollments'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">{{ __('messages.total_revenue') }}</h5>
                    <h2 class="mb-0">{{ number_format($courseStats['total_revenue'], 2) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">{{ __('messages.average_progress') }}</h5>
                    <h2 class="mb-0">{{ $courseStats['average_progress'] }}%</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Enrolled Students -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">{{ __('messages.enrolled_students') }}</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('messages.id') }}</th>
                            <th>{{ __('messages.student_name') }}</th>
                            <th>{{ __('messages.email') }}</th>
                            <th>{{ __('messages.phone') }}</th>
                            <th>{{ __('messages.enrollment_date') }}</th>
                            <th>{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($course->enrollments as $enrollment)
                        <tr>
                            <td>{{ $enrollment->id }}</td>
                            <td>{{ $enrollment->user->name }}</td>
                            <td>{{ $enrollment->user->email }}</td>
                            <td>{{ $enrollment->user->phone }}</td>
                            <td>{{ $enrollment->created_at->format('Y-m-d') }}</td>
                            <td>
                                <a href="{{ route('reports.enrollments.show-student', $enrollment->user->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> {{ __('messages.view_details') }}
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">{{ __('messages.no_students_found') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection