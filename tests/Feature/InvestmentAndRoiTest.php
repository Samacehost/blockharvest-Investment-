<?php

namespace Tests\Feature;

use App\Models\InvestmentPlan;
use App\Models\User;
use App\Services\InvestmentEngineService;
use App\Services\LedgerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvestmentAndRoiTest extends TestCase
{
    use RefreshDatabase;

    public function test_investment_creation_and_scheduled_payout(): void
    {
        $user = User::factory()->create(['first_name' => 'Jane', 'last_name' => 'Smith']);

        // Fund investor wallet
        LedgerService::recordTransaction($user, 'DEPOSIT_CREDIT', 'CREDIT', 1000.0000, 'USD');

        $plan = InvestmentPlan::create([
            'name' => 'Test Fund',
            'slug' => 'test-fund',
            'min_amount' => 100.0000,
            'max_amount' => 5000.0000,
            'duration_value' => 1,
            'duration_unit' => 'days',
            'roi_rate' => 2.0000, // 2% daily
            'roi_frequency' => 'daily',
            'calculation_type' => 'simple',
            'capital_return' => true,
            'is_active' => true,
        ]);

        $investment = InvestmentEngineService::createInvestment($user, $plan, 500.0000);

        $this->assertEquals('active', $investment->status);
        $this->assertDatabaseHas('investments', ['user_id' => $user->id, 'amount' => '500.0000']);

        // Fast-forward payout date
        $investment->update(['next_payout_at' => now()->subMinutes(5)]);

        $processed = InvestmentEngineService::processDuePayouts();
        $this->assertEquals(1, $processed);

        $this->assertDatabaseHas('investment_earnings', [
            'investment_id' => $investment->id,
            'amount' => '10.0000', // 2% of $500
        ]);
    }
}
