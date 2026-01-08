@extends('layouts.app')
@section('title','حسابي')
@section('content')
<section class="ud-wrap">

  <aside class="ud-menu">
    <div class="ud-user">
      <img data-src="{{ asset('assets_front/images/avatar-big.png') }}" alt="">
      <div>
        <h3>خالد أحمد</h3>
        <span>khaledahmed@gmail.com</span>
      </div>
    </div>

    <button class="ud-item active" data-target="profile"><i class="far fa-user"></i><span>حسابي الشخصي</span><i class="fas fa-angle-left"></i></button>
    <button class="ud-item" data-target="settings"><i class="fas fa-gear"></i><span>إعدادات الحساب</span><i class="fas fa-angle-left"></i></button>
    <button class="ud-item" data-target="notifications"><i class="far fa-bell"></i><span>الإشعارات</span><i class="fas fa-angle-left"></i></button>
    <button class="ud-item" data-target="inbox"><i class="far fa-comments"></i><span>الرسائل</span><i class="fas fa-angle-left"></i></button>
    <button class="ud-item" data-target="courses"><i class="fas fa-graduation-cap"></i><span>دوراتي</span><i class="fas fa-angle-left"></i></button>
    <button class="ud-item" data-target="schedule"><i class="far fa-calendar-days"></i><span>الجدول الزمني</span><i class="fas fa-angle-left"></i></button>
    <button class="ud-item" data-target="kids"><i class="fas fa-children"></i><span>الأبناء وإنجازاتهم</span><i class="fas fa-angle-left"></i></button>
    <button class="ud-item" data-target="offers"><i class="fas fa-tags"></i><span>العروض</span><i class="fas fa-angle-left"></i></button>
    <button class="ud-item" data-target="students"><i class="fas fa-user-group"></i><span>الطلاب</span><i class="fas fa-angle-left"></i></button>
    <button class="ud-item" data-target="wallet"><i class="far fa-wallet"></i><span>المحفظة</span><i class="fas fa-angle-left"></i></button>
    <button class="ud-item" data-target="results"><i class="fas fa-square-poll-vertical"></i><span>نتائج اختباراتي</span><i class="fas fa-angle-left"></i></button>
    <button class="ud-item" data-target="community"><i class="fas fa-magnifying-glass"></i><span>مجتمع Q</span><i class="fas fa-angle-left"></i></button>
    <button class="ud-item" data-target="support"><i class="fab fa-whatsapp"></i><span>الدعم الفني</span><i class="fas fa-angle-left"></i></button>

    <a href="#" class="ud-logout"><i class="fas fa-arrow-left-long"></i><span>تسجيل خروج</span></a>
  </aside>

  <div class="ud-content">

    <div class="ud-panel show" id="profile">
      <div class="ud-title">حسابي الشخصي</div>
      <div class="ud-profile-head">
        <img data-src="{{ asset('assets_front/images/avatar-round.png') }}" class="ud-ava" alt="">
        <div class="ud-name">
          <h2>خالد أحمد<br><span class="g-sub1" >khaledahmed@gmail.com</span></h2>
        </div>
      </div>
      <div class="ud-formlist">
        <div class="ud-row"><div class="ud-key">الاسم</div><div class="ud-val">خالد أحمد</div></div>
        <div class="ud-row"><div class="ud-key">الإيميل</div><div class="ud-val">khaledahmed@gmail.com</div></div>
        <div class="ud-row"><div class="ud-key">الهاتف</div><div class="ud-val">0799879876</div></div>
      </div>
    </div>

    <div class="ud-panel" id="settings">
      <div class="ud-title">إعدادات الحساب</div>
            <div class="ud-profile-head">
                <div class="ud-ava-wrap">
                    <img data-src="{{ asset('assets_front/images/avatar-round.png') }}" class="ud-ava" alt="">
                    <label class="ud-ava-edit">
                        <i class="fas fa-pen"></i>
                        <input type="file" name="avatar" accept="image/*" style="display:none">
                    </label>
                </div>
                <div class="ud-name">
                    <h2>خالد أحمد<br><span class="g-sub1">khaledahmed@gmail.com</span></h2>
                </div>
            </div>

      <div class="ud-edit">
        <label>الاسم<input type="text" value="خالد أحمد"></label>
        <label>الإيميل<input type="email" value="khaledahmed@gmail.com"></label>
        <label>الهاتف<input type="text" value="0799879876"></label>
        <button class="ud-primary">حفظ</button>
      </div>
    </div>

    <div class="ud-panel" id="notifications">
      <div class="ud-title">الإشعارات</div>
      <div class="ud-list">
        @for($i=0;$i<5;$i++)
          <div class="ud-note">
            <div class="ud-note-main">
              <b>تم رفع مادة الفيزياء الكونية</b>
              <small>يمكنك شراء البطاقة الآن لمشاهدة المادة</small>
            </div>
            <span class="ud-badge">جديد</span>
          </div>
        @endfor
      </div>
    </div>

    <div class="ud-panel" id="inbox">
      <div class="ud-title">الرسائل</div>
      <div class="ud-inbox">
        <div class="ud-threads">
          @foreach([['سالم أحمد',3],['محمد',0],['أحمد',0]] as [$n,$c])
            <button class="ud-thread{{ $loop->first?' active':'' }}">
              <div class="ud-thread-user">
                <img data-src="{{ asset('assets_front/images/uc'.($loop->index+1).'.png') }}">
                <div><b>{{ $n }}</b><small>قبل 12 دقيقة</small></div>
              </div>
              @if($c)<span class="ud-pill">{{ $c }}</span>@endif
            </button>
          @endforeach
        </div>
        <div class="ud-chat">
          <div class="ud-chat-flow" id="udChat">
            <div class="msg from"><span>كيفك؟</span></div>
            <div class="msg to"><span>تمام، كيف تحضيراتك للامتحان؟</span></div>
            <div class="msg from"><span>أي عارف على أتم الاستعداد</span></div>
          </div>
          <div class="ud-chat-box">
            <input type="text" placeholder="اكتب رسالة">
            <button class="ud-primary ud-send"><i class="fas fa-paper-plane"></i></button>
          </div>
        </div>
      </div>
    </div>

        <div class="ud-panel" id="courses">
        <div class="ud-title">{{__('front.courses')}}</div>
        <div class="ud-courses">
            @foreach($userCourses as $course)
            <a href="{{route('course',['course'=>$course->id,'slug'=>$course->slug])}}" class="ud-course">
                <div class="ud-course-meta">
                <h3>{{ $course->title }} <br>
                <span class="ud-course-meta-sub">
                    @if($course->subject) {{ $course->subject->localized_name }} @endif
                    @if($course->subject?->semester) - {{ $course->subject?->semester->localized_name }} @endif
                </span></h3>
                <div class="ud-course-teacher">
                    <img data-src="{{$course->teacher?->photo_url}}"><span>{{$course->teacher?->name}}</span>
                </div>
                </div>
                <div class="ud-course-date">
                <small>{{ $course->course_user?->created_at }}</small>
                </div>
            </a>
            @endforeach
        </div>
        </div>


    <div class="ud-panel" id="schedule">
      <div class="ud-title">{{ translate_lang('courses progress') }}</div>
      <div class="ud-bars">
        @foreach($userCourses as $index => $course)
          <div class="ud-bar">
            <div class="ud-bar-head"><b>{{ $course->title }}</b>
            @if($course->subject) <small> {{ $course->subject->localized_name }} </small> @endif
            @if($course->subject?->semester) <small> - {{ $course->subject?->semester->localized_name }} </small> @endif
           </div>
           @php $courseProgress = $course->calculateCourseProgress()['total_progress'] ?? 0; @endphp
           @if($courseProgress)
           <div class="ud-bar-track"><span style="width:{{ $courseProgress }}%"></span></div>
           <div class="ud-bar-foot">100%<b>{{ number_format($courseProgress, 1, '.', '') }}%
           </b></div>
           @else
           <div class="ud-bar-track"><span style="width:0%"></span></div>
           <div class="ud-bar-foot">100%<b>0% 
           </b></div>
           @endif
          </div>
        @endforeach
      </div>
    </div>

    <div class="ud-panel" id="kids">
      <div class="ud-title">الأبناء وإنجازاتهم</div>
      <div class="ud-kid-head">
        <img data-src="{{ asset('assets_front/images/kid.png') }}">
        <div>
          <h2>خالد أحمد<br><span class="g-sub1" >khaledahmed@gmail.com</span></h2>
        </div>
      </div>
      <div class="ud-bars">
        @foreach([['اللغة العربية',25],['اللغة الإنجليزية',35],['اللغة العربية',25],['اللغة العربية',25]] as [$t,$p])
          <div class="ud-bar">
            <div class="ud-bar-head"><b>{{ $t }}</b><small>(الفصل الأول)</small></div>
            <div class="ud-bar-track"><span style="width:{{ $p }}%"></span></div>
            <div class="ud-bar-foot">100%<b>{{ $p }}%</b></div>
          </div>
        @endforeach
      </div>
    </div>

    <div class="ud-panel" id="offers">
      <div class="ud-title">العروض</div>
      <div class="ud-offers">
        @foreach(['البكج الأول','#3488FC','البكج الثاني','#0055D2','البكج الثالث','#0055D2'] as $i => $v)
          @if($loop->odd)
            @php $title=$v; $color=$loop->iteration==1?'#0055D2':'#3488FC'; @endphp
          @else
            @php $title=$i; $color=$v; @endphp
          @endif
          <div class="ud-offer">
            <a class="ud-offer-pill" style="background:{{ $color }}">{{ $title }}</a>
            <div class="ud-offer-body">
              <h3>بكج المادة الواحدة <small>2009</small></h3>
              <div class="ud-tags">
                <span>تاريخ الأردن</span><span>التربية الإسلامية</span><span>اللغة الإنجليزية</span><span>اللغة العربية</span>
              </div>
            </div>
            <div class="ud-offer-price">30 JOD</div>
            <a href="#" class="ud-ghost">شراء/تفعيل البطاقة</a>
          </div>
        @endforeach
      </div>
    </div>

    <div class="ud-panel" id="students">
      <div class="ud-title">الطلاب <small>50 طالب</small></div>
      <div class="ud-grid">
        @for($i=0;$i<16;$i++)
          <a href="#" class="ud-stu">
            <img data-src="{{ asset('assets_front/images/us'.(($i%4)+1).'.png') }}">
            <span>خالد أحمد</span>
          </a>
        @endfor
      </div>
    </div>

    <div class="ud-panel" id="wallet">
      <div class="ud-title">المحفظة</div>
      <div class="ud-wallet">
        <div class="ud-card">
          <small>Balance</small>
          <h2>2,354 JOD</h2>
        </div>
        <div class="ud-trans">
          @for($i=0;$i<2;$i++)
            <div class="ud-tr">
              <div class="ud-amt {{ $i? 'pos':'neg' }}">{{ $i?'+':'-' }}30 JOD</div>
            <div style="display: flex; flex-direction: column;text-align: left;">
            <b>Pay out</b>
            <small>Dec 07, 2024 - 10:45 AM</small>
            <small>RF: 35567867443</small>
            </div>

            </div>
          @endfor
        </div>
      </div>
    </div>

    <div class="ud-panel" id="results">
      <div class="ud-title">{{ translate_lang('my exam results') }}</div>
      <div class="ud-results">
        @foreach($userExamsResults as $result)
          <a href="{{route('course',['course'=>$course->id,'slug'=>$course->slug])}}" class="ud-res">
            <b>{{ $result->exam->title }}</b>
            <span class="ud-res-score">{{ $result->score }}/{{ $result->exam->total_grade }}</span>
          </a>
        @endforeach
      </div>
    </div>

    <div class="ud-panel" id="community">
      <div class="ud-title">مجتمع Q</div>
      <div class="ud-community">
        <div class="ud-postbox">
          <div class="ud-post-head">
            <img data-src="{{ asset('assets_front/images/avatar-round.png') }}"><b>خالد أحمد</b>
          </div>
          <textarea placeholder="اكتب سؤالك أو منشورك هنا"></textarea>
          <div class="ud-post-actions">
            <button class="ud-primary">Post</button>
          </div>
        </div>

        <div class="ud-feed">
          <div class="ud-post">
            <div class="ud-post-top">
              <div class="ud-post-user">
                <img data-src="{{ asset('assets_front/images/avatar-round.png') }}">
                <div><b>خالد أحمد</b><br><small>8:45 AM · Sep 1, 2022</small></div>
              </div>
              <img data-src="{{ asset('assets_front/images/qmark.png') }}" class="ud-q">
            </div>
            <p>رفعت مواد التربية الثقافية على المنصة لمن يرغب يطلب البطاقة الخاصة بها.</p>
          </div>
        </div>
      </div>
    </div>

    <div class="ud-panel" id="support">
      <div class="ud-title">الدعم الفني</div>
      <div class="ud-support">
        <p>لأي مشاكل تقنية تواجهك يرجى مراسلتنا عبر الواتساب على الرقم التالي</p>
        <a href="https://wa.me/09898756567876" class="ud-wh"><i class="fab fa-whatsapp"></i> 09898756567876</a>
      </div>
    </div>

  </div>
</section>
@endsection
