@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.Parents Management') }}</h3>
                    @can('parent-add')
                        <a href="{{ route('parents.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> {{ __('messages.Add Parent') }}
                        </a>
                    @endcan
                </div>

                <!-- Filters -->
                <div class="card-body">
                    <form method="GET" action="{{ route('parents.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="{{ __('messages.Search parents...') }}" 
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <select name="has_user" class="form-control">
                                    <option value="">{{ __('messages.All Parents') }}</option>
                                    <option value="yes" {{ request('has_user') == 'yes' ? 'selected' : '' }}>
                                        {{ __('messages.With User Account') }}
                                    </option>
                                    <option value="no" {{ request('has_user') == 'no' ? 'selected' : '' }}>
                                        {{ __('messages.Without User Account') }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="students_count" class="form-control">
                                    <option value="">{{ __('messages.All Parents') }}</option>
                                    <option value="with_students" {{ request('students_count') == 'with_students' ? 'selected' : '' }}>
                                        {{ __('messages.With Students') }}
                                    </option>
                                    <option value="no_students" {{ request('students_count') == 'no_students' ? 'selected' : '' }}>
                                        {{ __('messages.Without Students') }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <button type="submit" class="btn btn-info">
                                    <i class="fas fa-search"></i> {{ __('messages.Search') }}
                                </button>
                                <a href="{{ route('parents.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-redo"></i> {{ __('messages.Reset') }}
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Parents Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.ID') }}</th>
                                    <th>{{ __('messages.Photo') }}</th>
                                    <th>{{ __('messages.Parent Info') }}</th>
                                    <th>{{ __('messages.Students') }}</th>
                                    <th>{{ __('messages.User Account') }}</th>
                                    <th>{{ __('messages.Created At') }}</th>
                                    <th>{{ __('messages.Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($parents as $parent)
                                    <tr>
                                        <td>{{ $parent->id }}</td>
                                        <td>
                                            @if($parent->user && $parent->user->photo)
                                                <img src="{{ asset('assets/admin/uploads/' . $parent->user->photo) }}" 
                                                     alt="{{ $parent->name }}" 
                                                     class="img-thumbnail" 
                                                     style="width: 60px; height: 60px; object-fit: cover;">
                                            @else
                                                <div class="bg-warning text-white d-flex align-items-center justify-content-center" 
                                                     style="width: 60px; height: 60px; border-radius: 4px;">
                                                    {{ substr($parent->name, 0, 1) }}
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $parent->name }}</strong><br>
                                            @if($parent->user)
                                                <small class="text-muted">{{ $parent->user->email }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-info">
                                                {{ $parent->students_count }} {{ __('messages.Students') }}
                                            </span>
                                            @if($parent->students_count > 0)
                                                <br>
                                                @foreach($parent->students->take(3) as $student)
                                                    <small class="text-muted d-block">• {{ $student->name }}</small>
                                                @endforeach
                                                @if($parent->students_count > 3)
                                                    <small class="text-muted">{{ __('messages.and :count more', ['count' => $parent->students_count - 3]) }}</small>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if($parent->user)
                                                <span class="badge badge-success">
                                                    <i class="fas fa-user-check"></i> {{ __('messages.Has Account') }}
                                                </span>
                                                <br>
                                                <small>{{ $parent->user->email }}</small>
                                                @if($parent->user->phone)
                                                    <br><small>{{ $parent->user->phone }}</small>
                                                @endif
                                            @else
                                                <span class="badge badge-secondary">
                                                    <i class="fas fa-user-times"></i> {{ __('messages.No Account') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>{{ $parent->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @can('parent-table')
                                                    <a href="{{ route('parents.show', $parent) }}" 
                                                       class="btn btn-sm btn-info" title="{{ __('messages.View') }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                @endcan
                                                @can('parent-edit')
                                                    <a href="{{ route('parents.edit', $parent) }}" 
                                                       class="btn btn-sm btn-warning" title="{{ __('messages.Edit') }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endcan
                                               
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">{{ __('messages.No parents found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $parents->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection