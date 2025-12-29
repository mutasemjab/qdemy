@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4><i class="fas fa-chart-line"></i> {{ __('messages.card_reports') }}</h4>
                    <div>
                        <a href="{{ route('cards.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.back_to_list') }}
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
                                            <h6 class="text-white mb-1">{{ __('messages.total_cards') }}</h6>
                                            <h3 class="mb-0">{{ number_format($statistics['total_cards']) }}</h3>
                                        </div>
                                        <div class="text-white-50">
                                            <i class="fas fa-credit-card fa-3x"></i>
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
                                            <h6 class="text-white mb-1">{{ __('messages.total_card_numbers') }}</h6>
                                            <h3 class="mb-0">{{ number_format($statistics['total_card_numbers']) }}</h3>
                                        </div>
                                        <div class="text-white-50">
                                            <i class="fas fa-list-ol fa-3x"></i>
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
                                            <h6 class="text-white mb-1">{{ __('messages.available_numbers') }}</h6>
                                            <h3 class="mb-0">{{ number_format($statistics['available_card_numbers']) }}</h3>
                                        </div>
                                        <div class="text-white-50">
                                            <i class="fas fa-check-circle fa-3x"></i>
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
                                            <h6 class="text-white mb-1">{{ __('messages.sold_numbers') }}</h6>
                                            <h3 class="mb-0">{{ number_format($statistics['sold_card_numbers']) }}</h3>
                                        </div>
                                        <div class="text-white-50">
                                            <i class="fas fa-shopping-cart fa-3x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card bg-danger text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-white mb-1">{{ __('messages.used_numbers') }}</h6>
                                            <h3 class="mb-0">{{ number_format($statistics['used_card_numbers']) }}</h3>
                                        </div>
                                        <div class="text-white-50">
                                            <i class="fas fa-times-circle fa-3x"></i>
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
                                            <h6 class="text-white mb-1">{{ __('messages.active_numbers') }}</h6>
                                            <h3 class="mb-0">{{ number_format($statistics['active_card_numbers']) }}</h3>
                                        </div>
                                        <div class="text-white-50">
                                            <i class="fas fa-toggle-on fa-3x"></i>
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
                                            <h6 class="text-white mb-1">{{ __('messages.cards_with_doseyats') }}</h6>
                                            <h3 class="mb-0">{{ number_format($statistics['cards_with_doseyats']) }}</h3>
                                        </div>
                                        <div class="text-white-50">
                                            <i class="fas fa-gift fa-3x"></i>
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
                                            <h6 class="text-white mb-1">{{ __('messages.total_revenue') }}</h6>
                                            <h3 class="mb-0">{{ number_format($statistics['total_revenue'], 2) }}</h3>
                                        </div>
                                        <div class="text-white-50">
                                            <i class="fas fa-dollar-sign fa-3x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filters -->
                    <form method="GET" action="{{ route('card-reports.index') }}" id="filterForm">
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="fas fa-filter"></i> {{ __('messages.filters') }}</h5>
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
                                        <label for="doseyat_status" class="form-label">{{ __('messages.doseyat_status') }}</label>
                                        <select class="form-control" id="doseyat_status" name="doseyat_status">
                                            <option value="">{{ __('messages.all') }}</option>
                                            <option value="has_doseyats" {{ request('doseyat_status') == 'has_doseyats' ? 'selected' : '' }}>
                                                {{ __('messages.has_doseyats') }}
                                            </option>
                                            <option value="no_doseyats" {{ request('doseyat_status') == 'no_doseyats' ? 'selected' : '' }}>
                                                {{ __('messages.no_doseyats') }}
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="search" class="form-label">{{ __('messages.search') }}</label>
                                        <input type="text" class="form-control" id="search" name="search" 
                                               value="{{ request('search') }}" 
                                               placeholder="{{ __('messages.search_by_card_name') }}">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i> {{ __('messages.filter') }}
                                        </button>
                                        <a href="{{ route('card-reports.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-redo"></i> {{ __('messages.reset') }}
                                        </a>
                                    </div>
                                    <div>
                                        <a href="{{ route('card-reports.export-excel', request()->all()) }}" class="btn btn-success">
                                            <i class="fas fa-file-excel"></i> {{ __('messages.export_excel') }}
                                        </a>
                                       
                                        <a href="{{ route('card-reports.print', request()->all()) }}" class="btn btn-info" target="_blank">
                                            <i class="fas fa-print"></i> {{ __('messages.print') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Cards Table -->
                    @if($cards->count() > 0)
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
                                        <th>{{ __('messages.doseyats') }}</th>
                                        <th>{{ __('messages.total_numbers') }}</th>
                                        <th>{{ __('messages.available') }}</th>
                                        <th>{{ __('messages.sold') }}</th>
                                        <th>{{ __('messages.used') }}</th>
                                        <th>{{ __('messages.created_at') }}</th>
                                        <th>{{ __('messages.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cards as $card)
                                        <tr>
                                            <td>{{ $card->id }}</td>
                                            <td>
                                                <img src="{{ $card->photo_url }}" alt="{{ $card->name }}" 
                                                     style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;">
                                            </td>
                                            <td><strong>{{ $card->name }}</strong></td>
                                            <td>
                                                @if($card->pos)
                                                    <span class="badge bg-info">{{ $card->pos->name }}</span>
                                                @else
                                                    <span class="badge bg-secondary">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($card->category)
                                                    <span class="badge bg-primary">{{ $card->category->name_ar }}</span>
                                                @else
                                                    <span class="badge bg-secondary">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($card->teacher)
                                                    <span class="badge bg-success">{{ $card->teacher->name }}</span>
                                                @else
                                                    <span class="badge bg-secondary">-</span>
                                                @endif
                                            </td>
                                            <td><strong>{{ number_format($card->price, 2) }}</strong></td>
                                            <td>
                                                @if($card->doseyats->count() > 0)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-gift"></i> {{ $card->doseyats->count() }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">0</span>
                                                @endif
                                            </td>
                                            <td><span class="badge bg-primary">{{ $card->cardNumbers->count() }}</span></td>
                                            <td>
                                                <span class="badge bg-success">
                                                    {{ $card->cardNumbers->where('sell', 0)->where('activate', 1)->where('status', 0)->count() }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning">
                                                    {{ $card->cardNumbers->where('sell', 1)->count() }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-danger">
                                                    {{ $card->cardNumbers->where('status', 1)->count() }}
                                                </span>
                                            </td>
                                            <td>{{ $card->created_at->format('Y-m-d') }}</td>
                                            <td>
                                                <a href="{{ route('cards.show', $card->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('cards.edit', $card->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-3">
                            {{ $cards->appends(request()->all())->links() }}
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle fa-2x mb-2"></i>
                            <p class="mb-0">{{ __('messages.no_data_found') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection