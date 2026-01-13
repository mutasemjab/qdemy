<div class="answers-list">
    @if($attempt->answers->count() === 0)
        <div class="alert alert-info">{{ __('panel.no_answers') }}</div>
    @else
        @foreach($attempt->answers as $answer)
            @php
                $question = $answer->question;
                $status = $answer->is_correct === null ? 'pending' : ($answer->is_correct ? 'correct' : 'incorrect');
                $statusClass = $status === 'pending' ? 'warning' : ($status === 'correct' ? 'success' : 'danger');
            @endphp
            <div class="answer-item" style="margin-bottom:16px;padding-bottom:16px;border-bottom:1px solid #e5e7eb;">
                <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:8px;">
                    <h6 style="margin:0;font-weight:700;">
                        {{ app()->getLocale() == 'ar' ? $question->question_ar : $question->question_en }}
                    </h6>
                    <span class="badge badge-{{ $statusClass }}" style="padding:6px 10px;border-radius:999px;font-size:19px;">
                        @if($status === 'pending')
                            <i class="fas fa-hourglass-half"></i> {{ __('panel.pending') }}
                        @elseif($status === 'correct')
                            <i class="fas fa-check"></i> {{ __('panel.correct') }}
                        @else
                            <i class="fas fa-times"></i> {{ __('panel.incorrect') }}
                        @endif
                    </span>
                </div>

                @if($question->type === 'multiple_choice')
                    <div class="answer-content">
                        @foreach($question->options as $option)
                            @php
                                $selectedOptions = $answer->selected_options ?? [];
                                $isSelected = in_array($option->id, (array)$selectedOptions);
                                $optionText = app()->getLocale() == 'ar' ? $option->option_ar : $option->option_en;
                            @endphp
                            <div style="margin:4px 0;">
                                <input type="checkbox" disabled {{ $isSelected ? 'checked' : '' }} style="margin-left:8px;">
                                <span>{{ $optionText }}</span>
                                @if($option->is_correct)
                                    <strong style="color:#10b981;">(&#10003;)</strong>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @elseif($question->type === 'true_false')
                    @php
                        $selectedAnswer = $answer->selected_options[0] ?? null;
                        $correctOption = $question->options()->where('is_correct', true)->first();
                        $correctAnswer = strtolower($correctOption?->option_en ?? '') === 'true';
                    @endphp
                    <div class="answer-content" style="padding:8px;background:#f8fafc;border-radius:8px;">
                        <p style="margin:4px 0;">
                            <strong>{{ __('panel.student_answer') }}:</strong>
                            {{ $selectedAnswer ? __('panel.true') : __('panel.false') }}
                        </p>
                        <p style="margin:4px 0;">
                            <strong>{{ __('panel.correct_answer') }}:</strong>
                            {{ $correctAnswer ? __('panel.true') : __('panel.false') }}
                        </p>
                    </div>
                @elseif($question->type === 'essay')
                    <div class="answer-content">
                        <p style="margin:4px 0;"><strong>{{ __('panel.student_answer') }}:</strong></p>
                        <div style="padding:12px;background:#f8fafc;border-radius:8px;">
                            {{ $answer->essay_answer ?? __('panel.no_answer') }}
                        </div>
                    </div>
                @endif

                <div style="margin-top:8px;">
                    <small style="color:#64748b;">
                        {{ __('panel.score') }}:
                        <strong>{{ number_format($answer->score, 2) }}</strong>
                        / {{ number_format($question->pivot->grade ?? $question->grade, 2) }}
                    </small>
                </div>
            </div>
        @endforeach
    @endif
</div>