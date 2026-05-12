<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\LateReturnController;

class ProcessLateReturnEscalations extends Command
{
    // command signature
    protected $signature = 'app:process-late-returns';

    // command description
    protected $description = 'Process late returns and escalations';

    // execute command
    public function handle()
    {
        $this->info('Starting late return escalation processing...');

        // Resolve controller
        $controller = app()->make(LateReturnController::class);
        
        $response = $controller->checkLateReturns();
        $data = $response->getData();

        $this->info("Completed. Escalations created: {$data->escalations_created}");
    }
}
