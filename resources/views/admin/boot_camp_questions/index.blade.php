@extends('layouts.admin')

@section('title', __('messages.boot_camp_questions'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.boot_camp_questions') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.boot-camp-questions.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> {{ __('messages.add_new') }}
                        </a>
                    </div>
                </div>

                <div class="card-body">
                

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="20%">{{ __('messages.display_name') }}</th>
                                    <th width="15%">{{ __('messages.category') }}</th>
                                    <th width="15%">{{ __('messages.subject') }}</th>
                                    <th width="10%">{{ __('messages.file_size') }}</th>
                                    <th width="8%">{{ __('messages.downloads') }}</th>
                                    <th width="8%">{{ __('messages.status') }}</th>
                                    <th width="10%">{{ __('messages.created_at') }}</th>
                                    <th width="9%">{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bootCampQuestions as $bootCampQuestion)
                                    <tr>
                                        <td>{{ $loop->iteration + ($bootCampQuestions->currentPage() - 1) * $bootCampQuestions->perPage() }}</td>
                                        
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-file-pdf text-danger mr-2"></i>
                                                <div>
                                                    <strong>{{ $bootCampQuestion->display_name ?: __('messages.no_name') }}</strong>
                                                    @if($bootCampQuestion->pdf)
                                                        <br><small class="text-muted">{{ basename($bootCampQuestion->pdf) }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td>
                                            @if($bootCampQuestion->category)
                                                <span class="badge badge-info">
                                                    {{ $bootCampQuestion->category->localized_name }}
                                                </span>
                                                @if($bootCampQuestion->category->parent)
                                                    <br><small class="text-muted">{{ $bootCampQuestion->category->parent->localized_name }}</small>
                                                @endif
                                            @else
                                                <span class="text-muted">{{ __('messages.no_category') }}</span>
                                            @endif
                                        </td>
                                        
                                        <td>
                                            @if($bootCampQuestion->subject)
                                                <span class="badge badge-secondary">
                                                    {{ $bootCampQuestion->subject->localized_name }}
                                                </span>
                                            @else
                                                <span class="text-muted">{{ __('messages.no_subject') }}</span>
                                            @endif
                                        </td>
                                        
                                        <td>
                                            @if($bootCampQuestion->pdf_size)
                                                <span class="badge badge-light">{{ $bootCampQuestion->formatted_file_size }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        
                                        <td>
                                            <span class="badge badge-primary">{{ $bootCampQuestion->download_count ?? 0 }}</span>
                                        </td>
                                        
                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" 
                                                       class="custom-control-input status-toggle" 
                                                       id="status-{{ $bootCampQuestion->id }}"
                                                       data-id="{{ $bootCampQuestion->id }}"
                                                       {{ $bootCampQuestion->is_active ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="status-{{ $bootCampQuestion->id }}"></label>
                                            </div>
                                        </td>
                                        
                                        <td>
                                            <small>{{ $bootCampQuestion->created_at->format('Y-m-d') }}</small>
                                            <br><small class="text-muted">{{ $bootCampQuestion->created_at->format('H:i') }}</small>
                                        </td>
                                        
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                          
                                                
                                                <!-- Download Button -->
                                                @if($bootCampQuestion->pdf && $bootCampQuestion->pdfExists())
                                                    <a href="{{ route('admin.boot-camp-questions.download-pdf', $bootCampQuestion) }}" 
                                                       class="btn btn-success btn-sm" title="{{ __('messages.download') }}">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                @else
                                                    <button class="btn btn-outline-secondary btn-sm" disabled title="{{ __('messages.file_not_available') }}">
                                                        <i class="fas fa-download"></i>
                                                    </button>
                                                @endif
                                                
                                                <!-- Edit Button -->
                                                <a href="{{ route('admin.boot-camp-questions.edit', $bootCampQuestion) }}" 
                                                   class="btn btn-warning btn-sm" title="{{ __('messages.edit') }}">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                <!-- Delete Button -->
                                                <button type="button" 
                                                        class="btn btn-danger btn-sm delete-btn" 
                                                        data-id="{{ $bootCampQuestion->id }}"
                                                        data-name="{{ $bootCampQuestion->display_name ?: __('messages.this_item') }}"
                                                        title="{{ __('messages.delete') }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                                <p>{{ __('messages.no_boot_camp_questions_found') }}</p>
                                                <a href="{{ route('admin.boot-camp-questions.create') }}" class="btn btn-primary">
                                                    <i class="fas fa-plus"></i> {{ __('messages.add_first_boot_camp_question') }}
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($bootCampQuestions->hasPages())
                        <div class="d-flex justify-content-center">
                            {{ $bootCampQuestions->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">{{ __('messages.confirm_delete') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>{{ __('messages.are_you_sure_delete') }} "<span id="delete-item-name"></span>"?</p>
                <p class="text-danger"><small>{{ __('messages.this_action_cannot_be_undone') }}</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('messages.cancel') }}</button>
                <form id="delete-form" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">{{ __('messages.delete') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Handle status toggle
    $('.status-toggle').change(function() {
        var bootCampQuestionId = $(this).data('id');
        var isActive = $(this).is(':checked');
        var toggleElement = $(this);
        
        $.ajax({
            url: '{{ route('admin.boot-camp-questions.toggle-status', ':id') }}'.replace(':id', bootCampQuestionId),
            type: 'POST',
            data: {
                '_token': '{{ csrf_token() }}',
                'is_active': isActive ? 1 : 0
            },
            success: function(response) {
                if (response.success) {
                    // Show success message
                    showAlert('success', response.message);
                } else {
                    // Revert toggle state
                    toggleElement.prop('checked', !isActive);
                    showAlert('error', response.message || '{{ __('messages.error_occurred') }}');
                }
            },
            error: function() {
                // Revert toggle state
                toggleElement.prop('checked', !isActive);
                showAlert('error', '{{ __('messages.error_occurred') }}');
            }
        });
    });
    
    // Handle delete button click
    $('.delete-btn').click(function() {
        var bootCampQuestionId = $(this).data('id');
        var bootCampQuestionName = $(this).data('name');
        
        $('#delete-item-name').text(bootCampQuestionName);
        $('#delete-form').attr('action', '{{ route('admin.boot-camp-questions.destroy', ':id') }}'.replace(':id', bootCampQuestionId));
        $('#deleteModal').modal('show');
    });
    
    // Function to show alerts
    function showAlert(type, message) {
        var alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        var alertHtml = '<div class="alert ' + alertClass + ' alert-dismissible fade show" role="alert">' +
                       message +
                       '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                       '<span aria-hidden="true">&times;</span>' +
                       '</button>' +
                       '</div>';
        
        $('.card-body').prepend(alertHtml);
        
        // Auto dismiss after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 5000);
    }
});
</script>
@endpush