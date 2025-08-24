@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.User Details') }}: {{ $user->name }}</h3>
                    <div>
                        @can('user-edit')
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> {{ __('messages.Edit') }}
                            </a>
                        @endcan
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.Back to Users') }}
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- User Photo -->
                        <div class="col-md-3 text-center">
                            @if($user->photo)
                                <img src="{{ asset('assets/admin/uploads/' . $user->photo) }}" 
                                     alt="{{ $user->name }}" 
                                     class="img-fluid rounded-circle shadow" 
                                     style="max-width: 200px; max-height: 200px; object-fit: cover;">
                            @else
                                <div class="bg-secondary text-white d-flex align-items-center justify-content-center rounded-circle shadow mx-auto" 
                                     style="width: 200px; height: 200px; font-size: 4rem;">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                            @endif
                            <h4 class="mt-3">{{ $user->name }}</h4>
                            <span class="badge badge-{{ $user->role_name == 'teacher' ? 'success' : ($user->role_name == 'parent' ? 'warning' : 'info') }} badge-lg">
                                {{ __('messages.' . ucfirst($user->role_name)) }}
                            </span>
                        </div>

                        <!-- User Information -->
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <div class="info-box-icon bg-info">
                                            <i class="fas fa-id-card"></i>
                                        </div>
                                        <div class="info-box-content">
                                            <span class="info-box-text">{{ __('messages.User ID') }}</span>
                                            <span class="info-box-number">#{{ $user->id }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-box">
                                        <div class="info-box-icon bg-{{ $user->activate == 1 ? 'success' : 'danger' }}">
                                            <i class="fas fa-{{ $user->activate == 1 ? 'check-circle' : 'times-circle' }}"></i>
                                        </div>
                                        <div class="info-box-content">
                                            <span class="info-box-text">{{ __('messages.Status') }}</span>
                                            <span class="info-box-number">
                                                {{ $user->activate == 1 ? __('messages.Active') : __('messages.Inactive') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                @if($user->balance > 0)
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <div class="info-box-icon bg-success">
                                            <i class="fas fa-dollar-sign"></i>
                                        </div>
                                        <div class="info-box-content">
                                            <span class="info-box-text">{{ __('messages.Balance') }}</span>
                                            <span class="info-box-number">${{ number_format($user->balance, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if($user->category)
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <div class="info-box-icon bg-warning">
                                            <i class="fas fa-tags"></i>
                                        </div>
                                        <div class="info-box-content">
                                            <span class="info-box-text">{{ __('messages.Category') }}</span>
                                            <span class="info-box-number">{{ $user->category->name }}</span>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Detailed Information -->
                    <div class="row">
                        <div class="col-md-6">
                            <h5>{{ __('messages.Contact Information') }}</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>{{ __('messages.Email') }}:</strong></td>
                                    <td>
                                        @if($user->email)
                                            <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                                        @else
                                            <span class="text-muted">{{ __('messages.Not provided') }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>{{ __('messages.Phone') }}:</strong></td>
                                    <td>
                                        @if($user->phone)
                                            <a href="tel:{{ $user->phone }}">{{ $user->phone }}</a>
                                        @else
                                            <span class="text-muted">{{ __('messages.Not provided') }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @if($user->referal_code)
                                <tr>
                                    <td><strong>{{ __('messages.Referral Code') }}:</strong></td>
                                    <td>
                                        <code>{{ $user->referal_code }}</code>
                                        <button class="btn btn-sm btn-outline-secondary ml-2" 
                                                onclick="copyToClipboard('{{ $user->referal_code }}')">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h5>{{ __('messages.Account Information') }}</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>{{ __('messages.Created At') }}:</strong></td>
                                    <td>{{ $user->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>{{ __('messages.Last Updated') }}:</strong></td>
                                    <td>{{ $user->updated_at->format('M d, Y H:i') }}</td>
                                </tr>
                                @if($user->last_login)
                                <tr>
                                    <td><strong>{{ __('messages.Last Login') }}:</strong></td>
                                    <td>{{ $user->last_login }}</td>
                                </tr>
                                @endif
                                @if($user->ip_address)
                                <tr>
                                    <td><strong>{{ __('messages.IP Address') }}:</strong></td>
                                    <td><code>{{ $user->ip_address }}</code></td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    <!-- Social Login Information -->
                    @if($user->google_id || $user->apple_id)
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <h5>{{ __('messages.Social Login') }}</h5>
                            <div class="row">
                                @if($user->google_id)
                                <div class="col-md-6">
                                    <div class="alert alert-info">
                                        <i class="fab fa-google"></i> {{ __('messages.Google Account Connected') }}
                                        <br><small>ID: {{ $user->google_id }}</small>
                                    </div>
                                </div>
                                @endif
                                @if($user->apple_id)
                                <div class="col-md-6">
                                    <div class="alert alert-dark">
                                        <i class="fab fa-apple"></i> {{ __('messages.Apple Account Connected') }}
                                        <br><small>ID: {{ $user->apple_id }}</small>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Action Buttons -->
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <div class="btn-group" role="group">
                                @can('user-edit')
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                                        <i class="fas fa-edit"></i> {{ __('messages.Edit User') }}
                                    </a>
                                @endcan
                                @can('user-delete')
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('{{ __('messages.Are you sure you want to delete this user?') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-trash"></i> {{ __('messages.Delete User') }}
                                        </button>
                                    </form>
                                @endcan
                                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-list"></i> {{ __('messages.All Users') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('{{ __('messages.Referral code copied to clipboard!') }}');
    }, function(err) {
        console.error('Could not copy text: ', err);
    });
}
</script>

<style>
.badge-lg {
    font-size: 1rem;
    padding: 0.5rem 1rem;
}

.info-box {
    display: flex;
    margin-bottom: 1rem;
    background: #fff;
    border: 1px solid #dee2e6;
    border-radius: 0.25rem;
    box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
}

.info-box-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    width: 70px;
    text-align: center;
    font-size: 1.875rem;
}

.info-box-content {
    padding: 0.5rem;
    flex: 1;
}

.info-box-text {
    display: block;
    font-size: 0.875rem;
    color: #6c757d;
    text-transform: uppercase;
}

.info-box-number {
    display: block;
    font-weight: 700;
    font-size: 1.125rem;
}
</style>
@endsection