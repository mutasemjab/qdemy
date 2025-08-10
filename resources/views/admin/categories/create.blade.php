@extends('layouts.admin')

@section('title', __('messages.Add Category'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">{{ __('messages.Add Category') }}</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">{{ __('messages.Categories') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('messages.Add Category') }}</li>
                        </ol>
                    </nav>
                </div>
                <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>{{ __('messages.Back') }}
                </a>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('messages.Category Information') }}</h5>
                </div>
                
                <form method="POST" action="{{ route('categories.store') }}">
                    @csrf
                    
                    <div class="card-body">
                        <div class="row g-3">
                            <!-- Arabic Name -->
                            <div class="col-md-6">
                                <label for="name_ar" class="form-label required">{{ __('messages.Arabic Name') }}</label>
                                <input type="text" class="form-control @error('name_ar') is-invalid @enderror" 
                                       id="name_ar" name="name_ar" value="{{ old('name_ar') }}" required>
                                @error('name_ar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- English Name -->
                            <div class="col-md-6">
                                <label for="name_en" class="form-label">{{ __('messages.English Name') }}</label>
                                <input type="text" class="form-control @error('name_en') is-invalid @enderror" 
                                       id="name_en" name="name_en" value="{{ old('name_en') }}">
                                @error('name_en')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Parent Category -->
                            <div class="col-md-6">
                                <label for="parent_id" class="form-label">{{ __('messages.Parent Category') }}</label>
                                <select class="form-control @error('parent_id') is-invalid @enderror" 
                                        id="parent_id" name="parent_id">
                                    <option value="">{{ __('messages.Root Category') }}</option>
                                    @foreach($parentCategories as $parent)
                                        <option value="{{ $parent['category']->id }}" 
                                                {{ (old('parent_id', $parentId) == $parent['category']->id) ? 'selected' : '' }}>
                                            {{ $parent['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('parent_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                              <div class="col-md-6">
                                <label for="type" class="form-label">{{ __('messages.Category Type') }}</label>
                                <select class="form-control @error('type') is-invalid @enderror" 
                                        id="type" name="type">
                                    <option value="class" {{ old('type', 'class') == 'class' ? 'selected' : '' }}>
                                        {{ __('messages.Class') }} 
                                    </option>
                                    <option value="lesson" {{ old('type') == 'lesson' ? 'selected' : '' }}>
                                        {{ __('messages.Lesson') }} 
                                    </option>
                                    <option value="major" {{ old('type') == 'major' ? 'selected' : '' }}>
                                        {{ __('messages.Major') }}
                                    </option>
                                </select>
                                <div class="form-text">
                                    {{ __('messages.Type determines how this category can be used in the system') }}
                                </div>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Sort Order -->
                            <div class="col-md-6">
                                <label for="sort_order" class="form-label">{{ __('messages.Sort Order') }}</label>
                                <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                       id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Icon -->
                            <div class="col-md-6">
                                <label for="icon" class="form-label">{{ __('messages.Icon') }}</label>
                                <div class="input-group">
                                    <input type="text" class="form-control @error('icon') is-invalid @enderror" 
                                           id="icon" name="icon" value="{{ old('icon') }}" 
                                           placeholder="fas fa-folder">
                                    <button type="button" class="btn btn-outline-secondary" 
                                            data-bs-toggle="modal" data-bs-target="#iconModal">
                                        <i class="fas fa-icons"></i>
                                    </button>
                                </div>
                                <div class="form-text">{{ __('messages.Font Awesome icon class (e.g., fas fa-folder)') }}</div>
                                @error('icon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Color -->
                            <div class="col-md-6">
                                <label for="color" class="form-label">{{ __('messages.Color') }}</label>
                                <input type="color" class="form-control form-control-color @error('color') is-invalid @enderror" 
                                       id="color" name="color" value="{{ old('color', '#007bff') }}">
                                @error('color')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Arabic Description -->
                            <div class="col-12">
                                <label for="description_ar" class="form-label">{{ __('messages.Arabic Description') }}</label>
                                <textarea class="form-control @error('description_ar') is-invalid @enderror" 
                                          id="description_ar" name="description_ar" rows="3">{{ old('description_ar') }}</textarea>
                                @error('description_ar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- English Description -->
                            <div class="col-12">
                                <label for="description_en" class="form-label">{{ __('messages.English Description') }}</label>
                                <textarea class="form-control @error('description_en') is-invalid @enderror" 
                                          id="description_en" name="description_en" rows="3">{{ old('description_en') }}</textarea>
                                @error('description_en')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" 
                                           id="is_active" name="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        {{ __('messages.Category is active') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                                {{ __('messages.Cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>{{ __('messages.Save Category') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Icon Selection Modal -->
<div class="modal fade" id="iconModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('messages.Select Icon') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2" id="iconGrid">
                    <!-- Common icons -->
                    <div class="col-2 text-center">
                        <button type="button" class="btn btn-outline-secondary icon-btn" data-icon="fas fa-folder">
                            <i class="fas fa-folder fa-2x"></i><br>
                            <small>folder</small>
                        </button>
                    </div>
                    <div class="col-2 text-center">
                        <button type="button" class="btn btn-outline-secondary icon-btn" data-icon="fas fa-book">
                            <i class="fas fa-book fa-2x"></i><br>
                            <small>book</small>
                        </button>
                    </div>
                    <div class="col-2 text-center">
                        <button type="button" class="btn btn-outline-secondary icon-btn" data-icon="fas fa-graduation-cap">
                            <i class="fas fa-graduation-cap fa-2x"></i><br>
                            <small>graduation-cap</small>
                        </button>
                    </div>
                    <div class="col-2 text-center">
                        <button type="button" class="btn btn-outline-secondary icon-btn" data-icon="fas fa-school">
                            <i class="fas fa-school fa-2x"></i><br>
                            <small>school</small>
                        </button>
                    </div>
                    <div class="col-2 text-center">
                        <button type="button" class="btn btn-outline-secondary icon-btn" data-icon="fas fa-university">
                            <i class="fas fa-university fa-2x"></i><br>
                            <small>university</small>
                        </button>
                    </div>
                    <div class="col-2 text-center">
                        <button type="button" class="btn btn-outline-secondary icon-btn" data-icon="fas fa-globe">
                            <i class="fas fa-globe fa-2x"></i><br>
                            <small>globe</small>
                        </button>
                    </div>
                    <div class="col-2 text-center">
                        <button type="button" class="btn btn-outline-secondary icon-btn" data-icon="fas fa-flask">
                            <i class="fas fa-flask fa-2x"></i><br>
                            <small>flask</small>
                        </button>
                    </div>
                    <div class="col-2 text-center">
                        <button type="button" class="btn btn-outline-secondary icon-btn" data-icon="fas fa-calculator">
                            <i class="fas fa-calculator fa-2x"></i><br>
                            <small>calculator</small>
                        </button>
                    </div>
                    <div class="col-2 text-center">
                        <button type="button" class="btn btn-outline-secondary icon-btn" data-icon="fas fa-microscope">
                            <i class="fas fa-microscope fa-2x"></i><br>
                            <small>microscope</small>
                        </button>
                    </div>
                    <div class="col-2 text-center">
                        <button type="button" class="btn btn-outline-secondary icon-btn" data-icon="fas fa-language">
                            <i class="fas fa-language fa-2x"></i><br>
                            <small>language</small>
                        </button>
                    </div>
                    <div class="col-2 text-center">
                        <button type="button" class="btn btn-outline-secondary icon-btn" data-icon="fas fa-stethoscope">
                            <i class="fas fa-stethoscope fa-2x"></i><br>
                            <small>stethoscope</small>
                        </button>
                    </div>
                    <div class="col-2 text-center">
                        <button type="button" class="btn btn-outline-secondary icon-btn" data-icon="fas fa-briefcase">
                            <i class="fas fa-briefcase fa-2x"></i><br>
                            <small>briefcase</small>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Icon selection
    document.querySelectorAll('.icon-btn').forEach(button => {
        button.addEventListener('click', function() {
            const icon = this.dataset.icon;
            document.getElementById('icon').value = icon;
            const modal = bootstrap.Modal.getInstance(document.getElementById('iconModal'));
            modal.hide();
        });
    });
});
</script>
@endpush
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">{{ __('Add Category') }}</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">{{ __('Categories') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('Add Category') }}</li>
                        </ol>
                    </nav>
                </div>
                <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>{{ __('Back') }}
                </a>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Category Information') }}</h5>
                </div>
                
                <form method="POST" action="{{ route('categories.store') }}">
                    @csrf
                    
                    <div class="card-body">
                        <div class="row g-3">
                            <!-- Arabic Name -->
                            <div class="col-md-6">
                                <label for="name_ar" class="form-label required">{{ __('Arabic Name') }}</label>
                                <input type="text" class="form-control @error('name_ar') is-invalid @enderror" 
                                       id="name_ar" name="name_ar" value="{{ old('name_ar') }}" required>
                                @error('name_ar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- English Name -->
                            <div class="col-md-6">
                                <label for="name_en" class="form-label">{{ __('English Name') }}</label>
                                <input type="text" class="form-control @error('name_en') is-invalid @enderror" 
                                       id="name_en" name="name_en" value="{{ old('name_en') }}">
                                @error('name_en')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Parent Category -->
                            <div class="col-md-6">
                                <label for="parent_id" class="form-label">{{ __('Parent Category') }}</label>
                                <select class="form-control @error('parent_id') is-invalid @enderror" 
                                        id="parent_id" name="parent_id">
                                    <option value="">{{ __('Root Category') }}</option>
                                    @foreach($parentCategories as $parent)
                                        <option value="{{ $parent['category']->id }}" 
                                                {{ (old('parent_id', $parentId) == $parent['category']->id) ? 'selected' : '' }}>
                                            {{ $parent['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('parent_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Sort Order -->
                            <div class="col-md-6">
                                <label for="sort_order" class="form-label">{{ __('Sort Order') }}</label>
                                <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                       id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Icon -->
                            <div class="col-md-6">
                                <label for="icon" class="form-label">{{ __('Icon') }}</label>
                                <div class="input-group">
                                    <input type="text" class="form-control @error('icon') is-invalid @enderror" 
                                           id="icon" name="icon" value="{{ old('icon') }}" 
                                           placeholder="fas fa-folder">
                                    <button type="button" class="btn btn-outline-secondary" 
                                            data-bs-toggle="modal" data-bs-target="#iconModal">
                                        <i class="fas fa-icons"></i>
                                    </button>
                                </div>
                                <div class="form-text">{{ __('Font Awesome icon class (e.g., fas fa-folder)') }}</div>
                                @error('icon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Color -->
                            <div class="col-md-6">
                                <label for="color" class="form-label">{{ __('Color') }}</label>
                                <input type="color" class="form-control form-control-color @error('color') is-invalid @enderror" 
                                       id="color" name="color" value="{{ old('color', '#007bff') }}">
                                @error('color')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Arabic Description -->
                            <div class="col-12">
                                <label for="description_ar" class="form-label">{{ __('Arabic Description') }}</label>
                                <textarea class="form-control @error('description_ar') is-invalid @enderror" 
                                          id="description_ar" name="description_ar" rows="3">{{ old('description_ar') }}</textarea>
                                @error('description_ar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- English Description -->
                            <div class="col-12">
                                <label for="description_en" class="form-label">{{ __('English Description') }}</label>
                                <textarea class="form-control @error('description_en') is-invalid @enderror" 
                                          id="description_en" name="description_en" rows="3">{{ old('description_en') }}</textarea>
                                @error('description_en')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" 
                                           id="is_active" name="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        {{ __('Category is active') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>{{ __('Save Category') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Icon Selection Modal -->
<div class="modal fade" id="iconModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Select Icon') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2" id="iconGrid">
                    <!-- Common icons -->
                    <div class="col-2 text-center">
                        <button type="button" class="btn btn-outline-secondary icon-btn" data-icon="fas fa-folder">
                            <i class="fas fa-folder fa-2x"></i><br>
                            <small>folder</small>
                        </button>
                    </div>
                    <div class="col-2 text-center">
                        <button type="button" class="btn btn-outline-secondary icon-btn" data-icon="fas fa-book">
                            <i class="fas fa-book fa-2x"></i><br>
                            <small>book</small>
                        </button>
                    </div>
                    <div class="col-2 text-center">
                        <button type="button" class="btn btn-outline-secondary icon-btn" data-icon="fas fa-graduation-cap">
                            <i class="fas fa-graduation-cap fa-2x"></i><br>
                            <small>graduation-cap</small>
                        </button>
                    </div>
                    <div class="col-2 text-center">
                        <button type="button" class="btn btn-outline-secondary icon-btn" data-icon="fas fa-school">
                            <i class="fas fa-school fa-2x"></i><br>
                            <small>school</small>
                        </button>
                    </div>
                    <div class="col-2 text-center">
                        <button type="button" class="btn btn-outline-secondary icon-btn" data-icon="fas fa-university">
                            <i class="fas fa-university fa-2x"></i><br>
                            <small>university</small>
                        </button>
                    </div>
                    <div class="col-2 text-center">
                        <button type="button" class="btn btn-outline-secondary icon-btn" data-icon="fas fa-globe">
                            <i class="fas fa-globe fa-2x"></i><br>
                            <small>globe</small>
                        </button>
                    </div>
                    <div class="col-2 text-center">
                        <button type="button" class="btn btn-outline-secondary icon-btn" data-icon="fas fa-flask">
                            <i class="fas fa-flask fa-2x"></i><br>
                            <small>flask</small>
                        </button>
                    </div>
                    <div class="col-2 text-center">
                        <button type="button" class="btn btn-outline-secondary icon-btn" data-icon="fas fa-calculator">
                            <i class="fas fa-calculator fa-2x"></i><br>
                            <small>calculator</small>
                        </button>
                    </div>
                    <div class="col-2 text-center">
                        <button type="button" class="btn btn-outline-secondary icon-btn" data-icon="fas fa-microscope">
                            <i class="fas fa-microscope fa-2x"></i><br>
                            <small>microscope</small>
                        </button>
                    </div>
                    <div class="col-2 text-center">
                        <button type="button" class="btn btn-outline-secondary icon-btn" data-icon="fas fa-language">
                            <i class="fas fa-language fa-2x"></i><br>
                            <small>language</small>
                        </button>
                    </div>
                    <div class="col-2 text-center">
                        <button type="button" class="btn btn-outline-secondary icon-btn" data-icon="fas fa-stethoscope">
                            <i class="fas fa-stethoscope fa-2x"></i><br>
                            <small>stethoscope</small>
                        </button>
                    </div>
                    <div class="col-2 text-center">
                        <button type="button" class="btn btn-outline-secondary icon-btn" data-icon="fas fa-briefcase">
                            <i class="fas fa-briefcase fa-2x"></i><br>
                            <small>briefcase</small>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Icon selection
    document.querySelectorAll('.icon-btn').forEach(button => {
        button.addEventListener('click', function() {
            const icon = this.dataset.icon;
            document.getElementById('icon').value = icon;
            const modal = bootstrap.Modal.getInstance(document.getElementById('iconModal'));
            modal.hide();
        });
    });
});
</script>
@endsection
