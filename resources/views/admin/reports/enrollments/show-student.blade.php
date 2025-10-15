@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">{{ __('messages.student_enrollment_report') }}</h1>
        <a href="{{ route('reports.enrollments.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
        </a>
    </div>

    <!-- Student Information -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">{{ __('messages.student_information') }}</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>{{ __('messages.name') }}:</strong> {{ $student->name }}</p>
                    <p><strong>{{ __('messages.email') }}:</strong> {{ $student->email }}</p>
                    <p><strong>{{ __('messages.phone') }}:</strong> {{ $student->phone }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>{{ __('messages.registration_date') }}:</strong> {{ $student->created_at->format('Y-m-d') }}</p>
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
                    <h2 class="mb-0">{{ $enrollmentStats['total_enrollments'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">{{ __('messages.total_spent') }}</h5>
                    <h2 class="mb-0">{{ number_format($enrollmentStats['total_spent'], 2) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">{{ __('messages.active_courses') }}</h5>
                    <h2 class="mb-0">{{ $enrollmentStats['active_courses'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Enrolled Courses -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">{{ __('messages.enrolled_courses') }}</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('messages.id') }}</th>
                            <th>{{ __('messages.course') }}</th>
                            <th>{{ __('messages.teacher') }}</th>
                            <th>{{ __('messages.subject') }}</th>
                            <th>{{ __('messages.enrollment_date') }}</th>
                            <th>{{ __('messages.status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($student->courseEnrollments as $enrollment)
                        <tr>
                            <td>{{ $enrollment->id }}</td>
                            <td>{{ app()->getLocale() == 'ar' ? $enrollment->course->title_ar : $enrollment->course->title_en }}</td>
                            <td>{{ $enrollment->course->teacher->name }}</td>
                            <td>{{ app()->getLocale() == 'ar' ? $enrollment->course->subject->name_ar : $enrollment->course->subject->name_en }}</td>
                            <td>{{ $enrollment->created_at->format('Y-m-d') }}</td>
                            <td>
                                @if($enrollment->course->is_active)
                                    <span class="badge bg-success">{{ __('messages.active') }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ __('messages.inactive') }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">{{ __('messages.no_courses_found') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection