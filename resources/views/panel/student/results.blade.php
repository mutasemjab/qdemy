    <div class="ud-panel" id="results">
      <div class="ud-title">{{ translate_lang('my exam results') }}</div>
      <div class="ud-results">
        @foreach($userExamsResults as $result)
          <a href="{{route('exam',['exam'=>$result->exam->id,'slug'=>$result->exam->slug])}}" class="ud-res">
            <b>{{ $result->exam->title }}</b>
            <span class="ud-res-score">{{ $result->score }}/{{ $result->exam->total_grade }}</span>
          </a>
        @endforeach
      </div>
    </div>