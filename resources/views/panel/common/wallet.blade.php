<div class="ud-panel" id="wallet">
  <div class="ud-title">{{ __('panel.wallet') }}</div>
  <div class="ud-wallet">
    <div class="ud-card">
      <small>الرصيد المتاح</small>
      <h2>{{ rand(100, 500) }} دينار</h2>
    </div>
    <div class="ud-trans">
      @for($i=0;$i<4;$i++)
        <div class="ud-tr">
          <div class="ud-amt {{ $i % 2 ? 'pos':'neg' }}">{{ $i % 2 ? '+':'-' }}{{ [30, 50, 25, 75][$i] }} دينار</div>
          <div style="display: flex; flex-direction: column;text-align: left;">
            <b>{{ $i % 2 ? 'إيداع' : 'دفع رسوم' }}</b>
            <small>{{ now()->subDays($i)->format('M d, Y - h:i A') }}</small>
            <small>المرجع: {{ rand(10000000, 99999999) }}</small>
          </div>
        </div>
      @endfor
    </div>
  </div>
</div>