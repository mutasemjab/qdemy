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
                    class="far fa-bell"></i><span>{{ __('panel.notifications') }}</span>
                <i class="fas fa-angle-left"></i>
            </button>
            <a class="ud-item nav-link {{ request()->routeIs('chat.*') ? 'active' : '' }}" href="{{ route('chat.index') }}">
                <i class="fas fa-comments"></i>
                <span>{{ __('panel.messages') }}</span>
                {{-- Unread messages badge (optional - you can implement this later) --}}
                <span class="badge bg-danger ms-auto" id="unreadCount" style="display: none;">0</span>
                <i class="fas fa-angle-left"></i>
            </a>

            <button class="ud-item" data-target="kids"><i
                    class="fas fa-children"></i><span>{{ __('panel.children_overview') }}</span><i
                    class="fas fa-angle-left"></i></button>
            <button class="ud-item" data-target="child-reports"><i
                    class="fas fa-chart-line"></i><span>{{ __('panel.children_reports') }}</span><i
                    class="fas fa-angle-left"></i></button>

            <button class="ud-item" data-target="add-child"><i
                    class="fas fa-user-plus"></i><span>{{ __('panel.add_child') }}</span><i
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
                        <div class="ud-val">{{ $user->phone ?? 'N/A' }}</div>
                    </div>
                    <div class="ud-row">
                        <div class="ud-key">{{ __('panel.total_children') }}</div>
                        <div class="ud-val">{{ $children->count() }}</div>
                    </div>
                </div>
            </div>

            <div class="ud-panel" id="settings">
                <div class="ud-title">{{ __('panel.account_settings') }}</div>
                <form method="POST" action="{{ route('parent.update.account') }}" enctype="multipart/form-data">
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

            <!-- Children Panel with Real Data -->
            <div class="ud-panel" id="kids">
                <div class="ud-title">{{ __('panel.children_overview') }}</div>

                @if ($children->count() > 0)
                    <div class="ud-children-list">
                        @foreach ($children as $child)
                            <div class="ud-child-card">
                                <div class="ud-kid-head">
                                    <img data-src="{{ $child->photo_url ?? asset('assets_front/images/kid.png') }}">
                                    <div>
                                        <h2>{{ $child->name }}<br>
                                            <span class="g-sub1">
                                                @if ($child->clas)
                                                    {{ $child->clas->name }}
                                                @else
                                                    {{ __('panel.no_class_assigned') }}
                                                @endif
                                            </span>
                                        </h2>
                                    </div>
                                    <div class="ud-child-actions">
                                        <button class="ud-child-btn" onclick="viewChildDetails({{ $child->user_id }})">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="ud-child-btn"
                                            onclick="removeChildFromParent({{ $child->user_id }}, '{{ $child->name }}')">
                                            <i class="fas fa-trash text-danger"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Child Statistics -->
                                <div class="ud-child-stats">
                                    <div class="ud-stat-item">
                                        <div class="ud-stat-number">{{ $child->enrolledCoursesCount ?? 0 }}</div>
                                        <div class="ud-stat-label">{{ __('panel.courses') }}</div>
                                    </div>
                                    <div class="ud-stat-item">
                                        <div class="ud-stat-number">{{ $child->completedExamsCount ?? 0 }}</div>
                                        <div class="ud-stat-label">{{ __('panel.exams') }}</div>
                                    </div>
                                    <div class="ud-stat-item">
                                        <div class="ud-stat-number">{{ number_format($child->averageScore ?? 0, 1) }}%
                                        </div>
                                        <div class="ud-stat-label">{{ __('panel.avg_score') }}</div>
                                    </div>
                                </div>

                                <!-- Progress Bars for Recent Courses -->
                                <div class="ud-bars">
                                    @if ($child->courses && $child->courses->count() > 0)
                                        @foreach ($child->courses as $course)
                                            <div class="ud-bar">
                                                <div class="ud-bar-head">
                                                    <b>{{ Str::limit($course['title'], 25) }}</b>
                                                    <small>({{ $course['subject_name'] }})</small>
                                                </div>
                                                <div class="ud-bar-track">
                                                    <span style="width:{{ $course['progress'] }}%"></span>
                                                </div>
                                                <div class="ud-bar-foot">
                                                    100%<b>{{ number_format($course['progress'], 1) }}%</b>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="ud-no-courses">
                                            <i class="fas fa-book"></i>
                                            <span>{{ __('panel.no_courses_enrolled') }}</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Recent Exam Results -->


                                @if ($child->recentExams && $child->recentExams->count() > 0)
                                    <div class="ud-recent-exams">
                                        <h4>{{ __('panel.recent_exams') }}</h4>
                                        @foreach ($child->recentExams as $exam)
                                            <div class="ud-exam-result">
                                                <div class="ud-exam-info">
                                                    <span
                                                        class="ud-exam-title">{{ Str::limit($exam['exam_title'], 30) }}</span>
                                                    <span
                                                        class="ud-exam-date">{{ $exam['completed_at']->format('M d') }}</span>
                                                </div>
                                                <div class="ud-exam-score {{ $exam['passed'] ? 'passed' : 'failed' }}">
                                                    {{ number_format($exam['percentage'], 1) }}%
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="ud-no-children">
                        <i class="fas fa-users"></i>
                        <h3>{{ __('panel.no_children_added') }}</h3>
                        <p>{{ __('panel.add_children_message') }}</p>
                        <button class="ud-primary" onclick="switchPanel('add-child')">
                            {{ __('panel.add_first_child') }}
                        </button>
                    </div>
                @endif
            </div>

            <!-- Children Reports Panel -->
            <div class="ud-panel" id="child-reports">
                <div class="ud-title">{{ __('panel.children_reports') }}</div>

                @if ($children->count() > 0)
                    <div class="ud-reports-grid">
                        @foreach ($children as $child)
                            <div class="ud-report-card">
                                <div class="ud-report-header">
                                    <img data-src="{{ $child->photo_url ?? asset('assets_front/images/kid.png') }}">
                                    <div>
                                        <h3>{{ $child->name }}</h3>
                                        <span>{{ $child->clas->name ?? __('panel.no_class') }}</span>
                                    </div>
                                </div>

                                <div class="ud-report-metrics">
                                    <div class="ud-metric">
                                        <div class="ud-metric-value">{{ $child->courses ? $child->courses->count() : 0 }}
                                        </div>
                                        <div class="ud-metric-label">{{ __('panel.enrolled_courses') }}</div>
                                    </div>
                                    <div class="ud-metric">
                                        <div class="ud-metric-value">
                                            {{ $child->courses ? number_format($child->courses->avg('progress') ?? 0, 1) : 0 }}%
                                        </div>
                                        <div class="ud-metric-label">{{ __('panel.avg_progress') }}</div>
                                    </div>
                                    <div class="ud-metric">
                                        <div class="ud-metric-value">
                                            {{ $child->recentExams ? $child->recentExams->count() : 0 }}</div>
                                        <div class="ud-metric-label">{{ __('panel.completed_exams') }}</div>
                                    </div>
                                    <div class="ud-metric">
                                        <div class="ud-metric-value">
                                            {{ $child->recentExams ? number_format($child->recentExams->avg('percentage') ?? 0, 1) : 0 }}%
                                        </div>
                                        <div class="ud-metric-label">{{ __('panel.exam_average') }}</div>
                                    </div>
                                </div>

                                <div class="ud-report-actions">
                                    <button class="ud-btn-outline" onclick="viewChildDetails({{ $child->user_id }})">
                                        {{ __('panel.view_details') }}
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="ud-no-data">
                        <i class="fas fa-chart-line"></i>
                        <h3>{{ __('panel.no_reports_available') }}</h3>
                        <p>{{ __('panel.add_children_to_see_reports') }}</p>
                    </div>
                @endif
            </div>

            <!-- Add Child Panel with Search -->
            <div class="ud-panel" id="add-child">
                <div class="ud-title">{{ __('panel.add_child') }}</div>
                <div class="ud-add-child-form">
                    <div class="ud-search-student">
                        <label>{{ __('panel.search_student') }}
                            <input type="text" id="studentSearch"
                                placeholder="{{ __('panel.search_by_name_or_phone') }}" onkeyup="searchStudents()">
                        </label>
                        <div id="searchResults" class="ud-search-results"></div>
                    </div>


                </div>
            </div>

            <!-- Include common panels -->
            @include('panel.common.notifications')
            @include('panel.common.support')

        </div>
    </section>

    <!-- Remove Child Modal -->
    <div id="removeChildModal" class="ud-modal" style="display: none;">
        <div class="ud-modal-content">
            <div class="ud-modal-header">
                <h3>{{ __('panel.remove_child') }}</h3>
                <button class="ud-modal-close" onclick="closeModal('removeChildModal')">&times;</button>
            </div>
            <div class="ud-modal-body">
                <p>{{ __('panel.remove_child_confirmation') }} <strong id="childNameToRemove"></strong>?</p>
                <div class="ud-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    {{ __('panel.remove_child_warning') }}
                </div>
            </div>
            <div class="ud-modal-footer">
                <button class="ud-btn-secondary"
                    onclick="closeModal('removeChildModal')">{{ __('panel.cancel') }}</button>
                <button class="ud-btn-danger" id="confirmRemoveChild">{{ __('panel.remove') }}</button>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .ud-bars {
            display: block !important;
            width: 100% !important;
            grid-column: 1 / -1 !important;
            align-self: stretch !important;
            box-sizing: border-box !important;
            padding: 10px 18px 18px !important;
            margin: 0 !important;
        }

        .ud-bar {
            width: 100% !important
        }

        .ud-add-child-form {
            background: #fff;
            border: 1px solid #e6e9f2;
            border-radius: 16px;
            box-shadow: 0 14px 30px rgba(17, 24, 39, .06);
            padding: 18px
        }

        .ud-search-student {
            display: flex;
            flex-direction: column;
            gap: 10px;
            position: relative
        }

        .ud-search-student label {
            font-weight: 800;
            color: #0f172a;
            display: flex;
            flex-direction: column;
            gap: 8px
        }

        #studentSearch {
            width: 100%;
            height: 44px;
            border: 1.5px solid #e2e6ef;
            border-radius: 12px;
            padding: 10px 14px;
            font-weight: 600;
            color: #111827;
            background: #fff;
            transition: border-color .15s ease, box-shadow .15s ease;
        }

        #studentSearch::placeholder {
            color: #9aa3af;
            font-weight: 600
        }

        #studentSearch:focus {
            outline: none;
            border-color: #bcd3ff;
            box-shadow: 0 0 0 3px rgba(46, 108, 240, .12)
        }

        .ud-search-results {
            margin-top: 10px;
            border: 1px solid #e2e6ef;
            border-radius: 12px;
            overflow: hidden;
            background: #fff;
            max-height: 300px;
            overflow-y: auto;
            display: none;
        }

        .ud-search-results.show {
            display: block
        }

        .ud-search-results .item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            cursor: pointer;
            border-bottom: 1px solid #f1f3f7;
        }

        .ud-search-results .item:last-child {
            border-bottom: none
        }

        .ud-search-results .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            flex: 0 0 auto;
            border: 1px solid #e6e9f2;
        }

        .ud-search-results .meta {
            display: flex;
            flex-direction: column;
            min-width: 0
        }

        .ud-search-results .name {
            font-weight: 800;
            color: #0f172a;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis
        }

        .ud-search-results .sub {
            font-size: 12px;
            color: #6b7280;
            font-weight: 700
        }

        .ud-search-results .item:hover {
            background: #f7f9ff
        }

        @media (max-width:600px) {
            .ud-add-child-form {
                padding: 14px
            }

            #studentSearch {
                height: 42px
            }
        }

        @media (max-width:600px) {
            .ud-reports-grid {
                grid-template-columns: 1fr !important;
                gap: 14px;
            }
        }

        .ud-children-list {
            display: grid;
            grid-template-columns: 1fr;
            gap: 18px
        }

        .ud-child-card {
            grid-column: span 12;
            background: #ffffff;
            border: 1px solid #e6e9f2;
            border-radius: 16px;
            box-shadow: 0 14px 30px rgba(17, 24, 39, .06);
            overflow: hidden;
            display: flex;
            flex-direction: column
        }

        .ud-kid-head {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px 18px;
            background: #f8fafc;
            border-bottom: 1px solid #eef2f7
        }

        .ud-kid-head img {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e5e7eb;
            flex: 0 0 auto;
            filter: drop-shadow(0 2px 6px rgba(0, 0, 0, .08))
        }

        .ud-kid-head h2 {
            display: flex;
            align-items: center;
            margin: 0;
            font-size: 18px;
            font-weight: 800;
            line-height: 1.2
        }

        .ud-kid-head .g-sub1 {
            display: inline-block;
            margin-top: 6px;
            font-size: 13px;
            color: #6b7280;
            font-weight: 600
        }

        .ud-child-actions {
            margin-inline-start: auto;
            display: flex;
            gap: 8px
        }

        .ud-child-btn {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            border: 1px solid #e5e7ef;
            background: #fff;
            display: grid;
            place-items: center;
            cursor: pointer;
            transition: transform .12s ease, box-shadow .12s ease, border-color .12s ease
        }

        .ud-child-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, .08);
            border-color: #d7dcea
        }

        .ud-child-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            padding: 14px 18px
        }

        .ud-stat-item {
            background: #fdfdfd;
            border: 1px solid #eef2f7;
            border-radius: 12px;
            padding: 12px;
            text-align: center
        }

        .ud-stat-number {
            font-size: 20px;
            font-weight: 800;
            color: #0b57d0
        }

        .ud-stat-label {
            font-size: 12px;
            color: #6b7280;
            font-weight: 700;
            margin-top: 4px;
            text-transform: uppercase;
            letter-spacing: .02em
        }

        .ud-bars {
            display: grid;
            gap: 14px;
            padding: 10px 18px 18px
        }

        .ud-bar {
            background: #ffffff;
            border: 1px solid #eef2f7;
            border-radius: 12px;
            padding: 12px
        }

        .ud-bar-head {
            display: flex;
            gap: 8px;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px
        }

        .ud-bar-head b {
            font-weight: 800;
            font-size: 14px;
            color: #0f172a
        }

        .ud-bar-head small {
            color: #64748b;
            font-weight: 700
        }

        .ud-bar-track {
            position: relative;
            height: 10px;
            background: #eef2f7;
            border-radius: 999px;
            overflow: hidden
        }

        .ud-bar-track span {
            position: absolute;
            inset: 0 0 0 auto;
            width: 0;
            height: 100%;
            background: linear-gradient(90deg, #2e6cf0, #0b57d0);
            border-radius: 999px;
            transition: width .3s ease
        }

        .ud-bar-foot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 8px;
            font-size: 12px;
            color: #64748b
        }

        .ud-recent-exams {
            padding: 4px 18px 18px
        }

        .ud-recent-exams h4 {
            margin: 0 0 10px;
            font-size: 15px;
            font-weight: 900;
            color: #0f172a
        }

        .ud-exam-result {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #ffffff;
            border: 1px solid #eef2f7;
            border-radius: 12px;
            padding: 10px 12px;
            margin-bottom: 10px
        }

        .ud-exam-info {
            display: flex;
            gap: 10px;
            align-items: center
        }

        .ud-exam-title {
            font-weight: 800;
            color: #111827;
            font-size: 14px
        }

        .ud-exam-date {
            font-size: 12px;
            color: #6b7280
        }

        .ud-exam-score {
            margin-inline-start: auto;
            font-weight: 900;
            padding: 6px 10px;
            border-radius: 10px;
            font-size: 13px
        }

        .ud-exam-score.passed {
            background: #e8f7ef;
            color: #0f9d58;
            border: 1px solid #b9ebcf
        }

        .ud-exam-score.failed {
            background: #feeeee;
            color: #d93025;
            border: 1px solid #f6c7c3
        }

        .ud-no-courses {
            display: flex;
            gap: 8px;
            align-items: center;
            justify-content: center;
            background: #f7f9fc;
            border: 1px solid #eef2f7;
            border-radius: 12px;
            padding: 14px;
            color: #6b7280;
            font-weight: 700
        }

        .ud-no-children {
            background: #ffffff;
            border: 1px dashed #dbe2ee;
            border-radius: 16px;
            padding: 28px;
            text-align: center;
            color: #475569
        }

        .ud-no-children i {
            font-size: 36px;
            color: #0b57d0;
            margin-bottom: 8px
        }

        .ud-no-children h3 {
            margin: 6px 0 8px;
            font-size: 18px;
            font-weight: 900;
            color: #0f172a
        }

        .ud-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 14px;
            border-radius: 12px;
            background: #0b57d0;
            color: #fff;
            font-weight: 800;
            border: 0;
            cursor: pointer
        }

        .ud-primary:hover {
            filter: brightness(1.05)
        }

        @media (min-width:640px) {
            .ud-child-card {
                grid-column: span 6
            }
        }

        @media (min-width:992px) {
            .ud-child-card {
                grid-column: span 4
            }
        }

        @media (max-width:480px) {
            .ud-kid-head {
                padding: 14px
            }

            .ud-kid-head img {
                width: 48px;
                height: 48px
            }

            .ud-stat-number {
                font-size: 18px
            }

            .ud-child-stats {
                grid-template-columns: repeat(3, 1fr);
                gap: 8px
            }

            .ud-bars {
                padding: 8px 14px 14px
            }

            .ud-exam-result {
                padding: 8px 10px
            }
        }


        .ud-child-actions {
            display: flex;
            gap: 10px;
            margin-left: auto;
        }

        .ud-child-btn {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .ud-child-btn:hover {
            background: #e9ecef;
        }

        .ud-child-stats {
            display: flex;
            justify-content: space-around;
            margin: 15px 0;
            padding: 15px;
            border-radius: 8px;
        }

        .ud-stat-item {
            text-align: center;
        }

        .ud-stat-number {
            font-size: 1.5rem;
            font-weight: bold;
            color: #007bff;
        }

        .ud-stat-label {
            font-size: 0.8rem;
            color: #6c757d;
            margin-top: 5px;
        }

        .ud-recent-exams {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #dee2e6;
        }

        .ud-recent-exams h4 {
            margin-bottom: 10px;
            font-size: 1rem;
            color: #495057;
        }

        .ud-exam-result {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 10px;
            border-bottom: 1px solid #f1f3f4;
        }

        .ud-exam-info {
            display: flex;
            flex-direction: column;
        }

        .ud-exam-title {
            font-weight: 500;
            font-size: 0.9rem;
        }

        .ud-exam-date {
            font-size: 0.8rem;
            color: #6c757d;
        }

        .ud-exam-score {
            font-weight: bold;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.9rem;
        }

        .ud-exam-score.passed {
            background: #d4edda;
            color: #155724;
        }

        .ud-exam-score.failed {
            background: #f8d7da;
            color: #721c24;
        }

        .ud-no-children,
        .ud-no-data,
        .ud-no-students {
            text-align: center;
            padding: 40px 20px;
        }

        .ud-no-children i,
        .ud-no-data i,
        .ud-no-students i {
            font-size: 3rem;
            color: #6c757d;
            margin-bottom: 15px;
        }

        .ud-reports-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
        }

        button.ud-btn-outline {
            --c: #0055D2;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 16px;
            min-height: 40px;
            border: 2px solid var(--c);
            background: #fff;
            color: var(--c);
            font-weight: 800;
            border-radius: 12px;
            cursor: pointer;
            transition: background .15s ease, color .15s ease, box-shadow .15s ease, transform .06s ease;
        }

        button.ud-btn-outline:hover {
            background: rgba(0, 85, 210, .06);
            box-shadow: 0 6px 18px rgba(0, 85, 210, .18);
        }

        button.ud-btn-outline:active {
            transform: translateY(1px);
            box-shadow: none;
        }

        button.ud-btn-outline:focus-visible {
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 85, 210, .25);
        }

        button.ud-btn-outline[disabled],
        button.ud-btn-outline:disabled {
            opacity: .55;
            cursor: not-allowed;
            box-shadow: none;
        }

        button.ud-btn-outline .icon {
            font-size: 1.05em;
            line-height: 0
        }


        .ud-report-card {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .ud-report-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .ud-report-header img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 15px;
        }

        .ud-report-metrics {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 20px 0;
        }

        .ud-metric {
            text-align: center;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .ud-metric-value {
            font-size: 1.25rem;
            font-weight: bold;
            color: #007bff;
        }

        .ud-metric-label {
            font-size: 0.8rem;
            color: #6c757d;
            margin-top: 5px;
        }

        .ud-students-list {
            max-height: 400px;
            overflow-y: auto;
        }

        .ud-student-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .ud-student-info {
            display: flex;
            align-items: center;
        }

        .ud-student-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .ud-class-badge {
            background: #007bff;
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.7rem;
            margin-left: 10px;
        }

        .ud-search-results {
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            margin-top: 10px;
            display: none;
        }

        .ud-modal {
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .ud-modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: none;
            border-radius: 8px;
            width: 80%;
            max-width: 500px;
        }

        .ud-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .ud-modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
        }

        .ud-warning {
            background: #fff3cd;
            color: #856404;
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
        }

        .ud-modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        .ud-btn-secondary {
            background: #6c757d;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        .ud-btn-danger {
            background: #dc3545;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        .ud-no-courses {
            text-align: center;
            padding: 20px;
            color: #6c757d;
        }

        .ud-no-courses i {
            font-size: 2rem;
            margin-bottom: 10px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        let childIdToRemove = null;

        function viewChildDetails(childId) {
            // You can redirect to a detailed view or open a modal
            window.location.href = `{{ route('parent.children.detail', '') }}/${childId}`;

        }

        function removeChildFromParent(childId, childName) {
            childIdToRemove = childId;
            document.getElementById('childNameToRemove').textContent = childName;
            document.getElementById('removeChildModal').style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        document.getElementById('confirmRemoveChild').addEventListener('click', function() {
            if (childIdToRemove) {
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __('panel.removing') }}...';
                this.disabled = true;

                fetch('{{ route('parent.remove-child') }}', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: JSON.stringify({
                            student_id: childIdToRemove
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert(data.message || '{{ __('panel.error_occurred') }}');
                            this.innerHTML = '{{ __('panel.remove') }}';
                            this.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('{{ __('panel.error_occurred') }}');
                        this.innerHTML = '{{ __('panel.remove') }}';
                        this.disabled = false;
                    });
            }
        });

        function addChild(studentId, studentName) {
            const button = event.target;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            button.disabled = true;

            fetch('{{ route('parent.add-child-submit') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        student_id: studentId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove the student from the list
                        button.closest('.ud-student-item').remove();
                        alert(data.message);
                    } else {
                        alert(data.message || '{{ __('panel.error_occurred') }}');
                        button.innerHTML = '{{ __('panel.add') }}';
                        button.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('{{ __('panel.error_occurred') }}');
                    button.innerHTML = '{{ __('panel.add') }}';
                    button.disabled = false;
                });
        }

        function searchStudents() {
            const searchTerm = document.getElementById('studentSearch').value;

            if (searchTerm.length >= 2) {
                fetch(`{{ route('parent.search-students') }}?search=${encodeURIComponent(searchTerm)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            displaySearchResults(data.students);
                        }
                    })
                    .catch(error => console.error('Error:', error));
            } else {
                document.getElementById('searchResults').style.display = 'none';
            }
        }

        function displaySearchResults(students) {
            const resultsContainer = document.getElementById('searchResults');

            if (students.length > 0) {
                let html = '';
                students.forEach(student => {
                    html += `
                <div class="ud-student-item" data-student-id="${student.id}">
                    <div class="ud-student-info">
                        <img data-src="${student.photo_url || '{{ asset('assets_front/images/kid.png') }}'}" style="width: 30px; height: 30px;">
                        <div>
                            <h5>${student.name}</h5>
                            <span>${student.phone || 'N/A'}</span>
                        </div>
                    </div>
                    <button class="ud-primary" onclick="addChild(${student.id}, '${student.name}')">
                        {{ __('panel.add') }}
                    </button>
                </div>
            `;
                });
                resultsContainer.innerHTML = html;
                resultsContainer.style.display = 'block';
            } else {
                resultsContainer.innerHTML = '<div class="ud-no-results">{{ __('panel.no_students_found') }}</div>';
                resultsContainer.style.display = 'block';
            }
        }

        function switchPanel(panelId) {
            // Hide all panels
            document.querySelectorAll('.ud-panel').forEach(panel => {
                panel.classList.remove('show');
            });

            // Remove active class from all menu items
            document.querySelectorAll('.ud-item').forEach(item => {
                item.classList.remove('active');
            });

            // Show target panel
            document.getElementById(panelId).classList.add('show');

            // Add active class to corresponding menu item
            document.querySelector(`[data-target="${panelId}"]`).classList.add('active');
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('removeChildModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }

        // Preview uploaded image
        document.getElementById('avatarInput').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview-image').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush
