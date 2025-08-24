@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.Users Management') }}</h3>
                    @can('user-add')
                        <a href="{{ route('users.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> {{ __('messages.Add User') }}
                        </a>
                    @endcan
                </div>

                <!-- Filters -->
                <div class="card-body">
                    <form method="GET" action="{{ route('users.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="{{ __('messages.Search') }}" 
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <select name="role_name" class="form-control">
                                    <option value="">{{ __('messages.All Roles') }}</option>
                                    <option value="student" {{ request('role_name') == 'student' ? 'selected' : '' }}>
                                        {{ __('messages.Student') }}
                                    </option>
                                    <option value="parent" {{ request('role_name') == 'parent' ? 'selected' : '' }}>
                                        {{ __('messages.Parent') }}
                                    </option>
                                    <option value="teacher" {{ request('role_name') == 'teacher' ? 'selected' : '' }}>
                                        {{ __('messages.Teacher') }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="activate" class="form-control">
                                    <option value="">{{ __('messages.All Status') }}</option>
                                    <option value="1" {{ request('activate') == '1' ? 'selected' : '' }}>
                                        {{ __('messages.Active') }}
                                    </option>
                                    <option value="2" {{ request('activate') == '2' ? 'selected' : '' }}>
                                        {{ __('messages.Inactive') }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="clas_id" class="form-control">
                                    <option value="">{{ __('messages.All Classes') }}</option>
                                    @foreach($classes as $clas)
                                        <option value="{{ $clas->id }}" 
                                                {{ request('clas_id') == $clas->id ? 'selected' : '' }}>
                                            {{ $clas->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-info">
                                    <i class="fas fa-search"></i> {{ __('messages.Search') }}
                                </button>
                                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-redo"></i> {{ __('messages.Reset') }}
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Users Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.ID') }}</th>
                                    <th>{{ __('messages.Photo') }}</th>
                                    <th>{{ __('messages.Name') }}</th>
                                    <th>{{ __('messages.Role') }}</th>
                                    <th>{{ __('messages.Email') }}</th>
                                    <th>{{ __('messages.Phone') }}</th>
                                    <th>{{ __('messages.Category') }}</th>
                                    <th>{{ __('messages.Balance') }}</th>
                                    <th>{{ __('messages.Status') }}</th>
                                    <th>{{ __('messages.Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>
                                            @if($user->photo)
                                                <img src="{{ asset('assets/admin/uploads/' . $user->photo) }}" 
                                                     alt="{{ $user->name }}" 
                                                     class="img-thumbnail" 
                                                     style="width: 50px; height: 50px;">
                                            @else
                                                <div class="bg-secondary text-white d-flex align-items-center justify-content-center" 
                                                     style="width: 50px; height: 50px; border-radius: 4px;">
                                                    {{ substr($user->name, 0, 1) }}
                                                </div>
                                            @endif
                                        </td>
                                        <td>{{ $user->name }}</td>
                                        <td>
                                            <span class="badge badge-{{ $user->role_name == 'teacher' ? 'success' : ($user->role_name == 'parent' ? 'warning' : 'info') }}">
                                                {{ __('messages.' . ucfirst($user->role_name)) }}
                                            </span>
                                        </td>
                                        <td>{{ $user->email ?: '-' }}</td>
                                        <td>{{ $user->phone ?: '-' }}</td>
                                        <td>{{ $user->clas->name ?? '-' }}</td>
                                        <td>${{ number_format($user->balance, 2) }}</td>
                                        <td>
                                            <span class="badge badge-{{ $user->activate == 1 ? 'success' : 'danger' }}">
                                                {{ $user->activate == 1 ? __('messages.Active') : __('messages.Inactive') }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @can('user-table')
                                                    <a href="{{ route('users.show', $user) }}" 
                                                       class="btn btn-sm btn-info" title="{{ __('messages.View') }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                @endcan
                                                @can('user-edit')
                                                    <a href="{{ route('users.edit', $user) }}" 
                                                       class="btn btn-sm btn-warning" title="{{ __('messages.Edit') }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endcan
                                                @can('user-delete')
                                                    <form action="{{ route('users.destroy', $user) }}" 
                                                          method="POST" class="d-inline"
                                                          onsubmit="return confirm('{{ __('messages.Are you sure you want to delete this user?') }}')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                                title="{{ __('messages.Delete') }}">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">{{ __('messages.No users found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $users->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection