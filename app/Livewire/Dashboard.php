<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Router;

class Dashboard extends Component
{
    public function render()
    {
        // 7 Days Chart Data (Income vs Expense)
        $labels = [];
        $incomeData = [];
        $expenseData = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('D');
            
            $incomeData[] = Invoice::where('status', 'paid')
                ->whereDate('updated_at', $date->toDateString())
                ->sum('amount');
                
            $expenseData[] = \App\Models\Expense::whereDate('expense_date', $date->toDateString())
                ->sum('amount');
        }

        // Monthly Financials
        $currentMonthIncome = Invoice::where('status', 'paid')
            ->whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->sum('amount');
            
        $currentMonthExpense = \App\Models\Expense::whereMonth('expense_date', now()->month)
            ->whereYear('expense_date', now()->year)
            ->sum('amount');

        // Router Health
        $routers = Router::all();
        $onlineRouters = 0;
        $routerStatus = [];

        foreach ($routers as $router) {
            $isOnline = false;
            try {
                // Quick check for connectivity
                $isOnline = (bool) \Illuminate\Support\Facades\Http::timeout(1)->get("http://{$router->host}")->successful() || 
                           @fsockopen($router->host, $router->port, $errno, $errstr, 1);
            } catch (\Exception $e) {}
            
            if ($isOnline) $onlineRouters++;
            
            $routerStatus[] = [
                'name' => $router->name,
                'status' => $isOnline ? 'online' : 'offline',
            ];
        }

        // Recent Activity (Mixed: Recent Paid Invoices & Recent Expenses)
        $recentInvoices = Invoice::with('customer')->where('status', 'paid')->latest('updated_at')->take(3)->get();
        $recentExpenses = \App\Models\Expense::latest()->take(3)->get();
        
        $activities = collect();
        foreach ($recentInvoices as $inv) {
            $activities->push([
                'type' => 'income',
                'title' => "Pembayaran: {$inv->customer->name}",
                'amount' => $inv->amount,
                'time' => $inv->updated_at->diffForHumans(),
                'icon' => 'payments'
            ]);
        }
        foreach ($recentExpenses as $exp) {
            $activities->push([
                'type' => 'expense',
                'title' => "Pengeluaran: {$exp->title}",
                'amount' => $exp->amount,
                'time' => $exp->created_at->diffForHumans(),
                'icon' => 'shopping_cart'
            ]);
        }
        $activities = $activities->sortByDesc('time')->take(5);

        return view('livewire.dashboard', [
            'totalCustomers' => Customer::count(),
            'activeUsers' => Customer::where('status', 'active')->count(),
            'monthlyIncome' => $currentMonthIncome,
            'monthlyExpense' => $currentMonthExpense,
            'netProfit' => $currentMonthIncome - $currentMonthExpense,
            'pendingPayments' => Invoice::where('status', 'unpaid')->count(),
            'onlineRouters' => $onlineRouters,
            'totalRouters' => $routers->count(),
            'routerStatus' => $routerStatus,
            'chartLabels' => $labels,
            'incomeChartData' => $incomeData,
            'expenseChartData' => $expenseData,
            'activities' => $activities,
        ]);
    }
}
