<?php

namespace Tests\Feature;

use App\Models\DepositMethod;
use App\Models\InvestmentPlan;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WithdrawalMethod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TransactionPinTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_set_and_update_transaction_pin(): void
    {
        $user = User::factory()->create([
            'transaction_pin' => null,
        ]);

        // 1. Attempt weak PIN (e.g. 1234)
        $responseWeak = $this->actingAs($user)->post('/user/profile/pin', [
            'pin' => '1234',
            'pin_confirmation' => '1234',
        ]);
        $responseWeak->assertSessionHasErrors('pin');
        $this->assertFalse($user->fresh()->hasTransactionPin());

        // 2. Set valid strong 4-digit PIN (e.g. 8392)
        $response = $this->actingAs($user)->post('/user/profile/pin', [
            'pin' => '8392',
            'pin_confirmation' => '8392',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertTrue($user->fresh()->hasTransactionPin());
        $this->assertTrue($user->fresh()->verifyTransactionPin('8392'));

        // 3. Update PIN with wrong current PIN
        $responseBad = $this->actingAs($user)->post('/user/profile/pin', [
            'current_pin' => '0000',
            'pin' => '7492',
            'pin_confirmation' => '7492',
        ]);

        $responseBad->assertSessionHasErrors('current_pin');
        $this->assertTrue($user->fresh()->verifyTransactionPin('8392'));

        // 4. Update PIN with correct current PIN
        $responseGood = $this->actingAs($user)->post('/user/profile/pin', [
            'current_pin' => '8392',
            'pin' => '7492',
            'pin_confirmation' => '7492',
        ]);

        $responseGood->assertRedirect();
        $responseGood->assertSessionHas('success');
        $this->assertTrue($user->fresh()->verifyTransactionPin('7492'));
    }

    public function test_investment_creation_requires_valid_transaction_pin(): void
    {
        $user = User::factory()->create([
            'kyc_status' => 'approved',
            'transaction_pin' => Hash::make('7492'),
        ]);

        Wallet::create([
            'user_id' => $user->id,
            'currency_code' => 'USD',
            'available_balance' => '1000.0000',
        ]);

        $plan = InvestmentPlan::create([
            'slug' => 'test-yield-plan',
            'name' => 'Test Yield Plan',
            'min_amount' => '100.00',
            'max_amount' => '5000.00',
            'duration_unit' => 'days',
            'duration_value' => 10,
            'roi_rate' => '2.50',
            'roi_frequency' => 'daily',
            'calculation_type' => 'simple',
            'is_active' => true,
        ]);

        // Attempt without PIN or invalid PIN
        $responseInvalid = $this->actingAs($user)->post('/user/investments', [
            'plan_id' => $plan->id,
            'amount' => 200,
            'transaction_pin' => '9999',
        ]);

        $responseInvalid->assertSessionHas('error', 'Invalid Transaction Security PIN. Please try again.');

        // Attempt with valid PIN
        $responseValid = $this->actingAs($user)->post('/user/investments', [
            'plan_id' => $plan->id,
            'amount' => 200,
            'transaction_pin' => '7492',
        ]);

        $responseValid->assertRedirect(route('user.investments'));
        $responseValid->assertSessionHas('success');
        $this->assertDatabaseHas('investments', [
            'user_id' => $user->id,
            'amount' => 200,
        ]);
    }

    public function test_withdrawal_request_requires_valid_transaction_pin(): void
    {
        $user = User::factory()->create([
            'kyc_status' => 'approved',
            'transaction_pin' => Hash::make('9153'),
        ]);

        Wallet::create([
            'user_id' => $user->id,
            'currency_code' => 'USD',
            'available_balance' => '500.0000',
        ]);

        $method = WithdrawalMethod::create([
            'code' => 'bank_wire',
            'name' => 'Bank Wire',
            'min_amount' => '50.00',
            'max_amount' => '10000.00',
            'fee_flat' => '5.00',
            'fee_percent' => '0.00',
            'currency_code' => 'USD',
            'is_active' => true,
        ]);

        // Invalid PIN
        $responseInvalid = $this->actingAs($user)->post('/user/withdrawal', [
            'withdrawal_method_id' => $method->id,
            'amount' => 100,
            'destination' => 'IBAN US123456789',
            'transaction_pin' => '0000',
        ]);

        $responseInvalid->assertSessionHas('error', 'Invalid Transaction Security PIN. Please try again.');

        // Valid PIN
        $responseValid = $this->actingAs($user)->post('/user/withdrawal', [
            'withdrawal_method_id' => $method->id,
            'amount' => 100,
            'destination' => 'IBAN US123456789',
            'transaction_pin' => '9153',
        ]);

        $responseValid->assertRedirect(route('user.wallet'));
        $responseValid->assertSessionHas('success');
        $this->assertDatabaseHas('withdrawals', [
            'user_id' => $user->id,
            'amount' => '100.0000',
        ]);
    }
}
