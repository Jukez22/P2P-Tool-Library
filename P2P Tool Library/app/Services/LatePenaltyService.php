<?php

namespace App\Services;

class LatePenaltyService
{

    public function calculatePenalty(int $daysLate, float $basePrice): float
    {
        $rate = $this->getPenaltyRate($daysLate);

        return round($basePrice * $rate, 2);
    }

    protected function getPenaltyRate(int $daysLate): float
    {
        if ($daysLate >= 14) {
            return 0.20;
        }
        if ($daysLate >= 7) {
            return 0.10;
        }
        if ($daysLate >= 3) {
            return 0.05;
        }
        return 0.0;
    }
}
