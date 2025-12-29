@if($isApi)
@php $hideFooter = true; @endphp
@php $hideHeader = true; @endphp
@endif
@extends('layouts.app')
@section('title', __('front.Doseyat'))

@section('content')
<section class="co-page">
  <div class="universities-header-wrapper00" data-aos="fade-up" data-aos-duration="1000">
        <div class="universities-header-d">
            <img
                src="{{ app()->getLocale() == 'ar'
                    ? asset('assets_front/images/header-line-d.png')
                    : asset('assets_front/images/en/header-line-d.png') }}"
                alt=""
                class="universities-header-img"
                loading="lazy"
            >
        </div>
  </div>

  <div class="co-chooser" data-aos="zoom-in" data-aos-duration="1000" data-aos-delay="200">
    <select id="coChooserSelect" class="co-chooser-select" aria-label="{{ __('front.choose_your_doseya') }}">
      <option value="">{{ __('front.all_categories') }}</option>
      @foreach($categories as $category)
        <option value="{{ $category->id }}" data-label="{{ app()->getLocale() == 'ar' ? $category->name_ar : $category->name_en }}">
          {{ app()->getLocale() == 'ar' ? $category->name_ar : $category->name_en }}
        </option>
      @endforeach
    </select>
  </div>

  <div class="co-actions co-actions-select" data-aos="zoom-in" data-aos-duration="1000" data-aos-delay="260">
    <div class="co-action-tile" data-action="whatsapp">
      <div class="co-action-title">{{ __('front.contact_whatsapp') }}</div>
    </div>
    <div class="co-action-tile" data-action="cash">
      <div class="co-action-title">{{ __('front.cash_payment') }}</div>
    </div>
    <div class="co-action-tile" data-action="visa">
      <div class="co-action-title">{{ __('front.visa_payment') }}</div>
    </div>
  </div>

  <div class="co-grid" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="320">
    @foreach($doseyats as $doseyat)
      
        <a class="co-card"
        data-card-id="{{ $doseyat->id }}"
        data-card-name="{{ $doseyat->name }}"
        data-card-price="{{ $doseyat->price }}"
        data-category-id="{{ $doseyat->category_id }}">
        <img src="{{ asset('assets/admin/uploads/' . $doseyat->photo) }}" alt="{{ $doseyat->name }}">
        <div class="card-overlay"><i class="fas fa-check"></i></div>
      </a>
    @endforeach
  </div>

  <div class="co-panels">
    <div id="panelWhatsapp" class="co-panel" style="display:none; padding: 20px" data-aos="zoom-in" data-aos-duration="1000">
      <div class="selected-order-summary" id="summaryWhatsApp">
        <h3>{{ __('front.order_summary') }}</h3>
        <div class="summary-item">
          <span>{{ __('front.category') }}:</span>
          <span id="waCategory"></span>
        </div>
        <div class="summary-item">
          <span>{{ __('front.doseyat') }}:</span>
          <span id="waDoseyat"></span>
        </div>
        <div class="summary-item total">
          <span>{{ __('front.total') }}:</span>
          <span id="waPrice"></span>
        </div>
      </div>
      <a href="#" class="co-btn" id="whatsappBtn">{{ __('front.contact') }}</a>
    </div>

    <div id="panelCash" class="co-panel" style="display:none; padding: 20px" data-aos="zoom-in" data-aos-duration="1000">
      <div class="selected-order-summary" id="summaryCash">
        <h3>{{ __('front.order_summary') }}</h3>
        <div class="summary-item">
          <span>{{ __('front.category') }}:</span>
          <span id="cashCategory"></span>
        </div>
        <div class="summary-item">
          <span>{{ __('front.doseyat') }}:</span>
          <span id="cashDoseyat"></span>
        </div>
        <div class="summary-item total">
          <span>{{ __('front.total') }}:</span>
          <span id="cashPrice"></span>
        </div>
      </div>
      <a href="#" class="co-btn co-btn-inv" id="cashBtn">{{ __('front.submit') }}</a>
    </div>

    <div id="panelVisa" class="co-panel" style="display:none; padding: 20px" data-aos="zoom-in" data-aos-duration="1000">
      <div class="payment-header">
        <h2 class="checkout-heading">{{ __('front.payment_methods') }}</h2>
      </div>

      <div class="selected-order-summary" id="orderSummary">
        <h3>{{ __('front.order_summary') }}</h3>
        <div class="summary-item">
          <span>{{ __('front.category') }}:</span>
          <span id="summaryCategory"></span>
        </div>
        <div class="summary-item">
          <span>{{ __('front.doseyat') }}:</span>
          <span id="summaryDoseyat"></span>
        </div>
        <div class="summary-item total">
          <span>{{ __('front.total') }}:</span>
          <span id="summaryPrice"></span>
        </div>
      </div>

      <div class="payment-options">
        <button class="payment-option active">VISA</button>
      </div>

      <form class="payment-form" id="payment-form">
        <div class="payment-field">
          <label>{{ __('front.cardholder_name') }}</label>
          <input type="text" name="cardholder_name" placeholder="{{ __('front.seen_on_card') }}" required>
        </div>

        <div class="payment-field">
          <label>{{ __('front.card_number') }}</label>
          <input type="text" name="card_number" placeholder="{{ __('front.seen_on_card') }}" required>
        </div>

        <div class="payment-double">
          <div class="payment-field">
            <label>{{ __('front.expiry') }}</label>
            <input type="text" name="expiry" placeholder="MM/YY" required>
          </div>
          <div class="payment-field">
            <label>CVC</label>
            <input type="text" name="cvc" placeholder="***" required>
          </div>
        </div>

        <button type="submit" class="payment-submit">
          {{ __('front.pay') }} <span id="paymentAmount"></span>
        </button>
      </form>
    </div>
  </div>
</section>

<style>
.co-actions-select {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 14px;
  margin: 18px 0;
}
.co-action-tile {
  background: #f3f6fb;
  border: 1px solid #e2e8f4;
  border-radius: 12px;
  padding: 16px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background .2s ease, border-color .2s ease, box-shadow .2s ease;
}
.co-action-tile.active {
  color: #fff;
  background: #0055d3;
  border-color: #2e6cf0;
  box-shadow: 0 6px 18px rgba(46,108,240,.18);
}
.co-action-title {
  font-weight: 700;
}
.co-chooser {
  position: relative;
  max-width: 420px;
}
.co-chooser-select {
  width: 100%;
  appearance: none;
  -webkit-appearance: none;
  -moz-appearance: none;
  background: #fff;
  border: 1px solid #e2e6ef;
  border-radius: 10px;
  padding: 12px 44px 12px 14px;
  font: inherit;
  cursor: pointer;
  background-image: url("data:image/svg+xml,%3Csvg width='18' height='18' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M7 10l5 5 5-5' stroke='%23111827' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: calc(100% - 12px) 50%;
  background-size: 18px 18px;
}
.co-chooser-select:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(46,108,240,.12);
  border-color: #2e6cf0;
}
.co-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 14px;
  margin-top: 8px;
}
.co-card {
  cursor: pointer;
  position: relative;
  background: #fff;
  border: 1px solid #e2e6ef;
  border-radius: 12px;
  overflow: hidden;
  display: block;
  transition: all 0.3s ease;
}
.co-card img {
  width: 100%;
  height: 250px;
  object-fit: cover;
  display: block;
}
.co-card.selected {
  border: 2px solid #2e6cf0;
  box-shadow: 0 8px 24px rgba(46,108,240,.18);
}
.card-overlay {
  position: absolute;
  top: 10px;
  right: 10px;
  width: 30px;
  height: 30px;
  background: #2e6cf0;
  border-radius: 50%;
  display: none;
  align-items: center;
  justify-content: center;
  color: #fff;
  font-size: 16px;
}
.co-card.selected .card-overlay {
  display: flex;
}
.co-panels {
  margin-top: 18px;
}
.selected-order-summary {
  background: #f8f9fa;
  padding: 16px;
  border-radius: 10px;
  margin-bottom: 14px;
}
.selected-order-summary h3 {
  margin: 0 0 12px;
}
.summary-item {
  display: flex;
  justify-content: space-between;
  margin-bottom: 8px;
  padding-bottom: 8px;
}
.summary-item.total {
  border-top: 1px solid #ddd;
  margin-top: 10px;
  padding-top: 10px;
  font-weight: 700;
}
.co-btn,
.co-btn-inv {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 12px 16px;
  border-radius: 10px;
  text-decoration: none;
  cursor: pointer;
}
.co-btn {
  background: #2e6cf0;
  color: #fff;
  border: 0;
}
.co-btn-inv {
  background: #111827;
  color: #fff;
  border: 0;
}
.payment-header {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 12px;
}
.payment-options {
  display: flex;
  gap: 8px;
  margin-bottom: 12px;
}
.payment-option {
  background: #eef0f3;
  border: 0;
  border-radius: 8px;
  padding: 8px 12px;
  font-weight: 700;
  cursor: pointer;
}
.payment-option.active {
  background: #2e6cf0;
  color: #fff;
}
.payment-form {
  background: #fff;
  border: 1px solid #eee;
  border-radius: 12px;
  padding: 14px;
}
.payment-field {
  display: flex;
  flex-direction: column;
  gap: 6px;
  margin-bottom: 10px;
}
.payment-field input {
  border: 1px solid #e2e6ef;
  border-radius: 10px;
  padding: 12px 14px;
  background: #fff;
}
.payment-double {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 12px;
}
.payment-submit {
  width: 100%;
  background: #2e6cf0;
  border: 0;
  border-radius: 12px;
  color: #fff;
  font-weight: 800;
  padding: 12px 16px;
  cursor: pointer;
}
.hidden {
  display: none !important;
}
@media (max-width: 992px) {
  .co-grid {
    grid-template-columns: repeat(2, 1fr);
  }
  .payment-double {
    grid-template-columns: 1fr;
  }
}
@media (max-width: 600px) {
  .co-actions-select {
    grid-template-columns: 1fr;
  }
  .co-grid {
    grid-template-columns: 1fr;
  }
  .co-chooser {
    max-width: 100%;
  }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const whatsappNumber = '+962795970357';
  let selectedAction = '';
  let selectedCategoryId = '';
  let selectedCategoryLabel = '';
  let selectedCard = null;

  const selectEl = document.getElementById('coChooserSelect');
  const tiles = [...document.querySelectorAll('.co-action-tile')];
  const cards = [...document.querySelectorAll('.co-card')];

  const coPanels = document.querySelector('.co-panels');
  const panelWhatsapp = document.getElementById('panelWhatsapp');
  const panelCash = document.getElementById('panelCash');
  const panelVisa = document.getElementById('panelVisa');

  const whatsappBtn = document.getElementById('whatsappBtn');
  const cashBtn = document.getElementById('cashBtn');
  const paymentForm = document.getElementById('payment-form');
  const payAmount = document.getElementById('paymentAmount');

  function getCategoryLabel() {
    if (!selectedCategoryId) return '{{ __("front.all_categories") }}';
    return selectedCategoryLabel;
  }

  function filterCards() {
    let hasVisibleCards = false;
    
    cards.forEach(c => {
      const cardCategoryId = c.dataset.categoryId || '';
      const show = selectedCategoryId ? cardCategoryId === selectedCategoryId : true;
      c.classList.toggle('hidden', !show);
      
      if (show) {
        hasVisibleCards = true;
      }
      
      // Remove selection if card is hidden
      if (!show && c.classList.contains('selected')) {
        c.classList.remove('selected');
        if (selectedCard && selectedCard.id === c.dataset.cardId) {
          selectedCard = null;
        }
      }
    });
    
    updateSummaries();
    showPanel();
  }

  function updateSummaries() {
    const categoryText = getCategoryLabel();
    const doseyatName = selectedCard?.name || '-';
    const price = selectedCard?.price ? (selectedCard.price + ' ' + '{{ __("front.currency") }}') : '-';
    
    // Update all summaries
    const summaries = [
      {category: 'waCategory', doseyat: 'waDoseyat', price: 'waPrice'},
      {category: 'cashCategory', doseyat: 'cashDoseyat', price: 'cashPrice'},
      {category: 'summaryCategory', doseyat: 'summaryDoseyat', price: 'summaryPrice'}
    ];
    
    summaries.forEach(sum => {
      const catEl = document.getElementById(sum.category);
      const dosEl = document.getElementById(sum.doseyat);
      const priceEl = document.getElementById(sum.price);
      
      if (catEl) catEl.textContent = categoryText;
      if (dosEl) dosEl.textContent = doseyatName;
      if (priceEl) priceEl.textContent = price;
    });
    
    if (payAmount) {
      payAmount.textContent = selectedCard?.price ? (selectedCard.price + ' ' + '{{ __("front.currency") }}') : '';
    }
  }

  function showPanel() {
    const ready = !!(selectedAction && selectedCard);
    coPanels.style.display = ready ? 'block' : 'none';
    panelWhatsapp.style.display = 'none';
    panelCash.style.display = 'none';
    panelVisa.style.display = 'none';
    
    if (ready) {
      if (selectedAction === 'whatsapp') panelWhatsapp.style.display = 'block';
      if (selectedAction === 'cash') panelCash.style.display = 'block';
      if (selectedAction === 'visa') panelVisa.style.display = 'block';
      updateSummaries();
    }
  }

  // Category filter
  selectEl.addEventListener('change', function () {
    selectedCategoryId = this.value || '';
    const opt = this.options[this.selectedIndex];
    selectedCategoryLabel = opt ? (opt.dataset.label || opt.textContent.trim()) : '';
    filterCards();
  });

  // Action tiles
  tiles.forEach(t => {
    t.addEventListener('click', () => {
      tiles.forEach(x => x.classList.remove('active'));
      t.classList.add('active');
      selectedAction = t.getAttribute('data-action');
      showPanel();
    });
  });

  // Card selection
  cards.forEach(card => {
    card.addEventListener('click', function (e) {
      e.preventDefault();
      if (this.classList.contains('hidden')) return;
      
      cards.forEach(c => c.classList.remove('selected'));
      this.classList.add('selected');
      
      selectedCard = {
        id: this.dataset.cardId,
        name: this.dataset.cardName,
        price: this.dataset.cardPrice
      };
      
      showPanel();
    });
  });

  // WhatsApp contact
  if (whatsappBtn) {
    whatsappBtn.addEventListener('click', function (e) {
      e.preventDefault();
      if (!selectedCard) return;
      
      const msg = '{{ __("front.whatsapp_contact_message") }}'
        .replace(':card_type', getCategoryLabel())
        .replace(':card_name', selectedCard.name)
        .replace(':card_price', selectedCard.price + ' ' + '{{ __("front.currency") }}');
      
      const url = `https://wa.me/${whatsappNumber.replace('+', '')}?text=${encodeURIComponent(msg)}`;
      window.open(url, '_blank');
    });
  }

  // Cash payment
  if (cashBtn) {
    cashBtn.addEventListener('click', function (e) {
      e.preventDefault();
      if (!selectedCard) return;
      
      const msg = '{{ __("front.whatsapp_cash_message") }}'
        .replace(':card_type', getCategoryLabel())
        .replace(':card_name', selectedCard.name)
        .replace(':card_price', selectedCard.price + ' ' + '{{ __("front.currency") }}');
      
      const url = `https://wa.me/${whatsappNumber.replace('+', '')}?text=${encodeURIComponent(msg)}`;
      window.open(url, '_blank');
    });
  }

  // Visa payment
  if (paymentForm) {
    paymentForm.addEventListener('submit', function (e) {
      e.preventDefault();
      if (!selectedCard) return;
      alert('{{ __("front.payment_submitted") }}');
    });
  }

  // Initialize
  coPanels.style.display = 'none';
  filterCards();
});
</script>
@endsection