@extends('layouts.admin')

@section('title', __('messages.special_qdemies'))

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>{{ __('messages.special_qdemies') }}</h1>
        <a href="{{ route('special-qdemies.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> {{ __('messages.add_new') }}
        </a>
    </div>


    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('messages.title') }}</th>
                            <th>{{ __('messages.title_en') }}</th>
                            <th>{{ __('messages.title_ar') }}</th>
                            <th>{{ __('messages.created_at') }}</th>
                            <th>{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($specialQdemies as $specialQdemy)
                            <tr>
                                <td>{{ $loop->iteration + ($specialQdemies->currentPage() - 1) * $specialQdemies->perPage() }}</td>
                                <td>{{ Str::limit($specialQdemy->title, 30) }}</td>
                                <td>{{ Str::limit($specialQdemy->title_en, 30) }}</td>
                                <td dir="rtl">{{ Str::limit($specialQdemy->title_ar, 30) }}</td>
                                <td>{{ $specialQdemy->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('special-qdemies.show', $specialQdemy) }}" 
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> {{ __('messages.view') }}
                                        </a>
                                        <a href="{{ route('special-qdemies.edit', $specialQdemy) }}" 
                                           class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> {{ __('messages.edit') }}
                                        </a>
                                        <form action="{{ route('special-qdemies.destroy', $specialQdemy) }}" 
                                              method="POST" class="d-inline" 
                                              onsubmit="return confirm('{{ __('messages.confirm_delete') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i> {{ __('messages.delete') }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <p>{{ __('messages.no_data_found') }}</p>
                                        <a href="{{ route('special-qdemies.create') }}" class="btn btn-primary">
                                            {{ __('messages.add_first_item') }}
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($specialQdemies->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $specialQdemies->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection