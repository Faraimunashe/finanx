<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Account;
use App\Models\Currency;
use App\Models\OrganizationCurrency;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TransactionController extends Controller
{
    /**
     * Display a listing of transactions for the selected organization.
     */
    public function index(Request $request)
    {
        $selectedOrgId = $request->session()->get('selected_organization_id');

        if (!$selectedOrgId) {
            return redirect()->route('user.dashboard')->with('error', 'Please select an organization first.');
        }

        $filters = $request->only(['type', 'account_id', 'currency_id', 'start_date', 'end_date']);

        $query = Transaction::with('account', 'currency')
            ->whereHas('account', fn ($q) => $q->where('organization_id', $selectedOrgId));

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('account_id')) {
            $query->where('account_id', $request->account_id);
        }

        if ($request->filled('currency_id')) {
            $query->where('currency_id', $request->currency_id);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('transaction_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('transaction_date', '<=', $request->end_date);
        }

        $transactions = $query->orderBy('transaction_date', 'desc')
            ->paginate(20)
            ->withQueryString();

        $accounts = Account::where('organization_id', $selectedOrgId)
            ->withCount('transactions')
            ->get();

        $currencies = Currency::whereHas('organizations', fn ($q) => $q->where('organization_id', $selectedOrgId))->get();

        return Inertia::render('User/TransactionsPage', [
            'transactions' => $transactions,
            'filters' => $filters,
            'accounts' => $accounts,
            'currencies' => $currencies,
        ]);
    }

    /**
     * Show the form for creating a new transaction.
     */
    public function create(Request $request)
    {
        $selectedOrgId = $request->session()->get('selected_organization_id');

        if (!$selectedOrgId) {
            return redirect()->route('user.dashboard')->with('error', 'Please select an organization first.');
        }

        $accounts = Account::where('organization_id', $selectedOrgId)
            ->withCount('transactions')
            ->get();

        $currencies = Currency::whereHas('organizations', fn ($q) => $q->where('organization_id', $selectedOrgId))->get();

        return Inertia::render('User/TransactionsPage', [
            'accounts' => $accounts,
            'currencies' => $currencies,
            'showCreateModal' => true,
        ]);
    }

    /**
     * Store a newly created transaction.
     */
    public function store(Request $request)
    {
        $selectedOrgId = $request->session()->get('selected_organization_id');

        if (!$selectedOrgId) {
            return redirect()->route('user.dashboard')->with('error', 'Please select an organization first.');
        }

        $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'type' => 'required|in:Debit,Credit',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
            'transaction_date' => 'required|date',
            'currency_id' => 'required|exists:currencies,id',
        ]);

        // Verify the account belongs to the selected organization
        $account = Account::where('id', $request->account_id)
            ->where('organization_id', $selectedOrgId)
            ->first();

        if (!$account) {
            return redirect()->back()->with('error', 'Invalid account selected.');
        }

        // Verify the currency is available for the organization
        $currency = Currency::whereHas('organizations', fn ($q) => $q->where('organization_id', $selectedOrgId))
            ->where('id', $request->currency_id)
            ->first();

        if (!$currency) {
            return redirect()->back()->with('error', 'Invalid currency selected.');
        }

        try {
            $transaction = Transaction::create([
                'account_id' => $request->account_id,
                'type' => $request->type,
                'amount' => $request->amount,
                'description' => $request->description,
                'transaction_date' => $request->transaction_date,
                'currency_id' => $request->currency_id,
            ]);

            return redirect()->route('user.transactions.index')
                ->with('success', 'Transaction created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create transaction.');
        }
    }

    /**
     * Show the form for editing the specified transaction.
     */
    public function edit(Request $request, $id)
    {
        $selectedOrgId = $request->session()->get('selected_organization_id');

        if (!$selectedOrgId) {
            return redirect()->route('user.dashboard')->with('error', 'Please select an organization first.');
        }

        $transaction = Transaction::with('account', 'currency')
            ->whereHas('account', fn ($q) => $q->where('organization_id', $selectedOrgId))
            ->find($id);

        if (!$transaction) {
            return redirect()->route('user.transactions.index')->with('error', 'Transaction not found.');
        }

        $accounts = Account::where('organization_id', $selectedOrgId)
            ->withCount('transactions')
            ->get();

        $currencies = Currency::whereHas('organizations', fn ($q) => $q->where('organization_id', $selectedOrgId))->get();

        return Inertia::render('User/TransactionsPage', [
            'accounts' => $accounts,
            'currencies' => $currencies,
            'editingTransaction' => $transaction,
            'showEditModal' => true,
        ]);
    }

    /**
     * Update the specified transaction.
     */
    public function update(Request $request, $id)
    {
        $selectedOrgId = $request->session()->get('selected_organization_id');

        if (!$selectedOrgId) {
            return redirect()->route('user.dashboard')->with('error', 'Please select an organization first.');
        }

        $transaction = Transaction::with('account')
            ->whereHas('account', fn ($q) => $q->where('organization_id', $selectedOrgId))
            ->find($id);

        if (!$transaction) {
            return redirect()->route('user.transactions.index')->with('error', 'Transaction not found.');
        }

        $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'type' => 'required|in:Debit,Credit',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
            'transaction_date' => 'required|date',
            'currency_id' => 'required|exists:currencies,id',
        ]);

        // Verify the account belongs to the selected organization
        $account = Account::where('id', $request->account_id)
            ->where('organization_id', $selectedOrgId)
            ->first();

        if (!$account) {
            return redirect()->back()->with('error', 'Invalid account selected.');
        }

        // Verify the currency is available for the organization
        $currency = Currency::whereHas('organizations', fn ($q) => $q->where('organization_id', $selectedOrgId))
            ->where('id', $request->currency_id)
            ->first();

        if (!$currency) {
            return redirect()->back()->with('error', 'Invalid currency selected.');
        }

        try {
            $transaction->update([
                'account_id' => $request->account_id,
                'type' => $request->type,
                'amount' => $request->amount,
                'description' => $request->description,
                'transaction_date' => $request->transaction_date,
                'currency_id' => $request->currency_id,
            ]);

            return redirect()->route('user.transactions.index')
                ->with('success', 'Transaction updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update transaction.');
        }
    }
}
