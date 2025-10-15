@extends('layouts.admin')

@section('title', __('messages.add_section'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.add_section') }}</h3>
                    <p class="text-muted mb-0">{{ __('messages.course') }}: {{ $course->title_en }}</p>
                    <div class="card-tools">
                        <a href="{{ route('courses.sections.index', $course) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
                        </a>
                    </div>
                </div>

                <form action="{{ route('courses.sections.store', $course) }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <!-- English Title -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="title_en" class="form-label">
                                        {{ __('messages.section_title_en') }} <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('title_en') is-invalid @enderror" 
                                           id="title_en" 
                                           name="title_en" 
                                           value="{{ old('title_en') }}" 
                                           placeholder="{{ __('messages.enter_section_title_en') }}">
                                    @error('title_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Arabic Title -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="title_ar" class="form-label">
                                        {{ __('messages.section_title_ar') }} <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('title_ar') is-invalid @enderror" 
                                           id="title_ar" 
                                           name="title_ar" 
                                           value="{{ old('title_ar') }}" 
                                           placeholder="{{ __('messages.enter_section_title_ar') }}"
                                           dir="rtl">
                                    @error('title_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Parent Section -->
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="parent_id" class="form-label">{{ __('messages.parent_section') }}</label>
                                    <select class="form-control @error('parent_id') is-invalid @enderror" 
                                            id="parent_id" 
                                            name="parent_id">
                                        <option value="">{{ __('messages.select_parent_section') }}</option>
                                        @php
                                            $parentSections = $sections->whereNull('parent_id');
                                        @endphp
                                        @foreach($parentSections as $section)
                                            @include('admin.courses.partials.section-option', [
                                                'section' => $section, 
                                                'allSections' => $sections, 
                                                'level' => 0,
                                                'selectedId' => old('parent_id', request('parent_id'))
                                            ])
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">
                                        {{ __('messages.parent_section_help') }}
                                    </small>
                                    @error('parent_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('courses.sections.index', $course) }}" class="btn btn-secondary">
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