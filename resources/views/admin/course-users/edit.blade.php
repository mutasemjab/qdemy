@extends('layouts.admin')

@section('title', __('messages.Edit Course Enrollment'))
@section('css')

<style>
/* Custom Select2 styling for better UX */
.select2-container--bootstrap-5 .select2-dropdown {
    border: 1px solid #ced4da;
    border-radius: 0.375rem;
    z-index: 9999;
}

.select2-result-user,
.select2-result-course {
    padding: 8px 12px;
}

.select2-result-user__title,
.select2-result-course__title {
    font-weight: 600;
    color: #495057;
}

.select2-result-user__description {
    color: #6c757d;
    font-size: 0.875rem;
}

.select2-result-course__price {
    margin-top: 2px;
}

.select2-container--bootstrap-5.select2-container--focus .select2-selection {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
}

.select2-container--bootstrap-5 .select2-selection {
    border: 1px solid #ced4da;
    border-radius: 0.375rem;
    min-height: 38px;
}

.select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
    color: #212529;
    padding-left: 12px;
    padding-right: 20px;
    line-height: 36px;
}

.select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
    height: 36px;
    right: 3px;
}

/* RTL Support for Arabic */
[dir="rtl"] .select2-container--bootstrap-5 .select2-selection__rendered {
    text-align: right;
}

[dir="rtl"] .select2-container--bootstrap-5 .select2-selection__arrow {
    left: 3px;
    right: auto;
}

/* Form validation styling */
.select2-container--bootstrap-5.is-invalid .select2-selection {
    border-color: #dc3545;
}

.select2-container--bootstrap-5.is-invalid .select2-selection:focus {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
}
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.Edit Course Enrollment') }}</h3>
                    <a href="{{ route('admin.course-users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('messages.Back') }}
                    </a>
                </div>

                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.course-users.update', $courseUser) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="user_id" class="form-label">{{ __('messages.Student') }} <span class="text-danger">*</span></label>
                                    <select name="user_id" id="user_id" class="form-control select2 @error('user_id') is-invalid @enderror" required style="width: 100%;">
                                        <option value="">{{ __('messages.Select Student') }}</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" 
                                                    {{ (old('user_id') ?? $courseUser->user_id) == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->phone ?? $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">{{ __('messages.Search and select a different student if needed') }}</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="course_id" class="form-label">{{ __('messages.Course') }} <span class="text-danger">*</span></label>
                                    <select name="course_id" id="course_id" class="form-control select2 @error('course_id') is-invalid @enderror" required style="width: 100%;">
                                        <option value="">{{ __('messages.Select Course') }}</option>
                                        @foreach($courses as $course)
                                            <option value="{{ $course->id }}" 
                                                    data-price="{{ $course->price ?? 0 }}"
                                                    {{ (old('course_id') ?? $courseUser->course_id) == $course->id ? 'selected' : '' }}>
                                                {{ $course->title }}
                                                @if($course->price)
                                                    ({{ number_format($course->price, 2) }} {{ config('app.currency', 'USD') }})
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('course_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">{{ __('messages.Search and select a different course if needed') }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                          

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="payment_method" class="form-label">{{ __('messages.Payment Method') }} <span class="text-danger">*</span></label>
                                    <select name="payment_method" id="payment_method" class="form-control @error('payment_method') is-invalid @enderror" required>
                                        <option value="">{{ __('messages.Select Payment Method') }}</option>
                                        <option value="card" {{ (old('payment_method') ?? 'card') == 'card' ? 'selected' : '' }}>{{ __('messages.Card') }}</option>
                                        <option value="visa" {{ (old('payment_method') ?? 'card') == 'visa' ? 'selected' : '' }}>{{ __('messages.Visa') }}</option>
                                        <option value="cash" {{ (old('payment_method') ?? 'card') == 'cash' ? 'selected' : '' }}>{{ __('messages.Cash') }}</option>
                                    </select>
                                    @error('payment_method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">{{ __('messages.Notes') }}</label>
                            <textarea name="notes" 
                                      id="notes" 
                                      class="form-control @error('notes') is-invalid @enderror" 
                                      rows="3"
                                      placeholder="{{ __('messages.Optional notes') }}">{{ old('notes') ?? $paymentDetails->notes ?? '' }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> {{ __('messages.Update') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2 for user dropdown
    $('#user_id').select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: '{{ __("messages.Search for a student") }}',
        allowClear: true,
        templateResult: function(user) {
            if (user.loading) {
                return user.text;
            }
            
            if (!user.id) {
                return user.text;
            }
            
            var $container = $(
                '<div class="select2-result-user clearfix">' +
                    '<div class="select2-result-user__meta">' +
                        '<div class="select2-result-user__title"></div>' +
                        '<div class="select2-result-user__description"></div>' +
                    '</div>' +
                '</div>'
            );
            
            var text = user.text || '';
            var parts = text.match(/^([^(]+)\s*\(([^)]+)\)$/);
            
            if (parts) {
                $container.find('.select2-result-user__title').text(parts[1].trim());
                $container.find('.select2-result-user__description').text(parts[2].trim());
            } else {
                $container.find('.select2-result-user__title').text(text);
            }
            
            return $container;
        },
        templateSelection: function(user) {
            return user.text || user.id;
        }
    });
    
    // Initialize Select2 for course dropdown
    $('#course_id').select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: '{{ __("messages.Search for a course") }}',
        allowClear: true,
        templateResult: function(course) {
            if (course.loading) {
                return course.text;
            }
            
            if (!course.id) {
                return course.text;
            }
            
            var $container = $(
                '<div class="select2-result-course clearfix">' +
                    '<div class="select2-result-course__meta">' +
                        '<div class="select2-result-course__title"></div>' +
                        '<div class="select2-result-course__price"></div>' +
                    '</div>' +
                '</div>'
            );
            
            var text = course.text || '';
            var parts = text.match(/^([^(]+)\s*\(([^)]+)\)$/);
            
            if (parts) {
                $container.find('.select2-result-course__title').text(parts[1].trim());
                $container.find('.select2-result-course__price').html('<small class="text-success">' + parts[2].trim() + '</small>');
            } else {
                $container.find('.select2-result-course__title').text(text);
            }
            
            return $container;
        },
        templateSelection: function(course) {
            return course.text || course.id;
        }
    });
    
    const amountInput = document.getElementById('amount');
    
    // Handle course change to update amount
    $('#course_id').on('select2:select', function (e) {
        const selectedData = e.params.data;
        const selectedOption = document.querySelector(`option[value="${selectedData.id}"]`);
        const price = selectedOption ? selectedOption.dataset.price : null;
        
        if (price && parseFloat(price) > 0) {
            if (confirm('{{ __("messages.Do you want to update the amount with the course price?") }}')) {
                amountInput.value = parseFloat(price).toFixed(2);
            }
        }
    });
    
    // Handle user change - show confirmation
    $('#user_id').on('select2:select', function (e) {
        if (e.params.data.id !== '{{ $courseUser->user_id }}') {
            if (!confirm('{{ __("messages.Are you sure you want to change the student for this enrollment?") }}')) {
                $(this).val('{{ $courseUser->user_id }}').trigger('change');
            }
        }
    });
    
    // Handle course change - show confirmation
    $('#course_id').on('select2:select', function (e) {
        if (e.params.data.id !== '{{ $courseUser->course_id }}') {
            if (!confirm('{{ __("messages.Are you sure you want to change the course for this enrollment?") }}')) {
                $(this).val('{{ $courseUser->course_id }}').trigger('change');
            }
        }
    });
});
</script>

@endpush