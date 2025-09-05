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
    <button class="ud-item" data-target="courses"><i class="fa-solid fa-graduation-cap"></i><span>{{ __('panel.course_management') }}</span><i class="fa-solid fa-angle-left"></i></button>
    <button class="ud-item" data-target="students"><i class="fa-solid fa-user-group"></i><span>{{ __('panel.students') }}</span><i class="fa-solid fa-angle-left"></i></button>
    <button class="ud-item" data-target="class-schedule"><i class="fa-regular fa-calendar-days"></i><span>{{ __('panel.class_schedule') }}</span><i class="fa-solid fa-angle-left"></i></button>
    <button class="ud-item" data-target="attendance"><i class="fa-solid fa-user-check"></i><span>{{ __('panel.attendance') }}</span><i class="fa-solid fa-angle-left"></i></button>
    <button class="ud-item" data-target="grade-assignments"><i class="fa-solid fa-clipboard-check"></i><span>{{ __('panel.grade_assignments') }}</span><i class="fa-solid fa-angle-left"></i></button>
    <button class="ud-item" data-target="student-reports"><i class="fa-solid fa-chart-line"></i><span>{{ __('panel.student_reports') }}</span><i class="fa-solid fa-angle-left"></i></button>
    <button class="ud-item" data-target="create-course"><i class="fa-solid fa-plus"></i><span>{{ __('panel.create_course') }}</span><i class="fa-solid fa-angle-left"></i></button>
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
        <div class="ud-row"><div class="ud-key">نوع الحساب</div><div class="ud-val">معلم</div></div>
        <div class="ud-row"><div class="ud-key">عدد الطلاب</div><div class="ud-val">{{ rand(50, 150) }} طالب</div></div>
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
        <label>التخصص<input type="text" value="الرياضيات"></label>
        <button class="ud-primary">{{ __('panel.save') }}</button>
      </div>
    </div>

    <!-- Course Management Panel -->
    <div class="ud-panel" id="courses">
      <div class="ud-title">{{ __('panel.course_management') }}</div>
      <div class="ud-teacher-courses">
        @for($i=0;$i<4;$i++)
        <div class="ud-course-card">
          <div class="ud-course-header">
            <h3>{{ ['الرياضيات', 'الفيزياء', 'الكيمياء', 'الأحياء'][$i] }} - الصف {{ $i + 6 }}</h3>
            <div class="ud-course-actions">
              <button class="ud-btn-small">تعديل</button>
              <button class="ud-btn-small ud-danger">حذف</button>
            </div>
          </div>
          <div class="ud-course-stats">
            <span>{{ rand(20, 40) }} طالب</span>
            <span>{{ rand(8, 16) }} درس</span>
            <span>{{ rand(3, 8) }} واجب</span>
          </div>
        </div>
        @endfor
      </div>
    </div>

    <!-- Students Panel -->
    <div class="ud-panel" id="students">
      <div class="ud-title">{{ __('panel.students') }} <small>{{ rand(50, 150) }} طالب</small></div>
      <div class="ud-students-grid">
        @for($i=0;$i<16;$i++)
          <div class="ud-student-card">
            <img data-src="{{ asset('assets_front/images/us'.(($i%4)+1).'.png') }}">
            <div class="ud-student-info">
              <h4>{{ ['أحمد محمد', 'فاطمة علي', 'خالد أحمد', 'مريم سالم'][$i%4] }}</h4>
              <small>الصف {{ ($i%3) + 7 }}</small>
              <div class="ud-student-grade">{{ rand(70, 95) }}%</div>
            </div>
          </div>
        @endfor
      </div>
    </div>

    <!-- Class Schedule Panel -->
    <div class="ud-panel" id="class-schedule">
      <div class="ud-title">{{ __('panel.class_schedule') }}</div>
      <div class="ud-schedule-table">
        <div class="ud-schedule-header">
          <div>الوقت</div>
          <div>الأحد</div>
          <div>الاثنين</div>
          <div>الثلاثاء</div>
          <div>الأربعاء</div>
          <div>الخميس</div>
        </div>
        @foreach(['08:00-09:00', '09:00-10:00', '10:00-11:00', '11:00-12:00'] as $time)
        <div class="ud-schedule-row">
          <div class="ud-time">{{ $time }}</div>
          @for($day = 0; $day < 5; $day++)
            <div class="ud-class {{ rand(0,1) ? 'has-class' : '' }}">
              @if(rand(0,1))
                <span>الرياضيات - الصف {{ rand(7,9) }}</span>
              @endif
            </div>
          @endfor
        </div>
        @endforeach
      </div>
    </div>

    <!-- Attendance Panel -->
    <div class="ud-panel" id="attendance">
      <div class="ud-title">{{ __('panel.attendance') }}</div>
      <div class="ud-attendance">
        <div class="ud-attendance-controls">
          <select>
            <option>الرياضيات - الصف 7</option>
            <option>الفيزياء - الصف 8</option>
            <option>الكيمياء - الصف 9</option>
          </select>
          <input type="date" value="{{ date('Y-m-d') }}">
        </div>
        <div class="ud-attendance-list">
          @for($i=0;$i<10;$i++)
          <div class="ud-attendance-item">
            <img data-src="{{ asset('assets_front/images/us'.(($i%4)+1).'.png') }}">
            <span>{{ ['أحمد محمد', 'فاطمة علي', 'خالد أحمد', 'مريم سالم'][$i%4] }}</span>
            <div class="ud-attendance-status">
              <label><input type="radio" name="attendance_{{ $i }}" value="present" checked> حاضر</label>
              <label><input type="radio" name="attendance_{{ $i }}" value="absent"> غائب</label>
              <label><input type="radio" name="attendance_{{ $i }}" value="late"> متأخر</label>
            </div>
          </div>
          @endfor
        </div>
        <button class="ud-primary">حفظ الحضور</button>
      </div>
    </div>

    <!-- Grade Assignments Panel -->
    <div class="ud-panel" id="grade-assignments">
      <div class="ud-title">{{ __('panel.grade_assignments') }}</div>
      <div class="ud-assignments-to-grade">
        @for($i=0;$i<6;$i++)
        <div class="ud-assignment-grade">
          <div class="ud-assignment-info">
            <h4>واجب الرياضيات {{ $i + 1 }}</h4>
            <small>مرسل من: {{ ['أحمد محمد', 'فاطمة علي', 'خالد أحمد'][$i%3] }}</small>
            <small>تاريخ التسليم: {{ now()->subDays($i)->format('Y-m-d') }}</small>
          </div>
          <div class="ud-grading-section">
            <input type="number" placeholder="الدرجة" min="0" max="100">
            <textarea placeholder="ملاحظات..."></textarea>
            <button class="ud-primary ud-btn-small">حفظ التقييم</button>
          </div>
        </div>
        @endfor
      </div>
    </div>

    <!-- Student Reports Panel -->
    <div class="ud-panel" id="student-reports">
      <div class="ud-title">{{ __('panel.student_reports') }}</div>
      <div class="ud-reports-grid">
        @for($i=0;$i<4;$i++)
        <div class="ud-report-card">
          <h3>تقرير {{ ['الأسبوع الأول', 'الأسبوع الثاني', 'الأسبوع الثالث', 'الأسبوع الرابع'][$i] }}</h3>
          <div class="ud-report-stats">
            <div class="ud-stat">
              <span class="ud-stat-value">{{ 85 + $i * 2 }}%</span>
              <span class="ud-stat-label">معدل الحضور</span>
            </div>
            <div class="ud-stat">
              <span class="ud-stat-value">{{ 78 + $i * 3 }}%</span>
              <span class="ud-stat-label">معدل الدرجات</span>
            </div>
            <div class="ud-stat">
              <span class="ud-stat-value">{{ 25 + $i * 2 }}</span>
              <span class="ud-stat-label">عدد الطلاب</span>
            </div>
          </div>
          <button class="ud-ghost">عرض التفاصيل</button>
        </div>
        @endfor
      </div>
    </div>

    <!-- Create Course Panel -->
    <div class="ud-panel" id="create-course">
      <div class="ud-title">{{ __('panel.create_course') }}</div>
      <div class="ud-create-course-form">
        <form>
          <label>اسم الدورة<input type="text" placeholder="أدخل اسم الدورة"></label>
          <label>الوصف<textarea placeholder="وصف الدورة..."></textarea></label>
          <label>الصف المستهدف
            <select>
              <option>اختر الصف</option>
              @for($i=1;$i<=9;$i++)
              <option value="{{ $i }}">الصف الـ{{ $i }}</option>
              @endfor
            </select>
          </label>
          <label>تاريخ البداية<input type="date"></label>
          <label>تاريخ النهاية<input type="date"></label>
          <label>السعر<input type="number" placeholder="السعر بالدينار"></label>
          <button class="ud-primary">إنشاء الدورة</button>
        </form>
      </div>
    </div>

    <!-- Include common panels -->
    @include('panel.common.notifications')
    @include('panel.common.inbox')
    @include('panel.common.wallet')
    @include('panel.common.support')

  </div>
</section>
@endsection