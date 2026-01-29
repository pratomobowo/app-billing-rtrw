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
        // Real revenue data for the chart from last 7 days
        $days = [];
        $values = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $days[] = $date->format('D');
            $values[] = Invoice::where('status', 'paid')
                ->whereDate('updated_at', $date->toDateString())
                ->sum('amount');
        }

        $revenueData = [
            'labels' => $days,
            'values' => $values,
        ];

        return view('livewire.dashboard', [
            'totalCustomers' => Customer::count(),
            'activeUsers' => Customer::where('status', 'active')->count(),
            'monthlyRevenue' => Invoice::where('status', 'paid')
                ->whereMonth('updated_at', now()->month)
                ->whereYear('updated_at', now()->year)
                ->sum('amount'),
            'pendingPayments' => Invoice::where('status', 'unpaid')->count(),
            'revenueData' => $revenueData,
        ]);
    }
}
