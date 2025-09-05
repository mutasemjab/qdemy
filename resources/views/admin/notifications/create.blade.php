@extends('layouts.admin')

@section('title', __('messages.send_notification'))

@section('css')
<style>
.notification-preview {
    background: #ffffff;
    border: 1px solid #e3e6f0;
    border-radius: 8px;
    padding: 15px;
}

.notification-icon {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fc;
    border-radius: 50%;
}

.notification-title {
    font-weight: 600;
    color: #2d3436;
}

.notification-body {
    color: #636e72;
    line-height: 1.5;
}

.form-check {
    border: 1px solid #e3e6f0;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 10px;
    transition: all 0.3s ease;
}

.form-check:hover {
    border-color: #4e73df;
    background-color: #f8f9fc;
}

.form-check input[type="radio"]:checked + label {
    color: #4e73df;
}

.form-check input[type="radio"]:checked ~ .form-check {
    border-color: #4e73df;
    background-color: #f8f9fc;
}
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.send_notification') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('notifications.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
                        </a>
                    </div>
                </div>

                <form action="{{ route('notifications.store') }}" method="POST" id="notificationForm">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <!-- Notification Title -->
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label for="title" class="form-label">
                                        {{ __('messages.notification_title') }} <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           class="form-control @error('title') is-invalid @enderror"
                                           id="title"
                                           name="title"
                                           value="{{ old('title') }}"
                                           placeholder="{{ __('messages.enter_notification_title') }}"
                                           maxlength="255">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        <span id="titleCount">0</span>/255 {{ __('messages.characters') }}
                                    </small>
                                </div>
                            </div>

                            <!-- Notification Body -->
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label for="body" class="form-label">
                                        {{ __('messages.notification_message') }} <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control @error('body') is-invalid @enderror"
                                              id="body"
                                              name="body"
                                              rows="5"
                                              placeholder="{{ __('messages.enter_notification_message') }}">{{ old('body') }}</textarea>
                                    @error('body')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        <span id="bodyCount">0</span> {{ __('messages.characters') }}
                                    </small>
                                </div>
                            </div>

                            <!-- Recipient Type -->
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label class="form-label">
                                        {{ __('messages.send_to') }} <span class="text-danger">*</span>
                                    </label>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="recipient_type"
                                                       id="all" value="all" {{ old('recipient_type') == 'all' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="all">
                                                    <i class="fas fa-users text-primary"></i>
                                                    <strong>{{ __('messages.all_users') }}</strong>
                                                    <small class="d-block text-muted">{{ __('messages.all_students_and_teachers') }}</small>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="recipient_type"
                                                       id="all_users" value="all_users" {{ old('recipient_type') == 'all_users' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="all_users">
                                                    <i class="fas fa-user-graduate text-info"></i>
                                                    <strong>{{ __('messages.all_students') }}</strong>
                                                    <small class="d-block text-muted">{{ __('messages.send_to_all_students') }}</small>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="recipient_type"
                                                       id="all_teachers" value="all_teachers" {{ old('recipient_type') == 'all_teachers' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="all_teachers">
                                                    <i class="fas fa-chalkboard-teacher text-success"></i>
                                                    <strong>{{ __('messages.all_teachers') }}</strong>
                                                    <small class="d-block text-muted">{{ __('messages.send_to_all_teachers') }}</small>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="recipient_type"
                                                       id="specific_user" value="specific_user" {{ old('recipient_type') == 'specific_user' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="specific_user">
                                                    <i class="fas fa-user text-warning"></i>
                                                    <strong>{{ __('messages.specific_student') }}</strong>
                                                    <small class="d-block text-muted">{{ __('messages.send_to_specific_student') }}</small>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="recipient_type"
                                                       id="specific_teacher" value="specific_teacher" {{ old('recipient_type') == 'specific_teacher' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="specific_teacher">
                                                    <i class="fas fa-user-tie text-danger"></i>
                                                    <strong>{{ __('messages.specific_teacher') }}</strong>
                                                    <small class="d-block text-muted">{{ __('messages.send_to_specific_teacher') }}</small>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    @error('recipient_type')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Specific User Selection -->
                            <div class="col-md-6" id="userSelection" style="display: none;">
                                <div class="form-group mb-3">
                                    <label for="user_id" class="form-label">
                                        {{ __('messages.select_student') }} <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control select2 @error('user_id') is-invalid @enderror"
                                            id="user_id"
                                            name="user_id">
                                        <option value="">{{ __('messages.choose_student') }}</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Specific Teacher Selection -->
                            <div class="col-md-6" id="teacherSelection" style="display: none;">
                                <div class="form-group mb-3">
                                    <label for="teacher_id" class="form-label">
                                        {{ __('messages.select_teacher') }} <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control select2 @error('teacher_id') is-invalid @enderror"
                                            id="teacher_id"
                                            name="teacher_id">
                                        <option value="">{{ __('messages.choose_teacher') }}</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                                {{ $teacher->name }} ({{ $teacher->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('teacher_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Recipient Count Display -->
                            <div class="col-12">
                                <div class="alert alert-info" id="recipientInfo" style="display: none;">
                                    <i class="fas fa-info-circle"></i>
                                    <span id="recipientText"></span>
                                </div>
                            </div>

                            <!-- Preview Section -->
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <h6 class="mb-0">
                                            <i class="fas fa-eye"></i> {{ __('messages.notification_preview') }}
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="notification-preview">
                                            <div class="d-flex align-items-start">
                                                <div class="notification-icon mr-3">
                                                    <i class="fas fa-bell fa-2x text-primary"></i>
                                                </div>
                                                <div class="notification-content flex-grow-1">
                                                    <h6 class="notification-title mb-1" id="previewTitle">
                                                        {{ __('messages.notification_title_placeholder') }}
                                                    </h6>
                                                    <p class="notification-body mb-2" id="previewBody">
                                                        {{ __('messages.notification_body_placeholder') }}
                                                    </p>
                                                    <small class="text-muted">{{ __('messages.just_now') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('notifications.index') }}" class="btn btn-secondary">
                                {{ __('messages.cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary" id="sendBtn">
                                <i class="fas fa-paper-plane"></i> {{ __('messages.send_notification') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize select2
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%'
    });

    // Character counters
    $('#title').on('input', function() {
        const count = $(this).val().length;
        $('#titleCount').text(count);
        updatePreview();
    });

    $('#body').on('input', function() {
        const count = $(this).val().length;
        $('#bodyCount').text(count);
        updatePreview();
    });

    // Recipient type change
    $('input[name="recipient_type"]').change(function() {
        const selectedType = $(this).val();

        // Hide all specific selections
        $('#userSelection, #teacherSelection').hide();

        // Show relevant selection
        if (selectedType === 'specific_user') {
            $('#userSelection').show();
        } else if (selectedType === 'specific_teacher') {
            $('#teacherSelection').show();
        }

        updateRecipientInfo();
    });

    // Initial setup
    updateRecipientInfo();
    updatePreview();

    function updateRecipientInfo() {
        const selectedType = $('input[name="recipient_type"]:checked').val();
        let recipientText = '';
        let count = 0;

        switch (selectedType) {
            case 'all':
                count = {{ $users->count() + $teachers->count() }};
                recipientText = `{{ __('messages.will_send_to_all_users') }} (${count} {{ __('messages.recipients') }})`;
                break;
            case 'all_users':
                count = {{ $users->count() }};
                recipientText = `{{ __('messages.will_send_to_all_students') }} (${count} {{ __('messages.students') }})`;
                break;
            case 'all_teachers':
                count = {{ $teachers->count() }};
                recipientText = `{{ __('messages.will_send_to_all_teachers') }} (${count} {{ __('messages.teachers') }})`;
                break;
            case 'specific_user':
                recipientText = '{{ __('messages.will_send_to_selected_student') }}';
                break;
            case 'specific_teacher':
                recipientText = '{{ __('messages.will_send_to_selected_teacher') }}';
                break;
        }

        if (recipientText) {
            $('#recipientText').text(recipientText);
            $('#recipientInfo').show();
        } else {
            $('#recipientInfo').hide();
        }
    }

    function updatePreview() {
        const title = $('#title').val() || '{{ __('messages.notification_title_placeholder') }}';
        const body = $('#body').val() || '{{ __('messages.notification_body_placeholder') }}';

        $('#previewTitle').text(title);
        $('#previewBody').text(body);
    }

    // Test notification


    // Form submission
    $('#notificationForm').submit(function(e) {
        const selectedType = $('input[name="recipient_type"]:checked').val();

        if (!selectedType) {
            e.preventDefault();
            alert('{{ __('messages.please_select_recipient_type') }}');
            return;
        }

        if (selectedType === 'specific_user' && !$('#user_id').val()) {
            e.preventDefault();
            alert('{{ __('messages.please_select_student') }}');
            return;
        }

        if (selectedType === 'specific_teacher' && !$('#teacher_id').val()) {
            e.preventDefault();
            alert('{{ __('messages.please_select_teacher') }}');
            return;
        }

        // Show confirmation
        if (!confirm('{{ __('messages.confirm_send_notification') }}')) {
            e.preventDefault();
            return;
        }

        // Disable send button
        $('#sendBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> {{ __('messages.sending') }}...');
    });

    // Trigger initial recipient type change if old value exists
    const oldRecipientType = '{{ old('recipient_type') }}';
    if (oldRecipientType) {
        $(`input[name="recipient_type"][value="${oldRecipientType}"]`).trigger('change');
    }
});
</script>
@endpush
