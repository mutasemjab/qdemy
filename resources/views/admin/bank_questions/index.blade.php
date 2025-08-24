@extends('layouts.admin')

@section('title', __('messages.bank_questions'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.bank_questions') }}</h3>
                    @can('bank-question-add')
                    <a href="{{ route('bank-questions.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> {{ __('messages.add_new_bank_question') }}
                    </a>
                    @endcan
                </div>

                <div class="card-body">
                    

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('messages.category') }}</th>
                                    <th>{{ __('messages.pdf_file') }}</th>
                                    <th>{{ __('messages.file_size') }}</th>
                                    <th>{{ __('messages.created_at') }}</th>
                                    <th width="200">{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bankQuestions as $bankQuestion)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($bankQuestion->category && $bankQuestion->category->icon)
                                                <i class="{{ $bankQuestion->category->icon }} mr-2" 
                                                   style="color: {{ $bankQuestion->category->color ?? '#007bff' }}"></i>
                                            @endif
                                            <div>
                                                <strong>{{ $bankQuestion->category_breadcrumb }}</strong>
                                                @if($bankQuestion->category && $bankQuestion->category->parent)
                                                    <br><small class="text-muted">
                                                        {{ __('messages.parent') }}: {{ $bankQuestion->category->parent->localized_name }}
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($bankQuestion->pdf)
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-file-pdf text-danger mr-2"></i>
                                                <div>
                                                    <a target='_blank' href="{{ $bankQuestion->pdf_path }}">
                                                        <span class="text-truncate" style="max-width: 150px; display: inline-block;">
                                                            {{ $bankQuestion->pdf_path }}
                                                        </span>
                                                    </a>
                                                    <!-- @if($bankQuestion->pdfExist)
                                                        <span class="badge badge-success">{{ __('messages.available') }}</span>
                                                    @else
                                                        <span class="badge badge-danger">{{ __('messages.missing') }}</span>
                                                    @endif -->
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">{{ __('messages.no_file') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $bankQuestion->pdf_size ?? '--' }}
                                    </td>
                                    <td>{{ $bankQuestion->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            @can('bank-question-table')
                                            <a href="{{ route('bank-questions.show', $bankQuestion) }}" 
                                               class="btn btn-info btn-sm" title="{{ __('messages.view') }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @endcan

                                            @if($bankQuestion->pdfExists())
                                            <a target='_blank' href="{{ $bankQuestion->pdf_path }}"
                                                class="btn btn-success btn-sm" title="{{ __('messages.download') }}">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            @endif
                                            
                                            @can('bank-question-edit')
                                            <a href="{{ route('bank-questions.edit', $bankQuestion) }}" 
                                               class="btn btn-warning btn-sm" title="{{ __('messages.edit') }}">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @endcan
                                            
                                            @can('bank-question-delete')
                                            <form action="{{ route('bank-questions.destroy', $bankQuestion) }}" method="POST" 
                                                  class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" 
                                                        title="{{ __('messages.delete') }}"
                                                        onclick="return confirm('{{ __('messages.confirm_delete') }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">
                                        <div class="py-4">
                                            <i class="fas fa-file-pdf fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">{{ __('messages.no_bank_questions_found') }}</p>
                                            @can('bank-question-add')
                                            <a href="{{ route('bank-questions.create') }}" class="btn btn-primary">
                                                {{ __('messages.create_first_bank_question') }}
                                            </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $bankQuestions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
