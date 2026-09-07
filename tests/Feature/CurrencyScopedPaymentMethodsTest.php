<?php

namespace Tests\Feature;

use App\Models\DepositMethod;
use App\Models\User;
use App\Models\WithdrawalMethod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CurrencyScopedPaymentMethodsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_currency_symbol_returns_correct_symbol()
    {
        $usdUser = User::factory()->make(['currency_code' => 'USD']);
        $gbpUser = User::factory()->make(['currency_code' => 'GBP']);
        $cadUser = User::factory()->make(['currency_code' => 'CAD']);

        $this->assertEquals('$', $usdUser->currency_symbol);
        $this->assertEquals('£', $gbpUser->currency_symbol);
        $this->assertEquals('C$', $cadUser->currency_symbol);
    }

    public function test_wallet_displays_only_currency_scoped_and_all_methods()
    {
        $usdDeposit = DepositMethod::create([
            'name' => 'JP Morgan USD Wire',
            'code' => 'usd_wire',
            'currency_code' => 'USD',
            'min_amount' => 100,
            'max_amount' => 10000,
            'is_active' => true,
        ]);

        $cadDeposit = DepositMethod::create([
            'name' => 'RBC CAD Wire',
            'code' => 'cad_wire',
            'currency_code' => 'CAD',
            'min_amount' => 100,
            'max_amount' => 10000,
            'is_active' => true,
        ]);

        $allDeposit = DepositMethod::create([
            'name' => 'USDT Crypto',
            'code' => 'usdt_crypto',
            'currency_code' => 'ALL',
            'min_amount' => 10,
            'max_amount' => 10000,
            'is_active' => true,
        ]);

        $cadUser = User::create([
            'first_name' => 'Canadian',
            'last_name' => 'Investor',
            'email' => 'cad@example.com',
            'currency_code' => 'CAD',
            'password' => Hash::make('Password123!'),
        ]);

        $response = $this->actingAs($cadUser)->get('/user/wallet');
        $response->assertStatus(200);

        // CAD user sees RBC CAD Wire and USDT Crypto, but NOT JP Morgan USD Wire
        $response->assertSee('RBC CAD Wire');
        $response->assertSee('USDT Crypto');
        $response->assertDontSee('JP Morgan USD Wire');
    }

    public function test_admin_can_create_currency_scoped_deposit_method()
    {
        $admin = User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@example.com',
            'role' => 'super_admin',
            'password' => Hash::make('Password123!'),
        ]);

        $response = $this->actingAs($admin)->post('/admin/payment-methods/deposit', [
            'name' => 'Barclays GBP Transfer',
            'currency_code' => 'GBP',
            'min_amount' => 50,
            'max_amount' => 50000,
            'fee_flat' => 5,
            'fee_percent' => 0,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('deposit_methods', [
            'name' => 'Barclays GBP Transfer',
            'currency_code' => 'GBP',
        ]);
    }
}
