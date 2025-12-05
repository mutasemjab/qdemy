@extends('layouts.app')

@section('content')
<div class="blog-details">
    <div class="container">
        <div class="blog-details__cover">
            <img src="{{ $blog->photo_cover ? asset('assets/admin/uploads/' . $blog->photo_cover) : ($blog->photo ? asset('assets/admin/uploads/' . $blog->photo) : asset('assets_front/images/blog1.png')) }}" 
                 alt="{{ app()->getLocale() == 'ar' ? $blog->title_ar : $blog->title_en }}">
        </div>

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

<style>
.blog-details {
  padding: 60px 0 80px 0;
  background: linear-gradient(180deg, #f5f7fb 0%, #ffffff 60%);
}

.blog-details .container {
  max-width: 1100px;
  margin: 0 auto;
  display: block;
}

.blog-details__cover {
  margin-bottom: 26px;
}

.blog-details__cover img {
  width: 100%;
  border-radius: 26px;
  box-shadow: 0 18px 45px rgba(0, 0, 0, 0.12);
  display: block;
}

.blog-details__content {
  background: #ffffff;
  border-radius: 24px;
  padding: 32px 30px 36px;
  box-shadow: 0 18px 40px rgba(7, 31, 84, 0.08);
  position: relative;
}

.blog-details__content::before {
  content: "";
  position: absolute;
  inset-inline-start: -14px;
  inset-block-start: 18px;
  width: 4px;
  height: 50px;
  border-radius: 999px;
  background: var(--main-color, #0055d3);
}

.blog-details__title {
  font-size: 26px;
  line-height: 1.5;
  font-weight: 800;
  color: #0e1b3d;
  margin: 0 0 16px;
}

.blog-details__meta {
  display: flex;
  align-items: center;
  gap: 14px;
  font-size: 13px;
  color: #6b7280;
  margin-bottom: 22px;
}

.blog-details__date i {
  margin-inline-end: 6px;
  color: var(--main-color, #0055d3);
}

.blog-details__body {
  font-size: 15px;
  line-height: 1.9;
  color: #384152;
}

.blog-details__body p {
  margin-bottom: 14px;
}

.blog-details__body h2,
.blog-details__body h3,
.blog-details__body h4 {
  color: #0e1b3d;
  margin: 20px 0 10px;
  font-weight: 700;
}

.blog-details__body ul,
.blog-details__body ol {
  padding-inline-start: 20px;
  margin: 10px 0 16px;
}

.blog-details__body li {
  margin-bottom: 6px;
}

.blog-details__body a {
  color: var(--main-color, #0055d3);
  text-decoration: underline;
  text-decoration-thickness: 1px;
}

.blog-details__body a:hover {
  text-decoration: none;
}

@media (max-width: 992px) {
  .blog-details {
    padding: 40px 0 60px 0;
  }

  .blog-details__cover img {
    height: 280px;
    border-radius: 22px;
  }

  .blog-details__content {
    padding: 24px 20px 30px;
  }

  .blog-details__title {
    font-size: 22px;
  }
}

@media (max-width: 600px) {
  .blog-details {
    padding: 30px 0 50px 0;
  }

  .blog-details .container {
    padding: 0 16px;
  }

  .blog-details__cover {
    margin-bottom: 18px;
  }

  .blog-details__cover img {
    height: 220px;
    border-radius: 18px;
  }

  .blog-details__content {
    border-radius: 18px;
    padding: 20px 16px 24px;
  }

  .blog-details__title {
    font-size: 20px;
  }

  .blog-details__meta {
    font-size: 12px;
    margin-bottom: 18px;
  }

  .blog-details__body {
    font-size: 14px;
  }
}
</style>
@endsection
