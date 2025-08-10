@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.Edit User') }}: {{ $user->name }}</h3>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('messages.Back to Users') }}
                    </a>
                </div>

                <div class="card-body">
                    <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Current Photo Display -->
                            @if($user->photo)
                                <div class="col-12 mb-3">
                                    <div class="form-group">
                                        <label>{{ __('messages.Current Photo') }}</label>
                                        <div>
                                            <img src="{{ asset('assets/admin/uploads/' . $user->photo) }}" 
                                                 alt="{{ $user->name }}" 
                                                 class="img-thumbnail" 
                                                 style="max-width: 150px; max-height: 150px;">
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Basic Information -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="required">{{ __('messages.Name') }}</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                           

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">{{ __('messages.Email') }}</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $user->email) }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">{{ __('messages.Phone') }}</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
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
                                    <small class="form-text text-muted">{{ __('messages.Leave empty to keep current password') }}</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="clas_id">{{ __('messages.Class') }}</label>
                                    <select class="form-control @error('clas_id') is-invalid @enderror" 
                                            id="clas_id" name="clas_id">
                                        <option value="">{{ __('messages.Select Class') }}</option>
                                        @foreach($categories as $clas)
                                            <option value="{{ $clas->id }}" 
                                                    {{ old('clas_id', $user->clas_id) == $clas->id ? 'selected' : '' }}>
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
                                           id="balance" name="balance" value="{{ old('balance', $user->balance) }}">
                                    @error('balance')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="referal_code">{{ __('messages.Referral Code') }}</label>
                                    <input type="text" class="form-control @error('referal_code') is-invalid @enderror" 
                                           id="referal_code" name="referal_code" value="{{ old('referal_code', $user->referal_code) }}">
                                    @error('referal_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="activate" class="required">{{ __('messages.Status') }}</label>
                                    <select class="form-control @error('activate') is-invalid @enderror" 
                                            id="activate" name="activate" required>
                                        <option value="1" {{ old('activate', $user->activate) == 1 ? 'selected' : '' }}>
                                            {{ __('messages.Active') }}
                                        </option>
                                        <option value="2" {{ old('activate', $user->activate) == 2 ? 'selected' : '' }}>
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
                                    <label for="photo">{{ __('messages.New Photo') }}</label>
                                    <input type="file" class="form-control-file @error('photo') is-invalid @enderror" 
                                           id="photo" name="photo" accept="image/*">
                                    @error('photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">{{ __('messages.Accepted formats: jpeg, png, jpg, gif. Max size: 2MB') }}</small>
                                </div>
                            </div>

                            <!-- Read-only Information -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('messages.Created At') }}</label>
                                    <input type="text" class="form-control" value="{{ $user->created_at->format('Y-m-d H:i:s') }}" readonly>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('messages.Last Updated') }}</label>
                                    <input type="text" class="form-control" value="{{ $user->updated_at->format('Y-m-d H:i:s') }}" readonly>
                                </div>
                            </div>

                            @if($user->last_login)
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('messages.Last Login') }}</label>
                                        <input type="text" class="form-control" value="{{ $user->last_login }}" readonly>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> {{ __('messages.Update User') }}
                            </button>
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> {{ __('messages.Cancel') }}
                            </a>
                            <a href="{{ route('users.show', $user) }}" class="btn btn-info">
                                <i class="fas fa-eye"></i> {{ __('messages.View User') }}
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