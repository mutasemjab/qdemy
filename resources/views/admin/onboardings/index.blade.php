@extends('layouts.admin')

@section('title', __('messages.onboardings'))

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>{{ __('messages.onboardings') }}</h1>
        <a href="{{ route('onboardings.create') }}" class="btn btn-primary">
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
                            <th>{{ __('messages.photo') }}</th>
                            <th>{{ __('messages.title') }}</th>
                            <th>{{ __('messages.description') }}</th>
                            <th>{{ __('messages.created_at') }}</th>
                            <th>{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($onboardings as $onboarding)
                            <tr>
                                <td>{{ $loop->iteration + ($onboardings->currentPage() - 1) * $onboardings->perPage() }}</td>
                                <td>
                                    @if($onboarding->photo)
                                        <img src="{{ $onboarding->photo_url }}" alt="{{ $onboarding->title }}" 
                                             class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <span class="text-muted">{{ __('messages.no_image') }}</span>
                                    @endif
                                </td>
                                <td>{{ Str::limit($onboarding->title, 30) }}</td>
                                <td>{{ Str::limit($onboarding->description, 50) }}</td>
                                <td>{{ $onboarding->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('onboardings.show', $onboarding) }}" 
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> {{ __('messages.view') }}
                                        </a>
                                        <a href="{{ route('onboardings.edit', $onboarding) }}" 
                                           class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> {{ __('messages.edit') }}
                                        </a>
                                        <form action="{{ route('onboardings.destroy', $onboarding) }}" 
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
                                        <a href="{{ route('onboardings.create') }}" class="btn btn-primary">
                                            {{ __('messages.add_first_item') }}
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($onboardings->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $onboardings->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection