@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.Add New User') }}</h3>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('messages.Back to Users') }}
                    </a>
                </div>

                <div class="card-body">
                    <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="required">{{ __('messages.Name') }}</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                          

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">{{ __('messages.Email') }}</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">{{ __('messages.Phone') }}</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone') }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">{{ __('messages.Password') }}</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="clas_id">{{ __('messages.Class') }}</label>
                                    <select class="form-control @error('clas_id') is-invalid @enderror" 
                                            id="clas_id" name="clas_id">
                                        <option value="">{{ __('messages.Select Class') }}</option>
                                        @foreach($classes as $clas)
                                            <option value="{{ $clas->id }}" 
                                                    {{ old('clas_id') == $clas->id ? 'selected' : '' }}>
                                                {{ $clas->name_ar }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('clas_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Additional Information -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="balance">{{ __('messages.Balance') }}</label>
                                    <input type="number" step="0.01" min="0" 
                                           class="form-control @error('balance') is-invalid @enderror" 
                                           id="balance" name="balance" value="{{ old('balance', 0) }}">
                                    @error('balance')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="referal_code">{{ __('messages.Referral Code') }}</label>
                                    <input type="text" class="form-control @error('referal_code') is-invalid @enderror" 
                                           id="referal_code" name="referal_code" value="{{ old('referal_code') }}">
                                    @error('referal_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">{{ __('messages.Leave empty to auto-generate') }}</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="activate" class="required">{{ __('messages.Status') }}</label>
                                    <select class="form-control @error('activate') is-invalid @enderror" 
                                            id="activate" name="activate" required>
                                        <option value="1" {{ old('activate', 1) == 1 ? 'selected' : '' }}>
                                            {{ __('messages.Active') }}
                                        </option>
                                        <option value="2" {{ old('activate') == 2 ? 'selected' : '' }}>
                                            {{ __('messages.Inactive') }}
                                        </option>
                                    </select>
                                    @error('activate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="photo">{{ __('messages.Photo') }}</label>
                                    <input type="file" class="form-control-file @error('photo') is-invalid @enderror" 
                                           id="photo" name="photo" accept="image/*">
                                    @error('photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">{{ __('messages.Accepted formats: jpeg, png, jpg, gif. Max size: 2MB') }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> {{ __('messages.Create User') }}
                            </button>
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> {{ __('messages.Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.required:after {
    content: " *";
    color: red;
}
</style>
@endsection