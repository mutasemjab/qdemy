@extends('layouts.admin')

@section('title', __('messages.notifications'))

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">{{ __('messages.notifications') }}</h2>
                    <p class="text-muted mb-0">{{ __('messages.manage_notifications') }}</p>
                </div>
                <div class="btn-group">
                    <a href="{{ route('notifications.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> {{ __('messages.send_notification') }}
                    </a>
                   
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="info-box">
                <span class="info-box-icon bg-info">
                    <i class="fas fa-bell"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('messages.total_notifications') }}</span>
                    <span class="info-box-number" id="totalCount">{{ $notifications->total() }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="info-box">
                <span class="info-box-icon bg-success">
                    <i class="fas fa-envelope-open"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('messages.read_notifications') }}</span>
                    <span class="info-box-number" id="readCount">-</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="info-box">
                <span class="info-box-icon bg-warning">
                    <i class="fas fa-envelope"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('messages.unread_notifications') }}</span>
                    <span class="info-box-number" id="unreadCount">-</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="info-box">
                <span class="info-box-icon bg-primary">
                    <i class="fas fa-calendar-day"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('messages.today_notifications') }}</span>
                    <span class="info-box-number" id="todayCount">-</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" class="row align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">{{ __('messages.search') }}</label>
                            <input type="text" name="search" class="form-control" 
                                   placeholder="{{ __('messages.search_notifications') }}" 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">{{ __('messages.status') }}</label>
                            <select name="status" class="form-control">
                                <option value="">{{ __('messages.all_status') }}</option>
                                <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>
                                    {{ __('messages.read') }}
                                </option>
                                <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>
                                    {{ __('messages.unread') }}
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">{{ __('messages.student') }}</label>
                            <select name="user_id" class="form-control">
                                <option value="">{{ __('messages.all_students') }}</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">{{ __('messages.teacher') }}</label>
                            <select name="teacher_id" class="form-control">
                                <option value="">{{ __('messages.all_teachers') }}</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> {{ __('messages.filter') }}
                            </button>
                            <a href="{{ route('notifications.index') }}" class="btn btn-outline-secondary ml-2">
                                <i class="fas fa-times"></i> {{ __('messages.clear') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-list"></i> {{ __('messages.notifications_list') }}
                        </h5>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="selectAll">
                            <label class="form-check-label" for="selectAll">
                                {{ __('messages.select_all') }}
                            </label>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($notifications->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th width="40px">
                                        <input type="checkbox" id="masterCheckbox" class="form-check-input">
                                    </th>
                                    <th width="60px">{{ __('messages.status') }}</th>
                                    <th>{{ __('messages.title') }}</th>
                                    <th>{{ __('messages.recipient') }}</th>
                                    <th>{{ __('messages.type') }}</th>
                                    <th>{{ __('messages.sent_at') }}</th>
                                    <th width="150px">{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($notifications as $notification)
                                <tr class="{{ !$notification->is_read ? 'table-warning' : '' }}">
                                    <td>
                                        <input type="checkbox" class="form-check-input notification-checkbox" 
                                               value="{{ $notification->id }}">
                                    </td>
                                    <td class="text-center">
                                        @if($notification->is_read)
                                            <i class="fas fa-envelope-open text-success" title="{{ __('messages.read') }}"></i>
                                        @else
                                            <i class="fas fa-envelope text-warning" title="{{ __('messages.unread') }}"></i>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <strong class="d-block">{{ $notification->title }}</strong>
                                            <small class="text-muted">
                                                {{ Str::limit($notification->body, 100) }}
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-user-circle fa-2x text-muted mr-2"></i>
                                            <div>
                                                @if($notification->user)
                                                    <strong>{{ $notification->user->name }}</strong>
                                                    <small class="d-block text-muted">{{ $notification->user->email }}</small>
                                                @elseif($notification->teacher)
                                                    <strong>{{ $notification->teacher->name }}</strong>
                                                    <small class="d-block text-muted">{{ $notification->teacher->email }}</small>
                                                @else
                                                    <span class="text-muted">{{ __('messages.unknown_recipient') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $notification->type == 'student' ? 'primary' : 'info' }}">
                                            {{ $notification->type == 'student' ? __('messages.student') : __('messages.teacher') }}
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $notification->created_at->format('Y-m-d H:i') }}
                                            <span class="d-block">{{ $notification->time_since }}</span>
                                        </small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                         
                                            
                                            <form action="{{ route('notifications.destroy', $notification) }}" 
                                                  method="POST" class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" 
                                                        title="{{ __('messages.delete') }}">
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

                    <!-- Pagination -->
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted">
                                {{ __('messages.showing') }} {{ $notifications->firstItem() ?? 0 }} {{ __('messages.to') }} {{ $notifications->lastItem() ?? 0 }} 
                                {{ __('messages.of') }} {{ $notifications->total() }} {{ __('messages.notifications') }}
                            </div>
                            <div>
                                {{ $notifications->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">{{ __('messages.no_notifications_found') }}</h5>
                        <p class="text-muted">{{ __('messages.no_notifications_message') }}</p>
                        <a href="{{ route('notifications.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> {{ __('messages.send_first_notification') }}
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Load statistics
    loadStatistics();

    // Select all functionality
    $('#selectAll, #masterCheckbox').change(function() {
        const isChecked = this.checked;
        $('.notification-checkbox').prop('checked', isChecked);
        updateBulkDeleteButton();
    });

    $('.notification-checkbox').change(function() {
        updateBulkDeleteButton();
        updateSelectAllCheckbox();
    });

    function updateBulkDeleteButton() {
        const checkedBoxes = $('.notification-checkbox:checked').length;
        $('#bulkDeleteBtn').prop('disabled', checkedBoxes === 0);
    }

    function updateSelectAllCheckbox() {
        const totalCheckboxes = $('.notification-checkbox').length;
        const checkedCheckboxes = $('.notification-checkbox:checked').length;
        
        $('#selectAll, #masterCheckbox').prop('indeterminate', checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes);
        $('#selectAll, #masterCheckbox').prop('checked', checkedCheckboxes === totalCheckboxes);
    }


    // Mark as read/unread
    $('.mark-read-btn').click(function() {
        const notificationId = $(this).data('id');
        markNotification(notificationId, 'read');
    });

    $('.mark-unread-btn').click(function() {
        const notificationId = $(this).data('id');
        markNotification(notificationId, 'unread');
    });

    function markNotification(id, status) {
        const url = status === 'read' 
            ? `/admin/notifications/${id}/mark-read`
            : `/admin/notifications/${id}/mark-unread`;

        $.ajax({
            url: url,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                }
            },
            error: function() {
                alert('{{ __("messages.error_occurred") }}');
            }
        });
    }

    // Resend notification
    $('.resend-btn').click(function() {
        const notificationId = $(this).data('id');
        const button = $(this);
        
        if (confirm('{{ __("messages.confirm_resend_notification") }}')) {
            button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
            
            $.ajax({
                url: `/admin/notifications/${notificationId}/resend`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    alert(response.message);
                    button.prop('disabled', false).html('<i class="fas fa-redo"></i>');
                },
                error: function() {
                    alert('{{ __("messages.error_occurred") }}');
                    button.prop('disabled', false).html('<i class="fas fa-redo"></i>');
                }
            });
        }
    });

    // Delete confirmation
    $('.delete-form').submit(function(e) {
        if (!confirm('{{ __("messages.confirm_delete_notification") }}')) {
            e.preventDefault();
        }
    });

});
</script>
@endpush