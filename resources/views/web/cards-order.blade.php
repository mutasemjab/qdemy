@if($isApi)
@php $hideFooter = true; @endphp
@php $hideHeader = true; @endphp
@endif
@extends('layouts.app')
@section('title', __('front.page_title'))

@section('content')
<section class="co-page">

  <div data-aos="fade-up" data-aos-duration="1000" class="universities-header-wrapper">
    <div class="universities-header">
    <h2>{{ str_replace('<br>', ' ', __('front.order_card')) }}</h2>
    </div>
  </div>

  <div data-aos="zoom-in" data-aos-duration="1000" data-aos-delay="200" class="co-actions co-actions-select">
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

  <div data-aos="zoom-in" data-aos-duration="1000" data-aos-delay="300" class="co-chooser">
    <button class="co-chooser-btn" id="coChooserBtn">
      <span id="selectedCard">{{ __('front.choose_your_card') }}</span>
      <i class="fa-solid fa-caret-down"></i>
    </button>
    <ul class="co-chooser-list" id="coChooserList">
      <li data-label="{{ __('front.elementary_grades') }}">{{ __('front.elementary_grades') }}</li>
      <li data-label="{{ __('front.tawjihi') }}">{{ __('front.tawjihi') }}</li>
      <li data-label="{{ __('front.universities_colleges') }}">{{ __('front.universities_colleges') }}</li>
      <li data-label="{{ __('front.international_program') }}">{{ __('front.international_program') }}</li>
    </ul>
  </div>

  <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="400" class="co-grid">
    @foreach($cards as $card)
      <a class="co-card" data-card-id="{{ $card->id }}" data-card-name="{{ $card->name }}" data-card-price="{{ $card->price }}">
        <img data-src="{{ asset('assets/admin/uploads/'.$card->photo) }}" alt="card">
        <div class="card-overlay"><i class="fa-solid fa-check"></i></div>
      </a>
    @endforeach
  </div>

  <div class="co-panels">
    <div data-aos="zoom-in" data-aos-duration="1000" id="panelWhatsapp" class="co-panel" style="display:none;padding: 20px">
      <div class="selected-order-summary" id="summaryWhatsApp">
        <h3>{{ __('front.order_summary') }}</h3>
        <div class="summary-item"><span>{{ __('front.program') }}:</span><span id="waProgram"></span></div>
        <div class="summary-item"><span>{{ __('front.card') }}:</span><span id="waCard"></span></div>
        <div class="summary-item total"><span>{{ __('front.total') }}:</span><span id="waPrice"></span></div>
      </div>
      <a href="#" class="co-btn" id="whatsappBtn">{{ __('front.contact') }}</a>
    </div>

    <div data-aos="zoom-in" data-aos-duration="1000"  id="panelCash" class="co-panel" style="display:none;padding: 20px">
      <div class="selected-order-summary" id="summaryCash">
        <h3>{{ __('front.order_summary') }}</h3>
        <div class="summary-item"><span>{{ __('front.program') }}:</span><span id="cashProgram"></span></div>
        <div class="summary-item"><span>{{ __('front.card') }}:</span><span id="cashCard"></span></div>
        <div class="summary-item total"><span>{{ __('front.total') }}:</span><span id="cashPrice"></span></div>
      </div>
      <a href="#" class="co-btn co-btn-inv" id="cashBtn">{{ __('front.submit') }}</a>
    </div>

    <div data-aos="zoom-in" data-aos-duration="1000"  id="panelVisa" class="co-panel" style="display:none;padding: 20px">
      <div class="payment-header">
        <h2 class="checkout-heading">{{ __('front.payment_methods') }}</h2>
      </div>
      <div class="selected-order-summary" id="orderSummary">
        <h3>{{ __('front.order_summary') }}</h3>
        <div class="summary-item"><span>{{ __('front.program') }}:</span><span id="summaryProgram"></span></div>
        <div class="summary-item"><span>{{ __('front.card') }}:</span><span id="summaryCard"></span></div>
        <div class="summary-item total"><span>{{ __('front.total') }}:</span><span id="summaryPrice"></span></div>
      </div>
      <div class="payment-options"><button class="payment-option active">VISA</button></div>
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
        <button type="submit" class="payment-submit">{{ __('front.pay') }} <span id="paymentAmount"></span></button>
      </form>
    </div>
  </div>

</section>

<style>
.co-actions-select{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:18px}
.co-action-tile{background:#f3f6fb;border:1px solid #e2e8f4;border-radius:12px;padding:16px;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:background .2s ease,border-color .2s ease,box-shadow .2s ease}
.co-action-tile.active{color: #fff;background:#0055d3;border-color:#2e6cf0;box-shadow:0 6px 18px rgba(46,108,240,.18)}
.co-action-title{font-weight:700}
.co-chooser{position:relative;margin:12px 0 18px}
.co-chooser-btn{display:flex;align-items:center;justify-content:space-between;width:100%;max-width:420px;background:#fff;border:1px solid #e2e6ef;border-radius:10px;padding:12px 14px;cursor:pointer}
.co-chooser-list{position:absolute;z-index:10;display:none;background:#fff;border:1px solid #e2e6ef;border-radius:10px;margin-top:6px;min-width:260px;max-width:420px}
.co-chooser-list li{padding:10px 12px;cursor:pointer}
.co-chooser-list li:hover{background:#f6f8fc}
.co-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-top:8px}
.co-card{cursor: pointer;position:relative;background:#fff;border:1px solid #e2e6ef;border-radius:12px;overflow:hidden;display:block}
.co-card img{width:100%;height:250px;object-fit:cover;display:block}
.co-card.selected{border:2px solid #2e6cf0;box-shadow:0 8px 24px rgba(46,108,240,.18)}
.card-overlay{position:absolute;top:10px;right:10px;width:30px;height:30px;background:#2e6cf0;border-radius:50%;display:none;align-items:center;justify-content:center;color:#fff;font-size:16px}
.co-card.selected .card-overlay{display:flex}
.co-panels{margin-top:18px}
.selected-order-summary{background:#f8f9fa;padding:16px;border-radius:10px;margin-bottom:14px}
.selected-order-summary h3{margin:0 0 12px}
.summary-item{display:flex;justify-content:space-between;margin-bottom:8px;padding-bottom:8px}
.summary-item.total{border-top:1px solid #ddd;margin-top:10px;padding-top:10px;font-weight:700}
.co-btn,.co-btn-inv,.co-btn-c{display:inline-flex;align-items:center;justify-content:center;padding:12px 16px;border-radius:10px;text-decoration:none;cursor:pointer}
.co-btn{background:#2e6cf0;color:#fff;border:0}
.co-btn-inv{background:#111827;color:#fff;border:0}
.payment-header{display:flex;align-items:center;gap:12px;margin-bottom:12px}
.payment-options{display:flex;gap:8px;margin-bottom:12px}
.payment-option{background:#eef0f3;border:0;border-radius:8px;padding:8px 12px;font-weight:700}
.payment-option.active{background:#2e6cf0;color:#fff}
.payment-form{background:#fff;border:1px solid #eee;border-radius:12px;padding:14px}
.payment-field{display:flex;flex-direction:column;gap:6px;margin-bottom:10px}
.payment-field input{border:1px solid #e2e6ef;border-radius:10px;padding:12px 14px;background:#fff}
.payment-double{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.payment-submit{width:100%;background:#2e6cf0;border:0;border-radius:12px;color:#fff;font-weight:800;padding:12px 16px}
@media (max-width:992px){.co-grid{grid-template-columns:repeat(2,1fr)}.payment-double{grid-template-columns:1fr}}
@media (max-width:600px){.co-actions-select{grid-template-columns:1fr}.co-grid{grid-template-columns:1fr}.co-chooser-btn{max-width:100%}.co-chooser-list{width:100%}}
</style>

<script>
document.addEventListener('DOMContentLoaded',function(){
  const whatsappNumber='+962795970357';
  let selectedAction='';
  let selectedProgram='';
  let selectedCard=null;

  const chooserBtn=document.getElementById('coChooserBtn');
  const chooserList=document.getElementById('coChooserList');
  const selectedCardSpan=document.getElementById('selectedCard');
  const tiles=[...document.querySelectorAll('.co-action-tile')];

  const coPanels=document.querySelector('.co-panels');
  const panelWhatsapp=document.getElementById('panelWhatsapp');
  const panelCash=document.getElementById('panelCash');
  const panelVisa=document.getElementById('panelVisa');

  const whatsappBtn=document.getElementById('whatsappBtn');
  const cashBtn=document.getElementById('cashBtn');
  const paymentForm=document.getElementById('payment-form');
  const payAmount=document.getElementById('paymentAmount');

  function setDisabled(el,disabled){ if(!el) return; el.toggleAttribute('disabled',disabled); el.style.pointerEvents=disabled?'none':'auto'; el.style.opacity=disabled?.6:1; }

  function updateSummaries(){
    const programText=selectedProgram||'';
    const cardName=selectedCard?.name||'';
    const price=(selectedCard?.price?selectedCard.price+' '+'دينار':'');
    [['waProgram','waCard','waPrice'],['cashProgram','cashCard','cashPrice'],['summaryProgram','summaryCard','summaryPrice']].forEach(([p,c,t])=>{
      const ep=document.getElementById(p), ec=document.getElementById(c), et=document.getElementById(t);
      if(ep) ep.textContent=programText;
      if(ec) ec.textContent=cardName;
      if(et) et.textContent=price;
    });
    if(payAmount) payAmount.textContent=selectedCard?.price?selectedCard.price+' '+'دينار':'';
    const need=!selectedCard;
    setDisabled(whatsappBtn,need);
    setDisabled(cashBtn,need);
    if(paymentForm){
      const submit=paymentForm.querySelector('.payment-submit');
      setDisabled(submit,need);
    }
  }

  function showPanel(){
    const ready = !!(selectedAction && selectedCard);
    coPanels.style.display = ready ? 'block' : 'none';
    panelWhatsapp.style.display='none';
    panelCash.style.display='none';
    panelVisa.style.display='none';
    if(ready){
      if(selectedAction==='whatsapp') panelWhatsapp.style.display='block';
      if(selectedAction==='cash') panelCash.style.display='block';
      if(selectedAction==='visa') panelVisa.style.display='block';
      updateSummaries();
    }
  }

  tiles.forEach(t=>{
    t.addEventListener('click',()=>{
      tiles.forEach(x=>x.classList.remove('active'));
      t.classList.add('active');
      selectedAction=t.getAttribute('data-action');
      showPanel();
    });
  });

  chooserBtn.addEventListener('click',function(e){
    e.stopPropagation();
    chooserList.style.display=chooserList.style.display==='block'?'none':'block';
  });

  chooserList.addEventListener('click',function(e){
    if(e.target.tagName==='LI'){
      selectedProgram=e.target.dataset.label;
      selectedCardSpan.textContent=selectedProgram;
      chooserList.style.display='none';
      updateSummaries();
    }
  });

  document.addEventListener('click',function(e){
    if(!chooserBtn.contains(e.target)&&!chooserList.contains(e.target)){ chooserList.style.display='none'; }
  });

  document.querySelectorAll('.co-card').forEach(card=>{
    card.addEventListener('click',function(e){
      e.preventDefault();
      document.querySelectorAll('.co-card').forEach(c=>c.classList.remove('selected'));
      this.classList.add('selected');
      selectedCard={ id:this.dataset.cardId, name:this.dataset.cardName, price:this.dataset.cardPrice };
      showPanel();
    });
  });

  if(whatsappBtn){
    whatsappBtn.addEventListener('click',function(e){
      e.preventDefault();
      if(!selectedCard) return;
      const msg='{{ __("front.whatsapp_contact_message") }}'
        .replace(':card_type',selectedProgram||'')
        .replace(':card_name',selectedCard.name)
        .replace(':card_price',selectedCard.price+' '+'دينار');
      const url=`https://wa.me/${whatsappNumber.replace('+','')}?text=${encodeURIComponent(msg)}`;
      window.open(url,'_blank');
    });
  }

  if(cashBtn){
    cashBtn.addEventListener('click',function(e){
      e.preventDefault();
      if(!selectedCard) return;
      const msg='{{ __("front.whatsapp_cash_message") }}'
        .replace(':card_type',selectedProgram||'')
        .replace(':card_name',selectedCard.name)
        .replace(':card_price',selectedCard.price+' '+'دينار');
      const url=`https://wa.me/${whatsappNumber.replace('+','')}?text=${encodeURIComponent(msg)}`;
      window.open(url,'_blank');
    });
  }

  if(paymentForm){
    paymentForm.addEventListener('submit',function(e){
      e.preventDefault();
      if(!selectedCard) return;
      alert('تم إرسال تفاصيل الدفع');
    });
  }

  coPanels.style.display='none';
  updateSummaries();
});
</script>
@endsection