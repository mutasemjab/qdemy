@if($isApi == true)
@php $hideFooter = true; @endphp
@php $hideHeader = true; @endphp
@endif

@extends('layouts.app')

@section('title', $exam->title)

@section('content')

<section class="cmty-page">
    <div class="universities-header-wrapper">
        <div class="universities-header">
            <h2>{{ $exam->title }}</h2>
        </div>
    </div>

    <div class="cmty-feed">
        @include('web.alert-message')

        <!-- Exam Info Bar -->
        <div class="examx-row">
            <div class="examx-dropdown">
                <button class="examx-pill">
                    <span>{{ translate_lang('عدد المحاولات المسموحة') }}: {{ $exam->attempts_allowed }}</span>
                </button>
            </div>
            <div class="examx-dropdown">
                <button class="examx-pill">
                    <span>{{ translate_lang('الدرجة الكلية') }}: {{ $exam->total_grade }}</span>
                </button>
            </div>
            <div class="examx-dropdown">
                <button class="examx-pill">
                    <span>{{ translate_lang('المدة') }}: {{ $exam->duration_minutes }} {{ translate_lang('دقيقة') }}</span>
                </button>
            </div>
            @if($current_attempt && $current_attempt->remaining_time)
                <div class="examx-dropdown">
                    <button class="examx-pill" style="background-color: #ff6b6b; color: white;">
                        <span id="timer">{{ translate_lang('الوقت المتبقي') }}: {{ $current_attempt->remaining_time }} {{ translate_lang('دقيقة') }}</span>
                    </button>
                </div>
            @endif
        </div>

        <!-- Progress Bar -->
        @if($current_attempt)
            <div class="examx-progress" style="margin: 20px 0;">
                <div style="background: #f0f0f0; border-radius: 10px; padding: 3px;">
                    <div style="background: #4CAF50; height: 20px; border-radius: 7px; width: {{ $current_attempt->progress }}%; transition: width 0.3s;"></div>
                </div>
                <small>{{ translate_lang('تم الإجابة على') }} {{ $current_attempt->answers->count() }} {{ translate_lang('من') }} {{ count($exam->questions) }} {{ translate_lang('سؤال') }}</small>
            </div>
        @endif

        <!-- Exam Introduction (shown before starting or on first question) -->
        @if(!$current_attempt || $question_nm == 1)
            <article class="cmty-post cmty-post--outlined">
                <header class="cmty-head">
                    {{ $exam->course?->title }}
                    <img class="cmty-mark" data-src="{{ asset('assets_front/images/community-logo1.png') }}" alt="">
                </header>
                <p class="cmty-text">
                    {{ $exam->description }}
                </p>
                <div style="margin: 15px 0;">
                    <span>{{ translate_lang('المدة') }}: {{ $exam->duration_minutes }} {{ translate_lang('دقيقة') }}</span> -
                    @if($exam->attempts->count())
                      <span>{{ translate_lang('المحاولات السابقة') }}: {{ $exam->attempts->count() }}</span>
                    @endif
                </div>
                <div>
                    <span>{{ translate_lang('الدرجة الكلية') }}: {{ $exam->total_grade }}</span> -
                    <span>{{ translate_lang('درجة النجاح') }}: {{ $exam->passing_grade }}%</span>
                </div>

                @if(!$result && !$current_attempts->count() && $can_add_attempt)
                    <div class="cmty-actions">
                        <form action="{{route($apiRoutePrefix.'start.exam',['exam'=>$exam->id,'slug'=>$exam->slug])}}" method='post'>
                            @csrf
                            <button type='submit' class="cmty-like">
                                @if($attempts->count()) {{ translate_lang('محاولة جديدة') }} @else {{ translate_lang('بدء الامتحان') }} @endif
                            </button>
                        </form>
                    </div>
                @endif
            </article>
        @endif

        <!-- Results Display -->
        @if($result && !$current_attempt && $question_nm == 1)
            <article class="cmty-post cmty-post--outlined">
                <header class="cmty-head">
                    {{ translate_lang('نتيجة الامتحان') }} - {!! $result->passed() !!}
                    <img class="cmty-mark" data-src="{{ asset('assets_front/images/community-logo1.png') }}" alt="">
                </header>

                <div style="margin: 15px 0;">
                    <span>{{ translate_lang('بدأ في') }}: {{ $result->started_at->format('Y-m-d H:i') }}</span><br>
                    <span>{{ translate_lang('انتهى في') }}: {{ $result->submitted_at?->format('Y-m-d H:i') }}</span><br>
                    <span>{{ translate_lang('المدة المستغرقة') }}: {{ $result->duration }} {{ translate_lang('دقيقة') }}</span>
                </div>

                <div style="margin: 15px 0;">
                    <span>{{ translate_lang('النتيجة') }}: {{ $result->score }}/{{ $exam->total_grade }}</span><br>
                    <span>{{ translate_lang('النسبة المئوية') }}: {{ $result->percentage }}%</span><br>
                    <span>{{ translate_lang('الحالة') }}: {!! $result->passed() !!}</span>
                </div>

                @if($exam->show_results_immediately)
                    <div class="cmty-actions">
                        <a href="{{ route($apiRoutePrefix.'review.attempt', ['exam' => $exam->id, 'attempt' => $result->id]) }}" class="cmty-like">{{ translate_lang('مراجعة الإجابات') }}</a>
                    </div>
                @endif

                @if(!$current_attempts->count() && $can_add_attempt)
                <div class="cmty-actions">
                    <form action="{{route($apiRoutePrefix.'start.exam',['exam'=>$exam->id,'slug'=>$exam->slug])}}" method='post'>
                        @csrf
                        <button type='submit' class="cmty-like">{{ translate_lang('محاولة جديدة') }}</button>
                    </form>
                </div>
                @endif

            </article>
        @endif

        <!-- Current Question Display -->
        @if($current_attempt && $question)
            <article class="cmty-post cmty-post--outlined">
                <header class="cmty-head">
                    <div class="cmty-user">
                        <div>
                            <h4>{{ translate_lang('السؤال') }} {{ $question_nm }}: {{ $question->title }}</h4>
                        </div>
                    </div>
                    <img class="cmty-mark" data-src="{{ asset('assets_front/images/community-logo1.png') }}" alt="">
                </header>

                <p class="cmty-text">
                    {{ $question->question }}
                </p>

                  @if($question->photo)
                        <div style="margin: 15px 0; text-align: center;">
                            <img src="{{ asset('assets/admin/uploads/' . $question->photo) }}" 
                                alt="{{ $question->title }}" 
                                style="max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                        </div>
                    @endif

                @if($question->explanation)
                    <div style="margin: 10px 0; padding: 10px; background: #f9f9f9; border-radius: 5px;">
                        <small><strong>{{ translate_lang('ملاحظة') }}:</strong> {{ $question->explanation }}</small>
                    </div>
                @endif

                <div style="margin: 10px 0;">
                    <span>{{ translate_lang('رقم السؤال') }}: {{ $question_nm }}</span> |
                    <span>{{ translate_lang('الدرجة') }}: {{ $question->grade }}</span>
                </div>

                <form action="{{route($apiRoutePrefix.'answer.question',['exam'=>$exam->id,'question'=>$question->id,'page'=>$question_nm])}}" method='post'>
                    @csrf
                    <input type="hidden" name="page" value="{{ $question_nm }}">

                    @if($question->type === 'essay')
                        <div style="margin: 20px 0;">
                            <label>{{ translate_lang('إجابتك') }}:</label>
                            <textarea name='answer' class="cmty-input" rows="5" placeholder="{{ translate_lang('اكتب إجابتك هنا...') }}" required></textarea>
                        </div>

                    @elseif($question->type === 'true_false')
                        <div style="margin: 20px 0;">
                            <div style="margin: 10px 0;">
                                <input type="radio" id="answer_true" name='answer' value='true' required>
                                <label for="answer_true" style="margin-right: 10px;">{{ translate_lang('صحيح') }}</label>
                            </div>
                            <div style="margin: 10px 0;">
                                <input type="radio" id="answer_false" name='answer' value='false' required>
                                <label for="answer_false" style="margin-right: 10px;">{{ translate_lang('خطأ') }}</label>
                            </div>
                        </div>

                    @elseif($question->type === 'multiple_choice')
                        <div style="margin: 20px 0;">
                            @php
                                $multiple_choices = $exam->shuffle_options
                                    ? $question->getShuffledOptions()
                                    : $question->options()->orderBy('order','asc')->get();
                            @endphp

                            @foreach($multiple_choices as $option)
                                <div style="margin: 10px 0;">
                                    <input type="checkbox" name="answer[]" id="option_{{$option->id}}" value="{{$option->id}}">
                                    <label for="option_{{$option->id}}" style="margin-right: 10px;">{{ $option->option }}</label>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="cmty-actions" style="margin: 20px 0;">
                        <button type='submit' class="cmty-like">{{ translate_lang('حفظ الإجابة والمتابعة') }}</button>

                        @if($question_nm > 1)
                        <a href="{{ route($apiRoutePrefix.'exam', ['exam' => $exam->id, 'slug' => $exam->slug, 'page' => $question_nm - 1]) }}"
                            class="cmty-like" style="background: #6c757d; margin-right: 10px;">{{ translate_lang('السؤال السابق') }}</a>
                        @endif
                    </div>
                </form>

                <!-- Finish Exam Button -->
                @if($current_attempt->answers->count() > 0)
                    <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee;">
                        <form action="{{route($apiRoutePrefix.'finish.exam',['exam'=>$exam->id])}}" method='post' onsubmit="return confirm('{{ translate_lang('هل أنت متأكد من تسليم الامتحان؟ لن تتمكن من التراجع.') }}')">
                            @csrf
                            <button type='submit' class="cmty-like" style="background: #dc3545;">{{ translate_lang('تسليم الامتحان') }}</button>
                        </form>
                    </div>
                @endif
            </article>
        @endif

        <!-- Navigation -->
        @if($current_attempt && $questions)
        <div class="pagination-wrapper">
        {{ $questions?->links('pagination::custom-bootstrap-5') ?? '' }}
        </div>
        @endif

        <!-- Exam History -->
        @if($attempts->count() > 0)
            <article class="cmty-post cmty-post--outlined">
                <header class="cmty-head">
                    {{ translate_lang('تاريخ المحاولات السابقة') }}
                </header>

                <div style="margin: 15px 0;">
                    @foreach($attempts as $attempt)
                        <div style="padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px;">
                            <div>
                                <strong>
                                    <a href="{{route($apiRoutePrefix.'review.attempt',['exam'=>$exam->id,'attempt'=>$attempt->id])}}">
                                        {{ translate_lang('محاولة') }}
                                    </a>
                                </strong> -
                                <span>{{ $attempt->submitted_at?->format('Y-m-d H:i') }}</span>
                            </div>
                            <div>
                                {{ translate_lang('النتيجة') }}: {{ $attempt->score }}/{{ $exam->total_grade }}
                                ({{ $attempt->percentage }}%) -
                                {!! $attempt->passed() !!}
                            </div>
                            @if($exam->show_results_immediately)
                                <div style="margin-top: 10px;">
                                    <a href="{{ route($apiRoutePrefix.'review.attempt', ['exam' => $exam->id, 'attempt' => $attempt->id]) }}"
                                       class="cmty-like" style="font-size: 12px; padding: 5px 10px;">{{ translate_lang('مراجعة') }}</a>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </article>
        @endif

        <!-- Warning Messages -->
        @if(!$can_add_attempt && !$current_attempt)
            <article class="cmty-post cmty-post--outlined" style="border-color: #dc3545;">
                <div style="color: #dc3545; text-align: center; padding: 20px;">
                    <strong>{{ translate_lang('لقد استنفدت عدد المحاولات المسموحة') }} ({{ $exam->attempts_allowed }})</strong>
                </div>
            </article>
        @endif

        <!-- @if(!$exam->is_available())
            <article class="cmty-post cmty-post--outlined" style="border-color: #ffc107;">
                <div style="color: #856404; text-align: center; padding: 20px;">
                    <strong>{{ translate_lang('الامتحان غير متاح حاليا') }}</strong>
                    @if($exam->start_date && now() < $exam->start_date)
                        <br>{{ translate_lang('يبدأ في') }}: {{ $exam->start_date->format('Y-m-d H:i') }}
                    @elseif($exam->end_date && now() > $exam->end_date)
                        <br>{{ translate_lang('انتهى في') }}: {{ $exam->end_date->format('Y-m-d H:i') }}
                    @endif
                </div>
            </article>
        @endif -->

    </div>
</section>

@if($current_attempt && $current_attempt?->remaining_time)
<script>
    // Timer countdown with seconds
    let remainingSeconds = {{ $current_attempt->remaining_time }} * 60; // تحويل الدقائق إلى ثواني
    const timerElement = document.getElementById('timer');

    if (timerElement && remainingSeconds > 0) {
        const updateTimer = () => {
            if (remainingSeconds <= 0) {
                timerElement.textContent = 'انتهى الوقت!';
                timerElement.style.background = '#dc3545';

                // Auto submit form
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route($apiRoutePrefix."finish.exam", ["exam" => $exam->id]) }}';

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                document.body.appendChild(form);
                form.submit();
                return;
            }

            // حساب الساعات والدقائق والثواني
            const hours = Math.floor(remainingSeconds / 3600);
            const minutes = Math.floor((remainingSeconds % 3600) / 60);
            const seconds = remainingSeconds % 60;

            // تنسيق العرض
            let timeDisplay = 'الوقت المتبقي: ';

            if (hours > 0) {
                timeDisplay += `${hours}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            } else if (minutes > 0) {
                timeDisplay += `${minutes}:${seconds.toString().padStart(2, '0')}`;
            } else {
                timeDisplay += `${seconds} ثانية`;
            }

            timerElement.textContent = timeDisplay;

            // Warning colors based on remaining time in seconds
            if (remainingSeconds <= 300) { // آخر 5 دقائق (300 ثانية)
                timerElement.style.background = '#dc3545';
                timerElement.style.animation = 'blink 1s infinite';
            } else if (remainingSeconds <= 900) { // آخر 15 دقيقة (900 ثانية)
                timerElement.style.background = '#ffc107';
            } else {
                timerElement.style.background = '#28a745'; // اللون الأخضر للوقت الآمن
            }

            remainingSeconds--;
        };

        updateTimer();
        setInterval(updateTimer, 1000); // التحديث كل ثانية بدلاً من كل دقيقة
    }

    // Add blinking animation and smooth transitions
    const style = document.createElement('style');
    style.textContent = `
        @keyframes blink {
            0%, 50% { opacity: 1; }
            51%, 100% { opacity: 0.7; }
        }

        #timer {
            transition: background-color 0.3s ease;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 5px;
            color: white;
            display: inline-block;
        }
    `;
    document.head.appendChild(style);
</script>
@endif

@endsection
