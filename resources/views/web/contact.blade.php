@extends('layouts.app')
@section('title', __('front.Contact Us'))

@section('content')
<section class="ct-page">
  <div class="ct-wrap">

    <div class="ct-info">
      <h1 class="ct-title">{{ __('front.Contact Us') }}</h1>
      <p class="ct-desc">{{ __('front.If you have any inquiry, do not hesitate to contact us. We are available 24/7 to help you.') }}</p>

      <h3 class="ct-sec">{{ __('front.Customer Service') }}</h3>
      <p class="ct-note">{{ __('front.Our support team is available around the clock to answer any questions or concerns you have.') }}</p>

      <h3 class="ct-sec">{{ __('front.Sales') }}</h3>
      <p class="ct-note">{{ __('front.Our support team is available around the clock to answer any questions or concerns you have.') }}</p>

      <h3 class="ct-sec">{{ __('front.Join Our Team') }}</h3>
      <p class="ct-note">{{ __('front.To join our team, submit your information and qualifications via the following email:') }}<br>
        <span class="ct-mail">Qdemy@info.com</span>
      </p>
    </div>

    <div class="ct-card">
      <h2 class="ct-head">{{ __('front.Contact Us') }}</h2>
      <p class="ct-sub">{{ __('front.You can contact us anytime') }}</p>

      {{-- Display success message --}}
      @if(session('success'))
        <div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
          {{ session('success') }}
        </div>
      @endif

      {{-- Display error message --}}
      @if(session('error'))
        <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
          {{ session('error') }}
        </div>
      @endif

      {{-- Display validation errors --}}
      @if($errors->any())
        <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
          <ul style="margin: 0; padding-left: 20px;">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form class="ct-form" action="{{ route('contacts.store') }}" method="post">
        @csrf
        <input class="ct-input" 
               type="text" 
               name="name" 
               placeholder="{{ __('front.First Name') }}" 
               value="{{ old('name') }}" 
               required>

        <input class="ct-input" 
               type="email" 
               name="email" 
               placeholder="{{ __('front.Your Email') }}" 
               value="{{ old('email') }}">

        <div class="ct-phone">
          <input class="ct-input ct-number" 
                 type="tel" 
                 name="phone" 
                 placeholder="{{ __('front.Phone Number') }}" 
                 value="{{ old('phone') }}">
          
          <select class="ct-code" name="country_code">
            <option value="+962" {{ old('country_code') == '+962' ? 'selected' : '' }}>+962</option>
            <option value="+966" {{ old('country_code') == '+966' ? 'selected' : '' }}>+966</option>
            <option value="+971" {{ old('country_code') == '+971' ? 'selected' : '' }}>+971</option>
            <option value="+20" {{ old('country_code') == '+20' ? 'selected' : '' }}>+20</option>
          </select>
        </div>

        <textarea class="ct-text" 
                  rows="5" 
                  name="message" 
                  placeholder="{{ __('front.How can we help you?') }}" 
                  required>{{ old('message') }}</textarea>

        <button type="submit" class="ct-btn">{{ __('front.Send') }}</button>
      </form>
    </div>

  </div>
</section>
@endsection