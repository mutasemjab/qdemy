{{-- resources/views/lessons/index.blade.php --}}
@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>{{ __('messages.lessons_management') }}</h4>
                        <div>

                            <a href="{{ route('lessons.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> {{ __('messages.add_new_lesson') }}
                            </a>

                        </div>
                    </div>

                    <div class="card-body">


                        <!-- Filters -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <select class="form-control" id="categoryFilter" onchange="filterLessons()">
                                    <option value="">{{ __('messages.all_categories') }}</option>
                                    @php
                                        $categories = \App\Models\CategoryLesson::all();
                                    @endphp
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="searchFilter"
                                    placeholder="{{ __('messages.search_lessons') }}" onkeyup="filterLessons()">
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-outline-secondary w-100" onclick="clearFilters()">
                                    {{ __('messages.clear_filters') }}
                                </button>
                            </div>
                        </div>

                        {{-- resources/views/lessons/partials/lessons-table.blade.php --}}
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>

                                        <th width="5%">{{ __('messages.id') }}</th>
                                        <th width="15%">{{ __('messages.thumbnail') }}</th>
                                        <th width="30%">{{ __('messages.lesson_name') }}</th>
                                        <th width="20%">{{ __('messages.category') }}</th>
                                        <th width="15%">{{ __('messages.created_at') }}</th>
                                        <th width="10%">{{ __('messages.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($lessons as $lesson)
                                        <tr>

                                            <td>{{ $lesson->id }}</td>
                                            <td>
                                                @if ($lesson->getYoutubeThumbnailAttribute())
                                                    <img src="{{ $lesson->getYoutubeThumbnailAttribute() }}"
                                                        alt="Video Thumbnail" class="img-thumbnail"
                                                        style="width: 80px; height: 60px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center"
                                                        style="width: 80px; height: 60px;">
                                                        <i class="fas fa-video text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $lesson->name }}</strong>
                                                    <br>
                                                    <small class="text-muted">
                                                        <i class="fab fa-youtube text-danger"></i>
                                                        {{ __('messages.video_id') }}:
                                                        {{ $lesson->getYoutubeIdAttribute() ?? __('messages.invalid_url') }}
                                                    </small>
                                                </div>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-info">{{ $lesson->category->name ?? __('messages.no_category') }}</span>
                                            </td>
                                            <td>
                                                <div>
                                                    {{ $lesson->created_at->format('Y-m-d') }}
                                                    <br>
                                                    <small
                                                        class="text-muted">{{ $lesson->created_at->format('H:i') }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                   
                                                    <a href="{{ route('lessons.edit', $lesson) }}" class="btn btn-warning"
                                                        title="{{ __('messages.edit') }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('lessons.destroy', $lesson) }}" method="POST"
                                                        style="display: inline;">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-danger"
                                                            title="{{ __('messages.delete') }}"
                                                            onclick="return confirm('{{ __('messages.confirm_delete') }}')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <i class="fas fa-video text-muted"></i>
                                                <p class="mt-2">{{ __('messages.no_lessons_found') }}</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($lessons->hasPages())
                            <div class="d-flex justify-content-center mt-3">
                                {{ $lessons->links() }}
                            </div>
                        @endif


                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function filterLessons() {
            const categoryId = document.getElementById('categoryFilter').value;
            const search = document.getElementById('searchFilter').value;

            fetch(`{{ route('lessons.search') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        category_id: categoryId,
                        search: search
                    })
                })
                .then(response => response.text())
                .then(html => {
                    document.getElementById('lessonsContainer').innerHTML = html;
                    toggleBulkDelete();
                })
                .catch(error => console.error('Error:', error));
        }

        function clearFilters() {
            document.getElementById('categoryFilter').value = '';
            document.getElementById('searchFilter').value = '';
            filterLessons();
        }


        // Add event listeners to checkboxes
        document.addEventListener('DOMContentLoaded', function() {
            toggleBulkDelete();
        });
    </script>
@endsection
