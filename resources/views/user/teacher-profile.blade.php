@extends('layouts.app')

@section('title', 'الملف الشخصي للمعلم')

@section('content')
<section class="teacher-profile-wrapper">

<div class="teacher-header-wrapper">
    <div class="teacher-header-image">
        <img data-src="{{ $teacher->photo_url }}" alt="معلم المادة">
    </div>
    <div class="teacher-follow-btn">
        <button>متابعة</button>
    </div>
</div>

    <div class="teacher-details">
        <div class="teacher-stats">
            <div class="stat-item">
                <span>الدورات</span>
                <strong>10</strong>
            </div>
            <div class="stat-item">
                <span>التقييم</span>
                <div class="rating">
                    <i class="fas fa-star"></i> 3.5
                </div>
            </div>
        </div>

        <div class="teacher-bio">
            <h3>نبذة عن المعلم</h3>
            <p>الأستاذ خالد خوالدة معلم مادة الأحياء للصفوف الأساسية منذ 10 سنوات</p>
        </div>

        <div class="teacher-tabs">
            <button class="tab-btn active">المواد</button>
            <button class="tab-btn">الصفوف الأساسية</button>
        </div>

        <div class="teacher-content">
            <div class="tab-content active">
                <p>مادة الأحياء فصل أول - مادة الأحياء فصل ثاني</p>
            </div>
            <div class="tab-content">
                <p>الصف الأول - الصف الثاني - الصف الثالث</p>
            </div>
        </div>
    </div>

</section>
@endsection
