@extends('layouts.app')
@section('title', __('front.page_title'))

@section('content')
<section class="co-page">

    <div class="universities-header-wrapper">
        <div class="universities-header">
            <h2>{{ __('front.order_card') }}</h2>
        </div>
    </div>

    <!-- Step 1: Card Selection -->
    <div id="step1" class="step-container">
        <div class="step-header">
            <h3>{{ __('front.step_1_select_card') }}</h3>
        </div>
        
        <div class="co-grid">
            @php
            $cards = [
                ['img'=>'images/card-order.png', 'name' => __('front.elementary_grades'), 'id' => 'elementary'],
                ['img'=>'images/card-order.png', 'name' => __('front.tawjihi'), 'id' => 'tawjihi'],
                ['img'=>'images/card-order.png', 'name' => __('front.universities_colleges'), 'id' => 'universities'],
            ];
            @endphp

            @foreach($cards as $card)
            <div class="co-card selectable-card" data-card-type="{{ $card['name'] }}" data-card-id="{{ $card['id'] }}">
                <img data-src="{{ asset($card['img']) }}" alt="{{ $card['name'] }}">
                <div class="card-overlay">
                    <h4>{{ $card['name'] }}</h4>
                </div>
            </div>
            @endforeach
        </div>

        <div class="step-actions">
            <button id="nextToPayment" class="co-btn co-btn-primary" style="display: none;">
                {{ __('front.next_step') }}
            </button>
        </div>
    </div>

    <!-- Step 2: Payment Method Selection -->
    <div id="step2" class="step-container" style="display: none;">
        <div class="step-header">
            <h3>{{ __('front.step_2_select_payment') }}</h3>
            <p id="selectedCardDisplay"></p>
        </div>

        <div class="co-actions">
            <div class="co-action" data-payment="whatsapp">
                <div class="co-action-title">{{ __('front.contact_whatsapp') }}</div>
                <a href="#" class="co-btn payment-method-btn">{{ __('front.contact') }}</a>
            </div>

            <div class="co-action co-action-primary" data-payment="cash">
                <div class="co-action-title">{{ __('front.cash_payment') }}</div>
                <a href="#" class="co-btn co-btn-inv payment-method-btn">{{ __('front.cash') }}</a>
            </div>

            <div class="co-action" data-payment="visa">
                <div class="co-action-title">{{ __('front.visa_payment') }}</div>
                <a href="#" class="co-btn co-btn-c payment-method-btn">{{ __('front.visa') }}</a>
            </div>
        </div>

        <div class="step-actions">
            <button id="backToCards" class="co-btn co-btn-secondary">
                {{ __('front.back') }}
            </button>
        </div>
    </div>

    <!-- Step 3: Visa Payment Form -->
    <div id="step3" class="step-container" style="display: none;">
        <div class="step-header">
            <h3>{{ __('front.step_3_payment_details') }}</h3>
            <p id="selectedCardPaymentDisplay"></p>
        </div>

        <form class="payment-form" id="visaPaymentForm">
            <div class="payment-field">
                <label>{{ __('front.cardholder_name') }}</label>
                <input type="text" name="cardholder_name" placeholder="{{ __('front.cardholder_placeholder') }}" required>
            </div>
            <div class="payment-field">
                <label>{{ __('front.card_number') }}</label>
                <input type="text" name="card_number" placeholder="{{ __('front.card_number_placeholder') }}" required>
            </div>
            <div class="payment-double">
                <div class="payment-field">
                    <label>{{ __('front.expiry') }}</label>
                    <input type="text" name="expiry" placeholder="{{ __('front.expiry_placeholder') }}" required>
                </div>
                <div class="payment-field">
                    <label>{{ __('front.cvc') }}</label>
                    <input type="text" name="cvc" placeholder="{{ __('front.cvc_placeholder') }}" required>
                </div>
            </div>
            <button type="submit" class="payment-submit">{{ __('front.pay_now') }}</button>
        </form>

        <div class="step-actions">
            <button id="backToPayment" class="co-btn co-btn-secondary">
                {{ __('front.back') }}
            </button>
        </div>
    </div>

</section>

<style>
.step-container {
    margin-bottom: 2rem;
}

.step-header {
    text-align: center;
    margin-bottom: 2rem;
}

.step-header h3 {
    color: #333;
    margin-bottom: 0.5rem;
}

.step-header p {
    color: #666;
    font-size: 1.1rem;
}

.selectable-card {
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    border: 3px solid transparent;
    border-radius: 10px;
    overflow: hidden;
}

.selectable-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.selectable-card.selected {
    border-color: #007bff;
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,123,255,0.3);
}

.card-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(0,0,0,0.7));
    color: white;
    padding: 1rem;
    text-align: center;
}

.card-overlay h4 {
    margin: 0;
    font-size: 1.1rem;
}

.co-actions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.co-action {
    text-align: center;
    padding: 1.5rem;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.co-action:hover {
    border-color: #007bff;
    transform: translateY(-2px);
}

.co-action.selected {
    border-color: #007bff;
    background-color: rgba(0,123,255,0.1);
}

.payment-form {
    max-width: 400px;
    margin: 0 auto;
    padding: 2rem;
    border: 1px solid #ddd;
    border-radius: 10px;
    background: white;
}

.payment-field {
    margin-bottom: 1rem;
}

.payment-field label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: #333;
}

.payment-field input {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 1rem;
}

.payment-field input:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
}

.payment-double {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.payment-submit {
    width: 100%;
    padding: 12px;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 1.1rem;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.payment-submit:hover {
    background: #0056b3;
}

.step-actions {
    text-align: center;
    margin-top: 2rem;
}

.co-btn {
    display: inline-block;
    padding: 12px 24px;
    text-decoration: none;
    border-radius: 6px;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    font-size: 1rem;
    margin: 0 0.5rem;
}

.co-btn-primary {
    background: #007bff;
    color: white;
}

.co-btn-secondary {
    background: #6c757d;
    color: white;
}

.co-btn:hover {
    transform: translateY(-1px);
    text-decoration: none;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const whatsappNumber = '+962795970357';
    let selectedCard = '';
    let selectedPaymentMethod = '';
    
    // Step 1: Card Selection
    const selectableCards = document.querySelectorAll('.selectable-card');
    const nextToPaymentBtn = document.getElementById('nextToPayment');
    
    selectableCards.forEach(card => {
        card.addEventListener('click', function() {
            // Remove selected class from all cards
            selectableCards.forEach(c => c.classList.remove('selected'));
            // Add selected class to clicked card
            this.classList.add('selected');
            selectedCard = this.dataset.cardType;
            nextToPaymentBtn.style.display = 'inline-block';
        });
    });
    
    // Next to payment step
    nextToPaymentBtn.addEventListener('click', function() {
        document.getElementById('step1').style.display = 'none';
        document.getElementById('step2').style.display = 'block';
        document.getElementById('selectedCardDisplay').textContent = 
            '{{ __("front.selected_card") }}: ' + selectedCard;
    });
    
    // Step 2: Payment Method Selection
    const paymentActions = document.querySelectorAll('.co-action');
    
    paymentActions.forEach(action => {
        action.addEventListener('click', function() {
            selectedPaymentMethod = this.dataset.payment;
            
            if (selectedPaymentMethod === 'whatsapp' || selectedPaymentMethod === 'cash') {
                // Go to WhatsApp
                const messageKey = selectedPaymentMethod === 'whatsapp' ? 
                    '{{ __("front.whatsapp_contact_message") }}' : 
                    '{{ __("front.whatsapp_cash_message") }}';
                    
                sendWhatsAppMessage(messageKey);
            } else if (selectedPaymentMethod === 'visa') {
                // Show visa form
                document.getElementById('step2').style.display = 'none';
                document.getElementById('step3').style.display = 'block';
                document.getElementById('selectedCardPaymentDisplay').textContent = 
                    '{{ __("front.selected_card") }}: ' + selectedCard;
            }
        });
    });
    
    // Back buttons
    document.getElementById('backToCards').addEventListener('click', function() {
        document.getElementById('step2').style.display = 'none';
        document.getElementById('step1').style.display = 'block';
    });
    
    document.getElementById('backToPayment').addEventListener('click', function() {
        document.getElementById('step3').style.display = 'none';
        document.getElementById('step2').style.display = 'block';
    });
    
    // Visa form submission
    document.getElementById('visaPaymentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        alert('{{ __("front.visa_payment_processing") }}');
        // Here you would normally process the payment
    });
    
    function sendWhatsAppMessage(messageTemplate) {
        const message = messageTemplate.replace(':card_type', selectedCard);
        const whatsappUrl = `https://wa.me/${whatsappNumber.replace('+', '')}?text=${encodeURIComponent(message)}`;
        window.open(whatsappUrl, '_blank');
    }
});
</script>

@endsection