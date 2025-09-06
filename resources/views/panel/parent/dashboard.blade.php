@extends('layouts.app')
@section('title', __('panel.my_account'))
@section('content')
<section class="ud-wrap">

  <aside class="ud-menu">
    <div class="ud-user">
      <img data-src="{{ $user->photo ? asset('storage/'.$user->photo) : asset('assets_front/images/avatar-big.png') }}" alt="">
      <div>
        <h3>{{ $user->name }}</h3>
        <span>{{ $user->email }}</span>
      </div>
    </div>

    <button class="ud-item active" data-target="profile"><i class="fa-regular fa-user"></i><span>{{ __('panel.personal_profile') }}</span><i class="fa-solid fa-angle-left"></i></button>
    <button class="ud-item" data-target="settings"><i class="fa-solid fa-gear"></i><span>{{ __('panel.account_settings') }}</span><i class="fa-solid fa-angle-left"></i></button>
    <button class="ud-item" data-target="notifications"><i class="fa-regular fa-bell"></i><span>{{ __('panel.notifications') }}</span><i class="fa-solid fa-angle-left"></i></button>
    <button class="ud-item" data-target="inbox"><i class="fa-regular fa-comments"></i><span>{{ __('panel.messages') }}</span><i class="fa-solid fa-angle-left"></i></button>
    <button class="ud-item" data-target="kids"><i class="fa-solid fa-children"></i><span>{{ __('panel.children_achievements') }}</span><i class="fa-solid fa-angle-left"></i></button>
    <button class="ud-item" data-target="child-reports"><i class="fa-solid fa-chart-line"></i><span>{{ __('panel.children_reports') }}</span><i class="fa-solid fa-angle-left"></i></button>
    <button class="ud-item" data-target="payment-history"><i class="fa-solid fa-credit-card"></i><span>{{ __('panel.payment_history') }}</span><i class="fa-solid fa-angle-left"></i></button>
    <button class="ud-item" data-target="add-child"><i class="fa-solid fa-user-plus"></i><span>{{ __('panel.add_child') }}</span><i class="fa-solid fa-angle-left"></i></button>
    <button class="ud-item" data-target="offers"><i class="fa-solid fa-tags"></i><span>{{ __('panel.offers') }}</span><i class="fa-solid fa-angle-left"></i></button>
    <button class="ud-item" data-target="wallet"><i class="fa-regular fa-wallet"></i><span>{{ __('panel.wallet') }}</span><i class="fa-solid fa-angle-left"></i></button>
    <button class="ud-item" data-target="support"><i class="fa-brands fa-whatsapp"></i><span>{{ __('panel.technical_support') }}</span><i class="fa-solid fa-angle-left"></i></button>

    <a href="{{ route('user.logout') }}" class="ud-logout"><i class="fa-solid fa-arrow-left-long"></i><span>{{ __('panel.logout') }}</span></a>
  </aside>

  <div class="ud-content">

    <div class="ud-panel show" id="profile">
      <div class="ud-title">{{ __('panel.personal_profile') }}</div>
      <div class="ud-profile-head">
        <img data-src="{{ $user->photo ? asset('storage/'.$user->photo) : asset('assets_front/images/avatar-round.png') }}" class="ud-ava" alt="">
        <div class="ud-name">
          <h2>{{ $user->name }}<br><span class="g-sub1">{{ $user->email }}</span></h2>
        </div>
      </div>
      <div class="ud-formlist">
        <div class="ud-row"><div class="ud-key">{{ __('panel.name') }}</div><div class="ud-val">{{ $user->name }}</div></div>
        <div class="ud-row"><div class="ud-key">{{ __('panel.email') }}</div><div class="ud-val">{{ $user->email }}</div></div>
        <div class="ud-row"><div class="ud-key">{{ __('panel.phone') }}</div><div class="ud-val">{{ $user->phone }}</div></div>
        <div class="ud-row"><div class="ud-key">نوع الحساب</div><div class="ud-val">ولي أمر</div></div>
      </div>
    </div>

    <div class="ud-panel" id="settings">
      <div class="ud-title">{{ __('panel.account_settings') }}</div>
      <div class="ud-profile-head">
        <div class="ud-ava-wrap">
          <img data-src="{{ $user->photo ? asset('storage/'.$user->photo) : asset('assets_front/images/avatar-round.png') }}" class="ud-ava" alt="">
          <label class="ud-ava-edit">
            <i class="fa-solid fa-pen"></i>
            <input type="file" name="avatar" accept="image/*" style="display:none">
          </label>
        </div>
        <div class="ud-name">
          <h2>{{ $user->name }}<br><span class="g-sub1">{{ $user->email }}</span></h2>
        </div>
      </div>

      <div class="ud-edit">
        <label>{{ __('panel.name') }}<input type="text" value="{{ $user->name }}"></label>
        <label>{{ __('panel.email') }}<input type="email" value="{{ $user->email }}"></label>
        <label>{{ __('panel.phone') }}<input type="text" value="{{ $user->phone }}"></label>
        <button class="ud-primary">{{ __('panel.save') }}</button>
      </div>
    </div>

    <!-- Children Panel -->
    <div class="ud-panel" id="kids">
      <div class="ud-title">{{ __('panel.children_achievements') }}</div>
      <div class="ud-children-list">
        @for($i=0;$i<3;$i++)
        <div class="ud-child-card">
          <div class="ud-kid-head">
            <img data-src="{{ asset('assets_front/images/kid.png') }}">
            <div>
              <h2>أحمد خالد<br><span class="g-sub1">الصف الـ{{ $i + 3 }}</span></h2>
            </div>
          </div>
          <div class="ud-bars">
            @foreach([['اللغة العربية',25+($i*10)],['اللغة الإنجليزية',35+($i*5)],['الرياضيات',45+($i*3)]] as [$t,$p])
              <div class="ud-bar">
                <div class="ud-bar-head"><b>{{ $t }}</b><small>(الفصل الأول)</small></div>
                <div class="ud-bar-track"><span style="width:{{ $p }}%"></span></div>
                <div class="ud-bar-foot">100%<b>{{ $p }}%</b></div>
              </div>
            @endforeach
          </div>
        </div>
        @endfor
      </div>
    </div>

    <!-- Child Reports Panel -->
    <div class="ud-panel" id="child-reports">
      <div class="ud-title">{{ __('panel.children_reports') }}</div>
      <div class="ud-reports">
        @for($i=0;$i<4;$i++)
        <div class="ud-report-card">
          <h3>تقرير أحمد خالد - {{ ['الأسبوع الأول', 'الأسبوع الثاني', 'الأسبوع الثالث', 'الأسبوع الرابع'][$i] }}</h3>
          <div class="ud-report-stats">
            <div class="ud-stat">
              <span class="ud-stat-value">{{ 85 + $i * 2 }}%</span>
              <span class="ud-stat-label">معدل الحضور</span>
            </div>
            <div class="ud-stat">
              <span class="ud-stat-value">{{ 78 + $i * 3 }}%</span>
              <span class="ud-stat-label">معدل الدرجات</span>
            </div>
          </div>
        </div>
        @endfor
      </div>
    </div>

    <!-- Payment History Panel -->
    <div class="ud-panel" id="payment-history">
      <div class="ud-title">{{ __('panel.payment_history') }}</div>
      <div class="ud-payments">
        @for($i=0;$i<5;$i++)
        <div class="ud-payment">
          <div class="ud-payment-info">
            <h3>دفع رسوم {{ ['الفصل الأول', 'الفصل الثاني', 'دورة إضافية', 'كتب', 'رسوم تسجيل'][$i] }}</h3>
            <small>{{ now()->subDays($i * 7)->format('Y-m-d') }}</small>
          </div>
          <div class="ud-payment-amount">{{ [150, 150, 80, 45, 25][$i] }} دينار</div>
          <div class="ud-payment-status success">مدفوع</div>
        </div>
        @endfor
      </div>
    </div>

    <!-- Add Child Panel -->
    <div class="ud-panel" id="add-child">
      <div class="ud-title">{{ __('panel.add_child') }}</div>
      <div class="ud-add-child-form">
        <form>
          <label>اسم الطفل<input type="text" placeholder="أدخل اسم الطفل"></label>
          <label>الصف الدراسي
            <select>
              <option>اختر الصف</option>
              @for($i=1;$i<=9;$i++)
              <option value="{{ $i }}">الصف الـ{{ $i }}</option>
              @endfor
            </select>
          </label>
          <label>تاريخ الميلاد<input type="date"></label>
          <button class="ud-primary">إضافة الطفل</button>
        </form>
      </div>
    </div>

    <!-- Include common panels -->
    @include('panel.common.notifications')
    @include('panel.common.support')

  </div>
</section>
@endsection