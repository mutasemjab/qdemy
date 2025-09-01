@extends('layouts.admin')

@section('title', __('messages.Categories'))

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">{{ __('messages.Categories') }}</h1>
            <p class="text-muted">{{ __('messages.Manage category hierarchy') }}</p>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('categories.index') }}" class="row">
                <div class="col-md-8">
                    <input type="text" name="search" class="form-control"
                           value="{{ request('search') }}"
                           placeholder="{{ __('messages.Search categories...') }}">
                </div>
                <div class="col-md-4 d-flex">
                    <button type="submit" class="btn btn-outline-primary mr-2">
                        <i class="fas fa-search mr-1"></i>{{ __('messages.Search') }}
                    </button>
                    @if(request('search'))
                        <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times mr-1"></i>{{ __('messages.Clear') }}
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Categories Tree -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('messages.Categories List') }}</h5>
            <div class="d-flex">
                <button type="button" class="btn btn-sm btn-outline-primary mr-2" id="expand-all">
                    <i class="fas fa-expand-arrows-alt mr-1"></i>{{ __('messages.Expand All') }}
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" id="collapse-all">
                    <i class="fas fa-compress-arrows-alt mr-1"></i>{{ __('messages.Collapse All') }}
                </button>
            </div>
        </div>

        <div class="card-body p-0">
            @if($rootCategories->count() > 0)
                <div class="category-tree">
                    @foreach($rootCategories as $category)
                        @include('admin.categories.partials.tree-item', ['category' => $category, 'level' => 0])
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                    <h5>{{ __('messages.No categories found') }}</h5>
                    <p class="text-muted">{{ __('messages.Create your first category to get started') }}</p>
                </div>
            @endif
        </div>
    </div>
</div>


@push('scripts')
<script>
$(document).ready(function() {
    // Handle collapse toggle icon rotation
    $('[data-toggle="collapse"]').each(function() {
        var $toggleBtn = $(this);
        var $icon = $toggleBtn.find('.transition-icon');
        var targetSelector = $toggleBtn.attr('data-target');
        var $target = $(targetSelector);

        // Bootstrap 4 events
        $target.on('shown.bs.collapse', function() {
            $icon.removeClass('fa-chevron-right').addClass('fa-chevron-down');
            $toggleBtn.attr('aria-expanded', 'true');
        });

        $target.on('hidden.bs.collapse', function() {
            $icon.removeClass('fa-chevron-down').addClass('fa-chevron-right');
            $toggleBtn.attr('aria-expanded', 'false');
        });
    });

    // Expand all button
    $('#expand-all').click(function() {
        $('.collapse:not(.show)').collapse('show');
    });

    // Collapse all button
    $('#collapse-all').click(function() {
        $('.collapse.show').collapse('hide');
    });

    // Manual click handler as fallback
    $('.toggle-btn').click(function(e) {
        e.preventDefault();
        var targetSelector = $(this).attr('data-target');
        var $target = $(targetSelector);
        var $icon = $(this).find('.transition-icon');

        if ($target.length) {
            $target.collapse('toggle');
        }
    });
});
</script>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all');
    const categoryCheckboxes = document.querySelectorAll('.category-checkbox');
    const bulkButtons = document.querySelectorAll('#bulk-activate, #bulk-deactivate, #bulk-delete');
    const bulkForm = document.getElementById('bulk-form');
    const bulkActionInput = document.getElementById('bulk-action-input');

    // Select all functionality
    selectAllCheckbox.addEventListener('change', function() {
        categoryCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        toggleBulkButtons();
    });

    // Individual checkbox change
    categoryCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const checkedCount = document.querySelectorAll('.category-checkbox:checked').length;
            selectAllCheckbox.checked = checkedCount === categoryCheckboxes.length;
            selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < categoryCheckboxes.length;
            toggleBulkButtons();
        });
    });

    // Toggle bulk action buttons
    function toggleBulkButtons() {
        const checkedCount = document.querySelectorAll('.category-checkbox:checked').length;
        bulkButtons.forEach(button => {
            button.disabled = checkedCount === 0;
        });
    }

    // Bulk action buttons
    document.getElementById('bulk-activate').addEventListener('click', function() {
        if (confirm('{{ __("Are you sure you want to activate selected categories?") }}')) {
            bulkActionInput.value = 'activate';
            bulkForm.submit();
        }
    });

    document.getElementById('bulk-deactivate').addEventListener('click', function() {
        if (confirm('{{ __("Are you sure you want to deactivate selected categories?") }}')) {
            bulkActionInput.value = 'deactivate';
            bulkForm.submit();
        }
    });

    document.getElementById('bulk-delete').addEventListener('click', function() {
        if (confirm('{{ __("Are you sure you want to delete selected categories?") }}')) {
            bulkActionInput.value = 'delete';
            bulkForm.submit();
        }
    });
});
</script>
@endpush
@endsection
