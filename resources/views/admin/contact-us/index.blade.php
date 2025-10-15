@extends('layouts.admin')

@section('title', __('messages.contactUs'))

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>{{ __('messages.contactUs') }}</h1>
       
    </div>

   

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('messages.name') }}</th>
                            <th>{{ __('messages.phone') }}</th>
                            <th>{{ __('messages.message') }}</th>
                            <th>{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($contactUs as $contact)
                            <tr>
                                <td>{{ $loop->iteration + ($contactUs->currentPage() - 1) * $contactUs->perPage() }}</td>
                               
                                <td>{{ Str::limit($contact->name, 30) }}</td>
                                <td>{{ Str::limit($contact->phone, 30) }}</td>
                                <td>{{ Str::limit($contact->message, 30) }}</td>
                                <td>{{ $contact->created_at->format('Y-m-d H:i') }}</td>
                               
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <p>{{ __('messages.no_data_found') }}</p>
                                       
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($contactUs->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $contactUs->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection