<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Wallet;
use App\Models\InvestmentPlan;
use App\Services\CurrencyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MultiCurrencyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_guest_currency_selection_persists(): void
    {
        $response = $this->post('/set-currency', ['currency' => 'GBP']);
        $response->assertCookie('guest_currency', 'GBP');

        $this->assertEquals('GBP', CurrencyService::getGuestCurrency());
    }

    public function test_registration_requires_preferred_currency(): void
    {
        $response = $this->post('/register', [
            'first_name' => 'David',
            'last_name' => 'Miller',
            'email' => 'david@example.com',
            'phone' => '+15550001111',
            'preferred_currency' => 'CAD',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'terms' => '1',
        ]);

        $response->assertRedirect(route('user.dashboard'));
        $this->assertDatabaseHas('users', ['email' => 'david@example.com', 'currency_code' => 'CAD']);
        $this->assertDatabaseHas('wallets', ['currency_code' => 'CAD']);
    }

    public function test_currency_conversion_math(): void
    {
        // 100 USD at 0.79 rate = 79.00 GBP
        $convertedGbp = CurrencyService::convert(100.00, 'USD', 'GBP');
        $this->assertEquals('79.00', $convertedGbp);

        // 100 USD at 1.36 rate = 136.00 CAD
        $convertedCad = CurrencyService::convert(100.00, 'USD', 'CAD');
        $this->assertEquals('136.00', $convertedCad);
    }

    public function test_currency_change_restriction_when_balance_exists(): void
    {
        $user = User::factory()->create(['currency_code' => 'USD']);
        Wallet::create(['user_id' => $user->id, 'currency_code' => 'USD', 'available_balance' => 500.0000]);

        $canChange = CurrencyService::canUserChangeCurrency($user);
        $this->assertFalse($canChange);
    }
}
