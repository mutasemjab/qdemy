@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>{{ __('messages.social_media') }}</h2>
                @can('socialMedia-add')
                    <a href="{{ route('social-media.create') }}" class="btn btn-primary">
                        {{ __('messages.new') }}
                    </a>
                @endcan
            </div>
        </div>
    </div>

 

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('messages.video') }}</th>
                            <th>{{ __('messages.created_at') }}</th>
                            <th>{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($socialMedia as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if($item->video)
                                        <video width="200" height="150" controls>
                                            <source src="{{ asset('assets/admin/uploads/' . $item->video) }}" type="video/mp4">
                                            {{ __('messages.browser_not_support_video') }}
                                        </video>
                                    @else
                                        <span class="text-muted">{{ __('messages.no_video') }}</span>
                                    @endif
                                </td>
                                <td>{{ $item->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    @can('socialMedia-edit')
                                        <a href="{{ route('social-media.edit', $item->id) }}" 
                                           class="btn btn-sm btn-warning">
                                            {{ __('messages.edit') }}
                                        </a>
                                    @endcan

                                    @can('socialMedia-delete')
                                        <form action="{{ route('social-media.destroy', $item->id) }}" 
                                              method="POST" 
                                              style="display: inline-block;"
                                              onsubmit="return confirm('{{ __('messages.confirm_delete') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                {{ __('messages.delete') }}
                                            </button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">{{ __('messages.no_data') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $socialMedia->links() }}
            </div>
        </div>
    </div>
</div>
@endsection