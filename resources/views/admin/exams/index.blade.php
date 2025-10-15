{{-- resources/views/exams/index.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>{{ __('messages.exams') }}</h4>
                    <a href="{{ route('exams.create') }}" class="btn btn-primary">
                        {{ __('messages.create_new_exam') }}
                    </a>
                </div>

                <div class="card-body">
                   

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.id') }}</th>
                                    <th>{{ __('messages.exam_name') }}</th>
                                    <th>{{ __('messages.category') }}</th>
                                    <th>{{ __('messages.duration') }}</th>
                                    <th>{{ __('messages.total_grade') }}</th>
                                    <th>{{ __('messages.questions_count') }}</th>
                                    <th>{{ __('messages.status') }}</th>
                                    <th>{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($exams as $exam)
                                    <tr>
                                        <td>{{ $exam->id }}</td>
                                        <td>{{ $exam->name }}</td>
                                        <td>{{ $exam->category->name ?? __('messages.no_category') }}</td>
                                        <td>{{ $exam->duration_minutes }} {{ __('messages.minutes') }}</td>
                                        <td>{{ $exam->total_grade }}</td>
                                        <td>{{ $exam->questions->count() }}</td>
                                        <td>
                                            @if($exam->isAvailable())
                                                <span class="badge bg-success">{{ __('messages.active') }}</span>
                                            @elseif($exam->start_time > now())
                                                <span class="badge bg-warning">{{ __('messages.upcoming') }}</span>
                                            @elseif($exam->end_time < now())
                                                <span class="badge bg-secondary">{{ __('messages.expired') }}</span>
                                            @else
                                                <span class="badge bg-danger">{{ __('messages.inactive') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('exams.show', $exam) }}" class="btn btn-sm btn-info">
                                                    {{ __('messages.view') }}
                                                </a>
                                                <a href="{{ route('questions.index', $exam) }}" class="btn btn-sm btn-primary">
                                                    {{ __('messages.questions') }}
                                                </a>
                                                <a href="{{ route('exams.edit', $exam) }}" class="btn btn-sm btn-warning">
                                                    {{ __('messages.edit') }}
                                                </a>
                                                <a href="{{ route('exams.attempts', $exam) }}" class="btn btn-sm btn-secondary">
                                                    {{ __('messages.attempts') }}
                                                </a>
                                                <form action="{{ route('exams.destroy', $exam) }}" method="POST" style="display: inline;">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" 
                                                            onclick="return confirm('{{ __('messages.confirm_delete') }}')">
                                                        {{ __('messages.delete') }}
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">{{ __('messages.no_exams_found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $exams->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

