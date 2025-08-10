@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.Teacher Profile') }}: {{ $teacher->name }}</h3>
                    <div>
                        @can('teacher-edit')
                            <a href="{{ route('teachers.edit', $teacher) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> {{ __('messages.Edit') }}
                            </a>
                        @endcan
                        <a href="{{ route('teachers.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.Back to Teachers') }}
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Teacher Photo and Basic Info -->
                        <div class="col-md-4 text-center">
                            @if($teacher->photo)
                                <img src="{{ asset('storage/' . $teacher->photo) }}" 
                                     alt="{{ $teacher->name }}" 
                                     class="img-fluid rounded shadow" 
                                     style="max-width: 250px; max-height: 300px; object-fit: cover;">
                            @else
                                <div class="bg-secondary text-white d-flex align-items-center justify-content-center rounded shadow mx-auto" 
                                     style="width: 250px; height: 300px; font-size: 5rem;">
                                    {{ substr($teacher->name, 0, 1) }}
                                </div>
                            @endif
                            
                            <h4 class="mt-3">{{ $teacher->name }}</h4>
                            <span class="badge badge-primary badge-lg">{{ $teacher->name_of_lesson }}</span>
                            
                            <!-- Social Media Links -->
                            <div class="social-links mt-3">
                                @if($teacher->facebook)
                                    <a href="{{ $teacher->facebook }}" target="_blank" class="btn btn-outline-primary btn-sm me-2" title="Facebook">
                                        <i class="fab fa-facebook"></i> Facebook
                                    </a>
                                @endif
                                @if($teacher->instagram)
                                    <a href="{{ $teacher->instagram }}" target="_blank" class="btn btn-outline-danger btn-sm me-2" title="Instagram">
                                        <i class="fab fa-instagram"></i> Instagram
                                    </a>
                                @endif
                                @if($teacher->youtube)
                                    <a href="{{ $teacher->youtube }}" target="_blank" class="btn btn-outline-danger btn-sm me-2" title="YouTube">
                                        <i class="fab fa-youtube"></i> YouTube
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- Teacher Details -->
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <div class="info-box-icon bg-primary">
                                            <i class="fas fa-chalkboard-teacher"></i>
                                        </div>
                                        <div class="info-box-content">
                                            <span class="info-box-text">{{ __('messages.Teacher ID') }}</span>
                                            <span class="info-box-number">#{{ $teacher->id }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-box">
                                        <div class="info-box-icon bg-{{ $teacher->user ? 'success' : 'secondary' }}">
                                            <i class="fas fa-{{ $teacher->user ? 'user-check' : 'user-times' }}"></i>
                                        </div>
                                        <div class="info-box-content">
                                            <span class="info-box-text">{{ __('messages.User Account') }}</span>
                                            <span class="info-box-number">
                                                {{ $teacher->user ? __('messages.Active') : __('messages.No Account') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- User Account Information -->
                            @if($teacher->user)
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="text-success mb-3">{{ __('messages.User Account Information') }}</h5>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <div class="info-box-icon bg-info">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                        <div class="info-box-content">
                                            <span class="info-box-text">{{ __('messages.Email') }}</span>
                                            <span class="info-box-number">
                                                <a href="mailto:{{ $teacher->user->email }}">{{ $teacher->user->email }}</a>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                @if($teacher->user->phone)
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <div class="info-box-icon bg-success">
                                            <i class="fas fa-phone"></i>
                                        </div>
                                        <div class="info-box-content">
                                            <span class="info-box-text">{{ __('messages.Phone') }}</span>
                                            <span class="info-box-number">
                                                <a href="tel:{{ $teacher->user->phone }}">{{ $teacher->user->phone }}</a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if($teacher->user->category)
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <div class="info-box-icon bg-warning">
                                            <i class="fas fa-tags"></i>
                                        </div>
                                        <div class="info-box-content">
                                            <span class="info-box-text">{{ __('messages.Category') }}</span>
                                            <span class="info-box-number">{{ $teacher->user->category->name }}</span>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <div class="col-md-6">
                                    <div class="info-box">
                                        <div class="info-box-icon bg-{{ $teacher->user->activate == 1 ? 'success' : 'danger' }}">
                                            <i class="fas fa-{{ $teacher->user->activate == 1 ? 'check-circle' : 'times-circle' }}"></i>
                                        </div>
                                        <div class="info-box-content">
                                            <span class="info-box-text">{{ __('messages.Account Status') }}</span>
                                            <span class="info-box-number">
                                                {{ $teacher->user->activate == 1 ? __('messages.Active') : __('messages.Inactive') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <!-- Descriptions -->
                    <div class="row">
                        @if($teacher->description_en)
                        <div class="col-md-6">
                            <h5>{{ __('messages.Description (English)') }}</h5>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <p class="mb-0">{{ $teacher->description_en }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($teacher->description_ar)
                        <div class="col-md-6">
                            <h5>{{ __('messages.Description (Arabic)') }}</h5>
                            <div class="card bg-light">
                                <div class="card-body" dir="rtl">
                                    <p class="mb-0">{{ $teacher->description_ar }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if(!$teacher->description_en && !$teacher->description_ar)
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> {{ __('messages.No description provided') }}
                            </div>
                        </div>
                        @endif
                    </div>

                    <hr>

                    <!-- Social Media Details -->
                    <div class="row">
                        <div class="col-12">
                            <h5>{{ __('messages.Social Media Presence') }}</h5>
                            @if($teacher->facebook || $teacher->instagram || $teacher->youtube)
                                <div class="row">
                                    @if($teacher->facebook)
                                    <div class="col-md-4">
                                        <div class="card border-primary">
                                            <div class="card-body text-center">
                                                <i class="fab fa-facebook fa-3x text-primary mb-2"></i>
                                                <h6>Facebook</h6>
                                                <a href="{{ $teacher->facebook }}" target="_blank" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-external-link-alt"></i> {{ __('messages.Visit Profile') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    @if($teacher->instagram)
                                    <div class="col-md-4">
                                        <div class="card border-danger">
                                            <div class="card-body text-center">
                                                <i class="fab fa-instagram fa-3x text-danger mb-2"></i>
                                                <h6>Instagram</h6>
                                                <a href="{{ $teacher->instagram }}" target="_blank" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-external-link-alt"></i> {{ __('messages.Visit Profile') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    @if($teacher->youtube)
                                    <div class="col-md-4">
                                        <div class="card border-danger">
                                            <div class="card-body text-center">
                                                <i class="fab fa-youtube fa-3x text-danger mb-2"></i>
                                                <h6>YouTube</h6>
                                                <a href="{{ $teacher->youtube }}" target="_blank" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-external-link-alt"></i> {{ __('messages.Visit Channel') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> {{ __('messages.No social media links provided') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <!-- Record Information -->
                    <div class="row">
                        <div class="col-md-6">
                            <h6>{{ __('messages.Record Information') }}</h6>
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td><strong>{{ __('messages.Created At') }}:</strong></td>
                                    <td>{{ $teacher->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>{{ __('messages.Last Updated') }}:</strong></td>
                                    <td>{{ $teacher->updated_at->format('M d, Y H:i') }}</td>
                                </tr>
                                @if($teacher->user)
                                <tr>
                                    <td><strong>{{ __('messages.User Created') }}:</strong></td>
                                    <td>{{ $teacher->user->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <div class="btn-group" role="group">
                                @can('teacher-edit')
                                    <a href="{{ route('teachers.edit', $teacher) }}" class="btn btn-warning">
                                        <i class="fas fa-edit"></i> {{ __('messages.Edit Teacher') }}
                                    </a>
                                @endcan
                                @can('teacher-delete')
                                    <form action="{{ route('teachers.destroy', $teacher) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('{{ __('messages.Are you sure you want to delete this teacher? This will also delete the associated user account if exists.') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-trash"></i> {{ __('messages.Delete Teacher') }}
                                        </button>
                                    </form>
                                @endcan
                                <a href="{{ route('teachers.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-list"></i> {{ __('messages.All Teachers') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.badge-lg {
    font-size: 1rem;
    padding: 0.5rem 1rem;
}

.info-box {
    display: flex;
    margin-bottom: 1rem;
    background: #fff;
    border: 1px solid #dee2e6;
    border-radius: 0.25rem;
    box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
}

.info-box-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    width: 70px;
    text-align: center;
    font-size: 1.875rem;
}

.info-box-content {
    padding: 0.5rem;
    flex: 1;
}

.info-box-text {
    display: block;
    font-size: 0.875rem;
    color: #6c757d;
    text-transform: uppercase;
}

.info-box-number {
    display: block;
    font-weight: 700;
    font-size: 1.125rem;
}

.info-box-number a {
    color: inherit;
    text-decoration: none;
}

.info-box-number a:hover {
    text-decoration: underline;
}

.social-links a {
    margin: 0.25rem;
}
</style>
@endsection