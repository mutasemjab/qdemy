@extends('layouts.admin')

@section('title', __('messages.edit_section'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.edit_section') }}</h3>
                    <p class="text-muted mb-0">{{ __('messages.course') }}: {{ $course->title_en }}</p>
                    <div class="card-tools">
                        <a href="{{ route('courses.sections.index', $course) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
                        </a>
                    </div>
                </div>

                <form action="{{ route('courses.sections.update', [$course, $section]) }}" method="POST">
                    @csrf
                    @method('PUT')
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
                                           value="{{ old('title_en', $section->title_en) }}" 
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
                                           value="{{ old('title_ar', $section->title_ar) }}" 
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
                                        @foreach($parentSections as $parentSection)
                                            @include('admin.courses.partials.section-option', [
                                                'section' => $parentSection, 
                                                'allSections' => $sections, 
                                                'level' => 0,
                                                'selectedId' => old('parent_id', $section->parent_id)
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

                            <!-- Section Statistics -->
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-info-circle"></i> {{ __('messages.section_statistics') }}</h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong>{{ __('messages.total_contents') }}:</strong> 
                                            {{ $section->contents->count() }}
                                        </div>
                                        <div class="col-md-4">
                                            <strong>{{ __('messages.free_contents') }}:</strong> 
                                            {{ $section->contents->where('is_free', 1)->count() }}
                                        </div>
                                        <div class="col-md-4">
                                            <strong>{{ __('messages.paid_contents') }}:</strong> 
                                            {{ $section->contents->where('is_free', 2)->count() }}
                                        </div>
                                    </div>
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
                                <i class="fas fa-save"></i> {{ __('messages.update') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection