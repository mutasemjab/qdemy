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
        @foreach($cards as $card)
        <a class="co-card" data-card-id="{{ $card->id }}" data-card-name="{{ $card->name }}" data-card-price="{{ $card->price }}">
            <img data-src="{{ asset('assets/admin/uploads/'.$card->photo) }}" alt="card">
            <div class="card-overlay">
                <i class="fa-solid fa-check"></i>
            </div>
        </a>
        @endforeach
    </div>

    <div class="checkout-payment-box" id="visaPaymentForm" style="display: none;">
        <div class="payment-header">
            <button class="back-btn" id="backToCards" type="button">
                <i class="fa-solid fa-arrow-left"></i>
                {{ __('front.back') }}
            </button>
            <h2 class="checkout-heading">{{ __('front.payment_methods') }}</h2>
        </div>
        
        <div class="selected-order-summary" id="orderSummary">
            <h3>{{ __('front.order_summary') }}</h3>
            <div class="summary-item">
                <span>{{ __('front.program') }}:</span>
                <span id="summaryProgram"></span>
            </div>
            <div class="summary-item">
                <span>{{ __('front.card') }}:</span>
                <span id="summaryCard"></span>
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
</section>

<style>
/* Only CSS for card selection functionality */
.co-card {
    position: relative;
}

.co-card.selected {
    border: 3px solid #007bff !important;
    box-shadow: 0 0 15px rgba(0,123,255,0.3);
}

.card-overlay {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 30px;
    height: 30px;
    background: #007bff;
    border-radius: 50%;
    display: none;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 16px;
}

.co-card.selected .card-overlay {
    display: flex;
}

.payment-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
}

.back-btn {
    background: none;
    border: 1px solid #ddd;
    padding: 8px 12px;
    border-radius: 5px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.back-btn:hover {
    background: #f8f9fa;
    border-color: #007bff;
    color: #007bff;
}

.selected-order-summary {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
}

.selected-order-summary h3 {
    margin: 0 0 15px 0;
    font-size: 18px;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
    padding-bottom: 8px;
}

.summary-item.total {
    border-top: 1px solid #ddd;
    margin-top: 15px;
    padding-top: 15px;
    font-weight: bold;
    font-size: 18px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const whatsappNumber = '+962795970357';
    let selectedCardType = '';
    let selectedCard = null;
    
    // DOM elements
    const chooserBtn = document.getElementById('coChooserBtn');
    const chooserList = document.getElementById('coChooserList');
    const selectedCardSpan = document.getElementById('selectedCard');
    const visaPaymentForm = document.getElementById('visaPaymentForm');
    const coGrid = document.querySelector('.co-grid');
    const coActions = document.querySelector('.co-actions');
    const coChooser = document.querySelector('.co-chooser');
    
    // Dropdown functionality
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
    
    // Card image selection
    document.querySelectorAll('.co-card').forEach(card => {
        card.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove previous selection
            document.querySelectorAll('.co-card').forEach(c => c.classList.remove('selected'));
            
            // Add selection to clicked card
            this.classList.add('selected');
            
            // Store selected card data
            selectedCard = {
                id: this.dataset.cardId,
                name: this.dataset.cardName,
                price: this.dataset.cardPrice
            };
        });
    });
    
    // WhatsApp button
    document.getElementById('whatsappBtn').addEventListener('click', function(e) {
        e.preventDefault();
        if (!selectedCardType) {
            alert('{{ __("front.please_select_card") }}');
            return;
        }
        if (!selectedCard) {
            alert('يرجى اختيار صورة البطاقة');
            return;
        }
        sendWhatsAppMessage('{{ __("front.whatsapp_contact_message") }}');
    });
    
    // Cash payment button
    document.getElementById('cashBtn').addEventListener('click', function(e) {
        e.preventDefault();
        if (!selectedCardType) {
            alert('{{ __("front.please_select_card") }}');
            return;
        }
        if (!selectedCard) {
            alert('يرجى اختيار صورة البطاقة');
            return;
        }
        sendWhatsAppMessage('{{ __("front.whatsapp_cash_message") }}');
    });
    
    // Visa payment button
    document.getElementById('visaBtn').addEventListener('click', function(e) {
        e.preventDefault();
        if (!selectedCardType) {
            alert('{{ __("front.please_select_card") }}');
            return;
        }
        if (!selectedCard) {
            alert('يرجى اختيار صورة البطاقة');
            return;
        }
        showVisaForm();
    });
    
    // Show Visa payment form
    function showVisaForm() {
        // Hide main content and show visa form
        coGrid.style.display = 'none';
        coActions.style.display = 'none';
        coChooser.style.display = 'none';
        visaPaymentForm.style.display = 'block';
        
        // Update order summary
        document.getElementById('summaryProgram').textContent = selectedCardType;
        document.getElementById('summaryCard').textContent = selectedCard.name;
        document.getElementById('summaryPrice').textContent = selectedCard.price + ' دينار';
        document.getElementById('paymentAmount').textContent = selectedCard.price + ' دينار';
    }
    
    // Back to main page
    document.getElementById('backToCards').addEventListener('click', function() {
        visaPaymentForm.style.display = 'none';
        coGrid.style.display = 'grid';
        coActions.style.display = 'flex';
        coChooser.style.display = 'block';
    });
    
    // Visa payment form submission
    document.getElementById('payment-form').addEventListener('submit', function(e) {
        e.preventDefault();
        alert('جاري معالجة الدفعة...');
    });
    
    function sendWhatsAppMessage(messageTemplate) {
        const message = messageTemplate
            .replace(':card_type', selectedCardType)
            .replace(':card_name', selectedCard.name)
            .replace(':card_price', selectedCard.price + ' دينار');
            
        const whatsappUrl = `https://wa.me/${whatsappNumber.replace('+', '')}?text=${encodeURIComponent(message)}`;
        window.open(whatsappUrl, '_blank');
    }
});
</script>

@endsection