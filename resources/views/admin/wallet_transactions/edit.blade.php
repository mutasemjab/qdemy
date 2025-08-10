@extends('layouts.admin')

@section('title', __('messages.edit_transaction'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-lg-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.edit_transaction') }} #{{ $walletTransaction->id }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('wallet_transactions.show', $walletTransaction) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i> {{ __('messages.view') }}
                        </a>
                        <a href="{{ route('wallet_transactions.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <form action="{{ route('wallet_transactions.update', $walletTransaction) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label for="user_id">{{ __('messages.user') }} *</label>
                            <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
                                <option value="">{{ __('messages.select_user') }}</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" 
                                            {{ (old('user_id') ?? $walletTransaction->user_id) == $user->id ? 'selected' : '' }}
                                            data-balance="{{ $user->balance }}">
                                        {{ $user->name }} ({{ $user->email }}) - {{ __('messages.balance') }}: {{ number_format($user->balance, 2) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="admin_id">{{ __('messages.admin') }} *</label>
                            <select name="admin_id" id="admin_id" class="form-control @error('admin_id') is-invalid @enderror" required>
                                <option value="">{{ __('messages.select_admin') }}</option>
                                @foreach($admins as $admin)
                                    <option value="{{ $admin->id }}" 
                                            {{ (old('admin_id') ?? $walletTransaction->admin_id) == $admin->id ? 'selected' : '' }}>
                                        {{ $admin->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('admin_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="type">{{ __('messages.transaction_type') }} *</label>
                            <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
                                <option value="">{{ __('messages.select_type') }}</option>
                                <option value="1" {{ (old('type') ?? $walletTransaction->type) == '1' ? 'selected' : '' }}>{{ __('messages.add_money') }}</option>
                                <option value="2" {{ (old('type') ?? $walletTransaction->type) == '2' ? 'selected' : '' }}>{{ __('messages.withdrawal') }}</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="amount">{{ __('messages.amount') }} *</label>
                            <input type="number" step="0.01" name="amount" id="amount" 
                                   class="form-control @error('amount') is-invalid @enderror" 
                                   value="{{ old('amount') ?? $walletTransaction->amount }}" required min="0.01">
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small id="balance-warning" class="text-danger" style="display: none;">
                                {{ __('messages.withdrawal_exceeds_balance') }}
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="note">{{ __('messages.note') }}</label>
                            <textarea name="note" id="note" class="form-control @error('note') is-invalid @enderror" 
                                      rows="3" placeholder="{{ __('messages.transaction_note_placeholder') }}">{{ old('note') ?? $walletTransaction->note }}</textarea>
                            @error('note')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> {{ __('messages.update_transaction') }}
                            </button>
                            <a href="{{ route('wallet_transactions.show', $walletTransaction) }}" class="btn btn-info">
                                {{ __('messages.view') }}
                            </a>
                            <a href="{{ route('wallet_transactions.index') }}" class="btn btn-secondary">
                                {{ __('messages.cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const userSelect = document.getElementById('user_id');
    const typeSelect = document.getElementById('type');
    const amountInput = document.getElementById('amount');
    const balanceWarning = document.getElementById('balance-warning');

    function checkBalance() {
        const selectedOption = userSelect.options[userSelect.selectedIndex];
        const userBalance = selectedOption ? parseFloat(selectedOption.dataset.balance) : 0;
        const transactionType = typeSelect.value;
        const amount = parseFloat(amountInput.value) || 0;

        if (transactionType == '2' && amount > userBalance) {
            balanceWarning.style.display = 'block';
            amountInput.classList.add('is-invalid');
        } else {
            balanceWarning.style.display = 'none';
            amountInput.classList.remove('is-invalid');
        }
    }

    userSelect.addEventListener('change', checkBalance);
    typeSelect.addEventListener('change', checkBalance);
    amountInput.addEventListener('input', checkBalance);
});
</script>
@endsection