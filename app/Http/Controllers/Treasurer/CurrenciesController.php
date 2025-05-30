<?php

namespace App\Http\Controllers\Treasurer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Currency;
use App\Models\OrganizationCurrency;
use Inertia\Inertia;

class CurrenciesController extends Controller
{
    /**
     * Display a listing of the organization's chosen currencies.
     */
    public function index()
    {
        $organization = selected_org();

        $currencies = $organization->currencies()->get();

        $all_currencies = Currency::all();

        return Inertia::render('Treasurer/CurrenciesPage', [
            'currencies' => $currencies,
            'allCurrencies' => $all_currencies,
        ]);
    }

    /**
     * Store a newly chosen currency for the organization.
     */
    public function store(Request $request)
    {
        $request->validate([
            'currency_id' => 'required|exists:currencies,id',
        ]);

        $organization = selected_org();

        $alreadyLinked = OrganizationCurrency::where('organization_id', $organization->id)
            ->where('currency_id', $request->currency_id)
            ->exists();

        if ($alreadyLinked) {
            return back()->with('error', 'This currency is already chosen.');
        }

        OrganizationCurrency::create([
            'organization_id' => $organization->id,
            'currency_id' => $request->currency_id,
        ]);

        return back()->with('success', 'Currency added to your organization.');
    }

    /**
     * Remove the specified currency from the organization's list.
     */
    public function destroy(string $currencyId)
    {
        $organization = selected_org();

        OrganizationCurrency::where('organization_id', $organization->id)
            ->where('currency_id', $currencyId)
            ->delete();

        return back()->with('success', 'Currency removed from your organization.');
    }
}
