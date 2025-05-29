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
        $organization_id = selected_org()->id;

        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $recent_transactions = Transaction::whereHas('account', function ($query) use ($organization_id) {
                $query->where('organization_id', $organization_id);
            })
            ->when($start_date, function ($query) use ($start_date) {
                $query->whereDate('transaction_date', '>=', $start_date);
            })
            ->when($end_date, function ($query) use ($end_date) {
                $query->whereDate('transaction_date', '<=', $end_date);
            })
            ->orderBy('transaction_date', 'desc')
            ->take(10)
            ->get();

        $summaries = Transaction::select(
                'currency_id',
                DB::raw("SUM(CASE WHEN type = 'revenue' THEN amount ELSE 0 END) AS total_revenue"),
                DB::raw("SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) AS total_expense")
            )
            ->whereHas('account', function ($query) use ($organization_id) {
                $query->where('organization_id', $organization_id);
            })
            ->when($start_date, function ($query) use ($start_date) {
                $query->whereDate('transaction_date', '>=', $start_date);
            })
            ->when($end_date, function ($query) use ($end_date) {
                $query->whereDate('transaction_date', '<=', $end_date);
            })
            ->groupBy('currency_id')
            ->with('currency')
            ->get();

        return inertia('Treasurer/DashboardPage', [
            'recent_transactions' => $recent_transactions,
            'summaries' => $summaries,
            'filters' => [
                'start_date' => $start_date,
                'end_date' => $end_date,
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
