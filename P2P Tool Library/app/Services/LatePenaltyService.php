<?php

namespace App\Services;

// Handle calculations for late return penalties
class LatePenaltyService
{
    // Calculate penalty based on days late
    public function calculatePenalty(int $daysLate, float $basePrice): float
    {
        $rate = $this->getPenaltyRate($daysLate);

        return round($basePrice * $rate, 2);
    }
    
    // Determine penalty percentage rate based on duration
    // 1-2 days => 0%
    // 3-6 days => 5%
    // 7-13 days => 10%
    // 14+ days => 20%
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
