<?php

namespace Tests\Feature;

use App\Models\InvestmentPlan;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WithdrawalMethod;
use App\Services\InvestmentEngineService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ReferralProgramTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_registration_with_referral_code_links_referrer()
    {
        $referrer = User::create([
            'first_name' => 'John',
            'last_name' => 'Referrer',
            'email' => 'referrer@example.com',
            'password' => Hash::make('Password123!'),
            'referral_code' => 'BH-REF123',
        ]);

        $response = $this->get('/register?ref=BH-REF123');
        $response->assertStatus(200);
        $response->assertSee('BH-REF123');

        $regResponse = $this->post('/register', [
            'first_name' => 'Jane',
            'last_name' => 'Friend',
            'email' => 'friend@example.com',
            'preferred_currency' => 'USD',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms' => '1',
            'ref' => 'BH-REF123',
        ]);

        $regResponse->assertRedirect('/user/dashboard');

        $referee = User::where('email', 'friend@example.com')->first();
        $this->assertNotNull($referee);
        $this->assertEquals($referrer->id, $referee->referred_by_id);
    }

    public function test_activating_investment_credits_5_percent_commission_to_referrer()
    {
        $referrer = User::create([
            'first_name' => 'Alice',
            'last_name' => 'Referrer',
            'email' => 'alice@example.com',
            'password' => Hash::make('Password123!'),
            'referral_code' => 'BH-ALICE99',
        ]);
        Wallet::create([
            'user_id' => $referrer->id,
            'currency_code' => 'USD',
            'available_balance' => 0.0000,
            'referral_balance' => 0.0000,
        ]);

        $referee = User::create([
            'first_name' => 'Bob',
            'last_name' => 'Friend',
            'email' => 'bob@example.com',
            'password' => Hash::make('Password123!'),
            'referred_by_id' => $referrer->id,
        ]);
        Wallet::create([
            'user_id' => $referee->id,
            'currency_code' => 'USD',
            'available_balance' => 1000.0000,
            'referral_balance' => 0.0000,
        ]);

        $plan = InvestmentPlan::create([
            'name' => 'Starter Yield Plan',
            'slug' => 'starter-yield',
            'min_amount' => 100,
            'max_amount' => 10000,
            'roi_rate' => 10.00,
            'roi_frequency' => 'daily',
            'duration_value' => 30,
            'duration_unit' => 'days',
            'calculation_type' => 'simple',
            'capital_return' => true,
            'is_active' => true,
        ]);

        // Bob invests $1,000
        $investment = InvestmentEngineService::createInvestment($referee, $plan, 1000.00);

        $this->assertNotNull($investment);

        // Check 5% commission ($50) credited to Alice's wallet
        $referrerWallet = Wallet::where('user_id', $referrer->id)->first();
        $this->assertEquals(50.0000, (float)$referrerWallet->referral_balance);

        // Check ReferralCommission record created
        $this->assertDatabaseHas('referral_commissions', [
            'referrer_id' => $referrer->id,
            'referee_id' => $referee->id,
            'investment_amount' => '1000.0000',
            'commission_rate' => '5.00',
            'commission_amount' => '50.0000',
        ]);
    }

    public function test_referral_dashboard_renders_stats_and_referred_users()
    {
        $referrer = User::create([
            'first_name' => 'Charlie',
            'last_name' => 'Marketer',
            'email' => 'charlie@example.com',
            'password' => Hash::make('Password123!'),
            'transaction_pin' => Hash::make('5829'),
        ]);

        Wallet::create([
            'user_id' => $referrer->id,
            'currency_code' => 'USD',
            'available_balance' => 0,
            'referral_balance' => 50.0000,
        ]);

        $response = $this->actingAs($referrer)->get('/user/referrals');
        $response->assertStatus(200);
        $response->assertSee('Referral Program');
        $response->assertSee('Total Referrals');
        $response->assertSee('50.00');
    }

    public function test_user_can_withdraw_referral_commission_with_pin()
    {
        $user = User::create([
            'first_name' => 'Dave',
            'last_name' => 'Investor',
            'email' => 'dave@example.com',
            'password' => Hash::make('Password123!'),
            'transaction_pin' => Hash::make('9482'),
            'kyc_status' => 'approved',
        ]);

        Wallet::create([
            'user_id' => $user->id,
            'currency_code' => 'USD',
            'available_balance' => 0.0000,
            'referral_balance' => 100.0000,
            'pending_withdrawal_balance' => 0.0000,
        ]);

        $method = WithdrawalMethod::create([
            'name' => 'Bitcoin Wallet',
            'code' => 'BTC',
            'currency_code' => 'USD',
            'fee_flat' => 2.00,
            'fee_percent' => 0.00,
            'min_amount' => 10.00,
            'max_amount' => 5000.00,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->post('/user/withdrawal', [
            'withdrawal_method_id' => $method->id,
            'balance_type' => 'referral',
            'amount' => 50.00,
            'destination' => 'bc1qtestaddress123456',
            'transaction_pin' => '9482',
        ]);

        $response->assertRedirect('/user/wallet');
        $response->assertSessionHas('success');

        $wallet = Wallet::where('user_id', $user->id)->first();
        $this->assertEquals(50.0000, (float)$wallet->referral_balance);
        $this->assertEquals(50.0000, (float)$wallet->pending_withdrawal_balance);
    }
}
