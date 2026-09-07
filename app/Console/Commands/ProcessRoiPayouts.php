<?php

namespace App\Console\Commands;

use App\Models\ScheduledJobLog;
use App\Services\InvestmentEngineService;
use Illuminate\Console\Command;

class ProcessRoiPayouts extends Command
{
    protected $signature = 'roi:process';
    protected $description = 'Process due investment ROI payouts and maturity capital returns';

    public function handle(): int
    {
        $this->info('Starting ROI payout processing...');

        try {
            $processedCount = InvestmentEngineService::processDuePayouts();

            ScheduledJobLog::create([
                'job_name' => 'ProcessRoiPayouts',
                'status' => 'success',
                'summary' => "Successfully processed {$processedCount} due investment payouts.",
                'executed_at' => now(),
            ]);

            $this->info("Processed {$processedCount} investment payouts successfully.");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            ScheduledJobLog::create([
                'job_name' => 'ProcessRoiPayouts',
                'status' => 'failed',
                'summary' => $e->getMessage(),
                'executed_at' => now(),
            ]);

            $this->error('Failed to process ROI payouts: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
