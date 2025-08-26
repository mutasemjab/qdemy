@extends('layouts.app')

@section('title', __('messages.basic_grades'))

@section('content')
<section class="cmty-page">
    <div class="universities-header-wrapper">
        <div class="universities-header">
            <h2>{{ $exam->title }}</h2>
        </div>
    </div>

    <div class="cmty-feed">

        <div class="examx-row">
            <div class="examx-dropdown">
                <button class="examx-pill">
                <span>attempts_allowed: {{ $exam->attempts_allowed }}</span>
                </button>
            </div>
            <div class="examx-dropdown">
                <button class="examx-pill">
                <span>total_grade :{{ $exam->total_grade }}</span>
                </button>
            </div>
            <div class="examx-dropdown">
                <button class="examx-pill">
                <span>duration_minutes: {{ $exam->duration_minutes }}</span>
                </button>
            </div>
        </div>
        @if(!$current_attempt || $question_nm == 1)
            <article class="cmty-post cmty-post--outlined">
                <header class="cmty-head">
                    {{ $exam->course?->title }}
                    <img class="cmty-mark" data-src="{{ asset('assets_front/images/community-logo1.png') }}" alt="">
                </header>
                <p class="cmty-text">
                    {{ $exam->description }}
                </p>
                <span>duration_minutes: {{ $exam->duration_minutes }}</span> |
                <span>attempts_allowed: {{ $exam->attempts_allowed }}</span>
                <div>
                    <span>total_grade :{{ $exam->total_grade }}</span>
                    <span>passing_grade :{{ $exam->passing_grade }}</span>
                </div>


                @if(!$result && !$current_attempts->count() && $can_add_attempt)
                <div class="cmty-actions">
                    <form action="{{route('start.exam',['exam'=>$exam->id,'slug'=>$exam->slug])}}" method='post'>
                        @CSRF
                        <button type='submit' class="cmty-like"> @if($attempts->count()) try again @else start @endif</button>
                    </form>
                </div>
                @endif

            </article>
        @endif

        @if($result)
            <article class="cmty-post cmty-post--outlined">
                <header class="cmty-head">
                    {!! $result->passed() !!}
                    <img class="cmty-mark" data-src="{{ asset('assets_front/images/community-logo1.png') }}" alt="">
                </header>

                <span>started_at : {{ $result->started_at }}</span> -
                <span>submitted_at : {{ $result->submitted_at }}</span> -
                <span>score : {{ $result->score }}</span> - <br>
                <span>percentage : {{ $result->percentage }}</span> -
                <span>is_passed : {!! $result->passed() !!}</span> -

                @if(!$current_attempts->count() && $can_add_attempt)
                <div class="cmty-actions">
                    <form action="{{route('start.exam',['exam'=>$exam->id,'slug'=>$exam->slug])}}" method='post'>
                        @CSRF
                        <button type='submit' class="cmty-like"> @if($attempts->count()) try again @else start @endif</button>
                    </form>
                </div>
                @endif

            </article>
        @endif

        @if($current_attempt && $question)
            <article class="cmty-post cmty-post--outlined">
            <header class="cmty-head">
                <div class="cmty-user">
                    <div>
                        <h4>{{$question->title}}</h4>
                    </div>
                </div>
                <img class="cmty-mark" data-src="{{ asset('assets_front/images/community-logo1.png') }}" alt="">
            </header>

            <p class="cmty-text">
                {{$question->question}}
            </p>
            <sapn> {{$question->explanation_en}} </sapn> <br>

            <span>question nm : {{$question_nm}}</span>
            <span>grade : {{$question->grade}}</span> <br>

            <form  class="@if($question->type != 'multiple_choice') cmty-actions @endif"
               action="{{route('answer.question',['exam'=>$exam->id,'question'=>$question->id])}}" method='post'>
                @CSRF
                @if($question->type === 'essay')
                  <input name='answer' class="cmty-input" type="text" placeholder="">
                @elseif($question->type === 'true_false')
                  <input type="radio" id="answer1" name='answer' value='true'>
                  <label for="answer1">true</label><br>
                  <input type="radio" id="answer2" name='answer' value='false'>
                  <label for="answer2">false</label><br>
                @elseif($question->type === 'multiple_choice')
                    <br>

                   @php $multiple_choices = $question->options()->orderBy('order','asc')->get(); @endphp
                   @foreach($multiple_choices as $option)
                    <input type="checkbox" name="answer[]" id="option_{{$option->id}}" value="{{$option->id}}">
                    <label for="option_{{$option->id}}"> {{$option->option}}</label>
                    <br>
                   @endforeach
                @endif
                <br>
                <button type='submit' class="cmty-like">answer</button>
            </form>

            </article>
        @endif

        @if($current_attempt)
               <!-- <div class="pagination-wrapper"> -->
                    {{ $questions?->links('pagination::custom-bootstrap-5') ?? '' }}
                <!-- </div> -->

        @endif


    </div>

</section>

@endsection
