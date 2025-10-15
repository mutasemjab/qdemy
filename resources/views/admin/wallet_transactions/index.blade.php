@extends('layouts.admin')

@section('title', __('messages.wallet_transactions'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.wallet_transactions') }}</h3>
                    <a href="{{ route('wallet_transactions.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> {{ __('messages.add_transaction') }}
                    </a>
                </div>
                <div class="card-body">
                   

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.id') }}</th>
                                    <th>{{ __('messages.user') }}</th>
                                    <th>{{ __('messages.admin') }}</th>
                                    <th>{{ __('messages.amount') }}</th>
                                    <th>{{ __('messages.type') }}</th>
                                    <th>{{ __('messages.note') }}</th>
                                    <th>{{ __('messages.date') }}</th>
                                    <th>{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->id }}</td>
                                        <td>
                                            <strong>{{ $transaction->user->name }}</strong><br>
                                            <small class="text-muted">{{ $transaction->user->email }}</small><br>
                                            <small class="text-info">{{ __('messages.balance') }}: {{ number_format($transaction->user->balance, 2) }}</small>
                                        </td>
                                        <td>{{ $transaction->admin->name ?? __('messages.not_available') }}</td>
                                        <td>
                                            <span class="badge badge-{{ $transaction->type == 1 ? 'success' : 'danger' }}">
                                                {{ $transaction->type == 1 ? '+' : '-' }}{{ $transaction->formatted_amount }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $transaction->type == 1 ? 'success' : 'warning' }}">
                                                {{ $transaction->type_name }}
                                            </span>
                                        </td>
                                        <td>{{ Str::limit($transaction->note ?? __('messages.no_note'), 50) }}</td>
                                        <td>{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('wallet_transactions.show', $transaction) }}" 
                                                   class="btn btn-sm btn-info" title="{{ __('messages.view') }}">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('wallet_transactions.edit', $transaction) }}" 
                                                   class="btn btn-sm btn-warning" title="{{ __('messages.edit') }}">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('wallet_transactions.destroy', $transaction) }}" 
                                                      method="POST" class="d-inline" 
                                                      onsubmit="return confirm('{{ __('messages.confirm_delete') }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="{{ __('messages.delete') }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">{{ __('messages.no_transactions_found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $transactions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection