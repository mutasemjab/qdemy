@extends('layouts.admin')

@section('title', __('messages.edit'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.edit') }}: {{ $category->name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
                        </a>
                    </div>
                </div>

                <form action="{{ route('categories.update', $category->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <!-- Arabic Name -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name_ar" class="form-label">
                                        {{ __('messages.name') }} <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           class="form-control @error('name_ar') is-invalid @enderror"
                                           id="name_ar"
                                           name="name_ar"
                                           value="{{ old('name_ar', $category->name_ar) }}"
                                           placeholder="{{ __('messages.enter_name_ar') }}"
                                           dir="rtl"
                                           required>
                                    @error('name_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- English Name -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name_en" class="form-label">
                                        {{ __('messages.name') }}
                                    </label>
                                    <input type="text"
                                           class="form-control @error('name_en') is-invalid @enderror"
                                           id="name_en"
                                           name="name_en"
                                           value="{{ old('name_en', $category->name_en) }}"
                                           placeholder="{{ __('messages.enter_name_en') }}">
                                    @error('name_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Arabic Description -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="description_ar" class="form-label">
                                        {{ __('messages.description_ar') }}
                                    </label>
                                    <textarea class="form-control @error('description_ar') is-invalid @enderror"
                                              id="description_ar"
                                              name="description_ar"
                                              rows="4"
                                              placeholder="{{ __('messages.enter_description_ar') }}"
                                              dir="rtl">{{ old('description_ar', $category->description_ar) }}</textarea>
                                    @error('description_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- English Description -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="description_en" class="form-label">
                                        {{ __('messages.description_en') }}
                                    </label>
                                    <textarea class="form-control @error('description_en') is-invalid @enderror"
                                              id="description_en"
                                              name="description_en"
                                              rows="4"
                                              placeholder="{{ __('messages.enter_description_en') }}">{{ old('description_en', $category->description_en) }}</textarea>
                                    @error('description_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Icon -->
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="icon" class="form-label">
                                        {{ __('messages.icon') }}
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="{{ old('icon', $category->icon) ?: 'fas fa-folder' }}" id="icon-preview"></i>
                                        </span>
                                        <input type="text"
                                               class="form-control @error('icon') is-invalid @enderror"
                                               id="icon"
                                               name="icon"
                                               value="{{ old('icon', $category->icon) }}"
                                               placeholder="fas fa-folder">
                                        @error('icon')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="form-text text-muted">
                                        {{ __('messages.icon_help') }}
                                    </small>
                                </div>
                            </div>

                            <!-- Color -->
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="color" class="form-label">
                                        {{ __('messages.Color') }}
                                    </label>
                                    <input type="color"
                                           class="form-control form-control-color @error('color') is-invalid @enderror"
                                           id="color"
                                           name="color"
                                           value="{{ old('color', $category->color) }}"
                                           title="{{ __('messages.choose_color') }}">
                                    @error('color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Active Status -->
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="is_active" class="form-label">
                                        {{ __('messages.status') }}
                                    </label>
                                    <select class="form-control @error('is_active') is-invalid @enderror"
                                            id="is_active"
                                            name="is_active">
                                        <option value="1" {{ old('is_active', $category->is_active) == 1 ? 'selected' : '' }}>
                                            {{ __('messages.active') }}
                                        </option>
                                        <option value="0" {{ old('is_active', $category->is_active) == 0 ? 'selected' : '' }}>
                                            {{ __('messages.inactive') }}
                                        </option>
                                    </select>
                                    @error('is_active')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if($category->hasChildren())
                                        <small class="form-text text-warning">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            {{ __('messages.status_change_warning') }}
                                        </small>
                                    @endif
                                </div>
                            </div>

                            <!-- Display Only Fields -->
                            <!-- Sort Order (Display Only) -->
                            <div class="col-md-6 col-12">
                                <div class="form-group mb-3">
                                    <label class="form-label">{{ __('messages.sort_order') }}</label>
                                    <input type="text" class="form-control" value="{{ $category->sort_order }}" readonly disabled>
                                </div>
                            </div>

                            <!-- Parent Category (Display Only) -->
                            <div class="col-md-6 col-12">
                                <div class="form-group mb-3">
                                    <label class="form-label">{{ __('messages.parent_category') }}</label>
                                    <input type="text" class="form-control"
                                           value="{{ $category->parent ? $category->parent->name_ar : __('messages.root_category') }}"
                                           readonly disabled>
                                </div>
                            </div>

                            <!-- Type (Display Only) -->
                            <div class="col-md-6 col-12">
                                <div class="form-group mb-3">
                                    <label class="form-label">{{ __('messages.type') }}</label>
                                    <input type="text" class="form-control" value="{{ $category->type }}" readonly disabled>
                                </div>
                            </div>

                            @if($category->breadcrumb)
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <strong>{{ __('messages.Path') }}:</strong> {{ $category->breadcrumb }}
                                    </div>
                                </div>
                            @endif

                            <!-- WhatsApp Contacts Section (Only for Subcategories) -->
                            @if($category->parent_id)
                            <div class="col-12 mt-4">
                                <hr>
                                <h5 class="mb-3">{{ __('messages.whatsapp_numbers') ?? 'WhatsApp Numbers' }}</h5>

                                <!-- Existing Contacts -->
                                @if($category->whatsappContacts && $category->whatsappContacts->count() > 0)
                                    <div class="table-responsive mb-3">
                                        <table class="table table-sm table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>{{ __('messages.phone_number') ?? 'Phone Number' }}</th>
                                                    <th>{{ __('messages.label') ?? 'Label' }}</th>
                                                    <th>{{ __('messages.status') }}</th>
                                                    <th style="width: 100px;">{{ __('messages.action') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody id="whatsapp-contacts-list">
                                                @foreach($category->whatsappContacts as $contact)
                                                    <tr class="contact-row" data-contact-id="{{ $contact->id }}">
                                                        <td>{{ $contact->phone_number }}</td>
                                                        <td>{{ $contact->label ?? '-' }}</td>
                                                        <td>
                                                            <span class="badge bg-success">{{ __('messages.active') }}</span>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-sm btn-danger btn-delete-contact" data-contact-id="{{ $contact->id }}">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-muted">{{ __('messages.no_whatsapp_numbers') ?? 'No WhatsApp numbers saved for this category' }}</p>
                                @endif

                                <!-- Add New Contact Form -->
                                <div class="card card-outline card-primary mt-3">
                                    <div class="card-header">
                                        <h3 class="card-title">{{ __('messages.add_new_number') ?? 'Add New Number' }}</h3>
                                    </div>
                                    <div class="card-body">
                                        <form id="add-whatsapp-form" method="POST">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-2">
                                                        <label for="phone_number" class="form-label">
                                                            {{ __('messages.phone_number') }} <span class="text-danger">*</span>
                                                        </label>
                                                        <input type="text"
                                                               class="form-control"
                                                               id="phone_number"
                                                               name="phone_number"
                                                               placeholder="+962775743580"
                                                               required>
                                                        <small class="text-muted">{{ __('messages.example') }}: +962775743580</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-2">
                                                        <label for="label" class="form-label">
                                                            {{ __('messages.label') }} ({{ __('messages.optional') }})
                                                        </label>
                                                        <input type="text"
                                                               class="form-control"
                                                               id="label"
                                                               name="label"
                                                               placeholder="{{ __('messages.example') }}: Primary, Sales, Support">
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <i class="fas fa-plus"></i> {{ __('messages.add') }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                                {{ __('messages.cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> {{ __('messages.update') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Preview icon on change
    document.getElementById('icon').addEventListener('input', function() {
        const iconPreview = document.getElementById('icon-preview');
        iconPreview.className = this.value || 'fas fa-folder';
    });

    // WhatsApp contact form submission
    const form = document.getElementById('add-whatsapp-form');
    const categoryId = {{ $category->id }};

    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            const phoneNumber = document.getElementById('phone_number').value;
            const label = document.getElementById('label').value;

            try {
                const response = await fetch(`/admin/categories/${categoryId}/whatsapp-contacts`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        phone_number: phoneNumber,
                        label: label
                    })
                });

                const data = await response.json();

                if (data.success) {
                    location.reload();
                } else {
                    alert('{{ __('messages.error_occurred') }}: ' + data.message);
                }
            } catch (error) {
                alert('{{ __('messages.error_occurred') }}: ' + error.message);
            }
        });
    }

    // Delete contact
    document.querySelectorAll('.btn-delete-contact').forEach(btn => {
        btn.addEventListener('click', async function(e) {
            e.preventDefault();
            const contactId = this.dataset.contactId;

            if (!confirm('{{ __('messages.confirm_delete') }}')) return;

            try {
                const response = await fetch(`/admin/whatsapp-contacts/${contactId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    location.reload();
                } else {
                    alert('{{ __('messages.error_occurred') }}: ' + data.message);
                }
            } catch (error) {
                alert('{{ __('messages.error_occurred') }}: ' + error.message);
            }
        });
    });
</script>
@endpush

@endsection
