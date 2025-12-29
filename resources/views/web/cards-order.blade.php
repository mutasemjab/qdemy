@if($isApi)
@php $hideFooter = true; @endphp
@php $hideHeader = true; @endphp
@endif
@extends('layouts.app')
@section('title', __('front.page_title'))

@section('content')
<section class="co-page">
<div class="order-banner-shell" data-aos="fade-up" data-aos-duration="1000">
  <div class="order-banner-frame">
    <img src="{{ asset('assets_front/images/header-line.png') }}" alt="" class="order-banner-image">
    <div class="order-banner-title">
      <h2>{{ str_replace('<br>', ' ', __('front.order_card')) }}</h2>
    </div>
  </div>
</div>


  <div class="co-chooser" data-aos="zoom-in" data-aos-duration="1000" data-aos-delay="200">
    <select id="coChooserSelect" class="co-chooser-select" aria-label="{{ __('front.choose_your_card') }}">
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
    @foreach($cards as $card)
      <a class="co-card"
        data-card-id="{{ $card->id }}"
        data-card-name="{{ $card->name }}"
        data-card-price="{{ $card->price }}"
        data-category-id="{{ $card->category_id }}"
        data-doseyats='@json($card->doseyats->map(function($d) { return ["id" => $d->id, "name" => $d->name, "photo" => $d->photo]; }))'>
        <img src="{{ asset('assets/admin/uploads/' . $card->photo) }}" alt="{{ $card->name }}">
        <div class="card-overlay"><i class="fas fa-check"></i></div>
        
        @if($card->doseyats && $card->doseyats->count() > 0)
          <div class="card-free-badge">
            <i class="fas fa-gift"></i>
            <span>{{ __('front.free') }}</span>
          </div>
          <div class="card-doseyats">
            @foreach($card->doseyats as $doseyat)
              <div class="doseyat-item">
                <img src="{{ asset('assets/admin/uploads/' . $doseyat->photo) }}" alt="{{ $doseyat->name }}">
                <span>{{ $doseyat->name }}</span>
              </div>
            @endforeach
          </div>
        @endif
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
          <span>{{ __('front.card') }}:</span>
          <span id="waCard"></span>
        </div>
        <div id="waDoseyats" class="summary-doseyats" style="display:none;">
          <div class="doseyats-header">
            <i class="fas fa-gift"></i>
            <span>{{ __('front.free_doseyats') }}:</span>
          </div>
          <div id="waDoseyatsList" class="doseyats-list"></div>
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
          <span>{{ __('front.card') }}:</span>
          <span id="cashCard"></span>
        </div>
        <div id="cashDoseyats" class="summary-doseyats" style="display:none;">
          <div class="doseyats-header">
            <i class="fas fa-gift"></i>
            <span>{{ __('front.free_doseyats') }}:</span>
          </div>
          <div id="cashDoseyatsList" class="doseyats-list"></div>
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
          <span>{{ __('front.card') }}:</span>
          <span id="summaryCard"></span>
        </div>
        <div id="visaDoseyats" class="summary-doseyats" style="display:none;">
          <div class="doseyats-header">
            <i class="fas fa-gift"></i>
            <span>{{ __('front.free_doseyats') }}:</span>
          </div>
          <div id="visaDoseyatsList" class="doseyats-list"></div>
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

/* Free Badge Styles */
.card-free-badge {
  position: absolute;
  top: 10px;
  left: 10px;
  background: linear-gradient(135deg, #10b981 0%, #059669 100%);
  color: #fff;
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 700;
  display: flex;
  align-items: center;
  gap: 4px;
  box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
  z-index: 2;
}
.card-free-badge i {
  font-size: 14px;
}

/* Doseyats Display */
.card-doseyats {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.6) 70%, transparent 100%);
  padding: 12px 8px 8px;
  display: flex;
  flex-wrap: wrap;
  gap: 6px;
  z-index: 1;
}
.doseyat-item {
  display: flex;
  align-items: center;
  gap: 6px;
  background: rgba(255, 255, 255, 0.95);
  border-radius: 8px;
  padding: 4px 8px;
  font-size: 11px;
  font-weight: 600;
  color: #1f2937;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.doseyat-item img {
  width: 24px;
  height: 24px;
  object-fit: cover;
  border-radius: 4px;
}
.doseyat-item span {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: 100px;
}

/* Summary Doseyats Styles */
.summary-doseyats {
  background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
  border: 1px solid #a7f3d0;
  border-radius: 8px;
  padding: 12px;
  margin: 12px 0;
}
.doseyats-header {
  display: flex;
  align-items: center;
  gap: 8px;
  font-weight: 700;
  color: #047857;
  margin-bottom: 8px;
  font-size: 14px;
}
.doseyats-header i {
  font-size: 16px;
}
.doseyats-list {
  display: flex;
  flex-direction: column;
  gap: 6px;
}
.doseyat-summary-item {
  display: flex;
  align-items: center;
  gap: 8px;
  background: #fff;
  padding: 6px 10px;
  border-radius: 6px;
  font-size: 13px;
  color: #1f2937;
  box-shadow: 0 1px 2px rgba(0,0,0,0.05);
}
.doseyat-summary-item img {
  width: 28px;
  height: 28px;
  object-fit: cover;
  border-radius: 4px;
}
.doseyat-summary-item span {
  flex: 1;
  font-weight: 600;
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
  .doseyat-item span {
    max-width: 80px;
  }
  
  .order-banner-image {
    object-fit: contain!important;
}
}
.order-banner-shell {
  margin-bottom: 24px;
}

.order-banner-frame {
  position: relative;
  display: block;
  max-width: 100%;
  overflow: hidden;
}

.order-banner-image {
  width: 100%;
  height: 309px;
  object-fit: cover;
  display: block;
}

.order-banner-title {
  position: absolute;
  inset: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  text-align: center;
  padding: 0 16px;
}

.order-banner-title h2 {
  margin: 0;
  font-size: clamp(20px, 2.4vw, 35px);
  font-weight: 800;
  color: #fff;
  text-shadow: 0 2px 6px rgba(0,0,0,.45);
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

  function updateDoseyats(doseyats, containerIds) {
    containerIds.forEach(ids => {
      const container = document.getElementById(ids.container);
      const list = document.getElementById(ids.list);
      
      if (!container || !list) return;
      
      if (doseyats && doseyats.length > 0) {
        container.style.display = 'block';
        list.innerHTML = doseyats.map(d => `
          <div class="doseyat-summary-item">
            <img src="{{ asset('assets/admin/uploads/') }}/${d.photo}" alt="${d.name}">
            <span>${d.name}</span>
          </div>
        `).join('');
      } else {
        container.style.display = 'none';
        list.innerHTML = '';
      }
    });
  }

  function updateSummaries() {
    const categoryText = getCategoryLabel();
    const cardName = selectedCard?.name || '-';
    const price = selectedCard?.price ? (selectedCard.price + ' ' + '{{ __("front.currency") }}') : '-';
    const doseyats = selectedCard?.doseyats || [];
    
    const summaries = [
      {category: 'waCategory', card: 'waCard', price: 'waPrice'},
      {category: 'cashCategory', card: 'cashCard', price: 'cashPrice'},
      {category: 'summaryCategory', card: 'summaryCard', price: 'summaryPrice'}
    ];
    
    summaries.forEach(sum => {
      const catEl = document.getElementById(sum.category);
      const cardEl = document.getElementById(sum.card);
      const priceEl = document.getElementById(sum.price);
      
      if (catEl) catEl.textContent = categoryText;
      if (cardEl) cardEl.textContent = cardName;
      if (priceEl) priceEl.textContent = price;
    });
    
    updateDoseyats(doseyats, [
      {container: 'waDoseyats', list: 'waDoseyatsList'},
      {container: 'cashDoseyats', list: 'cashDoseyatsList'},
      {container: 'visaDoseyats', list: 'visaDoseyatsList'}
    ]);
    
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

  function buildWhatsAppMessage() {
    if (!selectedCard) return '';
    
    let msg = '{{ __("front.whatsapp_contact_message") }}'
      .replace(':card_type', getCategoryLabel())
      .replace(':card_name', selectedCard.name)
      .replace(':card_price', selectedCard.price + ' ' + '{{ __("front.currency") }}');
    
    if (selectedCard.doseyats && selectedCard.doseyats.length > 0) {
      msg += '\n\n🎁 ' + '{{ __("front.free_doseyats") }}' + ':\n';
      selectedCard.doseyats.forEach((d, index) => {
        msg += `${index + 1}. ${d.name}\n`;
      });
    }
    
    return msg;
  }

  selectEl.addEventListener('change', function () {
    selectedCategoryId = this.value || '';
    const opt = this.options[this.selectedIndex];
    selectedCategoryLabel = opt ? (opt.dataset.label || opt.textContent.trim()) : '';
    filterCards();
  });

  tiles.forEach(t => {
    t.addEventListener('click', () => {
      tiles.forEach(x => x.classList.remove('active'));
      t.classList.add('active');
      selectedAction = t.getAttribute('data-action');
      showPanel();
    });
  });

  cards.forEach(card => {
    card.addEventListener('click', function (e) {
      e.preventDefault();
      if (this.classList.contains('hidden')) return;
      
      cards.forEach(c => c.classList.remove('selected'));
      this.classList.add('selected');
      
      const doseyatsData = this.dataset.doseyats ? JSON.parse(this.dataset.doseyats) : [];
      
      selectedCard = {
        id: this.dataset.cardId,
        name: this.dataset.cardName,
        price: this.dataset.cardPrice,
        doseyats: doseyatsData
      };
      
      showPanel();
    });
  });

  if (whatsappBtn) {
    whatsappBtn.addEventListener('click', function (e) {
      e.preventDefault();
      if (!selectedCard) return;
      
      const msg = buildWhatsAppMessage();
      const url = `https://wa.me/${whatsappNumber.replace('+', '')}?text=${encodeURIComponent(msg)}`;
      window.open(url, '_blank');
    });
  }

  if (cashBtn) {
    cashBtn.addEventListener('click', function (e) {
      e.preventDefault();
      if (!selectedCard) return;
      
      const msg = buildWhatsAppMessage();
      const url = `https://wa.me/${whatsappNumber.replace('+', '')}?text=${encodeURIComponent(msg)}`;
      window.open(url, '_blank');
    });
  }

  if (paymentForm) {
    paymentForm.addEventListener('submit', function (e) {
      e.preventDefault();
      if (!selectedCard) return;
      alert('{{ __("front.payment_submitted") }}');
    });
  }

  coPanels.style.display = 'none';
  filterCards();
});
</script>
@endsection