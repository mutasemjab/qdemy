@extends('layouts.user')

@section('content')
      <!-- Hero Section -->
  
<section class="hero" id="home">
    <div class="container">
        <div class="hero-content">
            <div class="hero-products">
                <div class="hero-product-img">
                    <!-- Desktop Image -->
                    <img src="{{ asset('assets/admin/uploads/' . $banner->photo_for_desktop) }}" 
                         class="hero-image desktop-only" 
                         alt="Hero Banner Desktop">

                    <!-- Mobile Image -->
                    <img src="{{ asset('assets/admin/uploads/' . $banner->photo_for_mobile) }}" 
                         class="hero-image mobile-only" 
                         alt="Hero Banner Mobile">
                </div>
            </div>
        </div>
    </div>
</section>


    <!-- Products Section -->
    <div class="section-divider"></div>
    <h2 class="products-title">Products</h2>
    <section class="products" id="products">
        <div class="container">

            <div class="products-grid">
                @foreach ($products as $product )
                
                <div class="product-card">
    <a class="product-link" href="{{route('product.details',$product->id)}}">
                    <div class="product-image">
                        @if($product->productImages->count() > 0)
                        <img src="{{ optional($product->productImages->first())->photo
                        ? asset('assets/admin/uploads/' . $product->productImages->first()->photo)
                        : asset('assets_front/assets/images/logo.png') }}" alt="{{ $locale == 'en' ? $product->name_en : $product->name_ar }}" class="main-product-image" id="mainProductImage">
                        
                        <div class="product-thumbnails">
                            @foreach($product->productImages->skip(1) as $index => $image)
                            <img src="{{ asset('assets/admin/uploads/' . $image->photo) }}" alt="Thumbnail {{ $index + 1 }}" class="thumbnail {{ $index == 0 ? 'active-thumb' : '' }}" onclick="changeMainImage(this)">
                            @endforeach
                        </div>
                        @else
                        <img src="{{ asset('assets_front/assets/images/logo.png') }}" alt="No image available" class="main-product-image">
                        @endif
                    </div>
                      </a>
                    <a class="product-link" href="{{route('product.details',$product->id)}}">
                    <h3>{{ $locale == 'en' ? $product->name_en : $product->name_ar }}</h3>
                    <p>{{ $locale == 'en' ? $product->description_en : $product->description_ar }}</p>
                    <div class="product-price">{{ $product->selling_price }} JD</div>
                    </a>
                </div>
              
                 @endforeach

            </div>
        </div>
    </section>
   
    <section class="products" id="products">
        <div class="container">

            <div class="products-grid">
                @foreach ($packages as $package )
                
                <div class="product-card">
                    <div class="product-image">
                        @if($package->count() > 0)
                        <img src="{{ optional($package)->photo
                        ? asset('assets/admin/uploads/' . $package->photo)
                        : asset('assets_front/assets/images/logo.png') }}" alt="{{ $locale == 'en' ? $package->name_en : $package->name_ar }}" class="main-product-image" id="mainProductImage">
                      
                        @else
                        <img src="{{ asset('assets_front/assets/images/logo.png') }}" alt="No image available" class="main-product-image">
                        @endif
                    </div>
                    <a class="product-link" href="{{route('package.details',$package->id)}}">
                    <h3>{{ $locale == 'en' ? $package->name_en : $package->name_ar }}</h3>
                    <p>{{ $locale == 'en' ? $package->description_en : $package->description_ar }}</p>
                    <div class="product-price">{{ $package->price }} JD</div>
                    </a>
                </div>
              
                 @endforeach

            </div>
        </div>
    </section>

    <!-- About Section -->
    <div class="section-divider"></div>
    <section class="about" id="about-us-section">
        <h2 class="about-title">About us</h2>
        <div class="container">
            <div class="about-content">
                <div class="about-image">
                    <img src="{{ asset('assets/admin/uploads/' . $page->photo) }}" alt="About Image">
                </div>
                <div class="about-text">
                    <p >{!! $locale == 'en' ? $page->content_en : $page->content_ar !!}</p>
                  </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <div class="section-divider"></div>
    <section class="contact" id="contact"> 
        <h2 class="contact-title">Contact us</h2>
        <div class="container">

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form class="contact-form" method="POST" action="{{ route('contact.store') }}">
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <input type="text" name="name" placeholder="Name" required>
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" placeholder="Email Address">
                    </div>
                </div>
 
                <div class="form-group">
                    <input type="number" name="phone" placeholder="Phone Number" required>
                </div>
                <div class="form-group">
                    <input type="text" name="subject" placeholder="Subject" required>
                </div>
                <div class="form-group">
                    <textarea name="message" placeholder="Your Message" required></textarea>
                </div>
                <button type="submit" class="shop-btn">Send Message</button>
            </form>
        </div>
    </section>

@endsection
