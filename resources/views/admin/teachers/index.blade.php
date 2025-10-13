@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.Teachers Management') }}</h3>
                    @can('teacher-add')
                        <a href="{{ route('teachers.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> {{ __('messages.Add Teacher') }}
                        </a>
                    @endcan
                </div>

                <!-- Filters -->
                <div class="card-body">
                    <form method="GET" action="{{ route('teachers.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="{{ __('messages.Search teachers...') }}" 
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="lesson" class="form-control" 
                                       placeholder="{{ __('messages.Lesson') }}" 
                                       value="{{ request('lesson') }}">
                            </div>
                            <div class="col-md-2">
                                <select name="has_user" class="form-control">
                                    <option value="">{{ __('messages.All Teachers') }}</option>
                                    <option value="yes" {{ request('has_user') == 'yes' ? 'selected' : '' }}>
                                        {{ __('messages.With User Account') }}
                                    </option>
                                    <option value="no" {{ request('has_user') == 'no' ? 'selected' : '' }}>
                                        {{ __('messages.Without User Account') }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <button type="submit" class="btn btn-info">
                                    <i class="fas fa-search"></i> {{ __('messages.Search') }}
                                </button>
                                <a href="{{ route('teachers.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-redo"></i> {{ __('messages.Reset') }}
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Teachers Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.ID') }}</th>
                                    <th>{{ __('messages.Photo') }}</th>
                                    <th>{{ __('messages.Teacher Info') }}</th>
                                    <th>{{ __('messages.Lesson') }}</th>
                                    <th>{{ __('messages.Social Media') }}</th>
                                    <th>{{ __('messages.User Account') }}</th>
                                    <th>{{ __('messages.activate') }}</th>
                                    <th>{{ __('messages.Created At') }}</th>
                                    <th>{{ __('messages.Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($teachers as $teacher)
                                    <tr>
                                        <td>{{ $teacher->id }}</td>
                                        <td>
                                            @if($teacher->photo)
                                                <img src="{{ asset('assets/admin/uploads/' . $teacher->photo) }}" 
                                                     alt="{{ $teacher->name }}" 
                                                     class="img-thumbnail" 
                                                     style="width: 60px; height: 60px; object-fit: cover;">
                                            @else
                                                <div class="bg-secondary text-white d-flex align-items-center justify-content-center" 
                                                     style="width: 60px; height: 60px; border-radius: 4px;">
                                                    {{ substr($teacher->name, 0, 1) }}
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $teacher->name }}</strong><br>
                                            @if($teacher->description_en)
                                                <small class="text-muted">{{ Str::limit($teacher->description_en, 50) }}</small>
                                            @elseif($teacher->description_ar)
                                                <small class="text-muted">{{ Str::limit($teacher->description_ar, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-primary">{{ $teacher->name_of_lesson }}</span>
                                        </td>
                                        <td>
                                            <div class="social-links">
                                                @if($teacher->facebook)
                                                    <a href="{{ $teacher->facebook }}" target="_blank" class="text-primary me-2" title="Facebook">
                                                        <i class="fab fa-facebook"></i>
                                                    </a>
                                                @endif
                                                @if($teacher->instagram)
                                                    <a href="{{ $teacher->instagram }}" target="_blank" class="text-danger me-2" title="Instagram">
                                                        <i class="fab fa-instagram"></i>
                                                    </a>
                                                @endif
                                                @if($teacher->youtube)
                                                    <a href="{{ $teacher->youtube }}" target="_blank" class="text-danger me-2" title="YouTube">
                                                        <i class="fab fa-youtube"></i>
                                                    </a>
                                                @endif
                                                @if(!$teacher->facebook && !$teacher->instagram && !$teacher->youtube)
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @if($teacher->user)
                                                <span class="badge badge-success">
                                                    <i class="fas fa-user-check"></i> {{ __('messages.Has Account') }}
                                                </span>
                                                <br><small>{{ $teacher->user->email }}</small>
                                            @else
                                                <span class="badge badge-secondary">
                                                    <i class="fas fa-user-times"></i> {{ __('messages.No Account') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td> <span class="badge badge-{{ $teacher->user->activate == 1 ? 'success' : 'danger' }}">
                                                {{ $teacher->user->activate == 1 ? __('messages.Active') : __('messages.Inactive') }}
                                            </span>
                                        </td>
                                        <td>{{ $teacher->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @can('teacher-table')
                                                    <a href="{{ route('teachers.show', $teacher) }}" 
                                                       class="btn btn-sm btn-info" title="{{ __('messages.View') }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                @endcan
                                                @can('teacher-edit')
                                                    <a href="{{ route('teachers.edit', $teacher) }}" 
                                                       class="btn btn-sm btn-warning" title="{{ __('messages.Edit') }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endcan
                                                
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">{{ __('messages.No teachers found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $teachers->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.social-links a {
    font-size: 1.2rem;
    margin-right: 0.5rem;
}

.social-links a:hover {
    opacity: 0.7;
}
</style>
@endsection