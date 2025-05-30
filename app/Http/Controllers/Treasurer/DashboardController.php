<?php

namespace App\Http\Controllers\Treasurer;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $organizationId = selected_org()->id;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        // Recent transactions (with account and currency eager loading)
        $recentTransactions = Transaction::with('account', 'currency')
            ->whereHas('account', fn ($query) => $query->where('organization_id', $organizationId))
            ->when($startDate, fn ($query) => $query->whereDate('transaction_date', '>=', $startDate))
            ->when($endDate, fn ($query) => $query->whereDate('transaction_date', '<=', $endDate))
            ->orderBy('transaction_date', 'desc')
            ->take(10)
            ->get();

        // Summaries per currency (calculate total revenue and total expense)
        $summaries = Transaction::select(
                'currency_id',
                DB::raw("SUM(CASE WHEN type = 'Credit' THEN amount ELSE 0 END) as total_revenue"),
                DB::raw("SUM(CASE WHEN type = 'Debit' THEN amount ELSE 0 END) as total_expense")
            )
            ->whereHas('account', fn ($query) => $query->where('organization_id', $organizationId))
            ->when($startDate, fn ($query) => $query->whereDate('transaction_date', '>=', $startDate))
            ->when($endDate, fn ($query) => $query->whereDate('transaction_date', '<=', $endDate))
            ->groupBy('currency_id')
            ->get();

        // Attach related currency model data
        $summaries->load('currency');

        return inertia('Treasurer/DashboardPage', [
            'recent_transactions' => $recentTransactions,
            'summaries' => $summaries,
            'filters' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
