@extends('layouts.app')

@section('title', __('panel.create_question'))

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
        <a href="{{ route('teacher.exams.exam_questions.index', $exam) }}" class="ud-item">
            <i class="fa-solid fa-arrow-left"></i>
            <span>{{ __('panel.back_to_questions') }}</span>
        </a>
    </aside>

    <div class="ud-content">
        <div class="ud-panel show">
            <div class="ud-title">{{ __('panel.create_question') }}</div>
            <p class="ud-subtitle">{{ app()->getLocale() === 'ar' ? $exam->title_ar : $exam->title_en }}</p>

            <form action="{{ route('teacher.exams.exam_questions.store', $exam) }}" method="POST" id="questionForm" enctype="multipart/form-data">
                @csrf

                <!-- Basic Information -->
                <div class="form-section">
                    <div class="section-title">{{ __('panel.basic_information') }}</div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="title_en">{{ __('panel.question_title_en') }} *</label>
                            <input type="text" id="title_en" name="title_en" value="{{ old('title_en') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="title_ar">{{ __('panel.question_title_ar') }} *</label>
                            <input type="text" id="title_ar" name="title_ar" value="{{ old('title_ar') }}" dir="rtl" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="question_en">{{ __('panel.question_text_en') }} *</label>
                            <textarea id="question_en" name="question_en" rows="4" required>{{ old('question_en') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="question_ar">{{ __('panel.question_text_ar') }} *</label>
                            <textarea id="question_ar" name="question_ar" rows="4" dir="rtl" required>{{ old('question_ar') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Question Settings -->
                <div class="form-section">
                    <div class="section-title">{{ __('panel.question_settings') }}</div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="type">{{ __('panel.question_type') }} *</label>
                            <select id="type" name="type" required onchange="toggleQuestionType()">
                                <option value="">{{ __('panel.select_question_type') }}</option>
                                <option value="multiple_choice" {{ old('type') === 'multiple_choice' ? 'selected' : '' }}>
                                    {{ __('panel.multiple_choice') }}
                                </option>
                                <option value="true_false" {{ old('type') === 'true_false' ? 'selected' : '' }}>
                                    {{ __('panel.true_false') }}
                                </option>
                                <option value="essay" {{ old('type') === 'essay' ? 'selected' : '' }}>
                                    {{ __('panel.essay') }}
                                </option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="grade">{{ __('panel.grade') }} *</label>
                            <input type="number" id="grade" name="grade" value="{{ old('grade', 1) }}" step="1" min="1" required>
                        </div>

                        <div class="form-group">
                            <label for="photo">{{ __('panel.photo') }}</label>
                            <input type="file" id="photo" name="photo" accept="image/*">
                            <small class="form-text">JPG, PNG, GIF</small>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="explanation_en">{{ __('panel.explanation_en') }}</label>
                            <textarea id="explanation_en" name="explanation_en" rows="3">{{ old('explanation_en') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="explanation_ar">{{ __('panel.explanation_ar') }}</label>
                            <textarea id="explanation_ar" name="explanation_ar" rows="3" dir="rtl">{{ old('explanation_ar') }}</textarea>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="correct_feedback_en">{{ __('panel.correct_feedback_en') }}</label>
                            <textarea id="correct_feedback_en" name="correct_feedback_en" rows="3" placeholder="{{ __('panel.optional_correct_feedback_en') }}">{{ old('correct_feedback_en') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="correct_feedback_ar">{{ __('panel.correct_feedback_ar') }}</label>
                            <textarea id="correct_feedback_ar" name="correct_feedback_ar" rows="3" dir="rtl" placeholder="{{ __('panel.optional_correct_feedback_ar') }}">{{ old('correct_feedback_ar') }}</textarea>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="incorrect_feedback_en">{{ __('panel.incorrect_feedback_en') }}</label>
                            <textarea id="incorrect_feedback_en" name="incorrect_feedback_en" rows="3" placeholder="{{ __('panel.optional_incorrect_feedback_en') }}">{{ old('incorrect_feedback_en') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="incorrect_feedback_ar">{{ __('panel.incorrect_feedback_ar') }}</label>
                            <textarea id="incorrect_feedback_ar" name="incorrect_feedback_ar" rows="3" dir="rtl" placeholder="{{ __('panel.optional_incorrect_feedback_ar') }}">{{ old('incorrect_feedback_ar') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Multiple Choice Options -->
                <div id="multiple-choice-section" class="form-section" style="display:none">
                    <div class="section-title">{{ __('panel.answer_options') }}</div>
                    <div id="options-container"></div>
                    <button type="button" class="btn btn-outline-secondary" onclick="addOption()">
                        <i class="fa-solid fa-plus"></i>{{ __('panel.add_option') }}
                    </button>
                </div>

                <!-- True/False Options -->
                <div id="true-false-section" class="form-section" style="display:none">
                    <div class="section-title">{{ __('panel.correct_answer') }}</div>
                    <div class="radio-group">
                        <label class="radio-label">
                            <input type="radio" name="true_false_answer" value="1" {{ old('true_false_answer') === '1' ? 'checked' : '' }}>
                            <span class="badge badge-success">{{ __('panel.true') }}</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="true_false_answer" value="0" {{ old('true_false_answer') === '0' ? 'checked' : '' }}>
                            <span class="badge badge-danger">{{ __('panel.false') }}</span>
                        </label>
                    </div>
                </div>

                <!-- Essay Note -->
                <div id="essay-section" class="form-section" style="display:none">
                    <div class="alert alert-info">
                        <i class="fa-solid fa-info-circle"></i>
                        {{ __('panel.essay_question_note') }}
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="{{ route('teacher.exams.exam_questions.index', $exam) }}" class="btn btn-secondary">
                        {{ __('panel.cancel') }}
                    </a>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fa-solid fa-save"></i>{{ __('panel.create_question') }}
                    </button>
                </div>
            </form>
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

.form-section{margin-bottom:24px}
.section-title{font-size:14px;font-weight:800;color:#0f172a;margin-bottom:16px;padding-bottom:12px;border-bottom:2px solid #0055D2}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px}
.form-group{display:flex;flex-direction:column;gap:8px;margin-bottom:0}
.form-group label{font-weight:800;color:#0f172a;font-size:14px}
.form-group .form-text{color:#6b7280;font-size:12px}
.form-group input,.form-group select,.form-group textarea{border:1px solid #d1d5db;border-radius:10px;padding:12px 14px;font-size:14px;background:#fff;transition:border-color .16s,box-shadow .16s;font-family:inherit}
.form-group textarea{min-height:80px;resize:vertical}
.form-group input:focus,.form-group select:focus,.form-group textarea:focus{outline:none;border-color:#0055D2;box-shadow:0 0 0 3px rgba(0,85,210,.12)}

.radio-group{display:flex;gap:20px;margin-top:12px}
.radio-label{display:flex;align-items:center;gap:10px;cursor:pointer;font-weight:600;color:#0f172a}
.radio-label input[type="radio"]{cursor:pointer;width:18px;height:18px;margin:0}
.badge{font-size:12px;font-weight:700;padding:6px 12px;border-radius:6px}
.badge-success{background:#dcfce7;color:#15803d}
.badge-danger{background:#fee2e2;color:#dc2626}
.badge-info{background:#dbeafe;color:#0369a1}

.option-item{background:#f9fafb;border:1px solid #e5e7eb;border-radius:10px;padding:16px;margin-bottom:16px}
.option-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:12px}
.option-header h6{font-size:14px;font-weight:700;color:#0f172a;margin:0}
.btn{display:inline-flex;align-items:center;gap:8px;border-radius:10px;padding:10px 16px;font-weight:700;font-size:14px;text-decoration:none;cursor:pointer;transition:all .18s;border:none}
.btn-primary{background:#0055D2;color:#fff}
.btn-primary:hover{background:#0047b3;transform:translateY(-1px);box-shadow:0 6px 18px rgba(0,85,210,.18)}
.btn-secondary{background:#111827;color:#fff}
.btn-secondary:hover{background:#0f172a;transform:translateY(-1px)}
.btn-outline-secondary{border:1px solid #d1d5db;color:#374151;background:#fff}
.btn-outline-secondary:hover{background:#f9fafb;border-color:#9ca3af}
.btn-outline-danger{border:1px solid #fecaca;color:#dc2626;background:#fff}
.btn-outline-danger:hover{background:#fee2e2}

.alert{padding:12px 16px;border-radius:10px;margin-bottom:16px;border:1px solid transparent}
.alert-info{background:#dbeafe;border-color:#0369a1;color:#0369a1}

.form-actions{display:flex;gap:12px;justify-content:flex-end;margin-top:20px;padding-top:16px;border-top:1px solid #eef0f3}

@media (max-width:992px){
  .ud-wrap{grid-template-columns:1fr}
  .ud-menu{position:static}
  .form-row{grid-template-columns:1fr}
}
</style>
@endsection

@section('scripts')
<script>
let optionCount = 0;

function toggleQuestionType() {
  const type = document.getElementById('type').value;
  const multipleChoiceSection = document.getElementById('multiple-choice-section');
  const trueFalseSection = document.getElementById('true-false-section');
  const essaySection = document.getElementById('essay-section');

  // Hide all sections
  multipleChoiceSection.style.display = 'none';
  trueFalseSection.style.display = 'none';
  essaySection.style.display = 'none';

  // Show relevant section
  if (type === 'multiple_choice') {
    multipleChoiceSection.style.display = 'block';
    if (optionCount === 0) {
      // Add default 4 options
      for (let i = 0; i < 4; i++) {
        addOption();
      }
    }
  } else if (type === 'true_false') {
    trueFalseSection.style.display = 'block';
  } else if (type === 'essay') {
    essaySection.style.display = 'block';
  }
}

function addOption() {
  optionCount++;
  const container = document.getElementById('options-container');
  const letter = String.fromCharCode(64 + optionCount);
  const optionHtml = `
    <div class="option-item" id="option-${optionCount}">
      <div class="option-header">
        <h6>{{ __('panel.option') }} ${letter}</h6>
        <button type="button" class="btn-outline-danger" onclick="removeOption(${optionCount})" style="padding:6px 10px;font-size:12px;">
          <i class="fa-solid fa-trash"></i>
        </button>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>{{ __('panel.option_text_en') }}</label>
          <input type="text" name="options[${optionCount-1}][option_en]">
        </div>
        <div class="form-group">
          <label>{{ __('panel.option_text_ar') }}</label>
          <input type="text" name="options[${optionCount-1}][option_ar]" dir="rtl">
        </div>
      </div>
      <label class="radio-label">
        <input type="checkbox" name="options[${optionCount-1}][is_correct]" value="1">
        <span>{{ __('panel.correct_answer') }}</span>
      </label>
    </div>
  `;
  container.insertAdjacentHTML('beforeend', optionHtml);
}

function removeOption(optionId) {
  const option = document.getElementById(`option-${optionId}`);
  if (option) {
    option.remove();
    updateOptionLetters();
  }
}

function updateOptionLetters() {
  const options = document.querySelectorAll('.option-item');
  options.forEach((option, index) => {
    const letter = String.fromCharCode(65 + index);
    const header = option.querySelector('.option-header h6');
    if (header) header.textContent = `{{ __('panel.option') }} ${letter}`;
  });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
  const typeSelect = document.getElementById('type');

  // Initialize with current type
  if (typeSelect.value) {
    toggleQuestionType();
  }

  // Form validation
  const questionForm = document.getElementById('questionForm');
  if (questionForm) {
    questionForm.addEventListener('submit', function(e) {
      const type = document.getElementById('type').value;

      if (!type) {
        e.preventDefault();
        alert('{{ __("panel.select_question_type") }}');
        return;
      }

      if (type === 'multiple_choice') {
        const options = document.querySelectorAll('.option-item');
        if (options.length < 2) {
          e.preventDefault();
          alert('{{ __("panel.minimum_two_options_required") }}');
          return;
        }

        const correctOptions = document.querySelectorAll('input[name*="[is_correct]"]:checked');
        if (correctOptions.length === 0) {
          e.preventDefault();
          alert('{{ __("panel.at_least_one_correct_answer_required") }}');
          return;
        }

        // Validate all option inputs are filled
        for (let option of options) {
          const enInput = option.querySelector('input[name*="[option_en]"]');
          const arInput = option.querySelector('input[name*="[option_ar]"]');

          if (!enInput.value.trim() || !arInput.value.trim()) {
            e.preventDefault();
            alert('{{ __("panel.fill_all_option_fields") }}');
            return;
          }
        }
      } else if (type === 'true_false') {
        const selectedAnswer = document.querySelector('input[name="true_false_answer"]:checked');
        if (!selectedAnswer) {
          e.preventDefault();
          alert('{{ __("panel.please_select_correct_answer") }}');
          return;
        }
      }
    });
  }
});
</script>
@endsection
