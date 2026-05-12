<?php
namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;

use App\Models\LateReturnEscalation;
use App\Models\LateReturnLog;
use App\Models\Reservation;
use App\Models\Deposit;
use App\Models\Payment;
use App\Notifications\LateReturnNotification;
use App\Services\LatePenaltyService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;
use Exception;

class LateReturnController extends Controller
{
    protected $penaltyService;

    public function __construct(LatePenaltyService $penaltyService)
    {
        $this->penaltyService = $penaltyService;
    }

    // Check for late returns and create escalations
    public function checkLateReturns()
    {
        $now = Carbon::now();

        $overdueReservations = Reservation::where('end_datetime', '<', $now)
            ->where('status', '!=', 'completed')
            ->get();

        $escalationsCreated = 0;

        foreach ($overdueReservations as $reservation) {
            $daysLate = $now->diffInDays($reservation->end_datetime);
            $level = $this->getEscalationLevel($daysLate);

            if ($level) {
                $exists = LateReturnEscalation::where('borrow_id', $reservation->id)
                    ->where('escalation_level', $level)
                    ->exists();

                if (!$exists) {
                    $penaltyAmount = $this->penaltyService->calculatePenalty($daysLate, $reservation->total_price);

                    $escalation = LateReturnEscalation::create([
                        'borrow_id'        => $reservation->id,
                        'escalation_level' => $level,
                        'days_late'        => $daysLate,
                        'penalty_amount'   => $penaltyAmount,
                        'escalated_at'     => $now,
                    ]);
                    
                    $this->sendLateReturnNotification($escalation);

                    if ($penaltyAmount > 0) {
                        $this->applyPenalty($escalation);
                    }

                    $escalationsCreated++;
                }
            }
        }

        return response()->json([
            'message' => 'Overdue check completed',
            'escalations_created' => $escalationsCreated
        ]);
    }

    // Apply penalty from deposit
    public function applyPenalty(LateReturnEscalation $escalation)
    {
        try {
            DB::transaction(function () use ($escalation) {
                $deposit = Deposit::where('reservation_id', $escalation->borrow_id)
                    ->where('status', 'held')
                    ->first();

                if ($deposit) {
                    $amountToDeduct = min($deposit->amount, $escalation->penalty_amount);
                    
                    // Deduct from deposit
                    $deposit->decrement('amount', $amountToDeduct);
                    
                    if ($deposit->amount <= 0) {
                        $deposit->update(['status' => 'forfeited']);
                    }

                    // Log transaction
                    Payment::create([
                        'reservation_id' => $escalation->borrow_id,
                        'amount'         => $amountToDeduct,
                        'payment_method' => 'deposit_deduction',
                        'status'         => 'paid',
                    ]);

                    LateReturnLog::create([
                        'late_return_escalation_id' => $escalation->id,
                        'notification_type'         => 'email',
                        'message'                   => "Penalty of ${$amountToDeduct} applied via deposit deduction.",
                        'sent_at'                   => Carbon::now(),
                    ]);
                }
            });
        } catch (Exception $e) {
            report($e);
        }
    }

    // Notify user and log it
    public function sendLateReturnNotification(LateReturnEscalation $escalation)
    {
        $borrower = $escalation->borrow->borrower;

        // Trigger Laravel Notification
        $borrower->notify(new LateReturnNotification($escalation));

        // Create log record
        LateReturnLog::create([
            'late_return_escalation_id' => $escalation->id,
            'notification_type'         => 'system',
            'message'                   => "Sent {$escalation->escalation_level} notification to {$borrower->name}",
            'sent_at'                   => Carbon::now(),
        ]);

        $escalation->update(['notification_sent' => true]);
    }

    // Map days to escalation level
    protected function getEscalationLevel($days)
    {
        if ($days >= 14) return 'final_notice';
        if ($days >= 7)  return 'penalty_level_2';
        if ($days >= 3)  return 'penalty_level_1';
        if ($days >= 1)  return 'warning';
        
        return null;
    }

    // Mark escalation as resolved
    public function resolveEscalation($escalationId)
    {
        $escalation = LateReturnEscalation::find($escalationId);

        if (!$escalation) {
            return response()->json(['message' => 'Escalation not found'], 404);
        }

        $escalation->update([
            'resolved_at' => Carbon::now(),
        ]);

        return response()->json([
            'message' => 'Escalation resolved successfully',
            'data'    => $escalation
        ]);
    }
}
