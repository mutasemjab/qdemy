<div class="ud-panel" id="offers">
  <div class="ud-title">{{ __('panel.offers') }}</div>
  <div class="ud-offers">
    @foreach(['البكج الأساسي','البكج المتقدم','البكج الشامل'] as $i => $title)
      <div class="ud-offer">
        <a class="ud-offer-pill" style="background:{{ ['#0055D2', '#3488FC', '#00C851'][$i] }}">{{ $title }}</a>
        <div class="ud-offer-body">
          <h3>{{ $title }} <small>{{ date('Y') }}</small></h3>
          <div class="ud-tags">
            <span>{{ ['الرياضيات', 'العلوم', 'اللغات'][$i] }}</span>
            <span>{{ ['3 مواد', '5 مواد', '8 مواد'][$i] }}</span>
          </div>
        </div>
        <div class="ud-offer-price">{{ [30, 50, 80][$i] }} دينار</div>
        <a href="#" class="ud-ghost">شراء البطاقة</a>
      </div>
    @endforeach
  </div>
</div>