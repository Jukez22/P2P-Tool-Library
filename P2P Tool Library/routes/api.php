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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

use App\Http\Controllers\Maintenance\MaintenanceController;
use App\Http\Controllers\Maintenance\SafetyController;
use App\Http\Controllers\Maintenance\RepairController;
use App\Http\Controllers\Maintenance\InventoryController;
use App\Http\Controllers\Maintenance\KnowledgeBaseController;

Route::prefix('maintenance')->group(function () {
    Route::post('/usage', [MaintenanceController::class, 'logUsage']);
    Route::get('/warranty-expiry', [MaintenanceController::class, 'checkWarrantyExpiry']);

    Route::post('/safety-cert', [SafetyController::class, 'updateSafetyCertification']);
    Route::post('/unfit', [SafetyController::class, 'markUnfit']);

    Route::get('/repair-estimate', [RepairController::class, 'getCostEstimate']);
    Route::post('/external-repair', [RepairController::class, 'dispatchExternalRepair']);

    Route::post('/consumable-track', [InventoryController::class, 'trackConsumable']);

    Route::get('/history/{toolId}', [MaintenanceController::class, 'getHistoryPortfolio']);
    Route::post('/dispose', [MaintenanceController::class, 'disposeTool']);
    Route::get('/priority-queue', [MaintenanceController::class, 'getPriorityQueue']);

    Route::get('/articles', [KnowledgeBaseController::class, 'getArticles']);
    Route::post('/articles', [KnowledgeBaseController::class, 'createArticle']);

    Route::post('/battery-health', [InventoryController::class, 'logBatteryHealth']);

    Route::post('/spare-part', [RepairController::class, 'orderSparePart']);
    Route::put('/spare-part', [RepairController::class, 'updateSparePartStatus']);
    Route::get('/technician-metrics/{technicianId}', [RepairController::class, 'getTechnicianMetrics']);
});
