<?php

namespace App\Services;

use App\Models\LedgerEntry;
use App\Models\Wallet;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;

class LedgerService
{
    /**
     * Record an immutable double-entry ledger transaction and adjust wallet balance.
     */
    public static function recordTransaction(
        User $user,
        string $type,
        string $direction,
        float|string $amount,
        string $currencyCode = 'USD',
        mixed $relatedModel = null,
        ?User $adminUser = null,
        ?string $internalNotes = null,
        ?string $userNotes = null
    ): LedgerEntry {
        if ($amount <= 0) {
            throw new InvalidArgumentException("Transaction amount must be greater than zero.");
        }

        return DB::transaction(function () use ($user, $type, $direction, $amount, $currencyCode, $relatedModel, $adminUser, $internalNotes, $userNotes) {
            // Lock user's wallet for atomic update
            $wallet = Wallet::where('user_id', $user->id)
                ->where('currency_code', $currencyCode)
                ->lockForUpdate()
                ->first();

            if (!$wallet) {
                $wallet = Wallet::create([
                    'user_id' => $user->id,
                    'currency_code' => $currencyCode,
                    'available_balance' => 0.0000,
                    'invested_balance' => 0.0000,
                    'earnings_balance' => 0.0000,
                    'pending_deposit_balance' => 0.0000,
                    'pending_withdrawal_balance' => 0.0000,
                ]);
            }

            $before = (float) $wallet->available_balance;
            $formattedAmount = number_format((float)$amount, 4, '.', '');

            // Adjust appropriate balance depending on transaction type
            switch ($type) {
                case 'DEPOSIT_CREDIT':
                    $wallet->available_balance = number_format($before + (float)$amount, 4, '.', '');
                    $wallet->pending_deposit_balance = max(0, number_format((float)$wallet->pending_deposit_balance - (float)$amount, 4, '.', ''));
                    break;

                case 'INVESTMENT_DEBIT':
                    if ((float)$wallet->available_balance < (float)$amount) {
                        throw new InvalidArgumentException("Insufficient available balance for investment.");
                    }
                    $wallet->available_balance = number_format($before - (float)$amount, 4, '.', '');
                    $wallet->invested_balance = number_format((float)$wallet->invested_balance + (float)$amount, 4, '.', '');
                    break;

                case 'ROI_EARNING_CREDIT':
                    $wallet->available_balance = number_format($before + (float)$amount, 4, '.', '');
                    $wallet->earnings_balance = number_format((float)$wallet->earnings_balance + (float)$amount, 4, '.', '');
                    break;

                case 'REFERRAL_COMMISSION':
                    $wallet->referral_balance = number_format((float)$wallet->referral_balance + (float)$amount, 4, '.', '');
                    break;

                case 'CAPITAL_RETURN_CREDIT':
                    $wallet->available_balance = number_format($before + (float)$amount, 4, '.', '');
                    $wallet->invested_balance = max(0, number_format((float)$wallet->invested_balance - (float)$amount, 4, '.', ''));
                    break;

                case 'WITHDRAWAL_HOLD':
                    if ((float)$wallet->available_balance < (float)$amount) {
                        throw new InvalidArgumentException("Insufficient available balance for withdrawal.");
                    }
                    $wallet->available_balance = number_format($before - (float)$amount, 4, '.', '');
                    $wallet->pending_withdrawal_balance = number_format((float)$wallet->pending_withdrawal_balance + (float)$amount, 4, '.', '');
                    break;

                case 'WITHDRAWAL_HOLD_REFERRAL':
                    if ((float)$wallet->referral_balance < (float)$amount) {
                        throw new InvalidArgumentException("Insufficient referral commission balance for withdrawal.");
                    }
                    $wallet->referral_balance = number_format((float)$wallet->referral_balance - (float)$amount, 4, '.', '');
                    $wallet->pending_withdrawal_balance = number_format((float)$wallet->pending_withdrawal_balance + (float)$amount, 4, '.', '');
                    break;

                case 'WITHDRAWAL_PAYOUT':
                    $wallet->pending_withdrawal_balance = max(0, number_format((float)$wallet->pending_withdrawal_balance - (float)$amount, 4, '.', ''));
                    break;

                case 'WITHDRAWAL_REJECT_RELEASE':
                    $wallet->available_balance = number_format($before + (float)$amount, 4, '.', '');
                    $wallet->pending_withdrawal_balance = max(0, number_format((float)$wallet->pending_withdrawal_balance - (float)$amount, 4, '.', ''));
                    break;

                case 'WITHDRAWAL_REJECT_RELEASE_REFERRAL':
                    $wallet->referral_balance = number_format((float)$wallet->referral_balance + (float)$amount, 4, '.', '');
                    $wallet->pending_withdrawal_balance = max(0, number_format((float)$wallet->pending_withdrawal_balance - (float)$amount, 4, '.', ''));
                    break;

                case 'ADMIN_ADJUSTMENT':
                    if ($direction === 'CREDIT') {
                        $wallet->available_balance = number_format($before + (float)$amount, 4, '.', '');
                    } else {
                        $wallet->available_balance = max(0, number_format($before - (float)$amount, 4, '.', ''));
                    }
                    break;

                default:
                    throw new InvalidArgumentException("Unsupported transaction type [{$type}].");
            }

            $wallet->save();
            $after = (float) $wallet->available_balance;

            return LedgerEntry::create([
                'reference' => 'TXN-' . strtoupper(Str::random(12)),
                'user_id' => $user->id,
                'transaction_type' => $type,
                'direction' => $direction,
                'amount' => $formattedAmount,
                'currency_code' => $currencyCode,
                'balance_before' => number_format($before, 4, '.', ''),
                'balance_after' => number_format($after, 4, '.', ''),
                'related_type' => $relatedModel ? get_class($relatedModel) : null,
                'related_id' => $relatedModel ? $relatedModel->id : null,
                'processed_by_admin_id' => $adminUser ? $adminUser->id : null,
                'internal_notes' => $internalNotes,
                'user_notes' => $userNotes,
            ]);
        });
    }
}
