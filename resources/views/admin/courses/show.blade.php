
@extends('layouts.admin')

@section('title', __('messages.course_details'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.course_details') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('courses.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
                        </a>
                        @can('course-edit')
                            <a href="{{ route('courses.edit', $course) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> {{ __('messages.edit') }}
                            </a>
                        @endcan
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Course Image -->
                        <div class="col-md-4">
                            <div class="text-center mb-3">
                                <img src="{{ $course->photo_url }}" 
                                     alt="{{ $course->title }}" 
                                     class="img-fluid rounded"
                                     style="max-height: 300px; object-fit: cover;">
                            </div>
                        </div>

                        <!-- Course Information -->
                        <div class="col-md-8">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">{{ __('messages.title_en') }}:</th>
                                    <td>{{ $course->title_en }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.title_ar') }}:</th>
                                    <td dir="rtl">{{ $course->title_ar }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.description_en') }}:</th>
                                    <td>{{ $course->description_en }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.description_ar') }}:</th>
                                    <td dir="rtl">{{ $course->description_ar }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.selling_price') }}:</th>
                                    <td>
                                        <span class="badge bg-success fs-6">${{ number_format($course->selling_price, 2) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.teacher') }}:</th>
                                    <td>
                                        @if($course->teacher)
                                            <span class="badge bg-info">{{ $course->teacher->name }}</span>
                                        @else
                                            <span class="text-muted">{{ __('messages.no_teacher') }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.category') }}:</th>
                                    <td>
                                        @if($course->category)
                                            <span class="badge bg-primary">{{ $course->category->name }}</span>
                                        @else
                                            <span class="text-muted">{{ __('messages.no_category') }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.created_at') }}:</th>
                                    <td>{{ $course->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.updated_at') }}:</th>
                                    <td>{{ $course->updated_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Course Sections -->
                    @if($course->sections->count() > 0)
                        <div class="row mt-4">
                            <div class="col-12">
                                <h4>{{ __('messages.course_sections') }}</h4>
                                <div class="accordion" id="sectionsAccordion">
                                    @foreach($course->sections as $section)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading{{ $section->id }}">
                                                <button class="accordion-button collapsed" 
                                                        type="button" 
                                                        data-bs-toggle="collapse" 
                                                        data-bs-target="#collapse{{ $section->id }}" 
                                                        aria-expanded="false" 
                                                        aria-controls="collapse{{ $section->id }}">
                                                    <strong>{{ $section->title_en }}</strong>
                                                    @if($section->title_ar)
                                                        <span class="ms-2 text-muted">- {{ $section->title_ar }}</span>
                                                    @endif
                                                    <span class="badge bg-primary ms-auto me-2">
                                                        {{ $section->contents->count() }} {{ __('messages.contents') }}
                                                    </span>
                                                </button>
                                            </h2>
                                            <div id="collapse{{ $section->id }}" 
                                                 class="accordion-collapse collapse" 
                                                 aria-labelledby="heading{{ $section->id }}" 
                                                 data-bs-parent="#sectionsAccordion">
                                                <div class="accordion-body">
                                                    @if($section->contents->count() > 0)
                                                        <div class="table-responsive">
                                                            <table class="table table-sm">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>{{ __('messages.title') }}</th>
                                                                        <th>{{ __('messages.content_type') }}</th>
                                                                        <th>{{ __('messages.is_free') }}</th>
                                                                        <th>{{ __('messages.order') }}</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($section->contents->sortBy('order') as $content)
                                                                        <tr>
                                                                            <td>{{ $loop->iteration }}</td>
                                                                            <td>
                                                                                <strong>{{ $content->title_en }}</strong><br>
                                                                                <small class="text-muted">{{ $content->title_ar }}</small>
                                                                            </td>
                                                                            <td>
                                                                                <span class="badge bg-secondary">
                                                                                    {{ ucfirst($content->content_type) }}
                                                                                </span>
                                                                            </td>
                                                                            <td>
                                                                                @if($content->is_free == 1)
                                                                                    <span class="badge bg-success">{{ __('messages.free') }}</span>
                                                                                @else
                                                                                    <span class="badge bg-warning">{{ __('messages.paid') }}</span>
                                                                                @endif
                                                                            </td>
                                                                            <td>{{ $content->order }}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    @else
                                                        <p class="text-muted mb-0">{{ __('messages.no_contents_in_section') }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Course Contents (without sections) -->
                    @php
                        $directContents = $course->contents()->whereNull('section_id')->orderBy('order')->get();
                    @endphp
                    @if($directContents->count() > 0)
                        <div class="row mt-4">
                            <div class="col-12">
                                <h4>{{ __('messages.direct_contents') }}</h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{ __('messages.title') }}</th>
                                                <th>{{ __('messages.content_type') }}</th>
                                                <th>{{ __('messages.is_free') }}</th>
                                                <th>{{ __('messages.order') }}</th>
                                                <th>{{ __('messages.details') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($directContents as $content)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        <strong>{{ $content->title_en }}</strong><br>
                                                        <small class="text-muted">{{ $content->title_ar }}</small>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-secondary">
                                                            {{ ucfirst($content->content_type) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if($content->is_free == 1)
                                                            <span class="badge bg-success">{{ __('messages.free') }}</span>
                                                        @else
                                                            <span class="badge bg-warning">{{ __('messages.paid') }}</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $content->order }}</td>
                                                    <td>
                                                        @if($content->content_type === 'video')
                                                            <small>
                                                                {{ __('messages.type') }}: {{ ucfirst($content->video_type ?? 'N/A') }}<br>
                                                                {{ __('messages.duration') }}: {{ $content->video_duration ? gmdate('H:i:s', $content->video_duration) : 'N/A' }}
                                                            </small>
                                                        @elseif($content->content_type === 'pdf')
                                                            <small>
                                                                {{ __('messages.pdf_type') }}: {{ ucfirst($content->pdf_type ?? 'N/A') }}
                                                            </small>
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

                    @if($course->sections->count() == 0 && $directContents->count() == 0)
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="alert alert-info text-center">
                                    <i class="fas fa-info-circle fa-2x mb-2"></i>
                                    <h5>{{ __('messages.no_content_yet') }}</h5>
                                    <p class="mb-0">{{ __('messages.add_sections_and_contents') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection