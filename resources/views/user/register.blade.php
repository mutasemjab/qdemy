@php $hideFooter = true; @endphp
@extends('layouts.app')

@section('title', 'تسجيل حساب')

@section('content')
<section class="auth-page">
    <div class="auth-overlay"></div>

    <div class="auth-content-wrapper">
        <div class="auth-info-box">
            <img data-src="{{ asset('assets_front/images/logo-white.png') }}" alt="Qdemy Logo">
            <h2>ابدأ في بناء مستقبلك</h2>
            <p>منصة تعليمية تساعدك على التعلم والتطور من أي مكان وفي أي وقت</p>
        </div>

        <div class="auth-form-box">
            <p class="welcome-text">مرحباً بك في <strong>Qdemy</strong></p>
            <h3>تسجيل حساب جديد</h3>

            <div class="account-type">
                <button id='student_account' class="active account_type">حساب طالب</button>
                <button id='parent_account'  class="account_type">حساب ولي أمر</button>
            </div>

            <form method='post' action="{{ route('user.register.submit') }}">
                @csrf
                <input  type="hidden"   value="{{old('student') ?? 'student'}}" id="role_name" name="role_name">
                @error('role_name')<span class="text-danger">{{ $message }}</span>@enderror
                <input  value="{{old('name') }}" name="name"    type="text"     placeholder="الاسم الكامل">
                @error('name')<span class="text-danger">{{ $message }}</span>@enderror
                <input  value="{{old('phone') }}" name="phone"    type="phone"    placeholder="رقم الهاتف">
                @error('phone')<span class="text-danger">{{ $message }}</span>@enderror
                <input  value="{{old('email') }}" name="email"    type="email"    placeholder="إيميل">
                @error('email')<span class="text-danger">{{ $message }}</span>@enderror
                <input  value="{{old('password') }}" name="password" type="password" placeholder="كلمة المرور">
                @error('password')<span class="text-danger">{{ $message }}</span>@enderror
                <select name="grade"    class="grade-select">
                    <option disabled selected>اختر الصف</option>
                    <option {{old('grade') == 1 ? 'selected' : ''  }} value="1">الصف الأول</option>
                    <option {{old('grade') == 2 ? 'selected' : ''  }} value="2">الصف الثاني</option>
                    <option {{old('grade') == 3 ? 'selected' : ''  }} value="3">الصف الثالث</option>
                    <option {{old('grade') == 4 ? 'selected' : ''  }} value="4">الصف الرابع</option>
                    <option {{old('grade') == 5 ? 'selected' : ''  }} value="5">الصف الخامس</option>
                    <option {{old('grade') == 6 ? 'selected' : ''  }} value="6">الصف السادس</option>
                    <option {{old('grade') == 7 ? 'selected' : ''  }} value="7">الصف السابع</option>
                    <option {{old('grade') == 8 ? 'selected' : ''  }} value="8">الصف الثامن</option>
                    <option {{old('grade') == 9 ? 'selected' : ''  }} value="9">الصف التاسع</option>
                </select>
                @error('grade')<span class="text-danger">{{ $message }}</span>@enderror

                <div class="student-info">
                    <img data-src="{{ asset('assets_front/images/register-icon.png') }}">
                    <div>
                        <h4>خالد أحمد</h4>
                        <span>0098978787</span>
                    </div>
                    <span class="remove">×</span>
                </div>

                <button class="submit-btn" type="submit">إنشاء حساب</button>
            </form>

            <p class="login-link">لديك حساب؟ <a href="#">سجل دخول</a></p>
        </div>
    </div>
</section>
<script>
    const roleName       = document.getElementById('role_name');
    const studentAccount = document.getElementById('student_account');
    const parentAccount  = document.getElementById('parent_account');
    // إضافة مستمعي الأحداث لكل زر
    studentAccount.addEventListener('click', () => toggleActiveClass(studentAccount, parentAccount,'student'));
    parentAccount.addEventListener('click', () => toggleActiveClass(parentAccount, studentAccount,'parent'));
    // دالة التبديل
    function toggleActiveClass(clickedButton, otherButton ,role_name = 'student') {
        roleName.value = role_name;
        clickedButton.classList.add('active');
        otherButton.classList.remove('active');
    }

</script>
@endsection
