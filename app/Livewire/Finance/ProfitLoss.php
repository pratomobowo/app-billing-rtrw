<?php

namespace App\Livewire\Finance;

use App\Models\Expense;
use App\Models\Invoice;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProfitLoss extends Component
{
    public $year;
    public $month;
    
    public $totalIncome = 0;
    public $totalExpense = 0;
    public $netProfit = 0;
    
    public $monthlyData = [];

    public function mount()
    {
        $this->year = date('Y');
        $this->month = date('m');
        $this->calculate();
    }

    public function calculate()
    {
        // 1. Total Income (Paid Invoices) for selected month & year
        $this->totalIncome = Invoice::where('status', 'paid')
            ->whereMonth('paid_at', $this->month)
            ->whereYear('paid_at', $this->year)
            ->sum('amount');

        // 2. Total Expense for selected month & year
        $this->totalExpense = Expense::whereMonth('expense_date', $this->month)
            ->whereYear('expense_date', $this->year)
            ->sum('amount');

        $this->netProfit = $this->totalIncome - $this->totalExpense;

        // 3. Prepare Chart Data (Last 6 Months)
        $this->prepareChartData();
    }

    protected function prepareChartData()
    {
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $m = $date->format('m');
            $y = $date->format('Y');

            $income = Invoice::where('status', 'paid')
                ->whereMonth('paid_at', $m)
                ->whereYear('paid_at', $y)
                ->sum('amount');

            $expense = Expense::whereMonth('expense_date', $m)
                ->whereYear('expense_date', $y)
                ->sum('amount');

            $data[] = [
                'label' => $date->format('M Y'),
                'income' => (float) $income,
                'expense' => (float) $expense,
            ];
        }
        $this->monthlyData = $data;
        
        // Dispatch to browser for Chart.js
        $this->dispatch('stats-updated', data: $this->monthlyData);
    }

    public function updated($property)
    {
        if ($property === 'year' || $property === 'month') {
            $this->calculate();
        }
    }

    public function render()
    {
        return view('livewire.finance.profit-loss');
    }
}
