<?php

use App\Livewire\Dashboard;
use App\Livewire\CustomerList;
use App\Livewire\Packages\Index as PackageIndex;
use App\Livewire\Billing\Index as BillingIndex;
use App\Livewire\Invoices\Index as InvoiceIndex;
use App\Livewire\Routers\Index as RouterIndex;
use App\Livewire\Radius\Index as RadiusIndex;
use Illuminate\Support\Facades\Route;

// Auth
Route::get('/login', \App\Livewire\Auth\Login::class)->name('login');
Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/', Dashboard::class);
    Route::get('/customers', CustomerList::class);
    Route::get('/packages', PackageIndex::class);
    Route::get('/billing', BillingIndex::class);
    Route::get('/invoices', InvoiceIndex::class);
    Route::get('/routers', RouterIndex::class);
    Route::get('/radius', RadiusIndex::class);
    Route::get('/monitoring/traffic', \App\Livewire\Monitoring\Traffic::class);
    Route::get('/hotspot/vouchers', \App\Livewire\Hotspot\VoucherGenerator::class);
    Route::get('/hotspot/profiles', \App\Livewire\Hotspot\VoucherProfiles::class);
    Route::get('/network/map', \App\Livewire\Network\Map::class);
    Route::get('/network/olt', \App\Livewire\Network\Olt\Index::class);
    Route::get('/whatsapp', \App\Livewire\Whatsapp\Index::class);
    Route::get('/whatsapp/broadcast', \App\Livewire\Whatsapp\Broadcast::class);
    Route::get('/users', \App\Livewire\Users\Index::class);
    
    // Setting Routes
    Route::get('/settings', App\Livewire\Settings\Index::class);
    Route::get('/settings/application', App\Livewire\Settings\Application::class);
    Route::get('/settings/network', App\Livewire\Settings\Network::class);
    Route::get('/settings/payment', App\Livewire\Settings\Payment::class);
    Route::get('/settings/docs', App\Livewire\Settings\Documentation::class);
});
