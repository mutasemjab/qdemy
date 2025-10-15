{{-- resources/views/files/index.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>{{ __('messages.files_management') }}</h4>
                    <div>
                        <a href="{{ route('files.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> {{ __('messages.add_new_file') }}
                        </a>
                        <button class="btn btn-danger" id="bulkDeleteBtn" style="display: none;" onclick="bulkDelete()">
                            <i class="fas fa-trash"></i> {{ __('messages.delete_selected') }}
                        </button>
                    </div>
                </div>

                <div class="card-body">

                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <select class="form-control" id="categoryFilter" onchange="filterFiles()">
                                <option value="">{{ __('messages.all_categories') }}</option>
                                @if(isset($categories))
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                @else
                                    @php
                                        $categories = \App\Models\CategoryFile::all();
                                    @endphp
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="searchFilter" 
                                   placeholder="{{ __('messages.search_files') }}" onkeyup="filterFiles()">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-secondary w-100" onclick="clearFilters()">
                                {{ __('messages.clear_filters') }}
                            </button>
                        </div>
                    </div>

                   <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">{{ __('messages.id') }}</th>
                                    <th width="30%">{{ __('messages.file_name') }}</th>
                                    <th width="20%">{{ __('messages.category') }}</th>
                                    <th width="15%">{{ __('messages.created_at') }}</th>
                                    <th width="15%">{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($files as $file)
                                    <tr>
                                      
                                        <td>{{ $file->id }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-file-pdf text-danger me-2"></i>
                                                <div>
                                                    <strong>{{ $file->name }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $file->pdf }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $file->category->name ?? __('messages.no_category') }}</span>
                                        </td>
                                       
                                        <td>
                                            <div>
                                                {{ $file->created_at->format('Y-m-d') }}
                                                <br>
                                                <small class="text-muted">{{ $file->created_at->format('H:i') }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                               
                                              
                                                
                                                <a href="{{ route('files.edit', $file) }}" class="btn btn-warning" title="{{ __('messages.edit') }}">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('files.destroy', $file) }}" method="POST" style="display: inline;">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" title="{{ __('messages.delete') }}"
                                                            onclick="return confirm('{{ __('messages.confirm_delete') }}')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="fas fa-folder-open text-muted"></i>
                                            <p class="mt-2">{{ __('messages.no_files_found') }}</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($files->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{ $files->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function filterFiles() {
    const categoryId = document.getElementById('categoryFilter').value;
    const search = document.getElementById('searchFilter').value;
    
    fetch(`{{ route('files.search') }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            category_id: categoryId,
            search: search
        })
    })
    .then(response => response.text())
    .then(html => {
        document.getElementById('filesContainer').innerHTML = html;
        toggleBulkDelete();
    })
    .catch(error => console.error('Error:', error));
}

function clearFilters() {
    document.getElementById('categoryFilter').value = '';
    document.getElementById('searchFilter').value = '';
    filterFiles();
}

// Add event listeners to checkboxes
document.addEventListener('DOMContentLoaded', function() {
    toggleBulkDelete();
    
    // Add event listeners to existing checkboxes
    document.querySelectorAll('input[name="selected_files[]"]').forEach(checkbox => {
        checkbox.addEventListener('change', toggleBulkDelete);
    });
});
</script>
@endsection



