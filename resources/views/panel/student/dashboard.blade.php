@extends('layouts.app')
@section('title', __('panel.my_account'))
@section('content')
    <section class="ud-wrap">

        <aside class="ud-menu">
            <div class="ud-user">
                <img data-src="{{ $user->photo_url }}"
                    alt="">
                <div>
                    <h3>{{ $user->name }}</h3>
                    <span>{{ $user->email }}</span>
                </div>
            </div>

            <button class="ud-item active" data-target="profile"><i
                    class="far fa-user"></i><span>{{ __('panel.personal_profile') }}</span><i
                    class="fas fa-angle-left"></i></button>
            <button class="ud-item" data-target="settings"><i
                    class="fas fa-gear"></i><span>{{ __('panel.account_settings') }}</span><i
                    class="fas fa-angle-left"></i></button>
            <button class="ud-item" data-target="notifications"><i
                    class="far fa-bell"></i><span>{{ __('panel.notifications') }}</span><i
                    class="fas fa-angle-left"></i></button>

            <a class="ud-item nav-link {{ request()->routeIs('chat.*') ? 'active' : '' }}" href="{{ route('chat.index') }}">
                <i class="fas fa-comments"></i><span>{{ __('panel.messages') }}</span>
                {{-- Unread messages badge (optional - you can implement this later) --}}
                <span class="badge bg-danger ms-auto" id="unreadCount" style="display: none;">0</span>
                <i class="fas fa-angle-left"></i>
            </a>
            <button class="ud-item" data-target="courses">
                <i class="fas fa-graduation-cap"></i><span>{{ __('panel.my_courses') }}</span>
                <i class="fas fa-angle-left"></i>
            </button>

            <button class="ud-item" data-target="schedule"><i class="far fa-calendar-days"></i><span>الجدول
                    الزمني</span><i class="fas fa-angle-left"></i></button>

            <button class="ud-item" data-target="results"><i
                    class="fas fa-square-poll-vertical"></i><span>{{ __('panel.my_results') }}</span><i
                    class="fas fa-angle-left"></i></button>

            <button class="ud-item" data-target="community"><i
                    class="fas fa-magnifying-glass"></i><span>{{ __('panel.q_community') }}</span><i
                    class="fas fa-angle-left"></i></button>
            <button class="ud-item" data-target="support"><i
                    class="fab fa-whatsapp"></i><span>{{ __('panel.technical_support') }}</span><i
                    class="fas fa-angle-left"></i></button>

            <form action="{{ route('panel.user.logout') }}" method="POST" style="display: inline; width: 100%;">
                @csrf
                <button type="submit" class="ud-logout"
                    style="background: none; border: none; cursor: pointer; width: 100%; text-align: inherit; display: flex; align-items: center; padding: 0;">
                    <i class="fas fa-arrow-left-long"></i>
                    <span>{{ __('panel.logout') }}</span>
                </button>
            </form>
        </aside>

        <div class="ud-content">

            <div class="ud-panel show" id="profile">
                <div class="ud-title">{{ __('panel.personal_profile') }}</div>
                <div class="ud-profile-head">
                    <img data-src="{{ $user->photo_url }}"
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
                <form method="POST" action="{{ route('student.update.account') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Add this error/success messages section -->
                    @if (session('success'))
                        <div class="alert alert-success"
                            style="background: #d4edda; color: #155724; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger"
                            style="background: #f8d7da; color: #721c24; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger"
                            style="background: #f8d7da; color: #721c24; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                            <ul style="margin: 0; padding-right: 20px;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="ud-profile-head">
                        <div class="ud-ava-wrap">
                            <!-- صورة البروفايل -->
                            <img id="preview-image"
                                src="{{ $user->photo_url }}"
                                class="ud-ava" alt="">

                            <!-- زر التعديل -->
                            <label class="ud-ava-edit">
                                <i class="fas fa-pen"></i>
                                <input type="file" id="avatarInput" name="photo" accept="image/*" style="display:none">
                            </label>
                        </div>

                        <div class="ud-name">
                            <h2>{{ $user->name }}<br><span class="g-sub1">{{ $user->email }}</span></h2>
                        </div>
                    </div>

                    <div class="ud-edit">
                        <label>{{ __('panel.name') }}
                            <input type="text" name="name" value="{{ old('name', $user->name) }}">
                        </label>

                        <label>{{ __('panel.email') }}
                            <input type="email" name="email" value="{{ old('email', $user->email) }}">
                        </label>

                        <label>{{ __('panel.phone') }}
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}">
                        </label>

                        <button class="ud-primary" type="submit">{{ __('panel.save') }}</button>
                    </div>
                </form>
            </div>



            <!-- Include other panels (notifications, inbox, courses, schedule, results, offers, wallet, community, support) -->
            @include('panel.common.notifications')
            @include('panel.student.courses', ['userCourses' => $userCourses])
            @include('panel.student.results', ['userCourses' => $userCourses])
            @include('panel.student.schedule', ['userExamsResults' => $userExamsResults])
            @include('panel.student.community')
            @include('panel.common.support')

        </div>
    </section>
@endsection

@push('scripts')
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Function to update unread count in navigation
            async function updateUnreadCount() {
                try {
                    const response = await fetch('{{ route('chat.list') }}');
                    const chats = await response.json();

                    let totalUnread = 0;
                    chats.forEach(chat => {
                        const userChatUid = '{{ Auth::user()->role_name }}|{{ Auth::user()->id }}';
                        totalUnread += chat.unread[userChatUid] || 0;
                    });

                    const badge = document.getElementById('unreadCount');
                    if (totalUnread > 0) {
                        badge.textContent = totalUnread > 99 ? '99+' : totalUnread;
                        badge.style.display = 'inline-block';
                    } else {
                        badge.style.display = 'none';
                    }
                } catch (error) {
                    console.error('Error updating unread count:', error);
                }
            }

            // Update unread count every 30 seconds (only if not on chat page)
            if (!window.location.pathname.includes('/chat')) {
                updateUnreadCount();
                setInterval(updateUnreadCount, 30000);
            }
        });
    </script>
@endpush
