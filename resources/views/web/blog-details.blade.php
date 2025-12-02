@extends('layouts.app')

@section('content')
<div class="blog-details">
    <div class="container">
        <!-- Cover Image -->
        <div class="blog-details__cover">
            <img src="{{ $blog->photo_cover ? asset('assets/admin/uploads/' . $blog->photo_cover) : ($blog->photo ? asset('assets/admin/uploads/' . $blog->photo) : asset('assets_front/images/blog1.png')) }}" 
                 alt="{{ app()->getLocale() == 'ar' ? $blog->title_ar : $blog->title_en }}">
        </div>

        <!-- Blog Content -->
        <div class="blog-details__content">
            <h1 class="blog-details__title">
                {{ app()->getLocale() == 'ar' ? $blog->title_ar : $blog->title_en }}
            </h1>

            <div class="blog-details__meta">
                <span class="blog-details__date">
                    <i class="far fa-calendar"></i>
                    {{ $blog->created_at->format('M d, Y') }}
                </span>
            </div>

            <div class="blog-details__body">
                {!! app()->getLocale() == 'ar' ? $blog->description_ar : $blog->description_en !!}
            </div>
        </div>

    </div>
</div>
@endsection


