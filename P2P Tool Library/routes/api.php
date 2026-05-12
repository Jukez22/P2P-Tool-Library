<?php

use App\Http\Controllers\Member\ReservationController;
use App\Http\Controllers\Member\ToolController;
use App\Http\Controllers\HandoverController;
use App\Http\Controllers\InventoryAuditController;
use App\Http\Controllers\DisputeController;
use App\Http\Controllers\LateReturnController;
use App\Http\Controllers\InsuranceClaimController;
use App\Http\Controllers\ToolCategoryController;
use App\Http\Controllers\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Borrow & Reserve Routes (requires auth + active account)
Route::middleware(['auth:sanctum', 'not.suspended'])->group(function () {
    Route::apiResource('reservations', ReservationController::class);
    Route::apiResource('tools', ToolController::class);
    
    // Handover & QR Routes
    Route::get('/borrow/{id}/qr', [ReservationController::class, 'getReservationQR']);
    Route::post('/handover/verify', [HandoverController::class, 'verifyHandover']);

    // Dispute Routes
    Route::prefix('disputes')->group(function () {
        Route::post('/create', [DisputeController::class, 'createDispute']);
        Route::get('/pending', [DisputeController::class, 'getPendingDisputes']);
        Route::post('/{disputeId}/evidence', [DisputeController::class, 'uploadEvidence']);
        Route::post('/{disputeId}/start-review', [DisputeController::class, 'startReview']);
        Route::post('/{disputeId}/resolve', [DisputeController::class, 'resolveDispute']);
        Route::post('/{disputeId}/reject', [DisputeController::class, 'rejectDispute']);
    });

    // Inventory Audit Routes
    Route::prefix('inventory-audits')->group(function () {
        Route::post('/generate', [InventoryAuditController::class, 'generateAudit']);
        Route::get('/pending', [InventoryAuditController::class, 'getPendingAudits']);
        Route::post('/{auditItemId}/submit', [InventoryAuditController::class, 'submitAuditProof']);
        Route::post('/{auditItemId}/review', [InventoryAuditController::class, 'reviewAuditItem']);
        Route::post('/{auditId}/complete', [InventoryAuditController::class, 'completeAudit']);
    });

    // Late Return Routes
    Route::prefix('late-returns')->group(function () {
        Route::post('/check', [LateReturnController::class, 'checkLateReturns']);
        Route::post('/{escalationId}/resolve', [LateReturnController::class, 'resolveEscalation']);
    });

    // Insurance Claim Routes
    Route::prefix('insurance-claims')->group(function () {
        Route::post('/create', [InsuranceClaimController::class, 'createClaim']);
        Route::post('/{claimId}/evidence', [InsuranceClaimController::class, 'uploadEvidence']);
        Route::get('/{claimId}/report', [InsuranceClaimController::class, 'generateReport']);
        Route::post('/{claimId}/review', [InsuranceClaimController::class, 'reviewClaim']);
        Route::post('/{claimId}/complete', [InsuranceClaimController::class, 'completeClaim']);
    });

    // Tool Category Routes
    Route::prefix('categories')->group(function () {
        Route::post('/create', [ToolCategoryController::class, 'createCategory']);
        Route::put('/{categoryId}', [ToolCategoryController::class, 'updateCategory']);
        Route::post('/assign-tool', [ToolCategoryController::class, 'assignToolToCategory']);
        Route::get('/tree', [ToolCategoryController::class, 'getCategoryTree']);
        Route::get('/{categoryId}/tools', [ToolCategoryController::class, 'getToolsByCategory']);
        Route::get('/search/tools', [ToolCategoryController::class, 'searchTools']);
    });

    // Dashboard Routes
    Route::prefix('dashboard')->group(function () {
        Route::get('/metrics', [DashboardController::class, 'getDashboardMetrics']);
        Route::get('/activities', [DashboardController::class, 'getRecentActivities']);
        Route::get('/active-rentals', [DashboardController::class, 'getActiveRentals']);
        Route::get('/pending-returns', [DashboardController::class, 'getPendingReturns']);
        Route::get('/overdue-rentals', [DashboardController::class, 'getOverdueRentals']);
    });
});