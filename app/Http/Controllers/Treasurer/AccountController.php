<?php

namespace App\Http\Controllers\Treasurer;

use App\Http\Controllers\Controller;
use App\Models\Account;
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

        $accounts = $query->latest()->get();

        return inertia('Treasurer/AccountsPage', [
            'accounts' => $accounts,
            'filters' => $request->only(['type', 'name']),
        ]);
    }


    public function create()
    {
        return Inertia::render('Treasurer/Accounts/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:Asset,Liability,Revenue,Expense',
        ]);

        Account::create([
            'organization_id' => selected_org()->id,
            'name' => $request->name,
            'type' => $request->type,
        ]);

        return redirect()->route('accounts.index')->with('success', 'Account created successfully.');
    }

    public function show(string $id)
    {
        $account = Account::where('organization_id', selected_org()->id)->findOrFail($id);

        return Inertia::render('Treasurer/Accounts/Show', [
            'account' => $account
        ]);
    }

    public function edit(string $id)
    {
        $account = Account::where('organization_id', selected_org()->id)->findOrFail($id);

        return Inertia::render('Treasurer/Accounts/Edit', [
            'account' => $account
        ]);
    }

    public function update(Request $request, string $id)
    {
        $account = Account::where('organization_id', selected_org()->id)->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:Asset,Liability,Revenue,Expense',
        ]);

        $account->update([
            'name' => $request->name,
            'type' => $request->type,
        ]);

        return redirect()->route('accounts.index')->with('success', 'Account updated successfully.');
    }

    public function destroy(string $id)
    {
        $account = Account::where('organization_id', selected_org()->id)->findOrFail($id);
        $account->delete();

        return redirect()->route('accounts.index')->with('success', 'Account deleted.');
    }
}
