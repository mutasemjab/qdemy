@extends('layouts.app')

@section('title', 'مراجعة الامتحان - ' . $exam->title)

@section('content')
<section class="cmty-page">
    <div class="universities-header-wrapper">
        <div class="universities-header">
            <h2>مراجعة الامتحان: {{ $exam->title }}</h2>
        </div>
    </div>

    <div class="cmty-feed">

        <!-- Exam Summary -->
        <article class="cmty-post cmty-post--outlined">
            <header class="cmty-head">
                ملخص النتائج - {!! $attempt->passed() !!}
                <img class="cmty-mark" data-src="{{ asset('assets_front/images/community-logo1.png') }}" alt="">
            </header>

            <div style="margin: 15px 0;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                    <div>
                        <strong>تاريخ الامتحان:</strong><br>
                        {{ $attempt->started_at->format('Y-m-d H:i') }}
                    </div>
                    <div>
                        <strong>المدة المستغرقة:</strong><br>
                        {{ $attempt->duration }} دقيقة
                    </div>
                    @if($attempt->status == 'completed')
                    <div>
                        <strong>النتيجة:</strong><br>
                        {{ $attempt->score }}/{{ $exam->total_grade }}
                    </div>
                    <div>
                        <strong>النسبة المئوية:</strong><br>
                        {{ $attempt->percentage }}%
                    </div>
                    @endif
                </div>
            </div>

            <div class="cmty-actions">
                <a href="{{ route('exam', ['exam' => $exam->id, 'slug' => $exam->slug]) }}" class="cmty-like">العودة للامتحان</a>
            </div>
        </article>

        <!-- Questions Review -->
        @foreach($answers as $index => $answer)
            @php
                $question = $answer->question;
                $is_correct = $answer->is_correct;
            @endphp

            <article class="cmty-post cmty-post--outlined"
                     style="border-left: 5px solid {{ $is_correct ? '#4CAF50' : ($is_correct === false ? '#f44336' : '#ff9800') }};">

                <header class="cmty-head">
                    <div class="cmty-user">
                        <div>
                            <h4>
                                السؤال {{ $index + 1 }}: {{ $question->title }}
                                @if($is_correct === true)
                                    <span style="color: #4CAF50; font-size: 14px;">(صحيح - {{ $answer->score }}/{{ $question->grade }})</span>
                                @elseif($is_correct === false)
                                    <span style="color: #f44336; font-size: 14px;">(خطأ - {{ $answer->score }}/{{ $question->grade }})</span>
                                @else
                                    <span style="color: #ff9800; font-size: 14px;">(قيد التصحيح - {{ $answer->score }}/{{ $question->grade }})</span>
                                @endif
                            </h4>
                        </div>
                    </div>
                </header>

                <div class="cmty-text">
                    <p><strong>السؤال:</strong> {{ $question->question }}</p>

                    @if($question->explanation)
                        <div style="margin: 10px 0; padding: 10px; background: #f0f8ff; border-radius: 5px;">
                            <small><strong>ملاحظة:</strong> {{ $question->explanation }}</small>
                        </div>
                    @endif
                </div>

                <!-- Display Answer Based on Question Type -->
                <div style="margin: 15px 0; padding: 15px; background: #f9f9f9; border-radius: 5px;">
                    @if($question->type === 'essay')
                        <div>
                            <strong>إجابتك:</strong>
                            <p style="margin: 10px 0; padding: 10px; background: white; border-radius: 3px;">
                                {{ $answer->essay_answer ?: 'لم يتم الإجابة' }}
                            </p>
                        </div>

                    @elseif($question->type === 'true_false')
                        <div>
                            <strong>إجابتك:</strong>
                            <span style="color: {{ $is_correct ? '#4CAF50' : '#f44336' }};">
                                {{ $answer->answer_display }}
                            </span>
                        </div>

                        <div style="margin-top: 10px;">
                            <strong>الإجابة الصحيحة:</strong>
                            @php
                                $correct_option = $question->getCorrectOptions()->first();
                            @endphp
                            <span style="color: #4CAF50;">
                                {{ $correct_option ? $correct_option->option : 'غير محدد' }}
                            </span>
                        </div>

                    @elseif($question->type === 'multiple_choice')
                        <div>
                            <strong>إجاباتك:</strong>
                            @if($answer->selected_options)
                                <ul style="margin: 10px 0;">
                                    @foreach($answer->getSelectedOptionsModels() as $selected)
                                        <li style="color: {{ in_array($selected->id, $question->getCorrectOptions()->pluck('id')->toArray()) ? '#4CAF50' : '#f44336' }};">
                                            {{ $selected->option }}
                                            @if(in_array($selected->id, $question->getCorrectOptions()->pluck('id')->toArray()))
                                                ✓
                                            @else
                                                ✗
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <span style="color: #999;">لم يتم الإجابة</span>
                            @endif
                        </div>

                        <div style="margin-top: 10px;">
                            <strong>الإجابات الصحيحة:</strong>
                            <ul style="margin: 10px 0;">
                                @foreach($question->getCorrectOptions() as $correct)
                                    <li style="color: #4CAF50;">{{ $correct->option }} ✓</li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- Show all options with indicators -->
                        <div style="margin-top: 15px;">
                            <strong>جميع الخيارات:</strong>
                            <ul style="margin: 10px 0;">
                                @foreach($question->options as $option)
                                    @php
                                        $was_selected = $answer->selected_options && in_array($option->id, $answer->selected_options);
                                        $is_correct_option = $option->is_correct;
                                    @endphp

                                    <li style="
                                        color: {{ $is_correct_option ? '#4CAF50' : '#333' }};
                                        background: {{ $was_selected ? ($is_correct_option ? '#e8f5e8' : '#ffeaea') : 'transparent' }};
                                        padding: 5px;
                                        border-radius: 3px;
                                        margin: 5px 0;
                                    ">
                                        {{ $option->option }}

                                        @if($is_correct_option)
                                            <span style="color: #4CAF50; font-weight: bold;"> ✓ (صحيح)</span>
                                        @endif

                                        @if($was_selected && !$is_correct_option)
                                            <span style="color: #f44336; font-weight: bold;"> ✗ (اخترت هذا)</span>
                                        @elseif($was_selected && $is_correct_option)
                                            <span style="color: #4CAF50; font-weight: bold;"> ✓ (اخترت هذا)</span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <!-- Grade Display -->
                <div style="text-align: left; margin-top: 10px;">
                    <span style="
                        background: {{ $is_correct ? '#4CAF50' : ($is_correct === false ? '#f44336' : '#ff9800') }};
                        color: white;
                        padding: 5px 10px;
                        border-radius: 15px;
                        font-size: 12px;
                        font-weight: bold;
                    ">
                        {{ $answer->score }}/{{ $question->grade }}
                    </span>
                </div>
            </article>
        @endforeach

        <!-- Final Statistics -->
        <article class="cmty-post cmty-post--outlined">
            <header class="cmty-head">
                إحصائيات مفصلة
            </header>

            @php
                $total_questions = (clone $answers)->count();
                $correct_answers = (clone $answers)->where('is_correct','===', true)->count();
                $wrong_answers   = (clone $answers)->where('is_correct','===',false)->count();
                $pending_answers = (clone $answers)->where('is_correct','===',null)->count();
            @endphp

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; margin: 15px 0;">
                <div style="text-align: center; padding: 15px; background: #e8f5e8; border-radius: 10px;">
                    <div style="font-size: 24px; font-weight: bold; color: #4CAF50;">{{ $correct_answers }}</div>
                    <div>إجابات صحيحة</div>
                </div>

                <div style="text-align: center; padding: 15px; background: #ffeaea; border-radius: 10px;">
                    <div style="font-size: 24px; font-weight: bold; color: #f44336;">{{ $wrong_answers }}</div>
                    <div>إجابات خاطئة</div>
                </div>

                @if($pending_answers > 0)
                <div style="text-align: center; padding: 15px; background: #fff3cd; border-radius: 10px;">
                    <div style="font-size: 24px; font-weight: bold; color: #ff9800;">{{ $pending_answers }}</div>
                    <div>قيد التصحيح</div>
                </div>
                @endif

                <div style="text-align: center; padding: 15px; background: #e3f2fd; border-radius: 10px;">
                    <div style="font-size: 24px; font-weight: bold; color: #1976d2;">{{ $total_questions }}</div>
                    <div>إجمالي الأسئلة</div>
                </div>
            </div>

            <div class="cmty-actions">
                @if($exam->result_attempts()?->count())
                <a href="{{ route('exam', ['exam' => $exam->id, 'slug' => $exam->slug]) }}" class="cmty-like">عرض جميع المحاولات</a>
                @else
                <a href="{{ route('exam', ['exam' => $exam->id, 'slug' => $exam->slug]) }}" class="cmty-like">العودة للامتحان</a>
                @endif
            </div>
        </article>
    </div>
</section>

@endsection
