@extends('layouts.admin')

@section('title', __('messages.add_course'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.add_course') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('courses.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
                        </a>
                    </div>
                </div>

                <form action="{{ route('courses.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <!-- English Title -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="title_en" class="form-label">
                                        {{ __('messages.title_en') }} <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           class="form-control @error('title_en') is-invalid @enderror"
                                           id="title_en"
                                           name="title_en"
                                           value="{{ old('title_en') }}"
                                           placeholder="{{ __('messages.enter_title_en') }}">
                                    @error('title_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Arabic Title -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="title_ar" class="form-label">
                                        {{ __('messages.title_ar') }} <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           class="form-control @error('title_ar') is-invalid @enderror"
                                           id="title_ar"
                                           name="title_ar"
                                           value="{{ old('title_ar') }}"
                                           placeholder="{{ __('messages.enter_title_ar') }}"
                                           dir="rtl">
                                    @error('title_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- English Description -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="description_en" class="form-label">
                                        {{ __('messages.description_en') }} <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control @error('description_en') is-invalid @enderror"
                                              id="description_en"
                                              name="description_en"
                                              rows="4"
                                              placeholder="{{ __('messages.enter_description_en') }}">{{ old('description_en') }}</textarea>
                                    @error('description_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Arabic Description -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="description_ar" class="form-label">
                                        {{ __('messages.description_ar') }} <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control @error('description_ar') is-invalid @enderror"
                                              id="description_ar"
                                              name="description_ar"
                                              rows="4"
                                              placeholder="{{ __('messages.enter_description_ar') }}"
                                              dir="rtl">{{ old('description_ar') }}</textarea>
                                    @error('description_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Price -->
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="selling_price" class="form-label">
                                        {{ __('messages.selling_price') }} <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">{{ __('messages.currency_jd') }}</span>
                                        <input type="number"
                                               class="form-control @error('selling_price') is-invalid @enderror"
                                               id="selling_price"
                                               name="selling_price"
                                               value="{{ old('selling_price') }}"
                                               step="any"
                                               min="0"
                                               placeholder="0.00">
                                        @error('selling_price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                           
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="commission_of_admin" class="form-label">
                                        {{ __('messages.commission_of_admin') }} <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">%</span>
                                        <input type="number"
                                               class="form-control @error('commission_of_admin') is-invalid @enderror"
                                               id="commission_of_admin"
                                               name="commission_of_admin"
                                               value="{{ old('commission_of_admin') }}"
                                               step="any"
                                               min="0"
                                               placeholder="0.00">
                                        @error('commission_of_admin')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Teacher -->
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="teacher_id" class="form-label">{{ __('messages.teacher') }}</label>
                                    <select class="form-control @error('teacher_id') is-invalid @enderror"
                                            id="teacher_id"
                                            name="teacher_id">
                                        <option value="">{{ __('messages.select_teacher') }}</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}"
                                                    {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                                {{ $teacher->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('teacher_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Sequential Course -->
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="is_sequential" class="form-label">{{ __('messages.sequential_course') }}</label>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" id="is_sequential" name="is_sequential" {{ old('is_sequential', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_sequential">
                                            {{ __('messages.must_complete_in_order') }}
                                        </label>
                                    </div>
                                    <small class="form-text text-muted d-block mt-2">
                                        {{ __('messages.sequential_help_text') }}
                                    </small>
                                </div>
                            </div>

                            <!-- Course Status -->
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="status" class="form-label">{{ __('messages.course_status') }} <span class="text-danger">*</span></label>
                                    <select class="form-control @error('status') is-invalid @enderror"
                                            id="status" name="status" required>
                                        <option value="">{{ __('messages.select_status') }}</option>
                                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>
                                            {{ __('messages.status_pending') }}
                                        </option>
                                        <option value="accepted" {{ old('status', 'accepted') == 'accepted' ? 'selected' : '' }}>
                                            {{ __('messages.status_accepted') }}
                                        </option>
                                        <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>
                                            {{ __('messages.status_rejected') }}
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Subject -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="subject_id" class="form-label">
                                        {{ __('messages.subject') }} <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control @error('subject_id') is-invalid @enderror"
                                            id="subject_id" name="subject_id" required>
                                        <option value="">{{ __('messages.select_subject') }}</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                                {{ app()->getLocale() === 'ar' ? $subject->name_ar : $subject->name_en }}
                                                @if($subject->grade)
                                                    - {{ app()->getLocale() === 'ar' ? $subject->grade->name_ar : $subject->grade->name_en }}
                                                @endif
                                                @if($subject->semester)
                                                    - {{ app()->getLocale() === 'ar' ? $subject->semester->name_ar : $subject->semester->name_en }}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('subject_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Photo -->
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label for="photo" class="form-label">
                                        {{ __('messages.course_photo') }} <span class="text-danger">*</span>
                                    </label>
                                    <input type="file"
                                           class="form-control @error('photo') is-invalid @enderror"
                                           id="photo"
                                           name="photo"
                                           accept="image/*">
                                    <small class="form-text text-muted">
                                        {{ __('messages.photo_requirements') }}
                                    </small>
                                    @error('photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('courses.index') }}" class="btn btn-secondary">
                                {{ __('messages.cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> {{ __('messages.save') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection