<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateMonthlyInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'billing:generate-invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate monthly invoices for active customers based on their due date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::now();
        $dueDay = $today->day;

        $customers = Customer::where('status', 'active')
            ->where('due_date', $dueDay)
            ->get();

        $count = 0;
        foreach ($customers as $customer) {
            // Check if invoice for this period already exists
            $startOfMonth = $today->copy()->startOfMonth();
            $endOfMonth = $today->copy()->endOfMonth();

            $exists = Invoice::where('customer_id', $customer->id)
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->exists();

            if (!$exists) {
                DB::transaction(function () use ($customer, $today) {
                    Invoice::create([
                        'customer_id' => $customer->id,
                        'invoice_number' => 'INV-' . $customer->id . '-' . $today->format('Ymd'),
                        'amount' => $customer->package->price,
                        'status' => 'unpaid',
                        'due_date' => $today->copy()->addDays(3), // 3 days to pay
                    ]);
                });
                $count++;
            }
        }

        $this->info("Generated {$count} invoices for due date: {$dueDay}");
    }
}
