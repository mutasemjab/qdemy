@extends('layouts.admin')

@section('title', __('messages.view_doseyat'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.doseyat_details') }}</h3>
                    <div>
                        @can('doseyat-edit')
                            <a href="{{ route('doseyats.edit', $doseyat) }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> {{ __('messages.edit') }}
                            </a>
                        @endcan
                        <a href="{{ route('doseyats.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center mb-4">
                                <img src="{{ asset('assets/admin/uploads/' . $doseyat->photo) }}" 
                                     alt="{{ $doseyat->name }}" 
                                     class="img-fluid rounded"
                                     style="max-width: 100%; height: auto;">
                            </div>
                        </div>

                        <div class="col-md-8">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th style="width: 30%;">{{ __('messages.name') }}</th>
                                        <td>{{ $doseyat->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.price') }}</th>
                                        <td>
                                            <strong class="text-success">
                                                {{ number_format($doseyat->price, 2) }} {{ __('messages.currency') }}
                                            </strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.pos') }}</th>
                                        <td>
                                            @if($doseyat->pos)
                                                <strong>{{ $doseyat->pos->name }}</strong><br>
                                                <small class="text-muted">
                                                    <i class="fas fa-phone"></i> {{ $doseyat->pos->phone }}<br>
                                                    <i class="fas fa-map-marker-alt"></i> {{ $doseyat->pos->address }}, {{ $doseyat->pos->country_name }}
                                                </small>
                                            @else
                                                <span class="text-muted">{{ __('messages.not_assigned') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.teacher') }}</th>
                                        <td>
                                            @if($doseyat->teacher)
                                                <div class="d-flex align-items-center">
                                                    @if($doseyat->teacher->photo)
                                                        <img src="{{ asset('assets/admin/uploads/' . $doseyat->teacher->photo) }}" 
                                                             alt="{{ $doseyat->teacher->name }}"
                                                             class="rounded-circle mr-2"
                                                             style="width: 40px; height: 40px; object-fit: cover;">
                                                    @endif
                                                    <div>
                                                        <strong>{{ $doseyat->teacher->name }}</strong><br>
                                                        <small class="text-muted">{{ $doseyat->teacher->name_of_lesson }}</small>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">{{ __('messages.not_assigned') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.category') }}</th>
                                        <td>
                                            @if($doseyat->category)
                                                <span class="badge badge-info" style="background-color: {{ $doseyat->category->color }};">
                                                    @if($doseyat->category->icon)
                                                        <i class="{{ $doseyat->category->icon }}"></i>
                                                    @endif
                                                    {{ app()->getLocale() == 'ar' ? $doseyat->category->name_ar : $doseyat->category->name_en }}
                                                </span>
                                            @else
                                                <span class="text-muted">{{ __('messages.not_assigned') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.created_at') }}</th>
                                        <td>{{ $doseyat->created_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.updated_at') }}</th>
                                        <td>{{ $doseyat->updated_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    @can('doseyat-edit')
                        <a href="{{ route('doseyats.edit', $doseyat) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> {{ __('messages.edit') }}
                        </a>
                    @endcan
                    
                    @can('doseyat-delete')
                        <form action="{{ route('doseyats.destroy', $doseyat) }}" 
                              method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" 
                                    onclick="return confirm('{{ __('messages.confirm_delete') }}')">
                                <i class="fas fa-trash"></i> {{ __('messages.delete') }}
                            </button>
                        </form>
                    @endcan
                    
                    <a href="{{ route('doseyats.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection