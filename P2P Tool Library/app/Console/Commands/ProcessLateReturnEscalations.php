<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\LateReturnController;

class ProcessLateReturnEscalations extends Command
{

    protected $signature = 'app:process-late-returns';

    protected $description = 'Process late returns and escalations';

    public function handle()
    {
        $this->info('Starting late return escalation processing...');

        $controller = app()->make(LateReturnController::class);

        $response = $controller->checkLateReturns();
        $data = $response->getData();

        $this->info("Completed. Escalations created: {$data->escalations_created}");
    }
}
