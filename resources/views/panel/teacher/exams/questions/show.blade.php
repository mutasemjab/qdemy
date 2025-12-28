@extends('layouts.app')

@section('title', __('panel.view'))

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
            <div class="ud-title">{{ __('panel.view') }}</div>
            <p class="ud-subtitle">{{ app()->getLocale() === 'ar' ? $exam->title_ar : $exam->title_en }}</p>

            <!-- Action Buttons -->
            <div class="action-buttons" style="margin-bottom: 24px; display: flex; gap: 12px;">
                <a href="{{ route('teacher.exams.exam_questions.edit', [$exam, $question]) }}" class="btn btn-primary">
                    <i class="fa-solid fa-edit"></i> {{ __('panel.edit') }}
                </a>
                <form action="{{ route('teacher.exams.exam_questions.destroy', [$exam, $question]) }}" method="POST" style="display: inline;" onsubmit="return confirm('{{ __('panel.delete_question_warning') }}')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fa-solid fa-trash"></i> {{ __('panel.delete') }}
                    </button>
                </form>
            </div>

            <!-- Question Details -->
            <div class="info-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px; margin-bottom: 24px;">
                <div class="info-card">
                    <div class="info-label">{{ __('panel.question_type') }}</div>
                    <div class="info-value">{{ __('panel.' . $question->type) }}</div>
                </div>
                <div class="info-card">
                    <div class="info-label">{{ __('panel.grade') }}</div>
                    <div class="info-value">{{ number_format($question->grade, 2) }}</div>
                </div>
                <div class="info-card">
                    <div class="info-label">{{ __('panel.created_at') }}</div>
                    <div class="info-value">{{ $question->created_at->format('M d, Y') }}</div>
                </div>
            </div>

            <!-- Question Content -->
            <div class="form-section">
                <div class="section-title">{{ __('panel.question_content') }}</div>

                <!-- Titles -->
                <div class="form-row">
                    <div class="form-group">
                        <label>{{ __('panel.question_title_en') }}</label>
                        <div class="display-box">{{ $question->title_en }}</div>
                    </div>
                    <div class="form-group">
                        <label>{{ __('panel.question_title_ar') }}</label>
                        <div class="display-box" dir="rtl">{{ $question->title_ar }}</div>
                    </div>
                </div>

                <!-- Question Text -->
                <div class="form-row">
                    <div class="form-group">
                        <label>{{ __('panel.question_text_en') }}</label>
                        <div class="display-box" style="min-height: 100px;">{!! nl2br(e($question->question_en)) !!}</div>
                    </div>
                    <div class="form-group">
                        <label>{{ __('panel.question_text_ar') }}</label>
                        <div class="display-box" style="min-height: 100px;" dir="rtl">{!! nl2br(e($question->question_ar)) !!}</div>
                    </div>
                </div>

                <!-- Photo -->
                @if($question->photo)
                <div style="margin-top: 16px;">
                    <label>{{ __('panel.photo') }}</label>
                    <div class="display-box" style="text-align: center;">
                        <img src="{{ asset('assets/admin/uploads/' . $question->photo) }}" alt="{{ $question->title_en }}" style="max-width: 400px; max-height: 300px; border-radius: 8px;">
                    </div>
                </div>
                @endif
            </div>

            <!-- Answer Options/Details -->
            @if($question->type === 'multiple_choice' && $question->options->count() > 0)
            <div class="form-section">
                <div class="section-title">{{ __('panel.answer_options') }}</div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 16px;">
                    @foreach($question->options->sortBy('order') as $option)
                    <div style="padding: 16px; border: 1px solid #e5e7eb; border-radius: 10px; background: #f9fafb; {{ $option->is_correct ? 'border-color: #22c55e; background: #dcfce7;' : '' }}">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 8px;">
                            <div>
                                <span style="display: inline-block; background: #0055D2; color: white; padding: 4px 10px; border-radius: 6px; font-weight: 700; font-size: 12px;">
                                    {{ chr(65 + $loop->index) }}
                                </span>
                                @if($option->is_correct)
                                <span style="display: inline-block; margin-left: 8px; background: #22c55e; color: white; padding: 4px 10px; border-radius: 6px; font-weight: 700; font-size: 12px;">
                                    ✓ {{ __('panel.correct') }}
                                </span>
                                @endif
                            </div>
                            @if($option->is_correct)
                            <i class="fa-solid fa-check-circle" style="color: #22c55e; font-size: 18px;"></i>
                            @endif
                        </div>
                        <div style="margin-bottom: 8px;">
                            <strong style="color: #0f172a;">{{ __('panel.english') }}:</strong>
                            <div style="color: #6b7280;">{{ $option->option_en }}</div>
                        </div>
                        <div dir="rtl">
                            <strong style="color: #0f172a;">{{ __('panel.arabic') }}:</strong>
                            <div style="color: #6b7280;">{{ $option->option_ar }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @elseif($question->type === 'true_false' && $question->options->count() > 0)
            <div class="form-section">
                <div class="section-title">{{ __('panel.correct_answer') }}</div>
                @php
                    $trueOption = $question->options->where('option_en', 'True')->first();
                    $correctAnswer = $trueOption && $trueOption->is_correct ? 'true' : 'false';
                @endphp
                <div style="text-align: center;">
                    @if($correctAnswer === 'true')
                        <div style="display: inline-block; padding: 16px 32px; background: #dcfce7; border-radius: 10px; border: 2px solid #22c55e;">
                            <i class="fa-solid fa-circle-check" style="color: #22c55e; font-size: 32px; display: block; margin-bottom: 8px;"></i>
                            <span style="font-size: 18px; font-weight: 700; color: #22c55e;">{{ __('panel.true') }}</span>
                        </div>
                    @else
                        <div style="display: inline-block; padding: 16px 32px; background: #fee2e2; border-radius: 10px; border: 2px solid #dc2626;">
                            <i class="fa-solid fa-circle-xmark" style="color: #dc2626; font-size: 32px; display: block; margin-bottom: 8px;"></i>
                            <span style="font-size: 18px; font-weight: 700; color: #dc2626;">{{ __('panel.false') }}</span>
                        </div>
                    @endif
                </div>
            </div>
            @elseif($question->type === 'essay')
            <div class="form-section">
                <div class="section-title">{{ __('panel.essay') }}</div>
                <div style="padding: 12px 16px; background: #dbeafe; border: 1px solid #0369a1; border-radius: 10px; color: #0369a1;">
                    <i class="fa-solid fa-info-circle"></i>
                    {{ __('panel.essay_question_note') }}
                </div>
            </div>
            @endif

            <!-- Explanations -->
            @if($question->explanation_en || $question->explanation_ar)
            <div class="form-section">
                <div class="section-title">{{ __('panel.explanations') }}</div>
                <div class="form-row">
                    @if($question->explanation_en)
                    <div class="form-group">
                        <label>{{ __('panel.explanation_en') }}</label>
                        <div class="display-box">{!! nl2br(e($question->explanation_en)) !!}</div>
                    </div>
                    @endif
                    @if($question->explanation_ar)
                    <div class="form-group">
                        <label>{{ __('panel.explanation_ar') }}</label>
                        <div class="display-box" dir="rtl">{!! nl2br(e($question->explanation_ar)) !!}</div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Feedback Messages -->
            @if($question->correct_feedback_en || $question->correct_feedback_ar || $question->incorrect_feedback_en || $question->incorrect_feedback_ar)
            <div class="form-section">
                <div class="section-title">{{ __('panel.feedback_messages') }}</div>
                <div class="form-row">
                    @if($question->correct_feedback_en)
                    <div class="form-group">
                        <label>{{ __('panel.correct_feedback_en') }}</label>
                        <div class="display-box">{!! nl2br(e($question->correct_feedback_en)) !!}</div>
                    </div>
                    @endif
                    @if($question->correct_feedback_ar)
                    <div class="form-group">
                        <label>{{ __('panel.correct_feedback_ar') }}</label>
                        <div class="display-box" dir="rtl">{!! nl2br(e($question->correct_feedback_ar)) !!}</div>
                    </div>
                    @endif
                </div>
                <div class="form-row">
                    @if($question->incorrect_feedback_en)
                    <div class="form-group">
                        <label>{{ __('panel.incorrect_feedback_en') }}</label>
                        <div class="display-box">{!! nl2br(e($question->incorrect_feedback_en)) !!}</div>
                    </div>
                    @endif
                    @if($question->incorrect_feedback_ar)
                    <div class="form-group">
                        <label>{{ __('panel.incorrect_feedback_ar') }}</label>
                        <div class="display-box" dir="rtl">{!! nl2br(e($question->incorrect_feedback_ar)) !!}</div>
                    </div>
                    @endif
                </div>
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

.btn{display:inline-flex;align-items:center;gap:8px;border-radius:10px;padding:10px 16px;font-weight:700;font-size:14px;text-decoration:none;cursor:pointer;transition:all .18s;border:none}
.btn-primary{background:#0055D2;color:#fff}
.btn-primary:hover{background:#0047b3;transform:translateY(-1px);box-shadow:0 6px 18px rgba(0,85,210,.18)}
.btn-danger{background:#dc2626;color:#fff}
.btn-danger:hover{background:#b91c1c;transform:translateY(-1px)}

.info-card{background:#f9fafb;border:1px solid #e5e7eb;border-radius:10px;padding:16px}
.info-label{font-size:12px;color:#6b7280;font-weight:700;margin-bottom:4px}
.info-value{font-size:16px;font-weight:800;color:#0f172a}

.form-section{margin-bottom:24px}
.section-title{font-size:14px;font-weight:800;color:#0f172a;margin-bottom:16px;padding-bottom:12px;border-bottom:2px solid #0055D2}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px}
.form-group{display:flex;flex-direction:column;gap:8px}
.form-group label{font-weight:800;color:#0f172a;font-size:14px}
.display-box{padding:12px 14px;background:#f9fafb;border:1px solid #d1d5db;border-radius:10px;color:#0f172a;line-height:1.5}

@media (max-width:992px){
  .ud-wrap{grid-template-columns:1fr}
  .ud-menu{position:static}
  .form-row{grid-template-columns:1fr}
}
</style>
@endsection