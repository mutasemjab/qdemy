@extends('layouts.admin')

@section('title', __('messages.add_subject'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.add_subject') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('subjects.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
                        </a>
                    </div>
                </div>
                <form action="{{ route('subjects.store') }}" method="POST" id="subjectForm">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <!-- Arabic Name -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name_ar" class="form-label">
                                        {{ __('messages.name_ar') }} <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           class="form-control @error('name_ar') is-invalid @enderror"
                                           id="name_ar"
                                           name="name_ar"
                                           value="{{ old('name_ar') }}"
                                           placeholder="{{ __('messages.enter_name_ar') }}"
                                           dir="rtl"
                                           maxlength="100"
                                           required>
                                    @error('name_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- English Name -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name_en" class="form-label">
                                        {{ __('messages.name_en') }} <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           class="form-control @error('name_en') is-invalid @enderror"
                                           id="name_en"
                                           name="name_en"
                                           value="{{ old('name_en') }}"
                                           placeholder="{{ __('messages.enter_name_en') }}"
                                           maxlength="100"
                                           required>
                                    @error('name_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Arabic Description -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="description_ar" class="form-label">
                                        {{ __('messages.description_ar') }}
                                    </label>
                                    <textarea class="form-control @error('description_ar') is-invalid @enderror"
                                              id="description_ar"
                                              name="description_ar"
                                              rows="4"
                                              placeholder="{{ __('messages.enter_description_ar') }}"
                                              dir="rtl"
                                              maxlength="500">{{ old('description_ar') }}</textarea>
                                    @error('description_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- English Description -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="description_en" class="form-label">
                                        {{ __('messages.description_en') }}
                                    </label>
                                    <textarea class="form-control @error('description_en') is-invalid @enderror"
                                              id="description_en"
                                              name="description_en"
                                              rows="4"
                                              placeholder="{{ __('messages.enter_description_en') }}"
                                              maxlength="500">{{ old('description_en') }}</textarea>
                                    @error('description_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Icon -->
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="icon" class="form-label">
                                        {{ __('messages.icon') }}
                                    </label>
                                    <input type="text"
                                           class="form-control @error('icon') is-invalid @enderror"
                                           id="icon"
                                           name="icon"
                                           value="{{ old('icon') }}"
                                           placeholder="{{ __('messages.enter_icon_class') }}"
                                           maxlength="100">
                                    @error('icon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Color -->
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="color" class="form-label">
                                        {{ __('messages.color') }}
                                    </label>
                                    <input type="color"
                                           class="form-control @error('color') is-invalid @enderror"
                                           id="color"
                                           name="color"
                                           value="{{ old('color', '#007bff') }}">
                                    @error('color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="is_active" class="form-label">
                                        {{ __('messages.status') }}
                                    </label>
                                    <select class="form-control @error('is_active') is-invalid @enderror"
                                            id="is_active"
                                            name="is_active">
                                        <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>
                                            {{ __('messages.inactive') }}
                                        </option>
                                        <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>
                                            {{ __('messages.active') }}
                                        </option>
                                    </select>
                                    @error('is_active')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Program -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="programm_id" class="form-label">
                                        {{ __('messages.program') }} <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control @error('programm_id') is-invalid @enderror"
                                            id="programm_id"
                                            name="programm_id"
                                            required>
                                        <option value="">{{ __('messages.select_program') }}</option>
                                        @foreach($programs as $program)
                                            <option value="{{ $program->id }}"
                                                    data-ctg-key="{{ $program->ctg_key }}"
                                                    {{ old('programm_id') == $program->id ? 'selected' : '' }}>
                                                {{ app()->getLocale() == 'ar' ? $program->name_ar : ($program->name_en ?? $program->name_ar) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('programm_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Grade (Hidden initially) -->
                            <div class="col-md-6" id="gradeSection" style="display: none;">
                                <div class="form-group mb-3">
                                    <label for="grade_id" class="form-label">
                                        {{ __('messages.grade') }} <span class="text-danger grade-required" style="display: none;">*</span>
                                    </label>
                                    <select class="form-control @error('grade_id') is-invalid @enderror"
                                            id="grade_id"
                                            name="grade_id">
                                        <option {{ old('programm_id') == $program->id ? 'selected' : '' }}
                                            value="">{{ __('messages.select_grade') }}</option>
                                    </select>
                                    @error('grade_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Semester (for single semester selection) -->
                            <div class="col-md-6" id="semesterSection" style="display: none;">
                                <div class="form-group mb-3">
                                    <label for="semester_id" class="form-label">
                                        {{ __('messages.semester') }} <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control @error('semester_id') is-invalid @enderror"
                                            id="semester_id"
                                            name="semester_id">
                                        <option value="">{{ __('messages.select_semester') }}</option>
                                    </select>
                                    @error('semester_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Tawjihi First Year Options (Hidden initially) -->
                            <div class="col-12" id="tawjihiFirstYearSection" style="display: none;">

                            <!-- Field Type -->
                            <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="is_optional_single" class="form-label">
                                                {{ __('messages.is_optional') }} <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-control @error('is_optional_single') is-invalid @enderror"
                                                    id="is_optional_single"
                                                    name="is_optional_single">
                                                <option value="0" {{ old('is_optional_single', 0) == 0 ? 'selected' : '' }}>
                                                    {{ __('messages.no') }}
                                                </option>
                                                <option value="1" {{ old('is_optional_single') == 1 ? 'selected' : '' }}>
                                                    {{ __('messages.yes') }}
                                                </option>
                                            </select>
                                            @error('is_optional_single')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="is_ministry_single" class="form-label">
                                                {{ __('messages.is_ministry') }} <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-control @error('is_ministry_single') is-invalid @enderror"
                                                    id="is_ministry_single"
                                                    name="is_ministry_single">
                                                <option value="1" {{ old('is_ministry_single', 1) == 1 ? 'selected' : '' }}>
                                                    {{ __('messages.yes') }}
                                                </option>
                                                <option value="0" {{ old('is_ministry_single') == 0 ? 'selected' : '' }}>
                                                    {{ __('messages.no') }}
                                                </option>
                                            </select>
                                            @error('is_ministry_single')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- field_type_select && Fields Selection (Hidden initially) -->
                            <div class="col-12" id="fieldsSelectionSection" style="display: none;">

                                <!-- field_type_select -->
                                <div class="col-md-6 p-0">
                                    <div class="form-group mb-3">
                                        <label for="field_type_id" class="form-label">
                                            {{ __('messages.field_type') }} <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-control @error('field_type_id') is-invalid @enderror"
                                                id="field_type_id"
                                                name="field_type_id">
                                            <option value="">{{ __('messages.select_field_type') }}</option>
                                            @foreach($fieldTypes as $fieldType)
                                                <option value="{{ $fieldType->id }}"
                                                        {{ old('field_type_id') == $fieldType->id ? 'selected' : '' }}>
                                                    {{ app()->getLocale() == 'ar' ? $fieldType->name_ar : ($fieldType->name_en ?? $fieldType->name_ar) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('field_type_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label">
                                        {{ __('messages.select_fields') }} <span class="text-danger">*</span>
                                    </label>
                                    <div id="fieldsTable" class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('messages.field') }}</th>
                                                    <th>{{ __('messages.add_to_field') }}</th>
                                                    <th>{{ __('messages.is_optional') }}</th>
                                                    <th>{{ __('messages.is_ministry') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody id="fieldsTableBody">
                                                <!-- Will be populated dynamically -->
                                            </tbody>
                                        </table>
                                    </div>
                                    @error('field_categories')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('subjects.index') }}" class="btn btn-secondary">
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

@push('scripts')
 <script>
     {!! file_get_contents(resource_path('views/admin/subjects/subjects-js.js')) !!}
    // Initialize the SubjectFormManager
    document.addEventListener('DOMContentLoaded', function() {
        const formManager = new SubjectFormManager({
            formId: 'subjectForm',
            routes: {
                getGrades: '{{ route("admin.subjects.getGrades") }}',
                getSemesters: '{{ route("admin.subjects.getSemesters") }}',
                getFields: '{{ route("admin.subjects.getFields") }}'
            },
            translations: {
                selectGrade: '{{ __("messages.select_grade") }}',
                selectSemester: '{{ __("messages.select_semester") }}',
                selectField: '{{ __("messages.select_field") }}',
                yes: '{{ __("messages.yes") }}',
                no: '{{ __("messages.no") }}'
            },
            isEditMode: false,
            existingData: {
                programId:  "{{ old('programm_id')  }}",
                gradeId:    "{{ old('grade_id')  }}",
                semesterId: "{{ old('semester_id')  }}",
            }
        });
    });
</script>
@endpush
