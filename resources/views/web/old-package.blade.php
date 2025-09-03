@extends('layouts.app')
@section('title',$package?->name)

@section('content')
<section class="pkgo-wrap">


    <div class="universities-header-wrapper">
        <div class="universities-header">
            <h2>{{$package?->name}}</h2> <br>
        </div>
    </div>

    @if($is_type_class && $categoriesTree && $categoriesTree->count())
    <div class="co-chooser">
        <button class="co-chooser-btn" id="coChooserBtn">
            <span>{{ $clas?->localized_name ?? translate_lang('choose class') }}</span>
            <i class="fa-solid fa-caret-down"></i>
        </button>

        <ul class="co-chooser-list" id="coChooserList">
            <li><a href="javascript:void(0)" class='text-decoration-none'>
                {{translate_lang('all classes')}}</a>
            </li>
            @foreach($categoriesTree as $categories)
                @if($categories && count($categories))
                    @foreach($categories as $class)
                    <li>
                        <a class='text-decoration-none'
                            href="{{route('package',['package'=>$package->id,'clas'=>$class['id']])}}">
                            <!-- @if($class['category']?->parent) {{$class['category']?->parent->localized_name}} @endif >  -->
                            {{$class['name']}}
                        </a>
                    </li>
                    @endforeach
                @endif
            @endforeach
        </ul>


    </div>
    @endif

    <div type="submit" class="pkgo-head">
        {{ sprintf('%g', $package->price) }} <span>{{CURRENCY}}</span>
    </div>


    @if($lessons && $lessons->count())
    <div class="sp2-box">
    @foreach ($lessons as $lesson)
        <div class="sp2-group">
        <button class="sp2-group-head">
            <i class="fa-solid fa-plus"></i>
            <span>{{ $lesson->localized_name }}</span>
        </button>
        <div class="sp2-panel">
            <div class="sp2-content">
            <h4 class="sp2-title">{{ $lesson->localized_name }}</h4>

            @if($lesson->is_optional == 0)
                <!-- if lesson category is not optional get lesson courses -->
                @php $courses = CourseRepository()->getDirectCategoryCourses($lesson->id); @endphp
                @if($courses && $courses->count())
                <div class="courses-list">
                    @foreach ($courses as $not_optional_subject_course)
                    <div class="course-item">
                    <span class="course-name">{{ $not_optional_subject_course->title }}</span>
                    <button class="btn-add-cart">{{ translate_lang('add to cart') }}</button>
                    </div>
                    @endforeach
                </div>
                @endif

            @else
                <!-- if lesson category is optional get first field optional lessons -->
                @php $optionals = SubjectRepository()->getOptionalSubjectOptions($lesson); @endphp
                <div class="optional-subjects">
                @foreach ($optionals as $optional_lesson)
                    <div class="sp2-group sp2-nested">
                    <button class="sp2-group-head sp2-sub-head">
                        <i class="fa-solid fa-plus"></i>
                        <span>{{ $optional_lesson->localized_name }}</span>
                    </button>
                    <div class="sp2-panel">
                        <div class="sp2-content">
                        <!-- then get lesson (category) courses -->
                        @php $courses = CourseRepository()->getDirectCategoryCourses($optional_lesson->id); @endphp
                        @if($courses && $courses->count())
                            <div class="courses-list">
                            @foreach ($courses as $optional_subject_course)
                            <div class="course-item">
                                <span class="course-name">{{ $optional_subject_course->title }}</span>
                                <button class="btn-add-cart">{{ translate_lang('add to cart') }}</button>
                            </div>
                            @endforeach
                            </div>
                        @endif
                        </div>
                    </div>
                    </div>
                @endforeach
                </div>
            @endif
            </div>
        </div>
        </div>
    @endforeach
    </div>
    @endif

</section>
@endsection

@push('styles')
<style>
    /* قسم الدروس الرئيسي */
    .sp2-box {
    margin: 20px 0;
    padding: 0;
    }

    /* مجموعة الدرس */
    .sp2-group {
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    margin-bottom: 10px;
    background: #fff;
    }

    /* رأس المجموعة - الزر */
    .sp2-group-head {
    width: 100%;
    padding: 12px 16px;
    background: #f8f9fa;
    border: none;
    text-align: right;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 10px;
    transition: background 0.2s;
    }

    .sp2-group-head:hover {
    background: #e9ecef;
    }

    .sp2-group-head i {
    color: #6c757d;
    font-size: 12px;
    }

    /* اللوحة المخفية */
    .sp2-panel {
    display: none;
    padding: 16px;
    background: #fafafa;
    border-top: 1px solid #e0e0e0;
    }

    /* عند فتح اللوحة */
    .sp2-group.open .sp2-panel {
    display: block;
    }

    .sp2-group.open .sp2-group-head i {
    transform: rotate(45deg);
    }

    /* المحتوى داخل اللوحة */
    .sp2-content {
    padding: 8px 0;
    }

    .sp2-title {
    color: #495057;
    font-size: 14px;
    margin-bottom: 12px;
    font-weight: 500;
    }

    /* قائمة الكورسات */
    .courses-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
    }

    .course-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 12px;
    background: #fff;
    border: 1px solid #dee2e6;
    border-radius: 3px;
    }

    .course-name {
    color: #333;
    font-size: 14px;
    }

    /* زر إضافة للسلة */
    .btn-add-cart {
    padding: 6px 12px;
    background: #007bff;
    color: #fff;
    border: none;
    border-radius: 3px;
    font-size: 13px;
    cursor: pointer;
    transition: background 0.2s;
    }

    .btn-add-cart:hover {
    background: #0056b3;
    }

    /* المواد الاختيارية المتداخلة */
    .sp2-nested {
    margin: 10px 0;
    border: 1px solid #dee2e6;
    }

    .sp2-sub-head {
    background: #fff;
    font-size: 14px;
    padding: 10px 14px;
    }

    .sp2-nested .sp2-panel {
    background: #fff;
    padding: 12px;
    }

    /* تحسينات للاستجابة */
    @media (max-width: 768px) {
    .course-item {
        flex-direction: column;
        gap: 8px;
        text-align: center;
    }

    .btn-add-cart {
        width: 100%;
    }
    }
</style>
@endpush

@push('scripts')
<script>
// إضافة وظيفة فتح وإغلاق الأقسام
document.querySelectorAll('.sp2-group-head').forEach(button => {
  button.addEventListener('click', function() {
    this.closest('.sp2-group').classList.toggle('open');
  });
});
</script>
@endpush
