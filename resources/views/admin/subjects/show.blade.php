@extends('layouts.admin')

@section('title', __('messages.subject_details'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Main Details Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ __('messages.subject_details') }}: {{ $subject->name_ar }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('subjects.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
                        </a>
                        <a href="{{ route('subjects.edit', $subject->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> {{ __('messages.edit') }}
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Basic Information -->
                        <div class="col-md-6">
                            <h5 class="border-bottom pb-2 mb-3">{{ __('messages.basic_information') }}</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">{{ __('messages.name_ar') }}:</th>
                                    <td dir="rtl">{{ $subject->name_ar }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.name_en') }}:</th>
                                    <td>{{ $subject->name_en ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.description_ar') }}:</th>
                                    <td dir="rtl">{{ $subject->description_ar ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.description_en') }}:</th>
                                    <td>{{ $subject->description_en ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.icon') }}:</th>
                                    <td>
                                        @if($subject->icon)
                                            <i class="{{ $subject->icon }}"></i> {{ $subject->icon }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.color') }}:</th>
                                    <td>
                                        @if($subject->color)
                                            <span class="badge" style="background-color: {{ $subject->color }}; color: white;">
                                                {{ $subject->color }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.sort_order') }}:</th>
                                    <td>{{ $subject->sort_order }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.status') }}:</th>
                                    <td>
                                        @if($subject->is_active)
                                            <span class="badge badge-success">{{ __('messages.active') }}</span>
                                        @else
                                            <span class="badge badge-danger">{{ __('messages.inactive') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <!-- Category Relations -->
                        <div class="col-md-6">
                            <h5 class="border-bottom pb-2 mb-3">{{ __('messages.category_relations') }}</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">{{ __('messages.field_type') }}:</th>
                                    <td>
                                        @if($subject->fieldType)
                                            <span class="badge badge-dark">
                                                {{ app()->getLocale() == 'ar' ? $subject->fieldType->name_ar : ($subject->fieldType->name_en ?? $subject->fieldType->name_ar) }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.program') }}:</th>
                                    <td>
                                        @if($subject->program)
                                            <span class="badge badge-info">
                                                {{ app()->getLocale() == 'ar' ? $subject->program->name_ar : ($subject->program->name_en ?? $subject->program->name_ar) }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.grade') }}:</th>
                                    <td>
                                        @if($subject->grade)
                                            <span class="badge badge-secondary">
                                                {{ app()->getLocale() == 'ar' ? $subject->grade->name_ar : ($subject->grade->name_en ?? $subject->grade->name_ar) }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.semester') }}:</th>
                                    <td>
                                        @if($subject->semester)
                                            <span class="badge badge-warning">
                                                {{ app()->getLocale() == 'ar' ? $subject->semester->name_ar : ($subject->semester->name_en ?? $subject->semester->name_ar) }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            </table>

                            <!-- Timestamps -->
                            <h5 class="border-bottom pb-2 mb-3 mt-4">{{ __('messages.timestamps') }}</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">{{ __('messages.created_at') }}:</th>
                                    <td>{{ $subject->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.updated_at') }}:</th>
                                    <td>{{ $subject->updated_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Fields (for Tawjihi last year) -->
            @if($relatedFields->count() > 0)
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.related_fields') }}</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.field') }}</th>
                                    <th>{{ __('messages.is_optional') }}</th>
                                    <th>{{ __('messages.is_ministry') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($relatedFields as $field)
                                <tr>
                                    <td>
                                        {{ app()->getLocale() == 'ar' ? $field->name_ar : ($field->name_en ?? $field->name_ar) }}
                                    </td>
                                    <td>
                                        @if($field->pivot->is_optional)
                                            <span class="badge badge-warning">{{ __('messages.yes') }}</span>
                                        @else
                                            <span class="badge badge-success">{{ __('messages.no') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($field->pivot->is_ministry)
                                            <span class="badge badge-success">{{ __('messages.yes') }}</span>
                                        @else
                                            <span class="badge badge-danger">{{ __('messages.no') }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Related Courses -->
            @if($subject->courses->count() > 0)
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ __('messages.related_courses') }}
                        <span class="badge badge-primary">{{ $subject->courses->count() }}</span>
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('messages.course_name') }}</th>
                                    <th>{{ __('messages.teacher') }}</th>
                                    <th>{{ __('messages.price') }}</th>
                                    <th>{{ __('messages.students_count') }}</th>
                                    <th>{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subject->courses as $course)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        {{ app()->getLocale() == 'ar' ? $course->title_ar : ($course->title_en ?? $course->title_ar) }}
                                    </td>
                                    <td>{{ $course->teacher->name ?? '-' }}</td>
                                    <td>{{ $course->selling_price }} {{ __('messages.currency') }}</td>
                                    <td>
                                        <span class="badge badge-info">{{ $course->students_count }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('courses.show', $course->id) }}"
                                           class="btn btn-sm btn-info"
                                           title="{{ __('messages.view') }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @else
            <div class="card mt-3">
                <div class="card-body text-center">
                    <p class="mb-0">{{ __('messages.no_courses_for_this_subject') }}</p>
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="card mt-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('subjects.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.back_to_list') }}
                        </a>
                        <div>
                            <a href="{{ route('subjects.edit', $subject->id) }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> {{ __('messages.edit') }}
                            </a>
                            <form action="{{ route('subjects.destroy', $subject->id) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('{{ __('messages.confirm_delete') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash"></i> {{ __('messages.delete') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
