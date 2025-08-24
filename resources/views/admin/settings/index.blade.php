@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.Settings Management') }}</h3>
                  
                </div>

                <!-- Search -->
                <div class="card-body">
                    <form method="GET" action="{{ route('settings.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="{{ __('messages.Search by email, phone, address...') }}" 
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-info">
                                    <i class="fas fa-search"></i> {{ __('messages.Search') }}
                                </button>
                                <a href="{{ route('settings.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-redo"></i> {{ __('messages.Reset') }}
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Settings Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.ID') }}</th>
                                    <th>{{ __('messages.Logo') }}</th>
                                    <th>{{ __('messages.Contact Info') }}</th>
                                    <th>{{ __('messages.App Links') }}</th>
                                    <th>{{ __('messages.Statistics') }}</th>
                                    <th>{{ __('messages.Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($settings as $setting)
                                    <tr>
                                        <td>{{ $setting->id }}</td>
                                        <td>
                                            @if($setting->logo)
                                                <img src="{{ asset('assets/admin/uploads/' . $setting->logo) }}" 
                                                     alt="Logo" 
                                                     class="img-thumbnail" 
                                                     style="width: 60px; height: 60px; object-fit: contain;">
                                            @else
                                                <span class="text-muted">{{ __('messages.No Logo') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ __('messages.Email') }}:</strong> {{ $setting->email }}<br>
                                            <strong>{{ __('messages.Phone') }}:</strong> {{ $setting->phone }}<br>
                                            @if($setting->address)
                                                <strong>{{ __('messages.Address') }}:</strong> {{ Str::limit($setting->address, 30) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($setting->google_play_link)
                                                <span class="badge badge-success">{{ __('messages.Google Play') }}</span><br>
                                            @endif
                                            @if($setting->app_store_link)
                                                <span class="badge badge-info">{{ __('messages.App Store') }}</span><br>
                                            @endif
                                            @if($setting->hawawi_link)
                                                <span class="badge badge-warning">{{ __('messages.Huawei') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($setting->number_of_students)
                                                <small><strong>{{ __('messages.Students') }}:</strong> {{ $setting->number_of_students }}</small><br>
                                            @endif
                                            @if($setting->number_of_teacher)
                                                <small><strong>{{ __('messages.Teachers') }}:</strong> {{ $setting->number_of_teacher }}</small><br>
                                            @endif
                                            @if($setting->number_of_course)
                                                <small><strong>{{ __('messages.Courses') }}:</strong> {{ $setting->number_of_course }}</small><br>
                                            @endif
                                            @if($setting->number_of_viewing_hour)
                                                <small><strong>{{ __('messages.Hours') }}:</strong> {{ $setting->number_of_viewing_hour }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                            
                                                @can('setting-edit')
                                                    <a href="{{ route('settings.edit', $setting) }}" 
                                                       class="btn btn-sm btn-warning" title="{{ __('messages.Edit') }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endcan
                                               
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">{{ __('messages.No settings found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $settings->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection