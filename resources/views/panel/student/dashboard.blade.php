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
    <button class="ud-item" data-target="courses"><i class="fa-solid fa-graduation-cap"></i><span>{{ __('panel.my_courses') }}</span><i class="fa-solid fa-angle-left"></i></button>
    <button class="ud-item" data-target="schedule"><i class="fa-regular fa-calendar-days"></i><span>{{ __('panel.schedule') }}</span><i class="fa-solid fa-angle-left"></i></button>
    <button class="ud-item" data-target="question-bank"><i class="fa-solid fa-question"></i><span>{{ __('panel.question_bank') }}</span><i class="fa-solid fa-angle-left"></i></button>
    <button class="ud-item" data-target="assignments"><i class="fa-solid fa-tasks"></i><span>{{ __('panel.assignments') }}</span><i class="fa-solid fa-angle-left"></i></button>
    <button class="ud-item" data-target="results"><i class="fa-solid fa-square-poll-vertical"></i><span>{{ __('panel.my_results') }}</span><i class="fa-solid fa-angle-left"></i></button>
    <button class="ud-item" data-target="offers"><i class="fa-solid fa-tags"></i><span>{{ __('panel.offers') }}</span><i class="fa-solid fa-angle-left"></i></button>
    <button class="ud-item" data-target="wallet"><i class="fa-regular fa-wallet"></i><span>{{ __('panel.wallet') }}</span><i class="fa-solid fa-angle-left"></i></button>
    <button class="ud-item" data-target="community"><i class="fa-solid fa-magnifying-glass"></i><span>{{ __('panel.q_community') }}</span><i class="fa-solid fa-angle-left"></i></button>
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
        <div class="ud-row"><div class="ud-key">الصف</div><div class="ud-val">الصف {{ $user->clas_id ? 'الـ'.$user->clas_id : 'غير محدد' }}</div></div>
      </div>
    </div>

    <div class="ud-panel" id="settings">
      <div class="ud-title">{{ __('panel.account_settings') }}</div>
      <div class="ud-profile-head">
        <div class="ud-ava-wrap">
          <img data-src="{{ $user->photo ? asset('assets/admin/uploads/'.$user->photo) : asset('assets_front/images/avatar-round.png') }}" class="ud-ava" alt="">
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

    <!-- Question Bank Panel -->
    <div class="ud-panel" id="question-bank">
      <div class="ud-title">{{ __('panel.question_bank') }}</div>
      <div class="ud-question-bank">
        <div class="ud-subject-grid">
          @foreach(['اللغة العربية', 'اللغة الإنجليزية', 'الرياضيات', 'العلوم'] as $subject)
          <div class="ud-subject-card">
            <h3>{{ $subject }}</h3>
            <p>{{ rand(50, 200) }} سؤال متاح</p>
            <button class="ud-primary">عرض الأسئلة</button>
          </div>
          @endforeach
        </div>
      </div>
    </div>

    <!-- Assignments Panel -->
    <div class="ud-panel" id="assignments">
      <div class="ud-title">{{ __('panel.assignments') }}</div>
      <div class="ud-assignments">
        @for($i=0;$i<4;$i++)
        <div class="ud-assignment">
          <div class="ud-assignment-info">
            <h3>واجب الرياضيات {{ $i + 1 }}</h3>
            <small>تاريخ التسليم: {{ now()->addDays($i + 1)->format('Y-m-d') }}</small>
          </div>
          <div class="ud-assignment-status {{ $i % 2 == 0 ? 'pending' : 'completed' }}">
            {{ $i % 2 == 0 ? 'معلق' : 'مكتمل' }}
          </div>
        </div>
        @endfor
      </div>
    </div>

    <!-- Include other panels (notifications, inbox, courses, schedule, results, offers, wallet, community, support) -->
    @include('panel.common.notifications')
    @include('panel.common.inbox')
    @include('panel.student.courses')
    @include('panel.student.schedule')
    @include('panel.student.results')
    @include('panel.student.offers')
    @include('panel.common.wallet')
    @include('panel.common.community')
    @include('panel.common.support')

  </div>
</section>
@endsection