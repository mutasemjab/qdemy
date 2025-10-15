@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.Student Opinions Management') }}</h3>
                    @can('opinion-add')
                        <a href="{{ route('opinions.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> {{ __('messages.Add Opinion') }}
                        </a>
                    @endcan
                </div>

                <!-- Filters -->
                <div class="card-body">
                    <form method="GET" action="{{ route('opinions.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="{{ __('messages.Search opinions...') }}" 
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="rating" class="form-control">
                                    <option value="">{{ __('messages.All Ratings') }}</option>
                                    <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>
                                        ⭐⭐⭐⭐⭐ (4.5-5.0)
                                    </option>
                                    <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>
                                        ⭐⭐⭐⭐ (3.5-4.4)
                                    </option>
                                    <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>
                                        ⭐⭐⭐ (2.5-3.4)
                                    </option>
                                    <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>
                                        ⭐⭐ (1.5-2.4)
                                    </option>
                                    <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>
                                        ⭐ (0-1.4)
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-info">
                                    <i class="fas fa-search"></i> {{ __('messages.Search') }}
                                </button>
                                <a href="{{ route('opinions.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-redo"></i> {{ __('messages.Reset') }}
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Opinions Grid -->
                    <div class="opinions-grid">
                        @forelse($opinions as $opinion)
                            <div class="opinion-card">
                                <div class="opinion-header">
                                    <div class="student-info">
                                        <div class="student-avatar">
                                            @if($opinion->photo)
                                                <img src="{{ asset('assets/admin/uploads/' . $opinion->photo) }}" 
                                                     alt="{{ $opinion->name }}">
                                            @else
                                                <div class="avatar-placeholder">
                                                    {{ substr($opinion->name, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="student-details">
                                            <h6 class="student-name">{{ $opinion->name }}</h6>
                                            <div class="rating">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= floor($opinion->number_of_star))
                                                        <i class="fas fa-star text-warning"></i>
                                                    @elseif($i - 0.5 <= $opinion->number_of_star)
                                                        <i class="fas fa-star-half-alt text-warning"></i>
                                                    @else
                                                        <i class="far fa-star text-muted"></i>
                                                    @endif
                                                @endfor
                                                <span class="rating-number">({{ $opinion->number_of_star }})</span>
                                            </div>
                                        </div>
                                    </div>
                                   
                                </div>

                                <div class="opinion-body">
                                    <h6 class="opinion-title">{{ $opinion->title }}</h6>
                                    <p class="opinion-description">{{ Str::limit($opinion->description, 150) }}</p>
                                </div>

                                <div class="opinion-actions">
                                   
                                    @can('opinion-edit')
                                        <a href="{{ route('opinions.edit', $opinion) }}" 
                                           class="btn btn-sm btn-warning" title="{{ __('messages.Edit') }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endcan
                                    @can('opinion-delete')
                                        <form action="{{ route('opinions.destroy', $opinion) }}" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('{{ __('messages.Are you sure you want to delete this opinion?') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    title="{{ __('messages.Delete') }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <i class="fas fa-comments fa-3x text-muted"></i>
                                <h5 class="text-muted mt-3">{{ __('messages.No opinions found') }}</h5>
                                @can('opinion-add')
                                    <a href="{{ route('opinions.create') }}" class="btn btn-primary mt-2">
                                        <i class="fas fa-plus"></i> {{ __('messages.Create your first opinion') }}
                                    </a>
                                @endcan
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($opinions->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $opinions->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.opinions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: 20px;
}

.opinion-card {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    height: fit-content;
}

.opinion-card:hover {
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    transform: translateY(-2px);
}

.opinion-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 15px;
}

.student-info {
    display: flex;
    align-items: center;
}

.student-avatar {
    width: 50px;
    height: 50px;
    margin-right: 12px;
    flex-shrink: 0;
}

.student-avatar img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #f8f9fa;
}

.avatar-placeholder {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    font-weight: bold;
}

.student-details {
    flex: 1;
}

.student-name {
    margin: 0 0 5px 0;
    color: #333;
    font-weight: 600;
}

.rating {
    display: flex;
    align-items: center;
    gap: 2px;
}

.rating-number {
    margin-left: 8px;
    font-size: 0.9rem;
    color: #666;
    font-weight: 500;
}

.opinion-date {
    text-align: right;
}

.opinion-body {
    margin-bottom: 15px;
}

.opinion-title {
    color: #2c3e50;
    font-weight: 600;
    margin-bottom: 8px;
    font-size: 1.1rem;
}

.opinion-description {
    color: #555;
    line-height: 1.6;
    margin: 0;
}

.opinion-actions {
    display: flex;
    gap: 5px;
    justify-content: flex-end;
    padding-top: 15px;
    border-top: 1px solid #f8f9fa;
}

.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 20px;
}

@media (max-width: 768px) {
    .opinions-grid {
        grid-template-columns: 1fr;
    }
    
    .opinion-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .opinion-date {
        margin-top: 10px;
        text-align: left;
    }
}
</style>
@endsection