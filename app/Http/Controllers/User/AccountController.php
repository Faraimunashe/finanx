<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AccountController extends Controller
{
    /**
     * Display a listing of accounts for the selected organization.
     */
    public function index(Request $request)
    {
        $selectedOrgId = $request->session()->get('selected_organization_id');

        if (!$selectedOrgId) {
            return redirect()->route('user.dashboard')->with('error', 'Please select an organization first.');
        }

        $filters = $request->only(['type', 'search']);

        $query = Account::where('organization_id', $selectedOrgId)
            ->withCount('transactions');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $accounts = $query->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('User/AccountsPage', [
            'accounts' => $accounts,
            'filters' => $filters,
        ]);
    }

    /**
     * Display the specified account with its transactions.
     */
    public function show(Request $request, $id)
    {
        $selectedOrgId = $request->session()->get('selected_organization_id');

        if (!$selectedOrgId) {
            return redirect()->route('user.dashboard')->with('error', 'Please select an organization first.');
        }

        $account = Account::where('id', $id)
            ->where('organization_id', $selectedOrgId)
            ->with(['transactions' => function($query) {
                $query->orderBy('transaction_date', 'desc');
            }, 'transactions.currency'])
            ->first();

        if (!$account) {
            return redirect()->route('user.accounts.index')->with('error', 'Account not found.');
        }

        // Calculate account balance
        $balance = $account->transactions->sum(function($transaction) {
            return $transaction->type === 'Credit' ? $transaction->amount : -$transaction->amount;
        });

        return Inertia::render('User/AccountDetailsPage', [
            'account' => $account,
            'balance' => $balance,
        ]);
    }
}
