<?php

namespace Tests\Feature;

use App\Models\Deposit;
use App\Models\DepositMethod;
use App\Models\Investment;
use App\Models\InvestmentPlan;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Withdrawal;
use App\Models\WithdrawalMethod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSyntheticHistoryGeneratorTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $investor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'super_admin',
            'password' => bcrypt('admin12345'),
        ]);

        $this->investor = User::factory()->create([
            'role' => 'investor',
            'currency_code' => 'CAD',
        ]);

        Wallet::create([
            'user_id' => $this->investor->id,
            'currency_code' => 'CAD',
            'available_balance' => 1000.00,
            'invested_balance' => 0.00,
            'earnings_balance' => 0.00,
        ]);

        DepositMethod::create([
            'name' => 'Interac e-Transfer CAD',
            'code' => 'interac_cad',
            'currency_code' => 'CAD',
            'is_active' => true,
        ]);

        WithdrawalMethod::create([
            'name' => 'EFT Bank Transfer CAD',
            'code' => 'eft_cad',
            'currency_code' => 'CAD',
            'is_active' => true,
        ]);

        InvestmentPlan::create([
            'name' => 'Starter Growth Plan',
            'slug' => 'starter-growth-plan',
            'min_amount' => 100.00,
            'max_amount' => 5000.00,
            'currency_code' => 'CAD',
            'duration_value' => 7,
            'duration_unit' => 'days',
            'roi_rate' => 10.00,
            'roi_frequency' => 'at_maturity',
            'calculation_type' => 'simple',
            'capital_return' => true,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_set_exact_wallet_balance(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.users.set-balance', $this->investor->id), [
            'available_balance' => 8500.50,
            'invested_balance' => 2000.00,
            'earnings_balance' => 500.00,
            'referral_balance' => 150.00,
            'admin_password' => 'admin12345',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $wallet = Wallet::where('user_id', $this->investor->id)->first();
        $this->assertEquals(8500.50, (float)$wallet->available_balance);
        $this->assertEquals(2000.00, (float)$wallet->invested_balance);
        $this->assertEquals(500.00, (float)$wallet->earnings_balance);
        $this->assertEquals(150.00, (float)$wallet->referral_balance);
    }

    public function test_admin_can_generate_deposit_history(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.users.generate-deposits', $this->investor->id), [
            'count' => 15,
            'min_amount' => 100,
            'max_amount' => 2000,
            'days_back' => 20,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals(15, Deposit::where('user_id', $this->investor->id)->where('currency_code', 'CAD')->count());
    }

    public function test_admin_can_generate_withdrawal_history(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.users.generate-withdrawals', $this->investor->id), [
            'count' => 8,
            'min_amount' => 50,
            'max_amount' => 500,
            'days_back' => 15,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals(8, Withdrawal::where('user_id', $this->investor->id)->where('currency_code', 'CAD')->count());
    }

    public function test_admin_can_generate_investment_history_and_calculate_maturity(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.users.generate-investments', $this->investor->id), [
            'count' => 10,
            'from_date' => now()->subDays(60)->format('Y-m-d'),
            'to_date' => now()->subDays(10)->format('Y-m-d'),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals(10, Investment::where('user_id', $this->investor->id)->count());
        $completedCount = Investment::where('user_id', $this->investor->id)->where('status', 'completed')->count();
        $this->assertGreaterThan(0, $completedCount);
    }
}
