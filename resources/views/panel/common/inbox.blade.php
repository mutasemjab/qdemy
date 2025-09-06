<div class="ud-panel" id="inbox">
  <div class="ud-title">{{ __('panel.messages') }}</div>
  <div class="ud-inbox">
    <div class="ud-threads">
      @foreach([['سالم أحمد',3],['محمد علي',0],['فاطمة أحمد',1]] as [$n,$c])
        <button class="ud-thread{{ $loop->first?' active':'' }}">
          <div class="ud-thread-user">
            <img data-src="{{ asset('assets_front/images/uc'.($loop->index+1).'.png') }}">
            <div><b>{{ $n }}</b><small>قبل {{ rand(5, 60) }} دقيقة</small></div>
          </div>
          @if($c)<span class="ud-pill">{{ $c }}</span>@endif
        </button>
      @endforeach
    </div>
    <div class="ud-chat">
      <div class="ud-chat-flow" id="udChat">
        <div class="msg from"><span>مرحباً، كيف حالك؟</span></div>
        <div class="msg to"><span>بخير والحمد لله، كيف الدراسة؟</span></div>
        <div class="msg from"><span>جيدة، أحتاج مساعدة في الرياضيات</span></div>
        <div class="msg to"><span>طبعاً، ما المشكلة تحديداً؟</span></div>
      </div>
      <div class="ud-chat-box">
        <input type="text" placeholder="اكتب رسالة">
        <button class="ud-primary ud-send"><i class="fa-solid fa-paper-plane"></i></button>
      </div>
    </div>
  </div>
</div>