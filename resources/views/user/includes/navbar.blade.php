<header>
        <nav>

              <!-- زر القائمة للموبايل -->
              <div class="menu-toggle" id="navbar-toggle">
                <span></span>
                <span></span>
                <span></span>
              </div>
              
            <ul class="nav-left">
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">{{ __('messages.Home') }}</a></li>
                <li><a href="{{ route('home') }}#about-us-section">{{ __('messages.About') }}</a></li>
                <li><a href="#products">Products</a></li>
            </ul>

            <div class="logo">
               <a href="{{ route('home') }}"><img src="{{ asset('assets/admin/uploads/myimage.png') }}" alt="Logo"></a> 
            </div>

            <ul class="nav-right">
                <li><a href="#contact">Contact us</a></li>
                 @if (!Auth::check())
                   <li> <a href="{{ route('user.login') }}"><strong>{{ __('messages.Login') }}</strong></a></li>
                   <li> <a href="{{ route('user.register') }}"><strong>{{ __('messages.Register') }}</strong></a></li>
                 @else
                  <li><a href="{{ route('profile') }}">{{ __('messages.My Account') }}</a></li>
                  <li><a href="{{ route('orders.history') }}">{{ __('messages.My Orders') }}</a></li>
                  <li>
                    <form action="{{ route('logout') }}" method="POST">
                      @csrf
                      <button type="submit" class="logout-btn">{{ __('messages.Logout') }}</button>
                    </form>
                  </li>
                 @endif
                <div class="cart-container">
                  <a href="{{ route('cart.index') }}">
                    <i class="fa fa-shopping-cart cart-icon"></i>
                    @php
                      $cartCount = App\Models\Cart::where('user_id', Auth::id())->where('status', 0)->count();
                        $locale = App::getLocale();
                    @endphp
                    @if($cartCount > 0)
                      <span class="cart-badge">{{ $cartCount }}</span>
                    @endif
                  </a>
                  <!--
                  <div class="cart-dropdown">
                    <hr />
                    @php
                      $cartItems = App\Models\Cart::where('user_id', Auth::id())
                                    ->where('status', 0)
                                    ->with(['product'])
                                    ->limit(3)
                                    ->get();
                      $subtotal = $cartItems->sum('total_price_product');
                    @endphp

                    <div class="cart-box-dropdown">
                      @forelse($cartItems as $item)
                        <div class="cart-box-item">
                          @if($item->product && $item->product->productImages && $item->product->productImages->first())
                            <img class="cart-box-img" src="{{ asset('assets/admin/uploads/' . $item->product->productImages->first()->photo) }}" alt="{{ $item->product->name_en }}" />
                          @else
                            <img class="cart-box-img" src="{{ asset('assets_front/assets/images/placeholder.png') }}" alt="Product" />
                          @endif

                         <div class="cart-box-info">
                            @if($item->product_id && $item->product)
                                {{-- Display Product Name --}}
                                <p>{{ $locale == 'en' ? $item->product->name_en : $item->product->name_ar }}</p>
                            @elseif($item->package_id && $item->package)
                                {{-- Display Package Name --}}
                                <p>{{ $locale == 'en' ? $item->package->name_en : $item->package->name_ar }}</p>
                            @else
                                {{-- Fallback for missing items --}}
                                <p>{{ __('messages.Item not found') }}</p>
                            @endif
                            <span>x{{ $item->quantity }}</span>
                        </div>

                          <div class="cart-box-price">{{ $item->price }} JD</div>
                        </div>
                      @empty
                        <div class="cart-box-empty">
                          <p>{{ __('messages.Your cart is empty') }}</p>
                        </div>
                      @endforelse

                        @if(count($cartItems) > 0)
                          <div class="cart-box-subtotal">
                            <span>{{ __('messages.Subtotal') }}:</span>
                            <strong>{{ $subtotal }} JD</strong>
                          </div>
                          <a href="{{ route('cart.index') }}" class="cart-box-view-btn">{{ __('messages.View Cart') }}</a>
                        @endif
                      </div>
-->
                </div>
            </ul>
        </nav>
    </header>

    <script>
document.addEventListener("DOMContentLoaded", function () {
  const toggleBtn = document.getElementById('navbar-toggle');
  const navLeft = document.querySelector('.nav-left');
  const navRight = document.querySelector('.nav-right');

  if (toggleBtn) {
    toggleBtn.addEventListener('click', () => {
      navLeft?.classList.toggle('show');
      navRight?.classList.toggle('show');
    });

    document.addEventListener('click', (e) => {
      if (
        !toggleBtn.contains(e.target) &&
        !navLeft.contains(e.target) &&
        !navRight.contains(e.target)
      ) {
        navLeft.classList.remove('show');
        navRight.classList.remove('show');
      }
    });
  }
});

</script>
