<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Member\ToolController;
use App\Http\Controllers\Member\ReservationController;
use App\Http\Controllers\Member\ProfileController;
use App\Http\Controllers\Member\MessageController;
use App\Http\Controllers\Member\ReportController;
use App\Http\Controllers\Member\ReviewController;
use App\Http\Controllers\Member\DashboardController as MemberDashboardController;

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
        Route::post('reviews', [ReviewController::class, 'store'])->name('reviews.store');
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
        Route::resource('queue', MaintenanceController::class);
        Route::resource('safety', SafetyController::class);
        Route::resource('repairs', RepairController::class);
        Route::resource('inventory', MaintenanceInventoryController::class);
        Route::resource('wiki', KnowledgeBaseController::class);
    });

});
