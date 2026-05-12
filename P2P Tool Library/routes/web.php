<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Member\ToolController;
use App\Http\Controllers\Member\DashboardController as MemberDashboardController;
use App\Http\Controllers\Member\ReservationController;
use App\Http\Controllers\Member\ProfileController;
use App\Http\Controllers\Member\MessageController;
use App\Http\Controllers\Member\ReportController;

use App\Http\Controllers\Librarian\DashboardController;
use App\Http\Controllers\Librarian\InventoryController;
use App\Http\Controllers\Librarian\DisputeController;
use App\Http\Controllers\Librarian\InsuranceController;
use App\Http\Controllers\Librarian\UserController;
use App\Http\Controllers\Librarian\CommunicationController;
use App\Http\Controllers\Librarian\MarketingController;
use App\Http\Controllers\Librarian\ZoneController;

use App\Http\Controllers\Maintenance\MaintenanceController;
use App\Http\Controllers\Maintenance\SafetyController;
use App\Http\Controllers\Maintenance\RepairController;
use App\Http\Controllers\Maintenance\InventoryController as MaintenanceInventoryController;
use App\Http\Controllers\Maintenance\KnowledgeBaseController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated User Routes (Common)
Route::middleware(['auth'])->group(function () {
    
    // ==========================================
    // MEMBER MODULE (Lenders & Borrowers)
    // ==========================================
    Route::prefix('member')->name('member.')->group(function () {
        Route::get('dashboard', [MemberDashboardController::class, 'index'])->name('dashboard');
        Route::resource('tools', ToolController::class);
        Route::resource('reservations', ReservationController::class);
        Route::resource('profile', ProfileController::class);
        Route::put('profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.changePassword');
        Route::resource('messages', MessageController::class);
        Route::resource('reports', ReportController::class);
    });

    // ==========================================
    // LIBRARIAN MODULE (Admins/Oversight)
    // ==========================================
    // Requires both 'auth' and 'role:librarian' authorization middleware
    Route::prefix('librarian')->name('librarian.')->middleware('role:librarian')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('inventory', InventoryController::class);
        Route::resource('disputes', DisputeController::class);
        Route::resource('insurance', InsuranceController::class);
        Route::resource('users', UserController::class);
        Route::resource('communication', CommunicationController::class);
        Route::resource('marketing', MarketingController::class);
        Route::resource('zones', ZoneController::class);
    });

    // ==========================================
    // MAINTENANCE MODULE (Technicians)
    // ==========================================
    // Requires both 'auth' and 'role:technician' authorization middleware
    Route::prefix('maintenance')->name('maintenance.')->middleware('role:technician')->group(function () {
        Route::get('dashboard', [MaintenanceController::class, 'index'])->name('dashboard');

        // Usage trigger threshold
        Route::post('trigger/store', [MaintenanceController::class, 'store'])->name('trigger.store');

        // Queue actions
        Route::post('queue/start', [MaintenanceController::class, 'startWork'])->name('queue.start');
        Route::post('queue/complete', [MaintenanceController::class, 'completeWork'])->name('queue.complete');

        // Safety actions
        Route::resource('safety', SafetyController::class);
        Route::post('safety/renew',   [SafetyController::class, 'updateSafetyCertification'])->name('safety.renew');
        Route::post('safety/lockout', [SafetyController::class, 'markUnfit'])->name('safety.lockout');
        Route::post('safety/release', [SafetyController::class, 'markFit'])->name('safety.release');

        // Repairs & Parts
        Route::resource('repairs', RepairController::class);
        Route::post('repairs/spare-part', [RepairController::class, 'orderSparePart'])->name('repairs.spare-part');
        Route::post('repairs/external',   [RepairController::class, 'dispatchExternalRepair'])->name('repairs.external');

        // Inventory & Consumables
        Route::resource('inventory', MaintenanceInventoryController::class);
        Route::post('inventory/stock', [MaintenanceInventoryController::class, 'updateStock'])->name('inventory.stock');

        // Knowledge Base
        Route::resource('wiki', KnowledgeBaseController::class);
        Route::post('wiki/store', [KnowledgeBaseController::class, 'store'])->name('wiki.store');
    });

});
