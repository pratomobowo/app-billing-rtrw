<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Models\Customer;
use App\Services\RadiusService;
use App\Services\MikrotikService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckOverdueInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'billing:check-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for overdue invoices and isolate customers';

    /**
     * Execute the console command.
     */
    public function handle(RadiusService $radiusService, MikrotikService $mikrotikService)
    {
        Log::info("Automation: Starting overdue invoice check...");

        $now = Carbon::now();
        
        // Find unpaid invoices that passed their due date
        $overdueInvoices = Invoice::where('status', 'unpaid')
            ->where('due_date', '<', $now)
            ->whereHas('customer', function($query) {
                $query->where('status', 'active');
            })
            ->get();

        $count = 0;
        foreach ($overdueInvoices as $invoice) {
            $customer = $invoice->customer;
            
            if (!$customer) continue;

            Log::info("Automation: Isolating customer {$customer->name} due to overdue invoice {$invoice->invoice_number}");

            // 1. Update DB Status
            $customer->update(['status' => 'isolated']);

            // 2. Sync to Radius
            if ($customer->connection_type === 'pppoe') {
                $package = $customer->package;
                if ($package) {
                    $radiusService->setUserStatus($customer->pppoe_user, 'isolated', $package->name);

                    // 3. Sync to Mikrotik router
                    if ($customer->router) {
                        // Get isolated group name from settings
                        $isolatedGroup = \App\Models\Setting::getValue('radius_isolated_group', 'ISOLATED');
                        
                        $mikrotikService->syncPppoeUser(
                            $customer->router,
                            $customer->pppoe_user,
                            $customer->pppoe_pass,
                            $isolatedGroup,
                            "Isolir Otomatis: Tagihan Belum Bayar ({$invoice->invoice_number})"
                        );

                        // 4. Kick active session
                        $mikrotikService->kickUser($customer->router, $customer->pppoe_user);
                    }
                }
            }

            $count++;
        }

        if ($count > 0) {
            $this->info("Successfully isolated {$count} overdue customers.");
            Log::info("Automation: Isolated {$count} overdue customers.");
        } else {
            $this->info("No overdue invoices found.");
        }
    }
}
