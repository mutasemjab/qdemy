@extends('layouts.admin')

@section('title', __('messages.edit'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.edit') }}: {{ $category->name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
                        </a>
                    </div>
                </div>

                <form action="{{ route('categories.update', $category->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <!-- Arabic Name -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name_ar" class="form-label">
                                        {{ __('messages.name') }} <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           class="form-control @error('name_ar') is-invalid @enderror"
                                           id="name_ar"
                                           name="name_ar"
                                           value="{{ old('name_ar', $category->name_ar) }}"
                                           placeholder="{{ __('messages.enter_name_ar') }}"
                                           dir="rtl"
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
                                        {{ __('messages.name') }}
                                    </label>
                                    <input type="text"
                                           class="form-control @error('name_en') is-invalid @enderror"
                                           id="name_en"
                                           name="name_en"
                                           value="{{ old('name_en', $category->name_en) }}"
                                           placeholder="{{ __('messages.enter_name_en') }}">
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
                                              dir="rtl">{{ old('description_ar', $category->description_ar) }}</textarea>
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
                                              placeholder="{{ __('messages.enter_description_en') }}">{{ old('description_en', $category->description_en) }}</textarea>
                                    @error('description_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Icon -->
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="icon" class="form-label">
                                        {{ __('messages.icon') }}
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="{{ old('icon', $category->icon) ?: 'fas fa-folder' }}" id="icon-preview"></i>
                                        </span>
                                        <input type="text"
                                               class="form-control @error('icon') is-invalid @enderror"
                                               id="icon"
                                               name="icon"
                                               value="{{ old('icon', $category->icon) }}"
                                               placeholder="fas fa-folder">
                                        @error('icon')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="form-text text-muted">
                                        {{ __('messages.icon_help') }}
                                    </small>
                                </div>
                            </div>

                            <!-- Color -->
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="color" class="form-label">
                                        {{ __('messages.Color') }}
                                    </label>
                                    <input type="color"
                                           class="form-control form-control-color @error('color') is-invalid @enderror"
                                           id="color"
                                           name="color"
                                           value="{{ old('color', $category->color) }}"
                                           title="{{ __('messages.choose_color') }}">
                                    @error('color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Active Status -->
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="is_active" class="form-label">
                                        {{ __('messages.status') }}
                                    </label>
                                    <select class="form-control @error('is_active') is-invalid @enderror"
                                            id="is_active"
                                            name="is_active">
                                        <option value="1" {{ old('is_active', $category->is_active) == 1 ? 'selected' : '' }}>
                                            {{ __('messages.active') }}
                                        </option>
                                        <option value="0" {{ old('is_active', $category->is_active) == 0 ? 'selected' : '' }}>
                                            {{ __('messages.inactive') }}
                                        </option>
                                    </select>
                                    @error('is_active')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if($category->hasChildren())
                                        <small class="form-text text-warning">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            {{ __('messages.status_change_warning') }}
                                        </small>
                                    @endif
                                </div>
                            </div>

                            <!-- Display Only Fields -->
                            <!-- Sort Order (Display Only) -->
                            <div class="col-md-6 col-12">
                                <div class="form-group mb-3">
                                    <label class="form-label">{{ __('messages.sort_order') }}</label>
                                    <input type="text" class="form-control" value="{{ $category->sort_order }}" readonly disabled>
                                </div>
                            </div>

                            <!-- Parent Category (Display Only) -->
                            <div class="col-md-6 col-12">
                                <div class="form-group mb-3">
                                    <label class="form-label">{{ __('messages.parent_category') }}</label>
                                    <input type="text" class="form-control"
                                           value="{{ $category->parent ? $category->parent->name_ar : __('messages.root_category') }}"
                                           readonly disabled>
                                </div>
                            </div>

                            <!-- Type (Display Only) -->
                            <div class="col-md-6 col-12">
                                <div class="form-group mb-3">
                                    <label class="form-label">{{ __('messages.type') }}</label>
                                    <input type="text" class="form-control" value="{{ $category->type }}" readonly disabled>
                                </div>
                            </div>

                            @if($category->breadcrumb)
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <strong>{{ __('messages.Path') }}:</strong> {{ $category->breadcrumb }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('categories.index') }}" class="btn btn-secondary">
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

@push('scripts')
<script>
    // Preview icon on change
    document.getElementById('icon').addEventListener('input', function() {
        const iconPreview = document.getElementById('icon-preview');
        iconPreview.className = this.value || 'fas fa-folder';
    });
</script>
@endpush

@endsection
