@extends('layouts.admin')

@section('title', __('messages.edit_doseyat'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.edit_doseyat') }}</h3>
                    <a href="{{ route('doseyats.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
                    </a>
                </div>

                <form action="{{ route('doseyats.update', $doseyat) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">{{ __('messages.name') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $doseyat->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="price">{{ __('messages.price') }} <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                           id="price" name="price" value="{{ old('price', $doseyat->price) }}" step="0.01" min="0" required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="pos_id">{{ __('messages.pos') }}</label>
                                    <select class="form-control @error('pos_id') is-invalid @enderror" 
                                            id="pos_id" name="pos_id">
                                        <option value="">{{ __('messages.select_pos') }}</option>
                                        @foreach($posList as $pos)
                                            <option value="{{ $pos->id }}" {{ old('pos_id', $doseyat->pos_id) == $pos->id ? 'selected' : '' }}>
                                                {{ $pos->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('pos_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="teacher_id">{{ __('messages.teacher') }}</label>
                                    <select class="form-control @error('teacher_id') is-invalid @enderror" 
                                            id="teacher_id" name="teacher_id">
                                        <option value="">{{ __('messages.select_teacher') }}</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}" {{ old('teacher_id', $doseyat->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                                {{ $teacher->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('teacher_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="category_id">{{ __('messages.category') }}</label>
                                    <select class="form-control @error('category_id') is-invalid @enderror" 
                                            id="category_id" name="category_id">
                                        <option value="">{{ __('messages.select_category') }}</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $doseyat->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ app()->getLocale() == 'ar' ? $category->name_ar : $category->name_en }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="photo">{{ __('messages.photo') }}</label>
                            <input type="file" class="form-control @error('photo') is-invalid @enderror" 
                                   id="photo" name="photo" accept="image/*">
                            @error('photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">{{ __('messages.leave_empty_keep_current') }}</small>
                        </div>

                        <div class="form-group">
                            <label>{{ __('messages.current_photo') }}</label>
                            <div>
                                <img src="{{ asset('assets/admin/uploads/' . $doseyat->photo) }}" alt="{{ $doseyat->name }}" 
                                     style="max-width: 200px;">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>{{ __('messages.new_photo_preview') }}</label>
                            <div>
                                <img id="preview" src="#" alt="" style="max-width: 200px; display: none;">
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> {{ __('messages.update') }}
                        </button>
                        <a href="{{ route('doseyats.index') }}" class="btn btn-secondary">
                            {{ __('messages.cancel') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('photo').addEventListener('change', function(e) {
    const preview = document.getElementById('preview');
    const file = e.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
});
</script>
@endpush
@endsection