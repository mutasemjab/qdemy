<div class="ud-panel" id="community">
  <div class="ud-title">{{ __('panel.q_community') }}</div>
  <div class="ud-community">
    <div class="ud-postbox">
      <div class="ud-post-head">
        <img data-src="{{ auth()->user()->photo ? asset('storage/'.auth()->user()->photo) : asset('assets_front/images/avatar-round.png') }}">
        <b>{{ auth()->user()->name }}</b>
      </div>
      <textarea placeholder="اكتب سؤالك أو منشورك هنا"></textarea>
      <div class="ud-post-actions">
        <button class="ud-primary">نشر</button>
      </div>
    </div>

    <div class="ud-feed">
      @for($i=0;$i<3;$i++)
      <div class="ud-post">
        <div class="ud-post-top">
          <div class="ud-post-user">
            <img data-src="{{ asset('assets_front/images/uc'.($i+1).'.png') }}">
            <div><b>{{ ['أحمد محمد', 'فاطمة علي', 'سالم أحمد'][$i] }}</b><br><small>{{ now()->subHours($i+1)->format('h:i A · M d, Y') }}</small></div>
          </div>
          <img data-src="{{ asset('assets_front/images/qmark.png') }}" class="ud-q">
        </div>
        <p>{{ ['هل يمكن شرح المعادلات التربيعية بطريقة أبسط؟', 'ما هي أفضل طريقة لحفظ المفردات الإنجليزية؟', 'أحتاج مساعدة في مسائل الفيزياء'][$i] }}</p>
        <div class="ud-post-actions">
          <button><i class="fa-regular fa-heart"></i> {{ rand(5, 25) }}</button>
          <button><i class="fa-regular fa-comment"></i> {{ rand(2, 10) }}</button>
        </div>
      </div>
      @endfor
    </div>
  </div>
</div>