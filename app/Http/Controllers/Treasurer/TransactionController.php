<?php

namespace App\Http\Controllers\Treasurer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Account;
use App\Models\Currency;
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

        $transactions = $query->latest()
            ->paginate(20)
            ->withQueryString();

        $accounts = \App\Models\Account::where('organization_id', $organizationId)->get();
        $currencies = \App\Models\Currency::all();

        return Inertia::render('Treasurer/Transactions/Index', [
            'transactions' => $transactions,
            'filters' => $filters,
            'accounts' => $accounts,
            'currencies' => $currencies,
        ]);
    }


    public function create()
    {
        return Inertia::render('Treasurer/Transactions/Create', [
            'accounts' => Account::where('organization_id', selected_org()->id)->get(),
            'currencies' => Currency::all()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'currency_id' => 'required|exists:currencies,id',
            'type' => 'required|in:revenue,expense',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:500',
            'transaction_date' => 'required|date',
        ]);

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

        return redirect()->route('transactions.index')->with('success', 'Transaction created successfully.');
    }

    public function show(string $id)
    {
        $transaction = Transaction::with('account', 'currency')
            ->whereHas('account', fn ($q) => $q->where('organization_id', selected_org()->id))
            ->findOrFail($id);

        return Inertia::render('Treasurer/Transactions/Show', [
            'transaction' => $transaction,
        ]);
    }

    public function edit(string $id)
    {
        $transaction = Transaction::with('account')
            ->whereHas('account', fn ($q) => $q->where('organization_id', selected_org()->id))
            ->findOrFail($id);

        return Inertia::render('Treasurer/Transactions/Edit', [
            'transaction' => $transaction,
            'accounts' => Account::where('organization_id', selected_org()->id)->get(),
            'currencies' => Currency::all(),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $transaction = Transaction::whereHas('account', fn ($q) => $q->where('organization_id', selected_org()->id))
            ->findOrFail($id);

        $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'currency_id' => 'required|exists:currencies,id',
            'type' => 'required|in:revenue,expense',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:500',
            'transaction_date' => 'required|date',
        ]);

        $transaction->update([
            'account_id' => $request->account_id,
            'currency_id' => $request->currency_id,
            'type' => $request->type,
            'amount' => $request->amount,
            'description' => $request->description,
            'transaction_date' => $request->transaction_date,
        ]);

        return redirect()->route('transactions.index')->with('success', 'Transaction updated successfully.');
    }

    public function destroy(string $id)
    {
        $transaction = Transaction::whereHas('account', fn ($q) => $q->where('organization_id', selected_org()->id))
            ->findOrFail($id);

        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', 'Transaction deleted.');
    }
}
