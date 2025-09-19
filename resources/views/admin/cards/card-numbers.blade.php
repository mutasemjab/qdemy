@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>{{ __('messages.card_numbers_for') }}: {{ $card->name }}</h4>
                    <div>
                        <a href="{{ route('cards.show', $card) }}" class="btn btn-info btn-sm">
                            {{ __('messages.card_details') }}
                        </a>
                        <a href="{{ route('cards.index') }}" class="btn btn-secondary btn-sm">
                            {{ __('messages.back_to_cards') }}
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Card Info Summary -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card border-primary">
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-md-2">
                                            <h6>{{ __('messages.card_name') }}</h6>
                                            <strong>{{ $card->name }}</strong>
                                        </div>
                                        <div class="col-md-1">
                                            <h6>{{ __('messages.price') }}</h6>
                                            <span class="badge bg-success">{{ number_format($card->price, 2) }}</span>
                                        </div>
                                        <div class="col-md-1">
                                            <h6>{{ __('messages.total_numbers') }}</h6>
                                            <span class="badge bg-primary">{{ $cardNumbers->total() }}</span>
                                        </div>
                                        <div class="col-md-2">
                                            <h6>{{ __('messages.available') }}</h6>
                                            <span class="badge bg-success">{{ $card->cardNumbers()->whereNull('assigned_user_id')->where('status', 2)->where('activate', 1)->count() }}</span>
                                        </div>
                                        <div class="col-md-2">
                                            <h6>{{ __('messages.assigned_not_used') }}</h6>
                                            <span class="badge bg-warning">{{ $card->cardNumbers()->whereNotNull('assigned_user_id')->where('status', 2)->count() }}</span>
                                        </div>
                                        <div class="col-md-2">
                                            <h6>{{ __('messages.used_numbers') }}</h6>
                                            <span class="badge bg-danger">{{ $card->cardNumbers()->where('status', 1)->count() }}</span>
                                        </div>
                                        <div class="col-md-2">
                                            <h6>{{ __('messages.inactive') }}</h6>
                                            <span class="badge bg-secondary">{{ $card->cardNumbers()->where('activate', 2)->count() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filter and Actions -->
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <form method="GET" action="{{ route('cards.card-numbers', $card) }}" class="d-flex">
                                <select name="status" class="form-select me-2" onchange="this.form.submit()">
                                    <option value="">{{ __('messages.all_status') }}</option>
                                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>{{ __('messages.available') }}</option>
                                    <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>{{ __('messages.assigned_not_used') }}</option>
                                    <option value="used" {{ request('status') == 'used' ? 'selected' : '' }}>{{ __('messages.used') }}</option>
                                </select>
                                <select name="activate" class="form-select me-2" onchange="this.form.submit()">
                                    <option value="">{{ __('messages.all_activate') }}</option>
                                    <option value="1" {{ request('activate') == '1' ? 'selected' : '' }}>{{ __('messages.active') }}</option>
                                    <option value="2" {{ request('activate') == '2' ? 'selected' : '' }}>{{ __('messages.inactive') }}</option>
                                </select>
                                <input type="text" name="search" class="form-control me-2" placeholder="{{ __('messages.search_user_or_number') }}" value="{{ request('search') }}">
                                <button type="submit" class="btn btn-outline-primary">{{ __('messages.filter') }}</button>
                            </form>
                        </div>
                        <div class="col-md-4 text-end">
                        
                            <form action="{{ route('cards.regenerate-numbers', $card) }}" 
                                  method="POST" 
                                  style="display: inline-block;"
                                  onsubmit="return confirm('{{ __('messages.confirm_regenerate') }}')">
                                @csrf
                                @method('POST')
                                <button type="submit" class="btn btn-warning">
                                    {{ __('messages.regenerate_all') }}
                                </button>
                            </form>
                        </div>
                    </div>

                    @if($cardNumbers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>{{ __('messages.id') }}</th>
                                        <th>{{ __('messages.card_number') }}</th>
                                        <th>{{ __('messages.assigned_user') }}</th>
                                        <th>{{ __('messages.status') }}</th>
                                        <th>{{ __('messages.activate_status') }}</th>
                                        <th>{{ __('messages.created_at') }}</th>
                                        <th>{{ __('messages.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cardNumbers as $cardNumber)
                                        <tr>
                                            <td>{{ $cardNumber->id }}</td>
                                            <td>
                                                <strong>{{ $cardNumber->number }}</strong>
                                            </td>
                                            <td>
                                                @if($cardNumber->assignedUser)
                                                    <div>
                                                        <strong>{{ $cardNumber->assignedUser->name }}</strong><br>
                                                        <small class="text-muted">{{ $cardNumber->assignedUser->email }}</small>
                                                        @if($cardNumber->assignedUser->phone)
                                                            <br><small class="text-muted">{{ $cardNumber->assignedUser->phone }}</small>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-muted">{{ __('messages.not_assigned') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge {{ $cardNumber->getStatusBadgeClass() }}">
                                                    {{ $cardNumber->getStatusText() }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($cardNumber->activate == 1)
                                                    <span class="badge bg-success">{{ __('messages.active') }}</span>
                                                @else
                                                    <span class="badge bg-warning">{{ __('messages.inactive') }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $cardNumber->created_at->format('Y-m-d H:i') }}</td>
                                            <td>
                                                <div class="btn-group-vertical" role="group">
                                                    @if($cardNumber->isAvailable())
                                                        <!-- Assign to User Button -->
                                                        <button type="button" class="btn btn-primary btn-sm mb-1" 
                                                                onclick="showAssignModal({{ $cardNumber->id }}, '{{ $cardNumber->number }}')">
                                                            {{ __('messages.assign_to_user') }}
                                                        </button>
                                                    @elseif($cardNumber->isAssignedButNotUsed())
                                                        <!-- Mark as Used Button -->
                                                        <form action="{{ route('card-numbers.mark-used', $cardNumber) }}" 
                                                              method="POST" 
                                                              style="display: inline-block;"
                                                              onsubmit="return confirm('{{ __('messages.confirm_mark_used') }}')">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-success btn-sm mb-1">
                                                                {{ __('messages.mark_as_used') }}
                                                            </button>
                                                        </form>
                                                        <!-- Remove Assignment Button -->
                                                        <form action="{{ route('card-numbers.remove-assignment', $cardNumber) }}" 
                                                              method="POST" 
                                                              style="display: inline-block;"
                                                              onsubmit="return confirm('{{ __('messages.confirm_remove_assignment') }}')">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-outline-warning btn-sm mb-1">
                                                                {{ __('messages.remove_assignment') }}
                                                            </button>
                                                        </form>
                                                    @elseif($cardNumber->isUsed())
                                                        <!-- Mark as Not Used Button -->
                                                        <form action="{{ route('card-numbers.toggle-status', $cardNumber) }}" 
                                                              method="POST" 
                                                              style="display: inline-block;"
                                                              onsubmit="return confirm('{{ __('messages.confirm_mark_unused') }}')">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-outline-success btn-sm mb-1">
                                                                {{ __('messages.mark_unused') }}
                                                            </button>
                                                        </form>
                                                    @endif
                                                    
                                                    <!-- Toggle Activate Button -->
                                                    <form action="{{ route('card-numbers.toggle-activate', $cardNumber) }}" 
                                                          method="POST" 
                                                          style="display: inline-block;">
                                                        @csrf
                                                        @method('PATCH')
                                                        @if($cardNumber->activate == 1)
                                                            <button type="submit" class="btn btn-warning btn-sm">
                                                                {{ __('messages.deactivate') }}
                                                            </button>
                                                        @else
                                                            <button type="submit" class="btn btn-secondary btn-sm">
                                                                {{ __('messages.activate') }}
                                                            </button>
                                                        @endif
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center">
                            {{ $cardNumbers->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center">
                            <p class="text-muted">{{ __('messages.no_card_numbers_found') }}</p>
                            <form action="{{ route('cards.regenerate-numbers', $card) }}" 
                                  method="POST" 
                                  style="display: inline-block;"
                                  onsubmit="return confirm('{{ __('messages.confirm_regenerate') }}')">
                                @csrf
                                @method('POST')
                                <button type="submit" class="btn btn-primary">
                                    {{ __('messages.generate_numbers') }}
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Assign User Modal -->
<div class="modal fade" id="assignUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('messages.assign_card_to_user') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="assignUserForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">{{ __('messages.card_number') }}</label>
                        <input type="text" class="form-control" id="modalCardNumber" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('messages.search_user') }}</label>
                        <input type="text" class="form-control" id="userSearch" placeholder="{{ __('messages.type_to_search_users') }}">
                        <div id="userSearchResults" class="mt-2"></div>
                    </div>
                    <input type="hidden" id="selectedUserId" name="user_id">
                    <div id="selectedUserInfo" class="alert alert-info" style="display: none;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                    <button type="submit" class="btn btn-primary" id="assignBtn" disabled>{{ __('messages.assign') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
function showAssignModal(cardNumberId, cardNumber) {
    document.getElementById('modalCardNumber').value = cardNumber;

    // ✅ Use Laravel named route with placeholder replacement
    document.getElementById('assignUserForm').action =
        "{{ route('card-numbers.assign-form', ':id') }}".replace(':id', cardNumberId);

    document.getElementById('userSearch').value = '';
    document.getElementById('selectedUserId').value = '';
    document.getElementById('selectedUserInfo').style.display = 'none';
    document.getElementById('userSearchResults').innerHTML = '';
    document.getElementById('assignBtn').disabled = true;

    new bootstrap.Modal(document.getElementById('assignUserModal')).show();
}

// ✅ User search functionality
document.getElementById('userSearch').addEventListener('input', function() {
    const query = this.value;
    if (query.length < 2) {
        document.getElementById('userSearchResults').innerHTML = '';
        return;
    }

    fetch(`{{ route('admin.users.search') }}?q=${encodeURIComponent(query)}`, {
        headers: {
            "X-Requested-With": "XMLHttpRequest", // ✅ ensure Laravel knows it's AJAX
            "Accept": "application/json"
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
    })
    .then(users => {
        let html = '';
        users.forEach(user => {
            html += `
                <div class="user-result p-2 border rounded mb-1 cursor-pointer"
                     onclick="selectUser(${user.id}, '${user.name}', '${user.email}', '${user.phone || ''}')">
                    <strong>${user.name}</strong><br>
                    <small class="text-muted">${user.email}</small>
                    ${user.phone ? `<br><small class="text-muted">${user.phone}</small>` : ''}
                </div>
            `;
        });
        document.getElementById('userSearchResults').innerHTML = html;
    })
    .catch(err => {
        console.error("Fetch error:", err);
        document.getElementById('userSearchResults').innerHTML =
            `<div class="text-danger">Error loading users</div>`;
    });
});

function selectUser(userId, name, email, phone) {
    document.getElementById('selectedUserId').value = userId;
    document.getElementById('userSearch').value = name;
    document.getElementById('userSearchResults').innerHTML = '';

    const phoneInfo = phone ? `<br><strong>{{ __('messages.phone') }}:</strong> ${phone}` : '';
    document.getElementById('selectedUserInfo').innerHTML = `
        <strong>{{ __('messages.selected_user') }}:</strong><br>
        <strong>{{ __('messages.name') }}:</strong> ${name}<br>
        <strong>{{ __('messages.email') }}:</strong> ${email}
        ${phoneInfo}
    `;
    document.getElementById('selectedUserInfo').style.display = 'block';
    document.getElementById('assignBtn').disabled = false;
}
</script>

<style>
.cursor-pointer {
    cursor: pointer;
}
.user-result:hover {
    background-color: #f8f9fa;
}
</style>
@endsection