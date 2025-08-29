@extends('layouts.admin')

@section('title', __('messages.view_onboarding'))

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4>{{ __('messages.view_onboarding') }}</h4>
                        <div>
                            <a href="{{ route('onboardings.edit', $onboarding) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> {{ __('messages.edit') }}
                            </a>
                            <a href="{{ route('onboardings.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> {{ __('messages.back_to_list') }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        @if($onboarding->photo)
                            <div class="col-md-4 mb-4">
                                <img src="{{ $onboarding->photo_url }}" alt="{{ $onboarding->title }}" 
                                     class="img-fluid rounded shadow">
                            </div>
                        @endif
                        
                        <div class="col-md-{{ $onboarding->photo ? '8' : '12' }}">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <h6 class="text-muted">{{ __('messages.title_en') }}</h6>
                                    <p class="lead">{{ $onboarding->title_en }}</p>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <h6 class="text-muted">{{ __('messages.title_ar') }}</h6>
                                    <p class="lead" dir="rtl">{{ $onboarding->title_ar }}</p>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <h6 class="text-muted">{{ __('messages.description_en') }}</h6>
                                    <p>{{ $onboarding->description_en }}</p>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <h6 class="text-muted">{{ __('messages.description_ar') }}</h6>
                                    <p dir="rtl">{{ $onboarding->description_ar }}</p>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <h6 class="text-muted">{{ __('messages.created_at') }}</h6>
                                    <p>{{ $onboarding->created_at->format('Y-m-d H:i:s') }}</p>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <h6 class="text-muted">{{ __('messages.updated_at') }}</h6>
                                    <p>{{ $onboarding->updated_at->format('Y-m-d H:i:s') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <form action="{{ route('onboardings.destroy', $onboarding) }}" method="POST" 
                              onsubmit="return confirm('{{ __('messages.confirm_delete') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> {{ __('messages.delete') }}
                            </button>
                        </form>
                        
                        <div>
                            <a href="{{ route('onboardings.edit', $onboarding) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> {{ __('messages.edit') }}
                            </a>
                            <a href="{{ route('onboardings.index') }}" class="btn btn-secondary">
                                <i class="fas fa-list"></i> {{ __('messages.back_to_list') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection