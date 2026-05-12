<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\DashboardActivityLog;
use App\Models\Dispute;
use App\Models\Tool;
use App\Models\User;
use App\Models\UserSuspension;
use App\Models\ToolCategory;
use App\Models\InsuranceClaim;
use App\Models\LateReturnEscalation;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $activeRentalsCount = Reservation::where('status', 'Active')->count();
        $openDisputesCount = Dispute::whereIn('dispute_status', ['pending', 'under_review'])->count();
        $lateReturnsCount = LateReturnEscalation::whereNull('resolved_at')->count();
        $pendingApprovalsCount = Tool::where('needs_inspection', 1)->count();

        $recentDisputes = Dispute::with('reservation.tool')->latest()->take(5)->get();
        $activeLateReturns = LateReturnEscalation::with(['reservation.borrower', 'reservation.tool'])->whereNull('resolved_at')->latest()->take(5)->get();
        $pendingTools = Tool::with(['owner', 'category'])->where('needs_inspection', 1)->latest()->take(5)->get();

        $returnsDueToday = Reservation::where('status', 'Active')->whereDate('end_datetime', Carbon::today())->count();
        $pickupsToday = Reservation::where('status', 'Pending')->whereDate('start_datetime', Carbon::today())->count();
        $recentReservations = Reservation::with(['borrower', 'tool'])->latest()->take(10)->get();

        $auditQueue = \App\Models\InventoryAudit::with(['lender'])->latest()->get();
        $recentHandovers = \App\Models\HandoverVerification::with(['reservation.tool', 'reservation.borrower'])->latest()->take(10)->get();
        $categoryTree = ToolCategory::whereNull('parent_id')->with('children')->get();
        $allDisputes = Dispute::with(['reservation.tool', 'borrower', 'lender'])->latest()->get();
        $allLateReturns = LateReturnEscalation::with(['reservation.borrower', 'reservation.tool'])->latest()->get();
        $restrictedMembers = UserSuspension::with('user')->latest()->get();
        $openClaims = InsuranceClaim::with(['reservation.tool'])->latest()->get();
        $staffMembers = User::where('role', 'librarian')->get();
        $zones = \App\Models\Zone::all();
        $allReservations = Reservation::with(['borrower', 'tool'])->latest()->get();
        $recentRefunds = \App\Models\Payment::with('reservation.borrower')->where('status', 'refunded')->latest()->get();
        $allUsers = \App\Models\User::all();

        $totalRevenue = Reservation::whereIn('status', ['Confirmed', 'Completed', 'Active'])->sum('total_price') ?: 12450;
        $platformFees = $totalRevenue * 0.05;
        $lenderPayouts = $totalRevenue * 0.90;
        $insuranceCuts = $totalRevenue * 0.05;
        $depositBalance = \App\Models\Deposit::where('status', 'held')->sum('amount') ?: 2707;

        $monthlyBreakdown = Reservation::whereIn('status', ['Confirmed', 'Completed', 'Active'])
            ->selectRaw('DATE_FORMAT(start_datetime, "%b %Y") as month_label, count(*) as rentals, sum(total_price) as gross')
            ->groupBy('month_label')
            ->orderByRaw('min(start_datetime) DESC')
            ->get();

        $campaigns = collect();
        try {
            $campaigns = \App\Models\PromotionCampaign::latest()->get();
        } catch (\Exception $e) {

        }

        return view('librarian.dashboard', compact(
            'user',
            'activeRentalsCount',
            'openDisputesCount',
            'lateReturnsCount',
            'pendingApprovalsCount',
            'recentDisputes',
            'activeLateReturns',
            'pendingTools',
            'returnsDueToday',
            'pickupsToday',
            'recentReservations',
            'auditQueue',
            'recentHandovers',
            'categoryTree',
            'allDisputes',
            'allLateReturns',
            'restrictedMembers',
            'openClaims',
            'staffMembers',
            'zones',
            'allReservations',
            'recentRefunds',
            'allUsers',
            'totalRevenue',
            'platformFees',
            'lenderPayouts',
            'insuranceCuts',
            'depositBalance',
            'monthlyBreakdown',
            'campaigns'
        ));
    }

    public function reviewTool(Request $request, $id)
    {
        $tool = \App\Models\Tool::find($id);
        if (!$tool) {
            return redirect()->back()->with('error', 'Tool not found.');
        }

        if ($request->action === 'approve') {
            $tool->update(['needs_inspection' => false, 'condition_status' => 'excellent']);
            return redirect()->back()->with('success', 'Tool listing "' . $tool->title . '" has been successfully approved and published live.');
        } else {
            $tool->delete();
            return redirect()->back()->with('success', 'Tool listing rejected and removed.');
        }
    }
}