<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\UserOrganization;
use App\Models\Organization;
use DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the user dashboard.
     */
    public function index(Request $request)
    {
        $userId = auth()->id();
        $selectedOrgId = $request->session()->get('selected_organization_id');

        // Get user's organizations
        $userOrganizations = UserOrganization::with('organization')
            ->where('user_id', $userId)
            ->get()
            ->pluck('organization');

        // If no organization is selected, use the first one
        if (!$selectedOrgId && $userOrganizations->count() > 0) {
            $selectedOrgId = $userOrganizations->first()->id;
            $request->session()->put('selected_organization_id', $selectedOrgId);
        }

        $selectedOrganization = null;
        $recentTransactions = collect();
        $summaries = collect();

        if ($selectedOrgId) {
            $selectedOrganization = Organization::find($selectedOrgId);

            // Recent transactions for the selected organization
            $recentTransactions = Transaction::with('account', 'currency')
                ->whereHas('account', fn ($query) => $query->where('organization_id', $selectedOrgId))
                ->when($request->start_date, fn ($query) => $query->whereDate('transaction_date', '>=', $request->start_date))
                ->when($request->end_date, fn ($query) => $query->whereDate('transaction_date', '<=', $request->end_date))
                ->orderBy('transaction_date', 'desc')
                ->take(10)
                ->get();

            // Summaries per currency for the selected organization
            $summaries = Transaction::select(
                    'currency_id',
                    DB::raw("SUM(CASE WHEN type = 'Credit' THEN amount ELSE 0 END) as total_revenue"),
                    DB::raw("SUM(CASE WHEN type = 'Debit' THEN amount ELSE 0 END) as total_expense")
                )
                ->whereHas('account', fn ($query) => $query->where('organization_id', $selectedOrgId))
                ->when($request->start_date, fn ($query) => $query->whereDate('transaction_date', '>=', $request->start_date))
                ->when($request->end_date, fn ($query) => $query->whereDate('transaction_date', '<=', $request->end_date))
                ->groupBy('currency_id')
                ->get();

            // Attach related currency model data
            $summaries->load('currency');
        }

        return inertia('User/DashboardPage', [
            'userOrganizations' => $userOrganizations,
            'selectedOrganization' => $selectedOrganization,
            'recentTransactions' => $recentTransactions,
            'summaries' => $summaries,
            'filters' => $request->only(['start_date', 'end_date']),
        ]);
    }

    /**
     * Select an organization for the user.
     */
    public function selectOrganization(Request $request, $organizationId)
    {
        $userId = auth()->id();

        // Verify user has access to this organization
        $userOrg = UserOrganization::where('user_id', $userId)
            ->where('organization_id', $organizationId)
            ->first();

        if (!$userOrg) {
            return redirect()->back()->with('error', 'You do not have access to this organization.');
        }

        $request->session()->put('selected_organization_id', $organizationId);

        return redirect()->route('user.dashboard')->with('success', 'Organization selected successfully.');
    }
}
