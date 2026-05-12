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
use App\Http\Controllers\Librarian\HandoverController;
use App\Http\Controllers\Librarian\ToolCategoryController;
use App\Http\Controllers\Librarian\SuspensionController;
use App\Http\Controllers\Librarian\LateReturnController;
use App\Http\Controllers\Librarian\InventoryAuditController;
use App\Http\Controllers\Librarian\InsuranceClaimController;

use App\Http\Controllers\Maintenance\MaintenanceController;
use App\Http\Controllers\Maintenance\SafetyController;
use App\Http\Controllers\Maintenance\RepairController;
use App\Http\Controllers\Maintenance\InventoryController as MaintenanceInventoryController;
use App\Http\Controllers\Maintenance\KnowledgeBaseController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {

    Route::prefix('member')->name('member.')->group(function () {
        Route::get('dashboard', [MemberDashboardController::class, 'index'])->name('dashboard');
        Route::resource('tools', ToolController::class);
        Route::resource('reservations', ReservationController::class);
        Route::resource('profile', ProfileController::class);
        Route::put('profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.changePassword');
        Route::resource('messages', MessageController::class);
        Route::resource('reports', ReportController::class);
    });

    Route::prefix('librarian')->name('librarian.')->middleware('role:librarian')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::post('handovers/generate-qr', [HandoverController::class, 'generateQR'])->name('handovers.generate');
        Route::post('handovers/verify-qr', [HandoverController::class, 'verifyHandover'])->name('handovers.verify');

        Route::post('categories', [ToolCategoryController::class, 'createCategory'])->name('categories.store');

        Route::post('restrictions/apply', [SuspensionController::class, 'applyRestriction'])->name('restrictions.apply');
        Route::post('restrictions/{id}/lift', [SuspensionController::class, 'liftBan'])->name('restrictions.lift');

        Route::post('disputes/{id}/dashboard-resolve', [DisputeController::class, 'dashboardResolve'])->name('disputes.dashboard-resolve');
        Route::post('disputes/{id}/dashboard-assign', [DisputeController::class, 'dashboardAssign'])->name('disputes.dashboard-assign');

        Route::post('late-returns/{id}/escalate', [LateReturnController::class, 'dashboardEscalate'])->name('late-returns.escalate');

        Route::post('insurance-claims/dashboard-store', [InsuranceClaimController::class, 'dashboardStore'])->name('insurance-claims.dashboard-store');
        Route::post('refunds/process', [DisputeController::class, 'processRefund'])->name('refunds.process');
        Route::post('audits/dashboard-generate', [InventoryAuditController::class, 'dashboardGenerate'])->name('audits.dashboard-generate');
        Route::post('tools/{id}/review', [DashboardController::class, 'reviewTool'])->name('tools.review');
        Route::post('promotions/campaigns', [MarketingController::class, 'storeCampaign'])->name('promotions.store');
        Route::post('broadcasts/send', [CommunicationController::class, 'sendBroadcast'])->name('broadcasts.send');

        Route::resource('inventory', InventoryController::class);
        Route::resource('disputes', DisputeController::class);
        Route::resource('insurance', InsuranceController::class);
        Route::resource('users', UserController::class);
        Route::resource('communication', CommunicationController::class);
        Route::resource('marketing', MarketingController::class);
        Route::resource('zones', ZoneController::class);
    });

    Route::prefix('maintenance')->name('maintenance.')->middleware('role:technician')->group(function () {
        Route::get('dashboard', [MaintenanceController::class, 'index'])->name('dashboard');

        Route::post('trigger/store', [MaintenanceController::class, 'store'])->name('trigger.store');

        Route::post('queue/start', [MaintenanceController::class, 'startWork'])->name('queue.start');
        Route::post('queue/complete', [MaintenanceController::class, 'completeWork'])->name('queue.complete');

        Route::resource('safety', SafetyController::class);
        Route::post('safety/renew',   [SafetyController::class, 'updateSafetyCertification'])->name('safety.renew');
        Route::post('safety/lockout', [SafetyController::class, 'markUnfit'])->name('safety.lockout');
        Route::post('safety/release', [SafetyController::class, 'markFit'])->name('safety.release');

        Route::resource('repairs', RepairController::class);
        Route::post('repairs/spare-part', [RepairController::class, 'orderSparePart'])->name('repairs.spare-part');
        Route::post('repairs/external',   [RepairController::class, 'dispatchExternalRepair'])->name('repairs.external');

        Route::resource('inventory', MaintenanceInventoryController::class);
        Route::post('inventory/stock', [MaintenanceInventoryController::class, 'updateStock'])->name('inventory.stock');

        Route::resource('wiki', KnowledgeBaseController::class);
        Route::post('wiki/store', [KnowledgeBaseController::class, 'store'])->name('wiki.store');
    });

});
