@extends('layouts.admin')

@section('title', __('messages.banned_words'))

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">{{ __('messages.banned_words') }}</h3>
        @can('bannedWord-add')
            <a href="{{ route('banned-words.create') }}" class="btn btn-sm btn-success">
                <i class="fa fa-plus"></i> {{ __('messages.Add_New') }}
            </a>
        @endcan
    </div>

    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover text-center">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>{{ __('messages.word') }}</th>
                    <th>{{ __('messages.language') }}</th>
                    <th>{{ __('messages.type') }}</th>
                    <th>{{ __('messages.severity') }}</th>
                    <th>{{ __('messages.Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($words as $word)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $word->word }}</td>
                        <td>
                            @if($word->language === 'ar') العربية
                            @elseif($word->language === 'en') English
                            @else كلاهما @endif
                        </td>
                        <td>
                            @switch($word->type)
                                @case('profanity') بذيئة @break
                                @case('political') سياسية @break
                                @case('spam') مزعجة @break
                                @default أخرى
                            @endswitch
                        </td>
                        <td>{{ $word->severity }}</td>
                        <td>
                            @can('bannedWord-delete')
                                <form action="{{ route('banned-words.destroy', $word->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه الكلمة؟')" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        <i class="fa fa-trash"></i> {{ __('messages.Delete') }}
                                    </button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-muted">{{ __('messages.No_Data_Found') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-3">
            {{ $words->links() }}
        </div>
    </div>
</div>
@endsection
