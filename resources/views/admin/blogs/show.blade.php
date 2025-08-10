@extends('layouts.admin')

@section('title', __('messages.view_blog'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.blog_details') }}</h3>
                    <div class="card-tools">
                        @can('blog-edit')
                        <a href="{{ route('blogs.edit', $blog) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> {{ __('messages.edit') }}
                        </a>
                        @endcan
                        <a href="{{ route('blogs.index') }}" class="btn btn-secondary ml-2">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Cover Photo -->
                        @if($blog->photo_cover)
                        <div class="col-12 mb-4">
                            <div class="text-center">
                                <img src="{{ asset('assets/admin/uploads/' . $blog->photo_cover) }}" alt="{{ $blog->title }}" 
                                     class="img-fluid rounded shadow" style="max-height: 400px; width: 100%; object-fit: cover;">
                            </div>
                        </div>
                        @endif

                        <div class="col-md-8">
                            <!-- Titles -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <div class="info-box-content">
                                            <span class="info-box-text">{{ __('messages.title_ar') }}</span>
                                            <span class="info-box-number" dir="rtl">{{ $blog->title_ar }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <div class="info-box-content">
                                            <span class="info-box-text">{{ __('messages.title_en') }}</span>
                                            <span class="info-box-number">{{ $blog->title_en }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Descriptions -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card card-outline card-info">
                                        <div class="card-header">
                                            <h3 class="card-title">{{ __('messages.description_ar') }}</h3>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-justify" dir="rtl">{{ $blog->description_ar }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card card-outline card-info">
                                        <div class="card-header">
                                            <h3 class="card-title">{{ __('messages.description_en') }}</h3>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-justify">{{ $blog->description_en }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Timestamps -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-box bg-success">
                                        <span class="info-box-icon"><i class="fas fa-calendar-plus"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">{{ __('messages.created_at') }}</span>
                                            <span class="info-box-number">{{ $blog->created_at->format('Y-m-d H:i') }}</span>
                                            <span class="progress-description">{{ $blog->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-box bg-warning">
                                        <span class="info-box-icon"><i class="fas fa-calendar-edit"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">{{ __('messages.updated_at') }}</span>
                                            <span class="info-box-number">{{ $blog->updated_at->format('Y-m-d H:i') }}</span>
                                            <span class="progress-description">{{ $blog->updated_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Main Photo -->
                        <div class="col-md-4">
                            @if($blog->photo)
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">{{ __('messages.main_photo') }}</h3>
                                </div>
                                <div class="card-body text-center">
                                    <img src="{{ asset('assets/admin/uploads/' . $blog->photo) }}" alt="{{ $blog->title }}" 
                                         class="img-fluid rounded shadow-sm" style="max-height: 300px;">
                                </div>
                            </div>
                            @else
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">{{ __('messages.main_photo') }}</h3>
                                </div>
                                <div class="card-body text-center">
                                    <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                         style="height: 200px;">
                                        <div class="text-center">
                                            <i class="fas fa-image fa-3x text-muted"></i>
                                            <p class="text-muted mt-2">{{ __('messages.no_image') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Actions -->
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h3 class="card-title">{{ __('messages.actions') }}</h3>
                                </div>
                                <div class="card-body">
                                    @can('blog-edit')
                                    <a href="{{ route('blogs.edit', $blog) }}" class="btn btn-warning btn-block">
                                        <i class="fas fa-edit"></i> {{ __('messages.edit_blog') }}
                                    </a>
                                    @endcan
                                    
                                    @can('blog-delete')
                                    <form action="{{ route('blogs.destroy', $blog) }}" method="POST" class="mt-2">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-block" 
                                                onclick="return confirm('{{ __('messages.confirm_delete') }}')">
                                            <i class="fas fa-trash"></i> {{ __('messages.delete_blog') }}
                                        </button>
                                    </form>
                                    @endcan
                                    
                                    <a href="{{ route('blogs.index') }}" class="btn btn-secondary btn-block mt-2">
                                        <i class="fas fa-list"></i> {{ __('messages.all_blogs') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


