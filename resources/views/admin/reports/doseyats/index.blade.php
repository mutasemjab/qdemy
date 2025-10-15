@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4><i class="fa-solid fa-chart-line"></i> {{ __('messages.doseyat_reports') }}</h4>
                    <div>
                        <a href="{{ route('doseyats.index') }}" class="btn btn-secondary">
                            <i class="fa-solid fa-arrow-left"></i> {{ __('messages.back_to_list') }}
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card bg-primary text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-white mb-1">{{ __('messages.total_doseyats') }}</h6>
                                            <h3 class="mb-0">{{ number_format($statistics['total_doseyats']) }}</h3>
                                        </div>
                                        <div class="text-white-50">
                                            <i class="fa-solid fa-book fa-3x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card bg-success text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-white mb-1">{{ __('messages.doseyats_with_cards') }}</h6>
                                            <h3 class="mb-0">{{ number_format($statistics['doseyats_with_cards']) }}</h3>
                                        </div>
                                        <div class="text-white-50">
                                            <i class="fa-solid fa-check-circle fa-3x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card bg-warning text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-white mb-1">{{ __('messages.doseyats_without_cards') }}</h6>
                                            <h3 class="mb-0">{{ number_format($statistics['doseyats_without_cards']) }}</h3>
                                        </div>
                                        <div class="text-white-50">
                                            <i class="fa-solid fa-exclamation-circle fa-3x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card bg-info text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-white mb-1">{{ __('messages.total_cards_associated') }}</h6>
                                            <h3 class="mb-0">{{ number_format($statistics['total_cards_associated']) }}</h3>
                                        </div>
                                        <div class="text-white-50">
                                            <i class="fa-solid fa-credit-card fa-3x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <div class="card-body text-white">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-white mb-1">{{ __('messages.total_price') }}</h6>
                                            <h3 class="mb-0">{{ number_format($statistics['total_price'], 2) }}</h3>
                                        </div>
                                        <div class="text-white-50">
                                            <i class="fa-solid fa-dollar-sign fa-3x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card bg-dark text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-white mb-1">{{ __('messages.average_price') }}</h6>
                                            <h3 class="mb-0">{{ number_format($statistics['average_price'], 2) }}</h3>
                                        </div>
                                        <div class="text-white-50">
                                            <i class="fa-solid fa-chart-bar fa-3x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card bg-secondary text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-white mb-1">{{ __('messages.min_price') }}</h6>
                                            <h3 class="mb-0">{{ number_format($statistics['min_price'], 2) }}</h3>
                                        </div>
                                        <div class="text-white-50">
                                            <i class="fa-solid fa-arrow-down fa-3x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                <div class="card-body text-white">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-white mb-1">{{ __('messages.max_price') }}</h6>
                                            <h3 class="mb-0">{{ number_format($statistics['max_price'], 2) }}</h3>
                                        </div>
                                        <div class="text-white-50">
                                            <i class="fa-solid fa-arrow-up fa-3x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filters -->
                    <form method="GET" action="{{ route('doseyat-reports.index') }}" id="filterForm">
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="fa-solid fa-filter"></i> {{ __('messages.filters') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label for="from_date" class="form-label">{{ __('messages.from_date') }}</label>
                                        <input type="date" class="form-control" id="from_date" name="from_date" 
                                               value="{{ request('from_date') }}">
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label for="to_date" class="form-label">{{ __('messages.to_date') }}</label>
                                        <input type="date" class="form-control" id="to_date" name="to_date" 
                                               value="{{ request('to_date') }}">
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label for="pos_id" class="form-label">{{ __('messages.pos') }}</label>
                                        <select class="form-control" id="pos_id" name="pos_id">
                                            <option value="">{{ __('messages.all') }}</option>
                                            @foreach($posRecords as $pos)
                                                <option value="{{ $pos->id }}" {{ request('pos_id') == $pos->id ? 'selected' : '' }}>
                                                    {{ $pos->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label for="category_id" class="form-label">{{ __('messages.category') }}</label>
                                        <select class="form-control" id="category_id" name="category_id">
                                            <option value="">{{ __('messages.all') }}</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name_ar }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label for="teacher_id" class="form-label">{{ __('messages.teacher') }}</label>
                                        <select class="form-control" id="teacher_id" name="teacher_id">
                                            <option value="">{{ __('messages.all') }}</option>
                                            @foreach($teachers as $teacher)
                                                <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                                    {{ $teacher->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label for="card_status" class="form-label">{{ __('messages.card_status') }}</label>
                                        <select class="form-control" id="card_status" name="card_status">
                                            <option value="">{{ __('messages.all') }}</option>
                                            <option value="has_cards" {{ request('card_status') == 'has_cards' ? 'selected' : '' }}>
                                                {{ __('messages.has_cards') }}
                                            </option>
                                            <option value="no_cards" {{ request('card_status') == 'no_cards' ? 'selected' : '' }}>
                                                {{ __('messages.no_cards') }}
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-2 mb-3">
                                        <label for="min_price" class="form-label">{{ __('messages.min_price') }}</label>
                                        <input type="number" class="form-control" id="min_price" name="min_price" 
                                               value="{{ request('min_price') }}" step="0.01" min="0"
                                               placeholder="0.00">
                                    </div>

                                    <div class="col-md-2 mb-3">
                                        <label for="max_price" class="form-label">{{ __('messages.max_price') }}</label>
                                        <input type="number" class="form-control" id="max_price" name="max_price" 
                                               value="{{ request('max_price') }}" step="0.01" min="0"
                                               placeholder="1000.00">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="search" class="form-label">{{ __('messages.search') }}</label>
                                        <input type="text" class="form-control" id="search" name="search" 
                                               value="{{ request('search') }}" 
                                               placeholder="{{ __('messages.search_by_doseyat_name') }}">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa-solid fa-search"></i> {{ __('messages.filter') }}
                                        </button>
                                        <a href="{{ route('doseyat-reports.index') }}" class="btn btn-secondary">
                                            <i class="fa-solid fa-redo"></i> {{ __('messages.reset') }}
                                        </a>
                                    </div>
                                    <div>
                                        <a href="{{ route('doseyat-reports.export-excel', request()->all()) }}" class="btn btn-success">
                                            <i class="fa-solid fa-file-excel"></i> {{ __('messages.export_excel') }}
                                        </a>
                                        <a href="{{ route('doseyat-reports.print', request()->all()) }}" class="btn btn-info" target="_blank">
                                            <i class="fa-solid fa-print"></i> {{ __('messages.print') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Doseyats Table -->
                    @if($doseyats->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>{{ __('messages.id') }}</th>
                                        <th>{{ __('messages.photo') }}</th>
                                        <th>{{ __('messages.name') }}</th>
                                        <th>{{ __('messages.pos') }}</th>
                                        <th>{{ __('messages.category') }}</th>
                                        <th>{{ __('messages.teacher') }}</th>
                                        <th>{{ __('messages.price') }}</th>
                                        <th>{{ __('messages.associated_cards') }}</th>
                                        <th>{{ __('messages.created_at') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($doseyats as $doseyat)
                                        <tr>
                                            <td>{{ $doseyat->id }}</td>
                                            <td>
                                                <img src="{{ asset('assets/admin/uploads/' . $doseyat->photo) }}" alt="{{ $doseyat->name }}" 
                                                     style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;">
                                            </td>
                                            <td><strong>{{ $doseyat->name }}</strong></td>
                                            <td>
                                                @if($doseyat->pos)
                                                    <span class="badge bg-info">{{ $doseyat->pos->name }}</span>
                                                @else
                                                    <span class="badge bg-secondary">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($doseyat->category)
                                                    <span class="badge bg-primary">{{ $doseyat->category->name_ar }}</span>
                                                @else
                                                    <span class="badge bg-secondary">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($doseyat->teacher)
                                                    <span class="badge bg-success">{{ $doseyat->teacher->name }}</span>
                                                @else
                                                    <span class="badge bg-secondary">-</span>
                                                @endif
                                            </td>
                                            <td><strong>{{ number_format($doseyat->price, 2) }}</strong></td>
                                            <td>
                                                @if($doseyat->cards->count() > 0)
                                                    <span class="badge bg-primary">
                                                        <i class="fa-solid fa-credit-card"></i> {{ $doseyat->cards->count() }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">0</span>
                                                @endif
                                            </td>
                                            <td>{{ $doseyat->created_at->format('Y-m-d') }}</td>
                                          
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-3">
                            {{ $doseyats->appends(request()->all())->links() }}
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fa-solid fa-info-circle fa-2x mb-2"></i>
                            <p class="mb-0">{{ __('messages.no_data_found') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection