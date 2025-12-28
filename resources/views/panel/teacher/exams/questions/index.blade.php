@extends('layouts.app')

@section('title', __('panel.questions'))

@section('content')
<section class="ud-wrap">
    <aside class="ud-menu">
        <div class="ud-user">
            <img data-src="{{ auth()->user()->photo ? asset('assets/admin/uploads/' . auth()->user()->photo) : asset('assets_front/images/avatar-big.png') }}" alt="">
            <div>
                <h3>{{ auth()->user()->name }}</h3>
                <span>{{ auth()->user()->email }}</span>
            </div>
        </div>
        <a href="{{ route('teacher.exams.show', $exam) }}" class="ud-item">
            <i class="fa-solid fa-arrow-left"></i>
            <span>{{ __('panel.back_to_exam') }}</span>
        </a>
    </aside>

    <div class="ud-content">
        <div class="ud-panel show">
            <div class="ud-title">{{ __('panel.questions_management') }}</div>
            <p class="ud-subtitle">{{ $exam->title_en ?? $exam->title_ar }}</p>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fa-solid fa-question"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $totalQuestions ?? 0 }}</div>
                        <div class="stat-label">{{ __('panel.total_questions') }}</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background:#dcfce7">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $multipleChoiceCount ?? 0 }}</div>
                        <div class="stat-label">{{ __('panel.multiple_choice') }}</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background:#dbeafe">
                        <i class="fa-solid fa-toggle-on"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $trueFalseCount ?? 0 }}</div>
                        <div class="stat-label">{{ __('panel.true_false') }}</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background:#fef3c7">
                        <i class="fa-solid fa-pen"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $essayCount ?? 0 }}</div>
                        <div class="stat-label">{{ __('panel.essay') }}</div>
                    </div>
                </div>
            </div>

            <!-- Action Button -->
            <div class="action-bar">
                <a href="{{ route('teacher.exams.exam_questions.create', $exam) }}" class="btn btn-primary">
                    <i class="fa-solid fa-plus"></i>{{ __('panel.create_question') }}
                </a>
            </div>

            <!-- Filters -->
            <div class="filter-section">
                <form method="GET" action="{{ route('teacher.exams.exam_questions.index', $exam) }}" id="filterForm">
                    <div class="filter-row">
                        <div class="filter-group">
                            <label>{{ __('panel.type') }}</label>
                            <select name="type" class="form-select">
                                <option value="">{{ __('panel.all_types') }}</option>
                                <option value="multiple_choice" {{ request('type') === 'multiple_choice' ? 'selected' : '' }}>
                                    {{ __('panel.multiple_choice') }}
                                </option>
                                <option value="true_false" {{ request('type') === 'true_false' ? 'selected' : '' }}>
                                    {{ __('panel.true_false') }}
                                </option>
                                <option value="essay" {{ request('type') === 'essay' ? 'selected' : '' }}>
                                    {{ __('panel.essay') }}
                                </option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label>{{ __('panel.search') }}</label>
                            <input type="text" name="search" class="form-control"
                                   placeholder="{{ __('panel.search_questions_placeholder') }}"
                                   value="{{ request('search') }}">
                        </div>

                        <div class="filter-group">
                            <a href="{{ route('teacher.exams.exam_questions.index',$exam) }}" class="btn btn-outline-secondary w-100">
                                <i class="fa-solid fa-times"></i>{{ __('panel.clear') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Questions Grid -->
            @if(isset($questions) && $questions->count() > 0)
                <div class="questions-grid">
                    @foreach($questions as $question)
                        <div class="question-card">
                            <div class="card-header">
                                <div class="badges">
                                    <span class="badge {{ $question->type === 'multiple_choice' ? 'badge-success' :
                                               ($question->type === 'true_false' ? 'badge-info' : 'badge-warning') }}">
                                        {{ __('panel.' . $question->type) }}
                                    </span>
                                </div>
                                <div class="card-actions">
                                    <a href="{{ route('teacher.exams.exam_questions.show', [$exam, $question]) }}" class="btn-action" title="{{ __('panel.view') }}">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ route('teacher.exams.exam_questions.edit', [$exam, $question]) }}" class="btn-action" title="{{ __('panel.edit') }}">
                                        <i class="fa-solid fa-edit"></i>
                                    </a>
                                    <form action="{{ route('teacher.exams.exam_questions.destroy', [$exam, $question]) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('panel.delete_question_warning') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-danger" title="{{ __('panel.delete') }}">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="card-body">
                                <h5 class="question-title">
                                    {{ app()->getLocale() === 'ar' ? $question->title_ar : $question->title_en }}
                                </h5>

                                <p class="question-preview">
                                    {{ Str::limit(strip_tags(app()->getLocale() === 'ar' ? $question->question_ar : $question->question_en), 100) }}
                                </p>

                                <div class="question-meta">
                                    <div class="meta-item">
                                        <i class="fa-solid fa-star"></i>
                                        <span>{{ $question->grade }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fa-solid fa-clock"></i>
                                        <span>{{ $question->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="pagination-wrapper">
                    {{ $questions->appends(request()->query())->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="empty-state">
                    <i class="fa-solid fa-question"></i>
                    <h3>{{ __('panel.no_questions_found') }}</h3>
                    <p>{{ __('panel.no_questions_desc') }}</p>
                    <a href="{{ route('teacher.exams.exam_questions.create',$exam) }}" class="btn btn-primary">
                        <i class="fa-solid fa-plus"></i>{{ __('panel.create_first_question') }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</section>

@endsection

@section('styles')
<style>
.ud-wrap{display:grid;grid-template-columns:320px 1fr;gap:24px;padding:16px 0}
.ud-menu{margin:10px;background:#fff;border:1px solid #eef0f3;border-radius:14px;padding:16px;position:sticky;top:88px;height:max-content}
.ud-user{display:flex;align-items:center;gap:12px;margin-bottom:12px}
.ud-user img{width:56px;height:56px;border-radius:50%;object-fit:cover;border:2px solid #f1f5f9}
.ud-user h3{font-size:16px;margin:0 0 2px 0}
.ud-user span{font-size:12px;color:#6b7280}
.ud-item{display:flex;align-items:center;gap:10px;padding:12px 14px;border:1px solid #e5e7eb;border-radius:10px;text-decoration:none;color:#0f172a;transition:all .18s}
.ud-item:hover{border-color:#0055D2;box-shadow:0 6px 18px rgba(0,85,210,.12);transform:translateY(-2px)}
.ud-content{min-width:0}
.ud-panel{background:#fff;border:1px solid #eef0f3;border-radius:14px;padding:18px}
.ud-title{font-size:20px;font-weight:900;margin-bottom:8px;color:#0f172a}
.ud-subtitle{font-size:13px;color:#6b7280;margin:0 0 20px 0}

.stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:24px}
.stat-card{display:flex;align-items:center;gap:12px;padding:12px;background:#f9fafb;border-radius:10px;border:1px solid #e5e7eb}
.stat-icon{width:40px;height:40px;border-radius:8px;background:#dbeafe;display:flex;align-items:center;justify-content:center;color:#0369a1;font-size:18px}
.stat-content{flex:1}
.stat-number{font-size:18px;font-weight:800;color:#0f172a}
.stat-label{font-size:12px;color:#6b7280}

.action-bar{display:flex;gap:12px;margin-bottom:24px}
.btn{display:inline-flex;align-items:center;gap:8px;border-radius:10px;padding:10px 16px;font-weight:700;font-size:14px;text-decoration:none;cursor:pointer;transition:all .18s;border:none}
.btn-primary{background:#0055D2;color:#fff}
.btn-primary:hover{background:#0047b3;transform:translateY(-1px);box-shadow:0 6px 18px rgba(0,85,210,.18)}
.btn-outline-secondary{border:1px solid #d1d5db;color:#374151;background:#fff}
.btn-outline-secondary:hover{background:#f9fafb}

.filter-section{background:#f9fafb;border:1px solid #e5e7eb;border-radius:10px;padding:16px;margin-bottom:24px}
.filter-row{display:grid;grid-template-columns:200px 1fr 120px;gap:12px}
.filter-group{display:flex;flex-direction:column;gap:6px}
.filter-group label{font-weight:700;font-size:13px;color:#374151}
.form-select,.form-control{border:1px solid #d1d5db;border-radius:8px;padding:10px 12px;font-size:13px;font-family:inherit}
.form-select:focus,.form-control:focus{outline:none;border-color:#0055D2;box-shadow:0 0 0 3px rgba(0,85,210,.12)}

.questions-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:16px;margin-bottom:24px}
.question-card{background:#fff;border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;transition:all .18s}
.question-card:hover{box-shadow:0 6px 18px rgba(0,85,210,.12);transform:translateY(-2px)}

.card-header{display:flex;justify-content:space-between;align-items:flex-start;padding:12px;border-bottom:1px solid #e5e7eb;background:#f9fafb}
.badges{display:flex;gap:6px;flex-wrap:wrap}
.badge{font-size:11px;font-weight:700;padding:4px 10px;border-radius:6px;background:#dbeafe;color:#0369a1}
.badge-success{background:#dcfce7;color:#15803d}
.badge-info{background:#cffafe;color:#0891b2}
.badge-warning{background:#fef3c7;color:#b45309}

.card-actions{display:flex;gap:6px;opacity:0;transition:opacity .2s ease}
.question-card:hover .card-actions{opacity:1}
.btn-action{cursor:pointer;text-decoration:none;width:36px;height:36px;border-radius:50%;border:1px solid #e5e7eb;background:#fff;display:flex;align-items:center;justify-content:center;color:#374151;transition:all .2s;font-size:14px}
.btn-action:hover{border-color:#0055D2;color:#0055D2;box-shadow:0 6px 16px rgba(0,0,0,.06);transform:translateY(-1px)}
.btn-action.btn-danger:hover{border-color:#dc2626;color:#dc2626}

.card-body{padding:12px}
.question-title{font-size:15px;font-weight:700;color:#0f172a;margin:0 0 8px 0}
.question-preview{font-size:13px;color:#6b7280;margin:0 0 12px 0}
.question-meta{display:flex;gap:16px;font-size:12px;color:#6b7280}
.meta-item{display:flex;align-items:center;gap:4px}

.empty-state{text-align:center;padding:48px 20px;background:#f9fafb;border-radius:12px;border:1px dashed #d1d5db}
.empty-state i{font-size:48px;color:#d1d5db;margin-bottom:16px}
.empty-state h3{color:#374151;margin:0 0 8px 0}
.empty-state p{color:#9ca3af;margin:0 0 20px 0}

.pagination-wrapper{display:flex;justify-content:center;margin-top:24px}

@media (max-width:992px){
  .ud-wrap{grid-template-columns:1fr}
  .ud-menu{position:static}
  .stats-grid{grid-template-columns:repeat(2,1fr)}
  .questions-grid{grid-template-columns:1fr}
  .filter-row{grid-template-columns:1fr}
}

@media (max-width:768px){
  .stats-grid{grid-template-columns:1fr}
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
  const filterForm = document.getElementById('filterForm');
  const typeSelect = filterForm.querySelector('select[name="type"]');
  const searchInput = filterForm.querySelector('input[name="search"]');

  // Auto-submit filter form on select change
  if(typeSelect){
    typeSelect.addEventListener('change', function(){
      filterForm.submit();
    });
  }

  // Search with delay
  let searchTimeout;
  if(searchInput){
    searchInput.addEventListener('input', function(){
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(function(){
        filterForm.submit();
      }, 500);
    });
  }
});
</script>
@endsection
