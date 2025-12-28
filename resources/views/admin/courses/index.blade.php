@extends('layouts.admin')

@section('title', __('messages.courses'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.courses') }}</h3>
                    @can('course-add')
                        <a href="{{ route('courses.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> {{ __('messages.add_course') }}
                        </a>
                    @endcan
                </div>

                <div class="card-body">
                   

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('messages.photo') }}</th>
                                    <th>{{ __('messages.title') }}</th>
                                    <th>{{ __('messages.teacher') }}</th>
                                    <th>{{ __('messages.Lesson/Subject') }}</th>
                                    <th>{{ __('messages.price') }}</th>
                                    <th>{{ __('messages.commission_of_admin') }}</th>
                                    <th>{{ __('messages.course_status') }}</th>
                                    <th>{{ __('messages.created_at') }}</th>
                                    <th>{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($courses as $course)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <img src="{{ $course->photo_url }}" 
                                                 alt="{{ $course->title }}" 
                                                 class="img-thumbnail" 
                                                 style="width: 60px; height: 60px; object-fit: cover;">
                                        </td>
                                        <td>
                                            <strong>{{ $course->title_en }}</strong><br>
                                            <small class="text-muted">{{ $course->title_ar }}</small>
                                        </td>
                                        <td>
                                            @if($course->teacher)
                                                {{ $course->teacher->name }}
                                            @else
                                                <span class="text-muted">{{ __('messages.no_teacher') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($course->subject)
                                                {{ $course->subject->localized_name }}
                                            @else
                                                <span class="text-muted">{{ __('messages.No Subject') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-success">JD {{ number_format($course->selling_price, 2) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">% {{ $course->commission_of_admin }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'pending' => 'warning',
                                                    'accepted' => 'success',
                                                    'rejected' => 'danger'
                                                ];
                                                $statusColor = $statusColors[$course->status] ?? 'secondary';
                                                $statusLabel = __('messages.status_' . $course->status);
                                            @endphp
                                            <span class="badge bg-{{ $statusColor }}">
                                                {{ $statusLabel }}
                                            </span>
                                        </td>
                                        <td>{{ $course->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @can('course-table')
                                                    <a href="{{ route('courses.show', $course) }}" 
                                                       class="btn btn-sm btn-info"
                                                       title="{{ __('messages.view') }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                @endcan

                                                @can('course-edit')
                                                    <a href="{{ route('courses.sections.index', $course) }}" 
                                                       class="btn btn-sm btn-success"
                                                       title="{{ __('messages.manage_sections_contents') }}">
                                                        <i class="fas fa-list"></i>
                                                    </a>
                                                @endcan
                                                
                                                @can('course-edit')
                                                    <a href="{{ route('courses.edit', $course) }}" 
                                                       class="btn btn-sm btn-warning"
                                                       title="{{ __('messages.edit') }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endcan
                                                
                                                @can('course-delete')
                                                    <form action="{{ route('courses.destroy', $course) }}" 
                                                          method="POST" 
                                                          class="d-inline"
                                                          onsubmit="return confirm('{{ __('messages.confirm_delete') }}')">
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
                                        <td colspan="10" class="text-center">
                                            {{ __('messages.no_courses_found') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $courses->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection