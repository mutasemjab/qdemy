@extends('layouts.admin')

@section('title', __('messages.subjects'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.subjects_list') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('subjects.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> {{ __('messages.add_subject') }}
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="50">#</th>
                                    <th>{{ __('messages.name') }}</th>
                                    <th>{{ __('messages.semester') }}</th>
                                    <th>{{ __('messages.field_type') }}</th>
                                    <th width="100">{{ __('messages.status') }}</th>
                                    <th width="80">{{ __('messages.sort') }}</th>
                                    <th width="150">{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subjects as $subject)
                                    <tr>
                                        <td>{{ $loop->iteration + (($subjects->currentPage() - 1) * $subjects->perPage()) }}</td>
                                        <td dir="rtl">{{ $subject->localized_name }} <br> {{ $subject->grade?->breadcrumb }}</td>
                                        <td>
                                            @if($subject->semester)
                                                <span class="badge badge-warning">
                                                    {{ app()->getLocale() == 'ar' ? $subject->semester->name_ar : ($subject->semester->name_en ?? $subject->semester->name_ar) }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($subject->fieldType)
                                                <span class="badge badge-dark">
                                                    {{ app()->getLocale() == 'ar' ? $subject->fieldType->name_ar : ($subject->fieldType->name_en ?? $subject->fieldType->name_ar) }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($subject->is_active)
                                                <span class="badge badge-success">{{ __('messages.active') }}</span>
                                            @else
                                                <span class="badge badge-danger">{{ __('messages.inactive') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $subject->sort_order }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('subjects.edit', $subject->id) }}"
                                                   class="btn btn-sm btn-primary"
                                                   title="{{ __('messages.edit') }}">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('subjects.destroy', $subject->id) }}"
                                                      method="POST"
                                                      class="d-inline"
                                                      onsubmit="return confirm('{{ __('messages.confirm_delete') }}');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-sm btn-danger"
                                                            title="{{ __('messages.delete') }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">
                                            {{ __('messages.no_subjects_found') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $subjects->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
