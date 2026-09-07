<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Wallet;
use App\Services\LedgerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LedgerAndWalletTest extends TestCase
{
    use RefreshDatabase;

    public function test_ledger_records_transaction_and_updates_balance_atomically(): void
    {
        $user = User::factory()->create(['first_name' => 'John', 'last_name' => 'Doe']);

        $entry = LedgerService::recordTransaction(
            user: $user,
            type: 'DEPOSIT_CREDIT',
            direction: 'CREDIT',
            amount: 500.0000,
            currencyCode: 'USD'
        );

        $this->assertDatabaseHas('ledger_entries', [
            'user_id' => $user->id,
            'transaction_type' => 'DEPOSIT_CREDIT',
            'amount' => '500.0000',
            'balance_after' => '500.0000',
        ]);

        $wallet = Wallet::where('user_id', $user->id)->first();
        $this->assertEquals(500.0000, (float)$wallet->available_balance);
    }
}
