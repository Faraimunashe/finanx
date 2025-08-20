<?php

namespace App\Http\Controllers\Treasurer;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Currency;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $organizationId = selected_org()->id;

        $query = Account::where('organization_id', $organizationId);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $accounts = $query->withCount('transactions')->latest()->get();

        return inertia('Treasurer/AccountsPage', [
            'accounts' => $accounts,
            'filters' => $request->only(['type', 'name']),
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:Asset,Liability,Revenue,Expense',
        ]);

        try {
            Account::create([
                'organization_id' => selected_org()->id,
                'name' => $request->name,
                'type' => $request->type,
            ]);

            return back()->with('success', 'Account created successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create account. Please try again.']);
        }
    }

    public function show(string $id)
    {
        try {
            $account = Account::with('transactions.currency')
                ->where('organization_id', selected_org()->id)
                ->findOrFail($id);

            $transactions = $account->transactions()
                ->with('currency')
                ->orderBy('transaction_date', 'desc')
                ->paginate(20);

            $currencies = Currency::whereHas('organizations', fn ($q) => $q->where('organization_id', selected_org()->id))->get();

            return Inertia::render('Treasurer/AccountDetailsPage', [
                'account' => $account,
                'transactions' => $transactions,
                'currencies' => $currencies,
            ]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Account not found.']);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $account = Account::where('organization_id', selected_org()->id)->findOrFail($id);

            $request->validate([
                'name' => 'required|string|max:255',
                'type' => 'required|in:Asset,Liability,Revenue,Expense',
            ]);

            $account->update([
                'name' => $request->name,
                'type' => $request->type,
            ]);

            return back()->with('success', 'Account updated successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update account. Please try again.']);
        }
    }

    public function destroy(string $id)
    {
        try {
            $account = Account::where('organization_id', selected_org()->id)->findOrFail($id);

            // Check if account has transactions
            if ($account->transactions()->count() > 0) {
                return back()->withErrors(['error' => 'Cannot delete account with existing transactions.']);
            }

            $account->delete();

            return back()->with('success', 'Account deleted successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete account. Please try again.']);
        }
    }
}
