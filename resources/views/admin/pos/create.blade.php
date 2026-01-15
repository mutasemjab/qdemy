@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>{{ __('messages.create_pos') }}</h4>
                    <a href="{{ route('pos.index') }}" class="btn btn-secondary">
                        {{ __('messages.back_to_list') }}
                    </a>
                </div>

                <div class="card-body">
                    <form action="{{ route('pos.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('messages.country_name') }} <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('country_name') is-invalid @enderror" 
                                   id="country_name" 
                                   name="country_name" 
                                   value="{{ old('country_name') }}" 
                                   placeholder="{{ __('messages.enter_country_name') }}"
                                   required>
                            @error('country_name')
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
                                   placeholder="{{ __('messages.enter_name') }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">{{ __('messages.phone') }} <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   id="phone"
                                   name="phone"
                                   value="{{ old('phone') }}"
                                   placeholder="{{ __('messages.enter_phone') }}"
                                   required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   placeholder="example@gmail.com"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">{{ __('messages.google_map_link') }} <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('google_map_link') is-invalid @enderror" 
                                   id="google_map_link" 
                                   name="google_map_link" 
                                   value="{{ old('google_map_link') }}" 
                                   placeholder="{{ __('messages.enter_google_map_link') }}"
                                   required>
                            @error('google_map_link')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">{{ __('messages.address') }} <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('address') is-invalid @enderror"
                                      id="address"
                                      name="address"
                                      rows="3"
                                      placeholder="{{ __('messages.enter_address') }}"
                                      required>{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('messages.Password') }} <span class="text-danger">*</span></label>
                            <input type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   id="password"
                                   name="password"
                                   placeholder="أدخل كلمة المرور"
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">يجب أن تكون على الأقل 6 أحرف</small>
                        </div>

                        <div class="mb-3">
                            <label for="percentage" class="form-label">النسبة المئوية (%) <span class="text-danger">*</span></label>
                            <input type="number"
                                   step="0.01"
                                   min="0"
                                   max="100"
                                   class="form-control @error('percentage') is-invalid @enderror"
                                   id="percentage"
                                   name="percentage"
                                   value="{{ old('percentage', 0) }}"
                                   placeholder="أدخل النسبة المئوية"
                                   required>
                            @error('percentage')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('pos.index') }}" class="btn btn-secondary me-md-2">
                                {{ __('messages.cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                {{ __('messages.create') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection