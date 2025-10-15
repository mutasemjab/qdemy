@extends("layouts.admin")


@section('css')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.3/dist/sweetalert2.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item">
                                <a href="javascript: void(0);">{{ env('APP_NAME') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('messages.Roles') }}</li>
                        </ol>
                    </div>
                    <h4 class="page-title">{{ __('messages.Roles') }}</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                {{ $data->links() }}
                            </div>
                            <div class="col-sm-8">
                                <div class="text-sm-right">
                                    @can('role-add')
                                    <a type="button" href="{{ route('admin.role.create') }}"
                                        class="btn btn-primary waves-effect waves-light mb-2 text-white">
                                        {{ __('messages.New Role') }}
                                    </a>
                                    @endcan
                                </div>
                            </div>
                        </div>


                        <div class="table-responsive">
                            <table class="table table-centered table-nowrap table-hover mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ __('messages.Name') }}</th>
                                        <th>{{ __('messages.Users Count') }}</th>
                                        <th>{{ __('messages.Permissions') }}</th>
                                        <th style="width: 82px;">{{ __('messages.Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $role)
                                        <tr>
                                            <td>
                                                <span class="font-weight-bold">{{ $role->name }}</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-info">{{ $role->users->count() }}</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-success">{{ $role->permissions->count() }}</span>
                                                @if($role->permissions->count() > 0)
                                                <button class="btn btn-link btn-sm p-0 ml-1" 
                                                        type="button" 
                                                        data-toggle="collapse" 
                                                        data-target="#permissions{{ $role->id }}" 
                                                        aria-expanded="false">
                                                    <small>{{ __('messages.View') }}</small>
                                                </button>
                                                @endif
                                            </td>
                                            <td>
                                                @can('role-edit')
                                                <a class="btn btn-sm btn-outline-info"
                                                    href="{{ route('admin.role.edit', $role->id) }}">
                                                    <i class="mdi mdi-pencil-box"></i> {{ __('messages.Edit') }}
                                                </a>
                                                @endcan
                                                
                                                @can('role-delete')
                                                <a class="btn btn-sm btn-outline-danger" 
                                                   href="javascript:void(0)"
                                                   @if (env('Environment') == 'sendbox') 
                                                       onclick="myFunction()" 
                                                   @else 
                                                       onclick="confirmDelete({{ $role->id }}, '{{ $role->name }}', {{ $role->users->count() }})"
                                                   @endif>
                                                    <i class="mdi mdi-trash-can"></i> {{ __('messages.Delete') }}
                                                </a>
                                                @endcan
                                            </td>
                                        </tr>
                                        @if($role->permissions->count() > 0)
                                        <tr>
                                            <td colspan="4" class="p-0">
                                                <div class="collapse" id="permissions{{ $role->id }}">
                                                    <div class="card card-body m-2 bg-light">
                                                        <small class="text-muted mb-2">{{ __('messages.Permissions') }}:</small>
                                                        @foreach ($role->permissions as $permission)
                                                            <span class="badge badge-light mr-1 mb-1">{{ $permission->name }}</span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.3/dist/sweetalert2.min.js"></script>
    
    <script>
        function confirmDelete(roleId, roleName, userCount) {
            if (userCount > 0) {
                Swal.fire({
                    icon: 'warning',
                    title: '{{ __("messages.Cannot Delete") }}',
                    text: '{{ __("messages.This role is assigned to") }} ' + userCount + ' {{ __("messages.users") }}. {{ __("messages.Please reassign users before deleting") }}.',
                });
                return;
            }

            Swal.fire({
                title: '{{ __("messages.Are you sure?") }}',
                text: '{{ __("messages.You want to delete the role") }} "' + roleName + '"',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '{{ __("messages.Yes, delete it!") }}',
                cancelButtonText: '{{ __("messages.Cancel") }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Make AJAX request
                    $.ajax({
                        url: '{{ route("admin.role.delete") }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            id: roleId
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: '{{ __("messages.Deleted!") }}',
                                text: '{{ __("messages.Role has been deleted successfully") }}',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: '{{ __("messages.Error!") }}',
                                text: '{{ __("messages.Something went wrong") }}'
                            });
                        }
                    });
                }
            });
        }

        function myFunction() {
            Swal.fire({
                icon: 'info',
                title: '{{ __("messages.Demo Mode") }}',
                text: '{{ __("messages.This action is disabled in demo environment") }}'
            });
        }
    </script>
@endsection