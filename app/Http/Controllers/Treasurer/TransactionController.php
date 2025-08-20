<?php

namespace App\Http\Controllers\Treasurer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Account;
use App\Models\Currency;
use App\Models\OrganizationCurrency;
use Inertia\Inertia;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $organizationId = selected_org()->id;

        $filters = $request->only(['type', 'account_id', 'currency_id', 'start_date', 'end_date']);

        $query = Transaction::with('account', 'currency')
            ->whereHas('account', fn ($q) => $q->where('organization_id', $organizationId));

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

        $accounts = Account::where('organization_id', $organizationId)
            ->withCount('transactions')
            ->get();

        $currencies = Currency::whereHas('organizations', fn ($q) => $q->where('organization_id', $organizationId))->get();

        return Inertia::render('Treasurer/TransactionsPage', [
            'transactions' => $transactions,
            'filters' => $filters,
            'accounts' => $accounts,
            'currencies' => $currencies,
        ]);
    }

    public function create()
    {
        $organizationId = selected_org()->id;

        $accounts = Account::where('organization_id', $organizationId)
            ->withCount('transactions')
            ->get();

        $currencies = Currency::whereHas('organizations', fn ($q) => $q->where('organization_id', $organizationId))->get();

        return Inertia::render('Treasurer/TransactionsPage', [
            'transactions' => collect(),
            'accounts' => $accounts,
            'currencies' => $currencies,
            'showCreateModal' => true,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'currency_id' => 'required|exists:currencies,id',
            'type' => 'required|in:Credit,Debit',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:500',
            'transaction_date' => 'required|date',
        ]);

        try {
            $account = Account::where('id', $request->account_id)
                ->where('organization_id', selected_org()->id)
                ->firstOrFail();

            $account->transactions()->create([
                'currency_id' => $request->currency_id,
                'type' => $request->type,
                'amount' => $request->amount,
                'description' => $request->description,
                'transaction_date' => $request->transaction_date,
            ]);

            return back()->with('success', 'Transaction created successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create transaction. Please try again.']);
        }
    }

    public function show(string $id)
    {
        try {
            $transaction = Transaction::with('account', 'currency')
                ->whereHas('account', fn ($q) => $q->where('organization_id', selected_org()->id))
                ->findOrFail($id);

            return Inertia::render('Treasurer/TransactionsPage', [
                'transactions' => Transaction::with('account', 'currency')
                    ->whereHas('account', fn ($q) => $q->where('organization_id', selected_org()->id))
                    ->orderBy('transaction_date', 'desc')
                    ->paginate(20),
                'selectedTransaction' => $transaction,
                'accounts' => Account::where('organization_id', selected_org()->id)
                    ->withCount('transactions')
                    ->get(),
                'currencies' => Currency::whereHas('organizations', fn ($q) => $q->where('organization_id', selected_org()->id))->get(),
            ]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Transaction not found.']);
        }
    }

    public function edit(string $id)
    {
        try {
            $organizationId = selected_org()->id;

            $transaction = Transaction::with('account', 'currency')
                ->whereHas('account', fn ($q) => $q->where('organization_id', $organizationId))
                ->findOrFail($id);

            $accounts = Account::where('organization_id', $organizationId)
                ->withCount('transactions')
                ->get();

            $currencies = Currency::whereHas('organizations', fn ($q) => $q->where('organization_id', $organizationId))->get();

            return Inertia::render('Treasurer/TransactionsPage', [
                'transactions' => Transaction::with('account', 'currency')
                    ->whereHas('account', fn ($q) => $q->where('organization_id', $organizationId))
                    ->orderBy('transaction_date', 'desc')
                    ->paginate(20),
                'editingTransaction' => $transaction,
                'accounts' => $accounts,
                'currencies' => $currencies,
                'showEditModal' => true,
            ]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Transaction not found.']);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $organizationId = selected_org()->id;

            $transaction = Transaction::whereHas('account', fn ($q) => $q->where('organization_id', $organizationId))
                ->findOrFail($id);

            $request->validate([
                'account_id' => 'required|exists:accounts,id',
                'currency_id' => 'required|exists:currencies,id',
                'type' => 'required|in:Credit,Debit',
                'amount' => 'required|numeric|min:0.01',
                'description' => 'nullable|string|max:500',
                'transaction_date' => 'required|date',
            ]);

            $account = Account::where('id', $request->account_id)
                ->where('organization_id', $organizationId)
                ->firstOrFail();

            $transaction->update([
                'account_id' => $account->id,
                'currency_id' => $request->currency_id,
                'type' => $request->type,
                'amount' => $request->amount,
                'description' => $request->description,
                'transaction_date' => $request->transaction_date,
            ]);

            return back()->with('success', 'Transaction updated successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update transaction. Please try again.']);
        }
    }

    public function destroy(string $id)
    {
        try {
            $transaction = Transaction::whereHas('account', fn ($q) => $q->where('organization_id', selected_org()->id))
                ->findOrFail($id);

            $transaction->delete();

            return back()->with('success', 'Transaction deleted successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete transaction. Please try again.']);
        }
    }
}
