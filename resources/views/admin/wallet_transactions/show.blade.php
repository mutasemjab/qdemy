@extends('layouts.admin')

@section('title', __('messages.transaction_details'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-lg-6 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.transaction_details') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('wallet_transactions.edit', $walletTransaction) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> {{ __('messages.edit') }}
                        </a>
                        <a href="{{ route('wallet_transactions.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <strong>{{ __('messages.transaction_id') }}:</strong>
                        </div>
                        <div class="col-md-8">
                            #{{ $walletTransaction->id }}
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-md-4">
                            <strong>{{ __('messages.user') }}:</strong>
                        </div>
                        <div class="col-md-8">
                            <div>
                                <strong>{{ $walletTransaction->user->name }}</strong>
                                <span class="badge badge-info">{{ __(ucfirst($walletTransaction->user->role_name)) }}</span>
                            </div>
                            <small class="text-muted">{{ $walletTransaction->user->email }}</small><br>
                            <small class="text-muted">{{ __('messages.phone') }}: {{ $walletTransaction->user->phone ?? __('messages.not_available') }}</small><br>
                            <small class="text-success">{{ __('messages.current_balance') }}: {{ number_format($walletTransaction->user->balance, 2) }}</small>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-md-4">
                            <strong>{{ __('messages.admin') }}:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $walletTransaction->admin->name ?? __('messages.not_available') }}
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-md-4">
                            <strong>{{ __('messages.transaction_type') }}:</strong>
                        </div>
                        <div class="col-md-8">
                            <span class="badge badge-{{ $walletTransaction->type == 1 ? 'success' : 'warning' }} badge-lg">
                                {{ $walletTransaction->type_name }}
                            </span>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-md-4">
                            <strong>{{ __('messages.amount') }}:</strong>
                        </div>
                        <div class="col-md-8">
                            <span class="h4 badge badge-{{ $walletTransaction->type == 1 ? 'success' : 'danger' }}">
                                {{ $walletTransaction->type == 1 ? '+' : '-' }}{{ $walletTransaction->formatted_amount }}
                            </span>
                        </div>
                    </div>
                    <hr>

                    @if($walletTransaction->note)
                    <div class="row">
                        <div class="col-md-4">
                            <strong>{{ __('messages.note') }}:</strong>
                        </div>
                        <div class="col-md-8">
                            <div class="alert alert-light">
                                {{ $walletTransaction->note }}
                            </div>
                        </div>
                    </div>
                    <hr>
                    @endif

                    <div class="row">
                        <div class="col-md-4">
                            <strong>{{ __('messages.created_at') }}:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $walletTransaction->created_at->format('Y-m-d H:i:s') }}
                            <small class="text-muted">({{ $walletTransaction->created_at->diffForHumans() }})</small>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-md-4">
                            <strong>{{ __('messages.updated_at') }}:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $walletTransaction->updated_at->format('Y-m-d H:i:s') }}
                            <small class="text-muted">({{ $walletTransaction->updated_at->diffForHumans() }})</small>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-12">
                            <div class="btn-group d-flex" role="group">
                                <a href="{{ route('wallet_transactions.edit', $walletTransaction) }}" class="btn btn-warning flex-fill">
                                    <i class="fas fa-edit"></i> {{ __('messages.edit') }}
                                </a>
                                <form action="{{ route('wallet_transactions.destroy', $walletTransaction) }}" 
                                      method="POST" class="flex-fill" 
                                      onsubmit="return confirm('{{ __('messages.confirm_delete') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="fas fa-trash"></i> {{ __('messages.delete') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection