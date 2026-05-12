<?php

namespace App\Services;

use App\Models\DashboardActivityLog;
use App\Models\Reservation;
use App\Models\User;
use App\Events\DashboardActivityUpdated;
use App\Notifications\DashboardAlertNotification;

class DashboardActivityService
{

    public function logRentalStarted(Reservation $reservation)
    {
        $log = DashboardActivityLog::create([
            'activity_type'    => 'rental_started',
            'borrow_id'        => $reservation->id,
            'user_id'          => $reservation->borrower_id,
            'tool_id'          => $reservation->tool_id,
            'activity_message' => "Rental started for tool: {$reservation->tool->title}",
            'activity_time'    => now(),
        ]);

        broadcast(new DashboardActivityUpdated($log));

        return $log;
    }

    public function logRentalCompleted(Reservation $reservation)
    {
        $log = DashboardActivityLog::create([
            'activity_type'    => 'rental_completed',
            'borrow_id'        => $reservation->id,
            'user_id'          => $reservation->borrower_id,
            'tool_id'          => $reservation->tool_id,
            'activity_message' => "Rental completed for tool: {$reservation->tool->title}",
            'activity_time'    => now(),
        ]);

        broadcast(new DashboardActivityUpdated($log));

        $this->notifyLibrarians('completed', [
            'reservation_id' => $reservation->id,
            'tool_id'        => $reservation->tool_id
        ]);

        return $log;
    }

    public function logPendingReturn(Reservation $reservation)
    {
        $log = DashboardActivityLog::create([
            'activity_type'    => 'pending_return',
            'borrow_id'        => $reservation->id,
            'user_id'          => $reservation->borrower_id,
            'tool_id'          => $reservation->tool_id,
            'activity_message' => "Return is pending for tool: {$reservation->tool->title}",
            'activity_time'    => now(),
        ]);

        broadcast(new DashboardActivityUpdated($log));

        $this->notifyLibrarians('pending', [
            'reservation_id' => $reservation->id,
            'tool_id'        => $reservation->tool_id
        ]);

        return $log;
    }

    public function logOverdueReturn(Reservation $reservation)
    {
        $log = DashboardActivityLog::create([
            'activity_type'    => 'overdue_return',
            'borrow_id'        => $reservation->id,
            'user_id'          => $reservation->borrower_id,
            'tool_id'          => $reservation->tool_id,
            'activity_message' => "URGENT: Rental is overdue for tool: {$reservation->tool->title}",
            'activity_time'    => now(),
        ]);

        broadcast(new DashboardActivityUpdated($log));

        $this->notifyLibrarians('overdue', [
            'reservation_id' => $reservation->id,
            'tool_id'        => $reservation->tool_id
        ]);

        return $log;
    }

    public function logPaymentReceived(Reservation $reservation, float $amount)
    {
        $log = DashboardActivityLog::create([
            'activity_type'    => 'payment_received',
            'borrow_id'        => $reservation->id,
            'user_id'          => $reservation->borrower_id,
            'tool_id'          => $reservation->tool_id,
            'activity_message' => "Payment of {$amount} received for rental #{$reservation->id}",
            'activity_time'    => now(),
        ]);

        broadcast(new DashboardActivityUpdated($log));

        return $log;
    }

    protected function notifyLibrarians(string $type, array $data)
    {
        User::where('role', 'libraian')->get()->each(function ($librarian) use ($type, $data) {
            $librarian->notify(new DashboardAlertNotification($type, $data));
        });
    }
}
