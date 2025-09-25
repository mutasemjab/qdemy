@extends('layouts.admin')

@section('title', __('messages.Course Enrollments'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.Course Enrollments') }}</h3>
                    @can('courseUser-add')
                        <a href="{{ route('admin.course-users.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> {{ __('messages.New') }}
                        </a>
                    @endcan
                </div>

                <div class="card-body">
                  

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.ID') }}</th>
                                    <th>{{ __('messages.Student') }}</th>
                                    <th>{{ __('messages.Course') }}</th>
                                    <th>{{ __('messages.Enrollment Date') }}</th>
                                    <th>{{ __('messages.Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($courseUsers as $courseUser)
                                    <tr>
                                        <td>{{ $courseUser->id }}</td>
                                        <td>
                                            <div>
                                                <strong>{{ $courseUser->user->name ?? __('messages.N/A') }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $courseUser->user->email ?? '' }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ $courseUser->course->title ?? __('messages.N/A') }}</strong>
                                                @if($courseUser->course && $courseUser->course->price)
                                                    <br>
                                                    <small class="text-success">{{ number_format($courseUser->course->price, 2) }} {{ config('app.currency', 'USD') }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            {{ $courseUser->created_at->format('Y-m-d H:i') }}
                                            <br>
                                            <small class="text-muted">{{ $courseUser->created_at->diffForHumans() }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                               
                                                
                                                @can('courseUser-edit')
                                                    <a href="{{ route('admin.course-users.edit', $courseUser) }}" 
                                                       class="btn btn-warning btn-sm" 
                                                       title="{{ __('messages.Edit') }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endcan
                                                
                                                @can('courseUser-delete')
                                                    <button type="button" 
                                                            class="btn btn-danger btn-sm" 
                                                            title="{{ __('messages.Delete') }}"
                                                            onclick="confirmDelete({{ $courseUser->id }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">{{ __('messages.No data found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $courseUsers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@can('courseUser-delete')
<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('messages.Confirm Delete') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                {{ __('messages.Are you sure you want to delete this course enrollment?') }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.Cancel') }}</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">{{ __('messages.Delete') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endcan
@endsection

@push('scripts')
<script>
function confirmDelete(id) {
    const form = document.getElementById('deleteForm');
    form.action = `/admin/course-users/${id}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
@endpush