<?php

namespace  App\Http\Controllers\Web;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Card;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CardController  extends Controller
{

     public function cards_order()
    {
        $cards = Card::get(); // Only show active cards
        return view('web.cards-order', compact('cards'));
    }

        public function processPayment(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'card_id' => 'required|exists:cards,id',
            'program' => 'required|string|max:255',
            'cardholder_name' => 'required|string|max:255',
            'card_number' => 'required|string|min:16|max:19',
            'expiry' => 'required|string|regex:/^\d{2}\/\d{2}$/',
            'cvc' => 'required|string|min:3|max:4',
            'amount' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();
            
            // Get the selected card
            $card = Card::findOrFail($request->card_id);
            
            // Verify the amount matches the card price
            if ($request->amount != $card->price) {
                return response()->json([
                    'success' => false,
                    'message' => __('front.invalid_amount')
                ], 400);
            }
            
            // Here you would integrate with your payment gateway
            // For example: Stripe, PayPal, local payment processor, etc.
            
         
            
            if ($card) {
             
                
                DB::commit();
                
             
                
            } else {
                DB::rollBack();
                
                return response()->json([
                    'success' => false,
                    'message' => $paymentResult['error'] ?? __('front.payment_failed')
                ], 400);
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            
            return response()->json([
                'success' => false,
                'message' => __('front.payment_error')
            ], 500);
        }
    }
    
    /**
     * Mock payment processing method
     * Replace this with actual payment gateway integration
     */
    private function processCardPayment($paymentData)
    {
        
       
    }
    
   
}