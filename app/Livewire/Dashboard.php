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
        // Mocking revenue data for the chart
        $revenueData = [
            'labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            'values' => [250, 400, 300, 500, 450, 600, 550],
        ];

        return view('livewire.dashboard', [
            'totalCustomers' => Customer::count(),
            'activeUsers' => Customer::where('status', 'active')->count(),
            'monthlyRevenue' => Invoice::where('status', 'paid')->sum('amount'),
            'pendingPayments' => Invoice::where('status', 'unpaid')->count(),
            'revenueData' => $revenueData,
        ]);
    }
}
