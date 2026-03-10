<?php

namespace  App\Http\Controllers\Web;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CardController  extends Controller
{

    public function cards_order(Request $request)
    {
        // Get filter data
        $programms = CategoryRepository()->getMajors();
        $grades = collect();

        // Build main query
        $query = Card::query()->with(['category', 'doseyats']);

        // Program filter
        if ($request->filled('programm_id')) {
            $selectedProgram = Category::find($request->programm_id);
            if ($selectedProgram) {
                // Check if program needs grades
                if (in_array($selectedProgram->ctg_key, ['tawjihi-and-secondary-program', 'elementary-grades-program'])) {
                    // Load grades for this program
                    if ($selectedProgram->ctg_key == 'elementary-grades-program') {
                        $grades = CategoryRepository()->getElementryProgramGrades();
                    } else {
                        $grades = CategoryRepository()->getTawjihiProgrammGrades();
                    }

                    // Filter cards by program
                    $query->whereHas('category', function($q) use ($request) {
                        $q->where('parent_id', $request->programm_id);
                    });
                }
            }
        }

        // Grade filter
        if ($request->filled('grade_id')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('id', $request->grade_id);
            });
        }

        $cards = $query->get();

        // Get active categories that have cards with their WhatsApp contacts
        $categories = Category::where('parent_id', null)
            ->whereHas('cards')
            ->with('whatsappContacts')
            ->get();

        // Build WhatsApp numbers map: category_id => phone_number
        $whatsappNumbers = [];
        foreach ($categories as $category) {
            if ($category->primary_whatsapp_phone) {
                $whatsappNumbers[$category->id] = $category->primary_whatsapp_phone;
            }
        }

        // Check if request from mobile
        $isApi = $request->is('api/*');

        // Auto-login for mobile
        if($isApi && $request->has('UserId')){
            auth()->loginUsingId($request->input('UserId'));
        }

        return view('web.cards-order', compact('cards', 'categories', 'isApi', 'whatsappNumbers', 'programms', 'grades'));
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