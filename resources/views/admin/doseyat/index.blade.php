@extends('layouts.admin')

@section('title', __('messages.doseyat'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.doseyat') }}</h3>
                    @can('doseyat-add')
                        <a href="{{ route('doseyats.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> {{ __('messages.add_doseyat') }}
                        </a>
                    @endcan
                </div>

                <div class="card-body">
                    <!-- Filters -->
                    <form method="GET" class="mb-3">
                        <div class="row">
                            <div class="col-md-2">
                                <select name="pos_id" class="form-control">
                                    <option value="">{{ __('messages.all_pos') }}</option>
                                    @foreach($posList as $pos)
                                        <option value="{{ $pos->id }}" {{ request('pos_id') == $pos->id ? 'selected' : '' }}>
                                            {{ $pos->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="teacher_id" class="form-control">
                                    <option value="">{{ __('messages.all_teachers') }}</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="category_id" class="form-control">
                                    <option value="">{{ __('messages.all_categories') }}</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ app()->getLocale() == 'ar' ? $category->name_ar : $category->name_en }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="number" name="min_price" class="form-control" 
                                       placeholder="{{ __('messages.min_price') }}" 
                                       value="{{ request('min_price') }}" step="0.01">
                            </div>
                            <div class="col-md-2">
                                <input type="number" name="max_price" class="form-control" 
                                       placeholder="{{ __('messages.max_price') }}" 
                                       value="{{ request('max_price') }}" step="0.01">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-secondary btn-block">
                                    <i class="fas fa-filter"></i> {{ __('messages.filter') }}
                                </button>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-10">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="{{ __('messages.search_doseyat') }}" 
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('doseyats.index') }}" class="btn btn-light btn-block">
                                    <i class="fas fa-redo"></i> {{ __('messages.reset') }}
                                </a>
                            </div>
                        </div>
                    </form>

                

                    @if($doseyats->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>{{ __('messages.photo') }}</th>
                                        <th>{{ __('messages.name') }}</th>
                                        <th>{{ __('messages.price') }}</th>
                                        <th>{{ __('messages.pos') }}</th>
                                        <th>{{ __('messages.teacher') }}</th>
                                        <th>{{ __('messages.category') }}</th>
                                        <th>{{ __('messages.created_at') }}</th>
                                        <th>{{ __('messages.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($doseyats as $doseyat)
                                        <tr>
                                            <td>
                                                <img src="{{ asset('assets/admin/uploads/' . $doseyat->photo) }}" 
                                                     alt="{{ $doseyat->name }}" 
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            </td>
                                            <td>{{ $doseyat->name }}</td>
                                            <td>{{ number_format($doseyat->price, 2) }} {{ __('messages.currency') }}</td>
                                            <td>{{ $doseyat->pos->name ?? '-' }}</td>
                                            <td>{{ $doseyat->teacher->name ?? '-' }}</td>
                                            <td>
                                                @if($doseyat->category)
                                                    {{ app()->getLocale() == 'ar' ? $doseyat->category->name_ar : $doseyat->category->name_en }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{ $doseyat->created_at->format('Y-m-d H:i') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    @can('doseyat-table')
                                                        <a href="{{ route('doseyats.show', $doseyat) }}" 
                                                           class="btn btn-sm btn-info" title="{{ __('messages.view') }}">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    @endcan
                                                    
                                                    @can('doseyat-edit')
                                                        <a href="{{ route('doseyats.edit', $doseyat) }}" 
                                                           class="btn btn-sm btn-primary" title="{{ __('messages.edit') }}">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endcan
                                                    
                                                    @can('doseyat-delete')
                                                        <form action="{{ route('doseyats.destroy', $doseyat) }}" 
                                                              method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                                    onclick="return confirm('{{ __('messages.confirm_delete') }}')"
                                                                    title="{{ __('messages.delete') }}">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        {{ $doseyats->withQueryString()->links() }}
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted">{{ __('messages.no_doseyat_found') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection