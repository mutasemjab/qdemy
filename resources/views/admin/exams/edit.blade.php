{{-- resources/views/exams/edit.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>{{ __('messages.edit_exam') }}: {{ $exam->name }}</h4>
                </div>

                <div class="card-body">
                    <form action="{{ route('exams.update', $exam) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="name" class="form-label">{{ __('messages.exam_name') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $exam->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="description" class="form-label">{{ __('messages.description') }}</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="3">{{ old('description', $exam->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="category_exam_id" class="form-label">{{ __('messages.category') }} <span class="text-danger">*</span></label>
                                <select class="form-control @error('category_exam_id') is-invalid @enderror" 
                                        id="category_exam_id" name="category_exam_id" required>
                                    <option value="">{{ __('messages.select_category') }}</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" 
                                            {{ old('category_exam_id', $exam->category_exam_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_exam_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="duration_minutes" class="form-label">{{ __('messages.duration_minutes') }} <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('duration_minutes') is-invalid @enderror" 
                                       id="duration_minutes" name="duration_minutes" 
                                       value="{{ old('duration_minutes', $exam->duration_minutes) }}" min="1" required>
                                @error('duration_minutes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="start_time" class="form-label">{{ __('messages.start_time') }} <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control @error('start_time') is-invalid @enderror" 
                                       id="start_time" name="start_time" 
                                       value="{{ old('start_time', $exam->start_time->format('Y-m-d\TH:i')) }}" required>
                                @error('start_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="end_time" class="form-label">{{ __('messages.end_time') }} <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control @error('end_time') is-invalid @enderror" 
                                       id="end_time" name="end_time" 
                                       value="{{ old('end_time', $exam->end_time->format('Y-m-d\TH:i')) }}" required>
                                @error('end_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="total_grade" class="form-label">{{ __('messages.total_grade') }} <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control @error('total_grade') is-invalid @enderror" 
                                       id="total_grade" name="total_grade" 
                                       value="{{ old('total_grade', $exam->total_grade) }}" min="0" required>
                                @error('total_grade')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="pass_grade" class="form-label">{{ __('messages.pass_grade') }} <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control @error('pass_grade') is-invalid @enderror" 
                                       id="pass_grade" name="pass_grade" 
                                       value="{{ old('pass_grade', $exam->pass_grade) }}" min="0" required>
                                @error('pass_grade')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="max_attempts" class="form-label">{{ __('messages.max_attempts') }} <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('max_attempts') is-invalid @enderror" 
                                       id="max_attempts" name="max_attempts" 
                                       value="{{ old('max_attempts', $exam->max_attempts) }}" min="1" required>
                                @error('max_attempts')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" id="randomize_questions" 
                                           name="randomize_questions" value="1" 
                                           {{ old('randomize_questions', $exam->randomize_questions) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="randomize_questions">
                                        {{ __('messages.randomize_questions') }}
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" id="show_results_immediately" 
                                           name="show_results_immediately" value="1" 
                                           {{ old('show_results_immediately', $exam->show_results_immediately) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="show_results_immediately">
                                        {{ __('messages.show_results_immediately') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_active" 
                                           name="is_active" value="1" 
                                           {{ old('is_active', $exam->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        {{ __('messages.is_active') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="instructions" class="form-label">{{ __('messages.exam_instructions') }}</label>
                            <textarea class="form-control @error('instructions') is-invalid @enderror" 
                                      id="instructions" name="instructions" rows="4" 
                                      placeholder="{{ __('messages.exam_instructions_placeholder') }}">{{ old('instructions', is_array($exam->instructions) ? implode("\n", $exam->instructions) : $exam->instructions) }}</textarea>
                            @error('instructions')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('exams.show', $exam) }}" class="btn btn-secondary">
                                {{ __('messages.cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                {{ __('messages.update_exam') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

