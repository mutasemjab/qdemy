@extends('layouts.admin')

@section('title', __('messages.Add_Banned_Word'))

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('messages.Add_Banned_Word') }}</h3>
        </div>

        <div class="card-body">
            @can('bannedWord-add')
                <form action="{{ route('banned-words.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="word" class="form-label">{{ __('messages.word') }}</label>
                        <input type="text" name="word" id="word" class="form-control" value="{{ old('word') }}" required>
                        @error('word') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="language" class="form-label">{{ __('messages.language') }}</label>
                        <select name="language" id="language" class="form-control" required>
                            <option value="ar">{{ __('messages.language_ar') }}</option>
                            <option value="en">{{ __('messages.language_en') }}</option>
                            <option value="both">{{ __('messages.language_both') }}</option>
                        </select>
                        @error('language') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="type" class="form-label">{{ __('messages.type') }}</label>
                        <select name="type" id="type" class="form-control" required>
                            <option value="profanity">{{ __('messages.type_profanity') }} (Profanity)</option>
                            <option value="political">{{ __('messages.type_political') }} (Political)</option>
                            <option value="spam">{{ __('messages.type_spam') }} (Spam)</option>
                            <option value="other">{{ __('messages.type_other') }} (Other)</option>
                        </select>
                        @error('type') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="severity" class="form-label">{{ __('messages.severity') }}</label>
                        <input type="number" name="severity" id="severity" class="form-control" value="{{ old('severity', 5) }}"
                            min="1" max="10" required>
                        @error('severity') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-success">{{ __('messages.Save') }}</button>
                        <a href="{{ route('banned-words.index') }}" class="btn btn-secondary">{{ __('messages.Cancel') }}</a>
                    </div>
                </form>
            @else
                <div class="alert alert-warning">{{ __('messages.no_permission_add_words') }}</div>
            @endcan
        </div>
    </div>
@endsection