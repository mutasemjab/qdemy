@extends('layouts.app')
@section('title', __('panel.my_account'))
@section('content')
    <section class="ud-wrap">

        <aside class="ud-menu">
            <div class="ud-user">
                <img data-src="{{ $user->photo ? asset('assets/admin/uploads/' . $user->photo) : asset('assets_front/images/avatar-big.png') }}"
                    alt="">
                <div>
                    <h3>{{ $user->name }}</h3>
                    <span>{{ $user->email }}</span>
                </div>
            </div>

            <button class="ud-item active" data-target="profile"><i
                    class="fa-regular fa-user"></i><span>{{ __('panel.personal_profile') }}</span><i
                    class="fa-solid fa-angle-left"></i></button>
            <button class="ud-item" data-target="settings"><i
                    class="fa-solid fa-gear"></i><span>{{ __('panel.account_settings') }}</span><i
                    class="fa-solid fa-angle-left"></i></button>
            <button class="ud-item" data-target="notifications"><i
                    class="fa-regular fa-bell"></i><span>{{ __('panel.notifications') }}</span><i
                    class="fa-solid fa-angle-left"></i></button>
            <button class="ud-item" data-target="inbox"><i
                    class="fa-regular fa-comments"></i><span>{{ __('panel.messages') }}</span><i
                    class="fa-solid fa-angle-left"></i></button>
            <button class="ud-item" data-target="courses"><i
                    class="fa-solid fa-graduation-cap"></i><span>{{ __('panel.my_courses') }}</span><i
                    class="fa-solid fa-angle-left"></i></button>
            <button class="ud-item" data-target="results"><i
                    class="fa-solid fa-square-poll-vertical"></i><span>{{ __('panel.my_results') }}</span><i
                    class="fa-solid fa-angle-left"></i></button>
    
            <button class="ud-item" data-target="community"><i
                    class="fa-solid fa-magnifying-glass"></i><span>{{ __('panel.q_community') }}</span><i
                    class="fa-solid fa-angle-left"></i></button>
            <button class="ud-item" data-target="support"><i
                    class="fa-brands fa-whatsapp"></i><span>{{ __('panel.technical_support') }}</span><i
                    class="fa-solid fa-angle-left"></i></button>

            <a href="{{ route('user.logout') }}" class="ud-logout"><i
                    class="fa-solid fa-arrow-left-long"></i><span>{{ __('panel.logout') }}</span></a>
        </aside>

        <div class="ud-content">

            <div class="ud-panel show" id="profile">
                <div class="ud-title">{{ __('panel.personal_profile') }}</div>
                <div class="ud-profile-head">
                    <img data-src="{{ $user->photo ? asset('assets/admin/uploads/' . $user->photo) : asset('assets_front/images/avatar-round.png') }}"
                        class="ud-ava" alt="">
                    <div class="ud-name">
                        <h2>{{ $user->name }}<br><span class="g-sub1">{{ $user->email }}</span></h2>
                    </div>
                </div>
                <div class="ud-formlist">
                    <div class="ud-row">
                        <div class="ud-key">{{ __('panel.name') }}</div>
                        <div class="ud-val">{{ $user->name }}</div>
                    </div>
                    <div class="ud-row">
                        <div class="ud-key">{{ __('panel.email') }}</div>
                        <div class="ud-val">{{ $user->email }}</div>
                    </div>
                    <div class="ud-row">
                        <div class="ud-key">{{ __('panel.phone') }}</div>
                        <div class="ud-val">{{ $user->phone }}</div>
                    </div>
                    <div class="ud-row">
                        <div class="ud-key">{{ __('panel.grade') }}</div>
                        <div class="ud-val">{{ __('panel.grade') }}
                            {{ $user->clas_id ? 'الـ' . $user->clas_id : 'غير محدد' }}</div>
                    </div>
                </div>
            </div>

       <div class="ud-panel" id="settings">
    <div class="ud-title">{{ __('panel.account_settings') }}</div>
    <form method="POST" action="{{ route('teacher.update.account') }}" enctype="multipart/form-data">
        @csrf
        <div class="ud-profile-head">
            <div class="ud-ava-wrap">
                <!-- صورة البروفايل -->
                <img id="preview-image"
                    src="{{ $user->photo ? asset('assets/admin/uploads/' . $user->photo) : asset('assets_front/images/avatar-round.png') }}"
                    class="ud-ava" alt="">

                <!-- زر التعديل -->
                <label class="ud-ava-edit">
                    <i class="fa-solid fa-pen"></i>
                    <input type="file" id="avatarInput" name="photo" accept="image/*" style="display:none">
                </label>
            </div>

            <div class="ud-name">
                <h2>{{ $user->name }}<br><span class="g-sub1">{{ $user->email }}</span></h2>
            </div>
        </div>

        <div class="ud-edit">
            <!-- Basic User Fields -->
            <label>{{ __('panel.name') }}
                <input type="text" name="name" value="{{ old('name', $user->name) }}">
            </label>

            <label>{{ __('panel.email') }}
                <input type="email" name="email" value="{{ old('email', $user->email) }}">
            </label>

            <label>{{ __('panel.phone') }}
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}">
            </label>

            <!-- Teacher Specific Fields -->
            <label>{{ __('panel.lesson_name') }}
                <input type="text" name="name_of_lesson" value="{{ old('name_of_lesson', $teacher->name_of_lesson ?? '') }}">
            </label>

            <label>{{ __('panel.description_en') }}
                <textarea name="description_en" rows="4">{{ old('description_en', $teacher->description_en ?? '') }}</textarea>
            </label>

            <label>{{ __('panel.description_ar') }}
                <textarea name="description_ar" rows="4">{{ old('description_ar', $teacher->description_ar ?? '') }}</textarea>
            </label>

            <!-- Social Media Links -->
            <div class="social-links">
                <h3>{{ __('panel.social_media') }}</h3>
                
                <label>{{ __('panel.facebook') }}
                    <input type="url" name="facebook" value="{{ old('facebook', $teacher->facebook ?? '') }}" placeholder="https://facebook.com/username">
                </label>

                <label>{{ __('panel.instagram') }}
                    <input type="url" name="instagram" value="{{ old('instagram', $teacher->instagram ?? '') }}" placeholder="https://instagram.com/username">
                </label>

                <label>{{ __('panel.youtube') }}
                    <input type="url" name="youtube" value="{{ old('youtube', $teacher->youtube ?? '') }}" placeholder="https://youtube.com/channel/username">
                </label>

                <label>{{ __('panel.whatsapp') }}
                    <input type="text" name="whatsapp" value="{{ old('whatsapp', $teacher->whatsapp ?? '') }}" placeholder="+1234567890">
                </label>
            </div>

            <button class="ud-primary" type="submit">{{ __('panel.save') }}</button>
        </div>
    </form>
</div>


            

            <!-- Include other panels (notifications, inbox, courses, schedule, results, offers, wallet, community, support) -->
            @include('panel.common.notifications')
            @include('panel.common.inbox')
         
            @include('panel.teacher.community')
            @include('panel.common.support')

        </div>
    </section>
@endsection

@section('scripts')
  <!-- JavaScript لمعاينة الصورة -->
 <script>
     document.getElementById('avatarInput').addEventListener('change', function(event) {
         const file = event.target.files[0];
         if (file) {
             const reader = new FileReader();

             reader.onload = function(e) {
                 document.getElementById('preview-image').setAttribute('src', e.target.result);
             };

             reader.readAsDataURL(file);
         }
     });
 </script>
@endsection