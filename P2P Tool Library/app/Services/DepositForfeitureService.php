<?php

namespace App\Services;

use App\Models\Dispute;
use App\Models\Deposit;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Exception;

class DepositForfeitureService
{

    public function processForfeiture(Dispute $dispute): bool
    {
        if (!$dispute->deposit_forfeited || $dispute->forfeited_amount <= 0) {
            return false;
        }

        return DB::transaction(function () use ($dispute) {

            $deposit = Deposit::where('reservation_id', $dispute->borrow_id)
                ->where('status', 'held')
                ->first();

            if (!$deposit) {
                throw new Exception("Active deposit not found for this dispute.");
            }

            if ($deposit->amount < $dispute->forfeited_amount) {
                throw new Exception("Forfeited amount exceeds available deposit.");
            }

            $remainingAmount = $deposit->amount - $dispute->forfeited_amount;

            $deposit->update([
                'status' => $remainingAmount > 0 ? 'held' : 'forfeited',
                'amount' => $remainingAmount
            ]);

            Payment::create([
                'reservation_id' => $dispute->borrow_id,
                'amount'         => $dispute->forfeited_amount,
                'payment_method' => 'wallet',
                'status'         => 'paid',
            ]);

            return true;
        });
    }
}
