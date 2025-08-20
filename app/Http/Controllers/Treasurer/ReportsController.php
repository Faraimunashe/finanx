<?php

namespace App\Http\Controllers\Treasurer;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\Currency;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function index()
    {
        $organizationId = selected_org()->id;

        // Get summary statistics
        $totalAccounts = Account::where('organization_id', $organizationId)->count();
        $totalTransactions = Transaction::whereHas('account', fn($q) => $q->where('organization_id', $organizationId))->count();

        // Get recent transactions for dashboard
        $recentTransactions = Transaction::with(['account', 'currency'])
            ->whereHas('account', fn($q) => $q->where('organization_id', $organizationId))
            ->orderBy('transaction_date', 'desc')
            ->limit(5)
            ->get();

        return Inertia::render('Treasurer/Reports/ReportsPage', [
            'totalAccounts' => $totalAccounts,
            'totalTransactions' => $totalTransactions,
            'recentTransactions' => $recentTransactions,
        ]);
    }

    public function balanceSheet(Request $request)
    {
        $organizationId = selected_org()->id;
        $date = $request->get('date', now()->format('Y-m-d'));
        $currencyId = $request->get('currency_id', null);

        // Get all accounts with their balances as of the specified date
        $accounts = Account::where('organization_id', $organizationId)
            ->with(['transactions' => function($query) use ($date, $currencyId) {
                $query->where('transaction_date', '<=', $date);
                if ($currencyId) {
                    $query->where('currency_id', $currencyId);
                }
            }])
            ->get()
            ->map(function($account) {
                $balance = $account->transactions->sum(function($transaction) {
                    return $transaction->type === 'Credit' ? $transaction->amount : -$transaction->amount;
                });

                return [
                    'id' => $account->id,
                    'name' => $account->name,
                    'type' => $account->type,
                    'balance' => $balance,
                    'transactions_count' => $account->transactions->count(),
                ];
            });

        // Group by account type
        $assets = $accounts->where('type', 'Asset')->values();
        $liabilities = $accounts->where('type', 'Liability')->values();
        $equity = $accounts->whereIn('type', ['Revenue', 'Expense'])->values();

        // Calculate totals
        $totalAssets = $assets->sum('balance');
        $totalLiabilities = $liabilities->sum('balance');
        $totalRevenue = $equity->where('type', 'Revenue')->sum('balance');
        $totalExpenses = $equity->where('type', 'Expense')->sum('balance');
        $netIncome = $totalRevenue - $totalExpenses;
        $totalEquity = $netIncome;

        // Get available currencies for this organization
        $currencies = Currency::whereHas('organizations', fn($q) => $q->where('organization_id', $organizationId))->get();

        return Inertia::render('Treasurer/Reports/BalanceSheetPage', [
            'date' => $date,
            'assets' => $assets,
            'liabilities' => $liabilities,
            'equity' => $equity,
            'totalAssets' => $totalAssets,
            'totalLiabilities' => $totalLiabilities,
            'totalRevenue' => $totalRevenue,
            'totalExpenses' => $totalExpenses,
            'netIncome' => $netIncome,
            'totalEquity' => $totalEquity,
            'currencies' => $currencies,
            'selectedCurrencyId' => $currencyId,
        ]);
    }

    public function incomeStatement(Request $request)
    {
        $organizationId = selected_org()->id;
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));
        $currencyId = $request->get('currency_id', null);

        // Get revenue and expense accounts with transactions in the date range
        $accounts = Account::where('organization_id', $organizationId)
            ->whereIn('type', ['Revenue', 'Expense'])
            ->with(['transactions' => function($query) use ($startDate, $endDate, $currencyId) {
                $query->whereBetween('transaction_date', [$startDate, $endDate]);
                if ($currencyId) {
                    $query->where('currency_id', $currencyId);
                }
            }])
            ->get()
            ->map(function($account) {
                $balance = $account->transactions->sum(function($transaction) {
                    return $transaction->type === 'Credit' ? $transaction->amount : -$transaction->amount;
                });

                return [
                    'id' => $account->id,
                    'name' => $account->name,
                    'type' => $account->type,
                    'balance' => $balance,
                    'transactions_count' => $account->transactions->count(),
                ];
            });

        $revenues = $accounts->where('type', 'Revenue')->values();
        $expenses = $accounts->where('type', 'Expense')->values();

        $totalRevenue = $revenues->sum('balance');
        $totalExpenses = $expenses->sum('balance');
        $netIncome = $totalRevenue - $totalExpenses;

        // Get available currencies for this organization
        $currencies = Currency::whereHas('organizations', fn($q) => $q->where('organization_id', $organizationId))->get();

        return Inertia::render('Treasurer/Reports/IncomeStatementPage', [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'revenues' => $revenues,
            'expenses' => $expenses,
            'totalRevenue' => $totalRevenue,
            'totalExpenses' => $totalExpenses,
            'netIncome' => $netIncome,
            'currencies' => $currencies,
            'selectedCurrencyId' => $currencyId,
        ]);
    }

    public function cashFlow(Request $request)
    {
        $organizationId = selected_org()->id;
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));
        $currencyId = $request->get('currency_id', null);

        // Get cash and bank accounts
        $cashAccounts = Account::where('organization_id', $organizationId)
            ->where('type', 'Asset')
            ->where(function($query) {
                $query->where('name', 'like', '%cash%')
                      ->orWhere('name', 'like', '%bank%')
                      ->orWhere('name', 'like', '%checking%')
                      ->orWhere('name', 'like', '%savings%');
            })
            ->with(['transactions' => function($query) use ($startDate, $endDate, $currencyId) {
                $query->whereBetween('transaction_date', [$startDate, $endDate]);
                if ($currencyId) {
                    $query->where('currency_id', $currencyId);
                }
            }])
            ->get();

        // Calculate cash flows
        $operatingActivities = [];
        $investingActivities = [];
        $financingActivities = [];

        foreach ($cashAccounts as $account) {
            foreach ($account->transactions as $transaction) {
                $flow = [
                    'date' => $transaction->transaction_date,
                    'description' => $transaction->description,
                    'amount' => $transaction->type === 'Credit' ? $transaction->amount : -$transaction->amount,
                    'account' => $account->name,
                ];

                // Categorize based on account type or description
                if (str_contains(strtolower($transaction->description), 'loan') ||
                    str_contains(strtolower($transaction->description), 'investment')) {
                    $financingActivities[] = $flow;
                } elseif (str_contains(strtolower($transaction->description), 'purchase') ||
                         str_contains(strtolower($transaction->description), 'sale')) {
                    $investingActivities[] = $flow;
                } else {
                    $operatingActivities[] = $flow;
                }
            }
        }

        // Calculate totals
        $totalOperating = collect($operatingActivities)->sum('amount');
        $totalInvesting = collect($investingActivities)->sum('amount');
        $totalFinancing = collect($financingActivities)->sum('amount');
        $netCashFlow = $totalOperating + $totalInvesting + $totalFinancing;

        // Get available currencies for this organization
        $currencies = Currency::whereHas('organizations', fn($q) => $q->where('organization_id', $organizationId))->get();

        return Inertia::render('Treasurer/Reports/CashFlowPage', [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'operatingActivities' => $operatingActivities,
            'investingActivities' => $investingActivities,
            'financingActivities' => $financingActivities,
            'totalOperating' => $totalOperating,
            'totalInvesting' => $totalInvesting,
            'totalFinancing' => $totalFinancing,
            'netCashFlow' => $netCashFlow,
            'currencies' => $currencies,
            'selectedCurrencyId' => $currencyId,
        ]);
    }

    public function trialBalance(Request $request)
    {
        $organizationId = selected_org()->id;
        $date = $request->get('date', now()->format('Y-m-d'));
        $currencyId = $request->get('currency_id', null);

        // Get all accounts with their balances as of the specified date
        $accounts = Account::where('organization_id', $organizationId)
            ->with(['transactions' => function($query) use ($date, $currencyId) {
                $query->where('transaction_date', '<=', $date);
                if ($currencyId) {
                    $query->where('currency_id', $currencyId);
                }
            }])
            ->get()
            ->map(function($account) {
                $debits = $account->transactions->where('type', 'Debit')->sum('amount');
                $credits = $account->transactions->where('type', 'Credit')->sum('amount');

                return [
                    'id' => $account->id,
                    'name' => $account->name,
                    'type' => $account->type,
                    'debits' => $debits,
                    'credits' => $credits,
                    'balance' => $credits - $debits,
                ];
            });

        $totalDebits = $accounts->sum('debits');
        $totalCredits = $accounts->sum('credits');
        $isBalanced = abs($totalDebits - $totalCredits) < 0.01;

        // Get available currencies for this organization
        $currencies = Currency::whereHas('organizations', fn($q) => $q->where('organization_id', $organizationId))->get();

        return Inertia::render('Treasurer/Reports/TrialBalancePage', [
            'date' => $date,
            'accounts' => $accounts,
            'totalDebits' => $totalDebits,
            'totalCredits' => $totalCredits,
            'isBalanced' => $isBalanced,
            'currencies' => $currencies,
            'selectedCurrencyId' => $currencyId,
        ]);
    }

    public function generalLedger(Request $request)
    {
        $organizationId = selected_org()->id;
        $accountId = $request->get('account_id');
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));
        $currencyId = $request->get('currency_id', null);

        $query = Account::where('organization_id', $organizationId);

        if ($accountId) {
            $query->where('id', $accountId);
        }

        $accounts = $query->with(['transactions' => function($query) use ($startDate, $endDate, $currencyId) {
            $query->whereBetween('transaction_date', [$startDate, $endDate])
                  ->orderBy('transaction_date', 'asc');
            if ($currencyId) {
                $query->where('currency_id', $currencyId);
            }
        }])
        ->get()
        ->map(function($account) {
            $runningBalance = 0;
            $transactions = $account->transactions->map(function($transaction) use (&$runningBalance) {
                if ($transaction->type === 'Credit') {
                    $runningBalance += $transaction->amount;
                } else {
                    $runningBalance -= $transaction->amount;
                }

                return [
                    'id' => $transaction->id,
                    'date' => $transaction->transaction_date,
                    'description' => $transaction->description,
                    'debit' => $transaction->type === 'Debit' ? $transaction->amount : 0,
                    'credit' => $transaction->type === 'Credit' ? $transaction->amount : 0,
                    'balance' => $runningBalance,
                    'currency' => $transaction->currency->code,
                ];
            });

            return [
                'id' => $account->id,
                'name' => $account->name,
                'type' => $account->type,
                'transactions' => $transactions,
                'opening_balance' => 0, // You might want to calculate this
                'closing_balance' => $runningBalance,
            ];
        });

        // Get available currencies for this organization
        $currencies = Currency::whereHas('organizations', fn($q) => $q->where('organization_id', $organizationId))->get();

        return Inertia::render('Treasurer/Reports/GeneralLedgerPage', [
            'accounts' => $accounts,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'selectedAccountId' => $accountId,
            'currencies' => $currencies,
            'selectedCurrencyId' => $currencyId,
        ]);
    }

    public function export(Request $request)
    {
        $organizationId = selected_org()->id;
        $reportType = $request->get('type', 'balance_sheet');
        $format = $request->get('format', 'pdf');
        $date = $request->get('date', now()->format('Y-m-d'));

        // This would typically generate and return a file
        // For now, we'll just return a success message
        return back()->with('success', ucfirst(str_replace('_', ' ', $reportType)) . ' exported successfully.');
    }
}
