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

                <!-- Filters Section -->
                <div class="card-body pb-0">
                    <form method="GET" action="{{ route('subjects.index') }}" class="mb-3">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="text"
                                           name="search"
                                           class="form-control"
                                           placeholder="{{ __('messages.search_by_name') }}"
                                           value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select name="program_id" id="filter_program_id" class="form-control">
                                        <option value="">{{ __('messages.all_programs') }}</option>
                                        @foreach($programs as $program)
                                            <option value="{{ $program->id }}"
                                                    data-ctg-key="{{ $program->ctg_key }}"
                                                    {{ request('program_id') == $program->id ? 'selected' : '' }}>
                                                {{ app()->getLocale() == 'ar' ? $program->name_ar : ($program->name_en ?? $program->name_ar) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2" id="gradeFilterSection" style="{{ count($grades) > 0 ? '' : 'display: none;' }}">
                                <div class="form-group">
                                    <select name="grade_id" id="filter_grade_id" class="form-control">
                                        <option value="">{{ __('messages.all_grades') }}</option>
                                        @foreach($grades as $grade)
                                            <option value="{{ $grade->id }}"
                                                    {{ request('grade_id') == $grade->id ? 'selected' : '' }}>
                                                {{ app()->getLocale() == 'ar' ? $grade->name_ar : ($grade->name_en ?? $grade->name_ar) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select name="status" class="form-control">
                                        <option value="">{{ __('messages.all_status') }}</option>
                                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>{{ __('messages.active') }}</option>
                                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>{{ __('messages.inactive') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-search"></i> {{ __('messages.search') }}
                                </button>
                            </div>
                        </div>
                    </form>
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
                                    <th width="100">{{ __('messages.courses_count') }}</th>
                                    <th width="100">{{ __('messages.status') }}</th>
                                    <th width="100">{{ __('messages.order') }}</th>
                                    <th width="200">{{ __('messages.actions') }}</th>
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
                                                    {{$subject->semester->localized_name}}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-info">{{ $subject->courses->count() }}</span>
                                        </td>
                                        <td>
                                            <form action="{{ route('subjects.toggleStatus', $subject->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm {{ $subject->is_active ? 'btn-success' : 'btn-danger' }}">
                                                    @if($subject->is_active)
                                                        <i class="fas fa-check"></i> {{ __('messages.active') }}
                                                    @else
                                                        <i class="fas fa-times"></i> {{ __('messages.inactive') }}
                                                    @endif
                                                </button>
                                            </form>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <form action="{{ route('subjects.moveUp', $subject->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-secondary" title="{{ __('messages.move_up') }}">
                                                        <i class="fas fa-arrow-up"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('subjects.moveDown', $subject->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-secondary" title="{{ __('messages.move_down') }}">
                                                        <i class="fas fa-arrow-down"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('subjects.edit', $subject->id) }}"
                                                   class="btn btn-sm btn-primary"
                                                   title="{{ __('messages.edit') }}">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('subjects.show', $subject->id) }}"
                                                class="btn btn-sm btn-info"
                                                title="{{ __('messages.view') }}">
                                                    <i class="fas fa-eye"></i>
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
                                        <td colspan="8" class="text-center">
                                            {{ __('messages.no_subjects_found') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $subjects->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const programSelect = document.getElementById('filter_program_id');
    const gradeSection = document.getElementById('gradeFilterSection');
    const gradeSelect = document.getElementById('filter_grade_id');

    programSelect.addEventListener('change', async function() {
        const programId = this.value;
        const selectedOption = this.selectedOptions[0];

        if (!programId) {
            gradeSection.style.display = 'none';
            gradeSelect.innerHTML = '<option value="">{{ __("messages.all_grades") }}</option>';
            return;
        }

        const ctgKey = selectedOption.dataset.ctgKey;

        if (['tawjihi-and-secondary-program', 'elementary-grades-program'].includes(ctgKey)) {
            // Load grades via AJAX
            try {
                const formData = new FormData();
                formData.append('program_id', programId);
                formData.append('_token', '{{ csrf_token() }}');

                const response = await fetch('{{ route("admin.subjects.getGrades") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const grades = await response.json();

                if (!grades.no_grades && grades.length > 0) {
                    gradeSection.style.display = 'block';
                    gradeSelect.innerHTML = '<option value="">{{ __("messages.all_grades") }}</option>';

                    grades.forEach(grade => {
                        const option = document.createElement('option');
                        option.value = grade.id;
                        option.textContent = '{{ app()->getLocale() }}' === 'ar' ?
                            grade.name_ar : (grade.name_en || grade.name_ar);
                        gradeSelect.appendChild(option);
                    });
                } else {
                    gradeSection.style.display = 'none';
                }
            } catch (error) {
                console.error('Error loading grades:', error);
                gradeSection.style.display = 'none';
            }
        } else {
            gradeSection.style.display = 'none';
        }
    });
});
</script>
@endpush
