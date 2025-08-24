<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\WalletTransaction;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletTransactionController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:wallet-transaction-table', ['only' => ['index', 'show']]);
        $this->middleware('permission:wallet-transaction-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:wallet-transaction-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:wallet-transaction-delete', ['only' => ['destroy']]);
    }
    
    public function index()
    {
        $transactions = WalletTransaction::with(['user', 'admin'])->paginate(15);
        return view('admin.wallet_transactions.index', compact('transactions'));
    }

    public function create()
    {
        $users = User::where('activate', 1)->get();
        $admins = Admin::all();
        return view('admin.wallet_transactions.create', compact('users', 'admins'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'admin_id' => 'required|exists:admins,id',
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:1,2',
            'note' => 'nullable|string'
        ]);

        $user = User::find($request->user_id);
        
        // Check if withdrawal amount doesn't exceed user balance
        if ($request->type == 2 && $request->amount > $user->balance) {
            return redirect()->back()->with('error', __('messages.insufficient_balance'));
        }

        $transaction = WalletTransaction::create($request->all());

        // Update user balance
        if ($request->type == 1) { // Add money
            $user->increment('balance', $request->amount);
        } else { // Withdrawal
            $user->decrement('balance', $request->amount);
        }

        return redirect()->route('wallet_transactions.index')->with('success', __('messages.transaction_created'));
    }

    public function show(WalletTransaction $walletTransaction)
    {
        $walletTransaction->load(['user', 'admin']);
        return view('admin.wallet_transactions.show', compact('walletTransaction'));
    }

    public function edit(WalletTransaction $walletTransaction)
    {
        $users = User::where('activate', 1)->get();
        $admins = Admin::all();
        return view('admin.wallet_transactions.edit', compact('walletTransaction', 'users', 'admins'));
    }

    public function update(Request $request, WalletTransaction $walletTransaction)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'admin_id' => 'required|exists:admins,id',
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:1,2',
            'note' => 'nullable|string'
        ]);

        // Revert previous transaction effect on user balance
        $user = User::find($walletTransaction->user_id);
        if ($walletTransaction->type == 1) {
            $user->decrement('balance', $walletTransaction->amount);
        } else {
            $user->increment('balance', $walletTransaction->amount);
        }

        // Check new transaction validity
        $newUser = User::find($request->user_id);
        if ($request->type == 2 && $request->amount > $newUser->balance) {
            // Restore original transaction effect
            if ($walletTransaction->type == 1) {
                $user->increment('balance', $walletTransaction->amount);
            } else {
                $user->decrement('balance', $walletTransaction->amount);
            }
            return redirect()->back()->with('error', __('messages.insufficient_balance'));
        }

        $walletTransaction->update($request->all());

        // Apply new transaction effect
        if ($request->type == 1) {
            $newUser->increment('balance', $request->amount);
        } else {
            $newUser->decrement('balance', $request->amount);
        }

        return redirect()->route('wallet_transactions.index')->with('success', __('messages.transaction_updated'));
    }

    public function destroy(WalletTransaction $walletTransaction)
    {
        // Revert transaction effect on user balance
        $user = User::find($walletTransaction->user_id);
        if ($walletTransaction->type == 1) {
            $user->decrement('balance', $walletTransaction->amount);
        } else {
            $user->increment('balance', $walletTransaction->amount);
        }

        $walletTransaction->delete();

        return redirect()->route('wallet_transactions.index')->with('success', __('messages.transaction_deleted'));
    }

    public function getUserTransactions(Request $request)
    {
        $userId = $request->get('user_id');
        if ($userId) {
            $transactions = WalletTransaction::where('user_id', $userId)
                ->with(['user', 'admin'])
                ->paginate(15);
            return view('admin.wallet_transactions.user_transactions', compact('transactions'));
        }
        return redirect()->route('wallet_transactions.index');
    }
}