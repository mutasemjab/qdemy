<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\CardNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CardController extends Controller
{
    /**
     * Show available cards for POS
     */
    public function index()
    {
        $pos = Auth::guard('pos')->user();

        $cards = Card::where('pos_id', $pos->id)
            ->with('cardNumbers')
            ->latest()
            ->paginate(10);

        return view('pos.cards.index', compact('cards', 'pos'));
    }

    /**
     * Show card details and its card numbers
     */
    public function show(Card $card)
    {
        $pos = Auth::guard('pos')->user();

        // Verify card belongs to authenticated POS
        if ($card->pos_id !== $pos->id) {
            abort(403, 'غير مصرح بالوصول إلى هذه البطاقة');
        }

        // Get all card numbers (both available and sold)
        $cardNumbers = $card->cardNumbers()
            ->where('activate', 1)
            ->where('status', 2)
            ->paginate(20);

        return view('pos.cards.show', compact('card', 'cardNumbers', 'pos'));
    }

    /**
     * Print single card number
     */
    public function printNumber(CardNumber $cardNumber)
    {
        $pos = Auth::guard('pos')->user();

        // Verify the card number belongs to POS
        $card = $cardNumber->card;
        if ($card->pos_id !== $pos->id) {
            abort(403, 'غير مصرح بالوصول إلى هذه البطاقة');
        }

        return view('pos.cards.print-single', compact('cardNumber', 'card', 'pos'));
    }

    /**
     * Confirm print and mark card as sold
     */
    public function confirmPrint(CardNumber $cardNumber)
    {
        $pos = Auth::guard('pos')->user();

        // Verify the card number belongs to POS
        $card = $cardNumber->card;
        if ($card->pos_id !== $pos->id) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح بالوصول إلى هذه البطاقة'
            ], 403);
        }

        // Update card status to sold (sell = 1)
        $cardNumber->update([
            'sell' => 1
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم تأكيد الطباعة وتحديث حالة البطاقة'
        ]);
    }
}
