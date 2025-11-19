@extends('layouts.app')
@section('title', __('front.Contact Us'))

@section('content')
<section class="qdemy-about">
    <div class="qdemy-about__inner">

        <div class="qdemy-about__hero">
            <div class="qdemy-about__hero-image">
                <img src="{{ asset('assets_front/images/qdemy-hero-books.png') }}" alt="QDEMY Platform">
            </div>
        </div>

        <div class="qdemy-about__features">
            <div class="qdemy-about__features-bg"></div>
            <div class="qdemy-about__features-grid">
                <div class="qdemy-feature qdemy-feature--image">
                    <img src="{{ asset('assets_front/images/qdemy-card-1.png') }}" alt="تعلم بذكاء">
                </div>
                <div class="qdemy-feature qdemy-feature--image">
                    <img src="{{ asset('assets_front/images/qdemy-card-2.png') }}" alt="بنشرحها صح">
                </div>
                <div class="qdemy-feature qdemy-feature--image">
                    <img src="{{ asset('assets_front/images/qdemy-card-3.png') }}" alt="يوصلك للعلامة الكاملة">
                </div>
            </div>
        </div>


        <div class="qdemy-about__founder">
                <div class="qdemy-about__founder-circle">
                    <img src="{{ asset('assets_front/images/qdemy-founder.png') }}" alt="Ahmed Khalil">
                </div>
        </div>

        <div class="qdemy-about__identity">
                <div class="qdemy-about__founder-id">
                    <img src="{{ asset('assets_front/images/qdemy-iden.png') }}" alt="Ahmed Khalil">
                </div>
        </div>

    </div>
</section>
@endsection

@push('styles')
<style>
.qdemy-about{
    position:relative;
    padding:70px 0 90px;
    background:#ffffff;
    direction:rtl;
    text-align:center;
}
.qdemy-about__inner{
    margin:0 auto;
}

.qdemy-about__founder-circle img {
    width: 1200px;
}

.qdemy-about__founder {
    max-width: 1300px;
    margin: auto;
}
.qdemy-about__hero{
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    text-align:center;
    margin-bottom:60px;
}

.qdemy-about__hero-image img{
    max-width:900px;
    width:100%;
    height:auto;
    display:block;
}

.qdemy-about__founder-id img {
    margin: auto;
    text-align: center;
    width: 1000px;
}

.qdemy-about__identity {
    max-width: 1300px;
    margin: auto;
}

.qdemy-about__features{
    position:relative;
    margin-bottom:80px;
}
.qdemy-about__features-bg{
    position: absolute;
    inset-inline: 0;
    height: 270px;
    background: #287cf0;
    z-index: 1;
}
.qdemy-about__features{
    position:relative;
    margin-bottom:80px;
}


.qdemy-about__features-grid{
    position:relative;
    z-index:2;
    display:grid;
    grid-template-columns:repeat(3,minmax(0,1fr));
    align-items:stretch;
    padding: 0 200px;
}

.qdemy-feature{
    border-radius:26px;
    overflow:hidden;
}

.qdemy-feature--image{
    background:transparent;
    box-shadow:none;
    padding:0;
}

.qdemy-feature--image img{
    display:block;
    width:60%;
    height:auto;
    border-radius:26px;
    margin: auto;
}


@media (max-width:768px){
    .qdemy-about__features{
        margin-bottom:60px;
    }
    .qdemy-about__features-grid{
        grid-template-columns:1fr!important;
        max-width:360px!important;
        margin:0 auto!important;
        gap:16px!important;
        padding: 0!important;
    }
    .qdemy-about__features-bg {
        height: 730px;
    }
    .qdemy-about__founder-circle img {
    width: 400px;
}
.qdemy-about__founder-id img {
    width: 400px;
}
}

@media (max-width:480px){
    .qdemy-about__features-grid{
        max-width:100%;
    }
}


.qdemy-about__founder-text{
    flex:1;
}
.qdemy-about__section-title{
    position:relative;
    display:inline-block;
    font-size:26px;
    font-weight:800;
    color:#0055D2;
    margin:0 0 8px;
    padding:0 10px 6px;
}
.qdemy-about__section-title span{
    position:relative;
    display:inline-block;
}
.qdemy-about__section-title span::after{
    content:"";
    position:absolute;
    left:0;
    right:0;
    bottom:6px;
    height:14px;
    background:#ffd602;
    z-index:-1;
}
.qdemy-about__section-title--center{
    text-align:center;
}
.qdemy-about__section-title--center span::after{
    bottom:6px;
}
.qdemy-about__section-tagline{
    font-size:15px;
    color:#6b7280;
    margin:0 0 16px;
}
.qdemy-about__founder-name{
    font-size:20px;
    font-weight:800;
    color:#0055D2;
    margin:0 0 6px;
}
.qdemy-about__founder-nickname{
    font-size:15px;
    color:#111827;
    margin:0 0 14px;
}
.qdemy-about__paragraph{
    font-size:15px;
    line-height:1.9;
    color:#4b5563;
    margin:0 0 12px;
}

@media (max-width:992px){
    .qdemy-about__features-grid{
        padding:24px 24px;
    }
}
@media (max-width:768px){
    .qdemy-about{
        padding:40px 0 60px;
    }
    .qdemy-about__hero{
        margin-bottom:40px;
    }
    .qdemy-about__hero-image img{
        max-width:520px;
    }
    .qdemy-about__features-grid{
        grid-template-columns:1fr;
        padding:20px;
        gap:16px;
    }
}
@media (max-width:480px){
    .qdemy-about__features-grid{
        padding:16px;
        gap:12px;
    }
    .qdemy-about__hero-image img{
        max-width:100%;
    }
}
</style>
@endpush
