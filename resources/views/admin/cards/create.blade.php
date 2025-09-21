@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>{{ __('messages.create_card') }}</h4>
                    <a href="{{ route('cards.index') }}" class="btn btn-secondary">
                        {{ __('messages.back_to_list') }}
                    </a>
                </div>

                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('cards.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="pos_id" class="form-label">{{ __('messages.pos') }}</label>
                            <select class="form-control @error('pos_id') is-invalid @enderror"
                                    id="pos_id"
                                    name="pos_id">
                                <option value="">{{ __('messages.select_pos') }}</option>
                                @foreach($posRecords as $pos)
                                    <option value="{{ $pos->id }}" {{ old('pos_id') == $pos->id ? 'selected' : '' }}>
                                        {{ $pos->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('pos_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="category" class="form-label">{{ __('messages.category') }}</label>
                            <select class="form-control @error('category_id') is-invalid @enderror"
                                    id="category"
                                    name="category_id">
                                <option value="">{{ __('messages.select_category') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name_ar }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="mb-3">
                            <label for="teacher" class="form-label">{{ __('messages.teacher') }}</label>
                            <select class="form-control @error('teacher_id') is-invalid @enderror"
                                    id="teacher"
                                    name="teacher_id">
                                <option value="">{{ __('messages.select_teacher') }}</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('teacher_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>



                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('messages.name') }} <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') }}"
                                   placeholder="{{ __('messages.enter_card_name') }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">{{ __('messages.price') }} <span class="text-danger">*</span></label>
                            <input type="number"
                                   class="form-control @error('price') is-invalid @enderror"
                                   id="price"
                                   name="price"
                                   value="{{ old('price') }}"
                                   placeholder="{{ __('messages.enter_price') }}"
                                   step="0.01"
                                   min="0"
                                   required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="number_of_cards" class="form-label">{{ __('messages.number_of_cards') }} <span class="text-danger">*</span></label>
                            <input type="number"
                                   class="form-control @error('number_of_cards') is-invalid @enderror"
                                   id="number_of_cards"
                                   name="number_of_cards"
                                   value="{{ old('number_of_cards') }}"
                                   placeholder="{{ __('messages.enter_number_of_cards') }}"
                                   min="1"
                                   max="10000"
                                   required>
                            @error('number_of_cards')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">{{ __('messages.number_of_cards_help') }}</div>
                        </div>

                        <div class="mb-3">
                            <label for="photo" class="form-label">{{ __('messages.photo') }} <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('photo') is-invalid @enderror" 
                                   id="photo" name="photo" accept="image/*" required>
                            @error('photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">{{ __('messages.photo_requirements') }}</div>
                        </div>

                        <div class="alert alert-info">
                            <strong>{{ __('messages.note') }}:</strong> {{ __('messages.card_generation_note') }}
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('cards.index') }}" class="btn btn-secondary me-md-2">
                                {{ __('messages.cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                {{ __('messages.create_and_generate') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
