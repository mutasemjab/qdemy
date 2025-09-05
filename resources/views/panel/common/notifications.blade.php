<div class="ud-panel" id="notifications">
  <div class="ud-title">{{ __('panel.notifications') }}</div>
  <div class="ud-list">
    @for($i=0;$i<5;$i++)
      <div class="ud-note">
        <div class="ud-note-main">
          <b>{{ ['تم رفع مادة جديدة', 'تذكير بموعد الامتحان', 'تم تحديث الدرجات', 'رسالة من المعلم', 'عرض جديد متاح'][$i] }}</b>
          <small>{{ ['يمكنك مشاهدة المادة الآن', 'الامتحان غداً الساعة 10 صباحاً', 'تم رفع درجات الاختبار الأخير', 'يرجى مراجعة الواجب المطلوب', 'خصم 20% على الدورات الجديدة'][$i] }}</small>
        </div>
        <span class="ud-badge">{{ $i < 2 ? 'جديد' : '' }}</span>
      </div>
    @endfor
  </div>
</div>