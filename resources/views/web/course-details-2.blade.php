@extends('layouts.app')
@section('title','تفاصيل الكورس')

@section('content')
<section class="crs2">
    <div class="crs2-hero">
        <div class="crs2-hero-inner">
            <h1 class="crs2-hero-title">الشهر الثاني (محمد صلاح - لغة عربية - 2 ثانوي)</h1>
            <p class="crs2-hero-sub">يتكون الشهر من 4 محاضرات - محاضرة أسبوعيًّا.</p>
            <div class="crs2-hero-text">
                <p>خد بالك:</p>
                <p>1- لازم تتفرج على فيديو كيفية تسليم الواجب قبل ما تضغط على تسليم الواجب.</p>
                <p>2- محاولة تسليم الواجب هي مرة واحدة فقط عشان تعرف تشوف باقي المحاضرات. لو المحاضرات أو الواجب قفل منك لازم تتواصل مع الدعم عشان يزودولك محاولة.</p>
                <p>3- ليك 3 محاولات في 3 امتحانات مختلفة عشان تفتح المحاضرة لو محاولاتك خلصت ومجبتش درجة النجاح هتتوصل مع الدعم.</p>
                <p>بالتوفيق يا بطل.</p>
            </div>
            <div class="crs2-hero-meta">
                <span class="crs2-chip crs2-chip--outline">تاريخ إنشاء الكورس الخميس 16 أكتوبر 2025</span>
                <span class="crs2-chip crs2-chip--outline">آخر تحديث للكورس الأحد 9 نوفمبر 2025</span>
            </div>
        </div>
    </div>

    <div class="crs2-main">
        <div class="crs2-main-inner">
            <div class="crs2-main-top">
                <div class="crs2-side-card">
                    <div class="crs2-side-cover">
                        <img src="{{ asset('assets_front/images/ourse-cover-2.webp') }}" alt="Course cover">
                    </div>
                    <div class="crs2-side-body">
                        <div class="crs2-side-note">احجز معاد الجلسة الخاصة بيك</div>
                        <div class="crs2-side-price-row">
                            <div class="crs2-side-price-chip">
                                <span>جنيهًا</span>
                            </div>
                            <div class="crs2-side-price-value">
                                <span class="crs2-side-price-number">115.00</span>
                            </div>
                        </div>
                        <button class="crs2-side-btn-primary">اشترك الآن !</button>
                    </div>
                </div>

                <div class="crs2-preview-card">
                    <img src="{{ asset('assets_front/images/ourse-cover-2.webp') }}" alt="Course main" class="crs2-preview-image">
                </div>
            </div>

            <div class="crs2-summary-card">
                <h2 class="crs2-summary-title">الشهر الثاني (محمد صلاح - لغة عربية - 2 ثانوي)</h2>
                <p class="crs2-summary-sub">يتكون الشهر من 4 محاضرات - محاضرة أسبوعيًّا.</p>
                <div class="crs2-summary-text">
                    <p>خد بالك:</p>
                    <p>1- لازم تتفرج على فيديو كيفية تسليم الواجب قبل ما تضغط على تسليم الواجب.</p>
                    <p>2- محاولة تسليم الواجب هي مرة واحدة فقط عشان تعرف تشوف باقي المحاضرات. لو المحاضرات أو الواجب قفل منك لازم تتواصل مع الدعم عشان يزودولك محاولة.</p>
                    <p>3- ليك 3 محاولات في 3 امتحانات مختلفة عشان تفتح المحاضرة لو محاولاتك خلصت ومجبتش درجة النجاح هتتوصل مع الدعم.</p>
                    <p>بالتوفيق يا بطل.</p>
                </div>
            </div>

            <section class="crs2-content">
                <div class="crs2-content-header">
                    <h2 class="crs2-content-title"><span>محتوى الكورس</span></h2>
                </div>

                <div class="crs2-sections">

                    <div class="crs2-section crs2-section--open">
                        <button type="button" class="crs2-section-header">
                            <span class="crs2-section-arrow"></span>
                            <div class="crs2-section-main">
                                <h3 class="crs2-section-title">المحاضرة الخامسة</h3>
                                <p class="crs2-section-sub">"نحو: اقتران جواب الشرط بالفاء + جزم المضارع في جواب الطلب - قراءة: أنماط القراءة المتحررة - قصة: الفصل الثالث"</p>
                            </div>
                            <span class="crs2-section-icon">
                                <i class="fa fa-th-large"></i>
                            </span>
                        </button>

                        <div class="crs2-section-body">
                            <div class="crs2-subgroup crs2-subgroup--open">
                                <button type="button" class="crs2-sub-header">
                                    <span class="crs2-sub-title">خلاصة الخلاويص (مراجعة على ما سبق)</span>
                                    <span class="crs2-sub-toggle">إخفاء</span>
                                </button>
                                <div class="crs2-sub-body">
                                    <div class="crs2-resource crs2-resource--file">
                                        <div class="crs2-resource-main">
                                            <span class="crs2-resource-icon"><i class="fa fa-file-alt"></i></span>
                                            <span class="crs2-resource-title">ملف خلاصة الخلاويص في النحو - المحاضرة الرابعة - محمد صلاح 2 ث</span>
                                        </div>
                                        <div class="crs2-resource-actions">
                                            <a href="#" class="crs2-pill-btn crs2-pill-btn--blue">تحميل</a>
                                        </div>
                                    </div>

                                    <div class="crs2-resource crs2-resource--file">
                                        <div class="crs2-resource-main">
                                            <span class="crs2-resource-icon"><i class="fa fa-file-alt"></i></span>
                                            <span class="crs2-resource-title">ملف خلاصة الخلاويص في النحو - المحاضرة الثالثة - محمد صلاح 2 ث</span>
                                        </div>
                                        <div class="crs2-resource-actions">
                                            <a href="#" class="crs2-pill-btn crs2-pill-btn--blue">تحميل</a>
                                        </div>
                                    </div>

                                    <div class="crs2-resource crs2-resource--file">
                                        <div class="crs2-resource-main">
                                            <span class="crs2-resource-icon"><i class="fa fa-file-alt"></i></span>
                                            <span class="crs2-resource-title">ملف خلاصة الخلاويص في الأدب - المحاضرة الرابعة - محمد صلاح 2 ث</span>
                                        </div>
                                        <div class="crs2-resource-actions">
                                            <a href="#" class="crs2-pill-btn crs2-pill-btn--blue">تحميل</a>
                                        </div>
                                    </div>

                                    <div class="crs2-resource crs2-resource--file">
                                        <div class="crs2-resource-main">
                                            <span class="crs2-resource-icon"><i class="fa fa-file-alt"></i></span>
                                            <span class="crs2-resource-title">ملف خلاصة الخلاويص في النصوص - المحاضرة الرابعة - محمد صلاح 2 ث</span>
                                        </div>
                                        <div class="crs2-resource-actions">
                                            <a href="#" class="crs2-pill-btn crs2-pill-btn--blue">تحميل</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="crs2-subgroup">
                                <button type="button" class="crs2-sub-header">
                                    <span class="crs2-sub-title">جزء الشرح و التدريبات</span>
                                    <span class="crs2-sub-toggle">عرض</span>
                                </button>
                                <div class="crs2-sub-body">
                                    <div class="crs2-resource crs2-resource--video">
                                        <div class="crs2-resource-main">
                                            <span class="crs2-resource-icon"><i class="fa fa-play-circle"></i></span>
                                            <span class="crs2-resource-title">فيديو شرح المحاضرة الخامسة - الجزء الأول</span>
                                        </div>
                                        <div class="crs2-resource-actions">
                                            <a href="#" class="crs2-pill-btn crs2-pill-btn--gray">مشاهدة</a>
                                        </div>
                                    </div>

                                    <div class="crs2-resource crs2-resource--video">
                                        <div class="crs2-resource-main">
                                            <span class="crs2-resource-icon"><i class="fa fa-play-circle"></i></span>
                                            <span class="crs2-resource-title">فيديو حل التدريبات - المحاضرة الخامسة</span>
                                        </div>
                                        <div class="crs2-resource-actions">
                                            <a href="#" class="crs2-pill-btn crs2-pill-btn--gray">مشاهدة</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="crs2-subgroup">
                                <button type="button" class="crs2-sub-header">
                                    <span class="crs2-sub-title">تحليل التقييمات الأسبوعية</span>
                                    <span class="crs2-sub-toggle">عرض</span>
                                </button>
                                <div class="crs2-sub-body">
                                    <div class="crs2-resource crs2-resource--exam">
                                        <div class="crs2-resource-main">
                                            <span class="crs2-resource-icon"><i class="fa fa-question-circle"></i></span>
                                            <span class="crs2-resource-title">الامتحان الشامل الأول (خارج التكريم)</span>
                                        </div>
                                        <div class="crs2-resource-actions">
                                            <div class="crs2-resource-meta">
                                                <span class="crs2-meta-chip">3 محاولات متاحة</span>
                                            </div>
                                            <a href="#" class="crs2-pill-btn crs2-pill-btn--red">دخول الامتحان</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="crs2-subgroup">
                                <button type="button" class="crs2-sub-header">
                                    <span class="crs2-sub-title">تسليم الواجب</span>
                                    <span class="crs2-sub-toggle">عرض</span>
                                </button>
                                <div class="crs2-sub-body">
                                    <div class="crs2-resource crs2-resource--task">
                                        <div class="crs2-resource-main">
                                            <span class="crs2-resource-icon"><i class="fa fa-upload"></i></span>
                                            <span class="crs2-resource-title">رفع ملف حل الواجب الخاص بالمحاضرة الخامسة</span>
                                        </div>
                                        <div class="crs2-resource-actions">
                                            <a href="#" class="crs2-pill-btn crs2-pill-btn--orange">تسليم الواجب</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>


                    <div class="crs2-section">
                        <button type="button" class="crs2-section-header">
                            <span class="crs2-section-arrow"></span>
                            <div class="crs2-section-main">
                                <h3 class="crs2-section-title">الامتحان الشامل الأول (خارج التكريم)</h3>
                                <p class="crs2-section-sub">الامتحان الشامل الأول (خارج التكريم) يغطي أجزاء الشهر بالكامل</p>
                            </div>
                            <span class="crs2-section-icon">
                                <i class="fa fa-th-large"></i>
                            </span>
                        </button>
                        <div class="crs2-section-body">
                            <div class="crs2-resource crs2-resource--exam">
                                <div class="crs2-resource-main">
                                    <span class="crs2-resource-icon"><i class="fa fa-question-circle"></i></span>
                                    <span class="crs2-resource-title">الامتحان الشامل الأول على الشهر الثاني بالكامل</span>
                                </div>
                                <div class="crs2-resource-actions">
                                    <div class="crs2-resource-meta">
                                        <span class="crs2-meta-chip">المدة 60 دقيقة</span>
                                        <span class="crs2-meta-chip">3 محاولات</span>
                                    </div>
                                    <a href="#" class="crs2-pill-btn crs2-pill-btn--red">دخول الامتحان</a>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="crs2-section">
                        <button type="button" class="crs2-section-header">
                            <span class="crs2-section-arrow"></span>
                            <div class="crs2-section-main">
                                <h3 class="crs2-section-title">المحاضرة السادسة</h3>
                                <p class="crs2-section-sub">"نصوص: وصايا الحكماء - بلاغة: الطباق والمقابلة - قراءة: العلاقات بين الجمل"</p>
                            </div>
                            <span class="crs2-section-icon">
                                <i class="fa fa-th-large"></i>
                            </span>
                        </button>
                        <div class="crs2-section-body">
                            <div class="crs2-subgroup">
                                <button type="button" class="crs2-sub-header">
                                    <span class="crs2-sub-title">جزء الشرح و التدريبات</span>
                                    <span class="crs2-sub-toggle">عرض</span>
                                </button>
                                <div class="crs2-sub-body">
                                    <div class="crs2-resource crs2-resource--video">
                                        <div class="crs2-resource-main">
                                            <span class="crs2-resource-icon"><i class="fa fa-play-circle"></i></span>
                                            <span class="crs2-resource-title">فيديو شرح نصوص المحاضرة السادسة</span>
                                        </div>
                                        <div class="crs2-resource-actions">
                                            <a href="#" class="crs2-pill-btn crs2-pill-btn--gray">مشاهدة</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="crs2-section">
                        <button type="button" class="crs2-section-header">
                            <span class="crs2-section-arrow"></span>
                            <div class="crs2-section-main">
                                <h3 class="crs2-section-title">المحاضرة السابعة</h3>
                                <p class="crs2-section-sub">نحو: توكيد الفعل بالنون الثقيلة والخفيفة - الأدب في عصر صدر الإسلام - قصة: الفصل الرابع</p>
                            </div>
                            <span class="crs2-section-icon">
                                <i class="fa fa-th-large"></i>
                            </span>
                        </button>
                        <div class="crs2-section-body">
                            <div class="crs2-subgroup">
                                <button type="button" class="crs2-sub-header">
                                    <span class="crs2-sub-title">مواد وملفات إضافية</span>
                                    <span class="crs2-sub-toggle">عرض</span>
                                </button>
                                <div class="crs2-sub-body">
                                    <div class="crs2-resource crs2-resource--file">
                                        <div class="crs2-resource-main">
                                            <span class="crs2-resource-icon"><i class="fa fa-file-alt"></i></span>
                                            <span class="crs2-resource-title">ملف ملخص المحاضرة السابعة</span>
                                        </div>
                                        <div class="crs2-resource-actions">
                                            <a href="#" class="crs2-pill-btn crs2-pill-btn--blue">تحميل</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </section>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.crs2{direction:rtl;background:#f3f4f6;padding-bottom:60px}
.crs2-hero{background-color:#2175ff;background-image:url('{{ asset('assets_front/images/course-pattern.png') }}');background-size:120px 120px;background-repeat:repeat;color:#fff;padding:70px 0 80px}
.crs2-hero-inner{max-width:1200px;margin:0 auto;padding:0 40px}
.crs2-hero-title{font-size:34px;font-weight:800;margin:0 0 10px}
.crs2-hero-sub{font-size:16px;margin:0 0 18px}
.crs2-hero-text p{margin:0 0 4px;font-size:14px;line-height:1.9}
.crs2-hero-meta{margin-top:26px;display:flex;gap:12px;justify-content:flex-end;flex-wrap:wrap}
.crs2-chip{display:inline-flex;align-items:center;justify-content:center;border-radius:999px;padding:6px 16px;font-size:12px}
.crs2-chip--outline{background:rgba(255,255,255,.16);border:1px solid rgba(255,255,255,.6)}
.crs2-main{margin-top:-50px;position:relative;z-index:2}
.crs2-main-inner{max-width:1200px;margin:0 auto;padding:0 20px}
.crs2-main-top{display:flex;gap:24px;align-items:flex-start;margin-bottom:26px}
.crs2-side-card{width:360px;max-width:100%;background:#fff;border-radius:18px;overflow:hidden;box-shadow:0 18px 45px rgba(15,23,42,.16)}
.crs2-side-cover img{display:block;width:100%;height:auto}
.crs2-side-body{padding:16px 18px 20px;background:#f3f4f6}
.crs2-side-note{background:#4b5563;color:#fff;border-radius:6px;padding:9px 10px;text-align:center;font-size:14px;margin-bottom:14px}
.crs2-side-price-row{display:flex;align-items:center;justify-content:space-between;margin-bottom:14px}
.crs2-side-price-chip{background:#ffd54a;border-radius:999px;padding:4px 12px;font-size:13px;color:#5b3b00}
.crs2-side-price-value{display:flex;align-items:center;gap:6px}
.crs2-side-price-number{font-size:20px;font-weight:800;color:#0055d2}
.crs2-side-btn-primary{width:100%;border-radius:8px;background:#2174ff;color:#fff;border:none;padding:11px 0;font-size:15px;font-weight:700;cursor:pointer}
.crs2-preview-card{flex:1;background:#fff;border-radius:18px;box-shadow:0 18px 45px rgba(15,23,42,.12);padding:16px}
.crs2-preview-image{display:block;width:100%;height:auto;border-radius:14px}
.crs2-summary-card{background:#fff;border-radius:18px;box-shadow:0 14px 35px rgba(15,23,42,.08);padding:24px 32px;margin-bottom:40px}
.crs2-summary-title{margin:0 0 6px;font-size:22px;font-weight:800;color:#111827;text-align:right}
.crs2-summary-sub{margin:0 0 14px;font-size:14px;color:#6b7280;text-align:right}
.crs2-summary-text p{margin:0 0 4px;font-size:14px;color:#4b5563;line-height:1.9;text-align:right}
.crs2-content{margin-top:10px}
.crs2-content-header{text-align:right;margin-bottom:18px}
.crs2-content-title{font-size:26px;font-weight:800;margin:0;color:#111827}
.crs2-content-title span{border-bottom:4px solid #00a3ff;padding-bottom:4px}
.crs2-sections{display:flex;flex-direction:column;gap:18px}
.crs2-section{background:#fff;border-radius:18px;box-shadow:0 12px 30px rgba(15,23,42,.09);overflow:hidden}
.crs2-section-header{width:100%;display:flex;align-items:center;gap:16px;padding:16px 22px;background:#f7f7f9;border:none;cursor:pointer}
.crs2-section-main{flex:1;text-align:right}
.crs2-section-title{margin:0 0 2px;font-size:18px;font-weight:800;color:#111827}
.crs2-section-sub{margin:0;font-size:13px;color:#6b7280}
.crs2-section-icon{font-size:18px;color:#ff4b5c}
.crs2-section-arrow{display:inline-block;width:18px;height:18px;border-radius:999px;border:2px solid #111827;position:relative}
.crs2-section-arrow::before{content:"";position:absolute;top:2px;left:4px;width:7px;height:8px;border-right:2px solid #111827;border-bottom:2px solid #111827;transform:rotate(45deg)}
.crs2-section-body{
    padding:14px 18px 18px;
    border-top:1px solid #eceff4;
    display:none;
}

.crs2-section--open .crs2-section-body{
    display:block;
}
.crs2-section--open .crs2-section-header{background:#ffe5ea}
.crs2-section--open .crs2-section-arrow{transform:rotate(180deg)}
.crs2-subgroup{margin-bottom:12px;border-radius:12px;overflow:hidden}
.crs2-subgroup:last-child{margin-bottom:0}
.crs2-sub-header{width:100%;display:flex;align-items:center;justify-content:space-between;padding:12px 14px;background:#fffbea;border:none;cursor:pointer;font-size:14px}
.crs2-sub-title{font-weight:700;color:#b91c1c}
.crs2-sub-toggle{font-size:13px;color:#4b5563}
.crs2-sub-body{background:#fff;border-top:1px solid #f1f5f9;padding:10px 14px;border-inline-start:3px solid #facc15}
.crs2-subgroup:not(.crs2-subgroup--open) .crs2-sub-body{display:none}
.crs2-resource{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:9px 0;border-bottom:1px solid #f3f4f6}
.crs2-resource:last-child{border-bottom:none}
.crs2-resource-main{display:flex;align-items:center;gap:10px;flex:1}
.crs2-resource-icon{width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;background:#e5f0ff;color:#0055d2;font-size:14px}
.crs2-resource-title{font-size:14px;color:#111827}
.crs2-resource-actions{display:flex;align-items:center;gap:10px}
.crs2-resource-meta{display:flex;gap:6px;font-size:12px;color:#6b7280}
.crs2-meta-chip{padding:3px 8px;border-radius:999px;background:#e5f3ff}
.crs2-pill-btn{display:inline-flex;align-items:center;justify-content:center;border-radius:999px;padding:6px 14px;font-size:13px;font-weight:700;border:none;text-decoration:none;cursor:pointer}
.crs2-pill-btn--blue{background:#1d4ed8;color:#fff}
.crs2-pill-btn--gray{background:#e5e7eb;color:#111827}
.crs2-pill-btn--red{background:#ef4444;color:#fff}
.crs2-pill-btn--orange{background:#f97316;color:#fff}
.crs2-resource--exam .crs2-resource-icon{background:#fee2e2;color:#b91c1c}
.crs2-resource--task .crs2-resource-icon{background:#ffedd5;color:#c2410c}
.crs2-resource--video .crs2-resource-icon{background:#dbeafe;color:#1d4ed8}
@media(max-width:1024px){
.crs2-hero-inner{padding:0 20px}
.crs2-main-top{flex-direction:column}
.crs2-side-card{width:100%}
}
@media(max-width:768px){
.crs2-hero{padding:50px 0 60px}
.crs2-hero-title{font-size:26px}
.crs2-main-inner{padding:0 12px}
.crs2-summary-card{padding:20px}
.crs2-content-title{font-size:22px}
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('click',function(e){
    var head=e.target.closest('.crs2-section-header');
    if(head){
        var section=head.closest('.crs2-section');
        section.classList.toggle('crs2-section--open');
        return;
    }
    var subHead=e.target.closest('.crs2-sub-header');
    if(subHead){
        var group=subHead.closest('.crs2-subgroup');
        group.classList.toggle('crs2-subgroup--open');
        var toggle=subHead.querySelector('.crs2-sub-toggle');
        if(toggle){
            if(group.classList.contains('crs2-subgroup--open')) toggle.textContent='إخفاء';
            else toggle.textContent='عرض';
        }
    }
});
</script>
@endpush
