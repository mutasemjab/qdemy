@extends('layouts.app')
@section('title', __('front.page_title'))

@section('content')
<section class="co-page">

    <div class="universities-header-wrapper">
        <div class="universities-header">
            <h2>{{ __('front.order_card') }}</h2>
        </div>
    </div>

    <div class="co-chooser">
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

    <div class="co-actions">
        <div class="co-action">
            <div class="co-action-title">{{ __('front.contact_whatsapp') }}</div>
            <a href="#" class="co-btn" id="whatsappBtn">{{ __('front.contact') }}</a>
        </div>

        <div class="co-action co-action-primary">
            <div class="co-action-title">{{ __('front.cash_payment') }}</div>
            <a href="#" class="co-btn co-btn-inv" id="cashBtn">{{ __('front.submit') }}</a>
        </div>

        <div class="co-action">
            <div class="co-action-title">{{ __('front.visa_payment') }}</div>
            <a href="#" class="co-btn co-btn-c" id="visaBtn">{{ __('front.pay') }}</a>
        </div>
    </div>

    <div class="co-grid">
        @php
        $cards = [
            ['img'=>'images/card-order.png','link'=>'#'],
            ['img'=>'images/card-order.png','link'=>'#'],
            ['img'=>'images/card-order.png','link'=>'#'],
            ['img'=>'images/card-order.png','link'=>'#'],
            ['img'=>'images/card-order.png','link'=>'#'],
            ['img'=>'images/card-order.png','link'=>'#'],
        ];
        @endphp

        @foreach($cards as $c)
        <a class="co-card" href="{{ $c['link'] }}">
            <img data-src="{{ asset($c['img']) }}" alt="card">
        </a>
        @endforeach
    </div>

</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const whatsappNumber = '+962795970357';
    let selectedCardType = '';
    
    // Dropdown functionality
    const chooserBtn = document.getElementById('coChooserBtn');
    const chooserList = document.getElementById('coChooserList');
    const selectedCardSpan = document.getElementById('selectedCard');
    
    chooserBtn.addEventListener('click', function() {
        chooserList.style.display = chooserList.style.display === 'block' ? 'none' : 'block';
    });
    
    // Handle card selection
    chooserList.addEventListener('click', function(e) {
        if (e.target.tagName === 'LI') {
            selectedCardType = e.target.dataset.label;
            selectedCardSpan.textContent = selectedCardType;
            chooserList.style.display = 'none';
        }
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!chooserBtn.contains(e.target) && !chooserList.contains(e.target)) {
            chooserList.style.display = 'none';
        }
    });
    
    // WhatsApp button
    document.getElementById('whatsappBtn').addEventListener('click', function(e) {
        e.preventDefault();
        sendWhatsAppMessage('{{ __("front.whatsapp_contact_message") }}');
    });
    
    // Cash payment button
    document.getElementById('cashBtn').addEventListener('click', function(e) {
        e.preventDefault();
        sendWhatsAppMessage('{{ __("front.whatsapp_cash_message") }}');
    });
    
    // Visa payment button
    document.getElementById('visaBtn').addEventListener('click', function(e) {
        e.preventDefault();
        sendWhatsAppMessage('{{ __("front.whatsapp_visa_message") }}');
    });
    
    function sendWhatsAppMessage(messageTemplate) {
        if (!selectedCardType) {
            alert('{{ __("front.please_select_card") }}');
            return;
        }
        
        const message = messageTemplate.replace(':card_type', selectedCardType);
        const whatsappUrl = `https://wa.me/${whatsappNumber.replace('+', '')}?text=${encodeURIComponent(message)}`;
        window.open(whatsappUrl, '_blank');
    }
});
</script>

@endsection