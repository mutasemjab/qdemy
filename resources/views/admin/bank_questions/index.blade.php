@extends('layouts.admin')

@section('title', __('messages.bank_questions'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.bank_questions') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('bank-questions.create') }}" class="btn btn-primary">
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
                                @forelse($bankQuestions as $bankQuestion)
                                    <tr>
                                        <td>{{ $loop->iteration + ($bankQuestions->currentPage() - 1) * $bankQuestions->perPage() }}</td>
                                        
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-file-pdf text-danger mr-2"></i>
                                                <div>
                                                    <strong>{{ $bankQuestion->display_name ?: __('messages.no_name') }}</strong>
                                                    @if($bankQuestion->pdf)
                                                        <br><small class="text-muted">{{ basename($bankQuestion->pdf) }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td>
                                            @if($bankQuestion->category)
                                                <span class="badge badge-info">
                                                    {{ $bankQuestion->category->localized_name }}
                                                </span>
                                                @if($bankQuestion->category->parent)
                                                    <br><small class="text-muted">{{ $bankQuestion->category->parent->localized_name }}</small>
                                                @endif
                                            @else
                                                <span class="text-muted">{{ __('messages.no_category') }}</span>
                                            @endif
                                        </td>
                                        
                                        <td>
                                            @if($bankQuestion->subject)
                                                <span class="badge badge-secondary">
                                                    {{ $bankQuestion->subject->localized_name }}
                                                </span>
                                            @else
                                                <span class="text-muted">{{ __('messages.no_subject') }}</span>
                                            @endif
                                        </td>
                                        
                                        <td>
                                            @if($bankQuestion->pdf_size)
                                                <span class="badge badge-light">{{ $bankQuestion->formatted_file_size }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        
                                        <td>
                                            <span class="badge badge-primary">{{ $bankQuestion->download_count ?? 0 }}</span>
                                        </td>
                                        
                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" 
                                                       class="custom-control-input status-toggle" 
                                                       id="status-{{ $bankQuestion->id }}"
                                                       data-id="{{ $bankQuestion->id }}"
                                                       {{ $bankQuestion->is_active ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="status-{{ $bankQuestion->id }}"></label>
                                            </div>
                                        </td>
                                        
                                        <td>
                                            <small>{{ $bankQuestion->created_at->format('Y-m-d') }}</small>
                                            <br><small class="text-muted">{{ $bankQuestion->created_at->format('H:i') }}</small>
                                        </td>
                                        
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <!-- View Button -->
                                                <a href="{{ route('bank-questions.show', $bankQuestion) }}" 
                                                   class="btn btn-info btn-sm" title="{{ __('messages.view') }}">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                <!-- Download Button -->
                                                @if($bankQuestion->pdf && $bankQuestion->pdfExists())
                                                    <a href="{{ route('bank-questions.download-pdf', $bankQuestion) }}" 
                                                       class="btn btn-success btn-sm" title="{{ __('messages.download') }}">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                @else
                                                    <button class="btn btn-outline-secondary btn-sm" disabled title="{{ __('messages.file_not_available') }}">
                                                        <i class="fas fa-download"></i>
                                                    </button>
                                                @endif
                                                
                                                <!-- Edit Button -->
                                                <a href="{{ route('bank-questions.edit', $bankQuestion) }}" 
                                                   class="btn btn-warning btn-sm" title="{{ __('messages.edit') }}">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                <!-- Delete Button -->
                                                <button type="button" 
                                                        class="btn btn-danger btn-sm delete-btn" 
                                                        data-id="{{ $bankQuestion->id }}"
                                                        data-name="{{ $bankQuestion->display_name ?: __('messages.this_item') }}"
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
                                                <p>{{ __('messages.no_bank_questions_found') }}</p>
                                                <a href="{{ route('bank-questions.create') }}" class="btn btn-primary">
                                                    <i class="fas fa-plus"></i> {{ __('messages.add_first_bank_question') }}
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($bankQuestions->hasPages())
                        <div class="d-flex justify-content-center">
                            {{ $bankQuestions->links() }}
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
        var bankQuestionId = $(this).data('id');
        var isActive = $(this).is(':checked');
        var toggleElement = $(this);
        
        $.ajax({
            url: '{{ route('bank-questions.toggle-status', ':id') }}'.replace(':id', bankQuestionId),
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
        var bankQuestionId = $(this).data('id');
        var bankQuestionName = $(this).data('name');
        
        $('#delete-item-name').text(bankQuestionName);
        $('#delete-form').attr('action', '{{ route('bank-questions.destroy', ':id') }}'.replace(':id', bankQuestionId));
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