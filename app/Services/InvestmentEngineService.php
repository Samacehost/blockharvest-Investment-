<?php

namespace App\Services;

use App\Models\Investment;
use App\Models\InvestmentEarning;
use App\Models\InvestmentPlan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;

class InvestmentEngineService
{
    /**
     * Calculate projected returns based on plan rules and principal amount
     */
    public static function calculateProjections(InvestmentPlan $plan, float $amount): array
    {
        $amount = (float)$amount;
        $roiRate = (float)$plan->roi_rate / 100;
        $durationValue = (int)$plan->duration_value;

        // Estimate number of periods based on duration unit & payout frequency
        $totalPeriods = match ($plan->roi_frequency) {
            'hourly' => $plan->duration_unit === 'days' ? $durationValue * 24 : $durationValue,
            'daily' => $plan->duration_unit === 'months' ? $durationValue * 30 : $durationValue,
            'weekly' => ceil(($durationValue * ($plan->duration_unit === 'months' ? 30 : 1)) / 7),
            'monthly' => $durationValue,
            'yearly' => $durationValue,
            'at_maturity' => 1,
            default => $durationValue,
        };

        if ($plan->calculation_type === 'compound') {
            // Compound Interest Formula: A = P * (1 + r)^n
            $totalReturn = $amount * pow((1 + $roiRate), $totalPeriods);
            $grossProfit = $totalReturn - $amount;
        } else {
            // Simple Interest Formula: Profit = P * r * n
            $profitPerPeriod = $amount * $roiRate;
            $grossProfit = $profitPerPeriod * $totalPeriods;
            $totalReturn = $amount + $grossProfit;
        }

        $netProfit = max(0, $grossProfit);
        $totalReturnFinal = $plan->capital_return ? ($amount + $netProfit) : $netProfit;

        return [
            'amount' => number_format($amount, 2, '.', ''),
            'total_periods' => (int)$totalPeriods,
            'roi_rate_percent' => $plan->roi_rate,
            'gross_profit' => number_format($netProfit, 2, '.', ''),
            'total_projected_return' => number_format($totalReturnFinal, 2, '.', ''),
            'capital_returned' => (bool)$plan->capital_return,
            'calculation_type' => $plan->calculation_type,
            'roi_frequency' => $plan->roi_frequency,
        ];
    }

    /**
     * Activate a new investment for a user
     */
    public static function createInvestment(User $user, InvestmentPlan $plan, float $amount): Investment
    {
        if (!$plan->is_active) {
            throw new InvalidArgumentException("Selected investment plan is currently inactive.");
        }

        if ($amount < (float)$plan->min_amount || $amount > (float)$plan->max_amount) {
            throw new InvalidArgumentException("Investment amount must be between $" . number_format($plan->min_amount, 2) . " and $" . number_format($plan->max_amount, 2) . ".");
        }

        $projections = self::calculateProjections($plan, $amount);

        return DB::transaction(function () use ($user, $plan, $amount, $projections) {
            $reference = 'INV-' . strtoupper(Str::random(10));
            $now = Carbon::now();

            $maturesAt = match ($plan->duration_unit) {
                'hours' => $now->copy()->addHours($plan->duration_value),
                'days' => $now->copy()->addDays($plan->duration_value),
                'weeks' => $now->copy()->addWeeks($plan->duration_value),
                'months' => $now->copy()->addMonths($plan->duration_value),
                'years' => $now->copy()->addYears($plan->duration_value),
                default => $now->copy()->addDays($plan->duration_value),
            };

            $nextPayoutAt = match ($plan->roi_frequency) {
                'hourly' => $now->copy()->addHour(),
                'daily' => $now->copy()->addDay(),
                'weekly' => $now->copy()->addWeek(),
                'monthly' => $now->copy()->addMonth(),
                'yearly' => $now->copy()->addYear(),
                'at_maturity' => $maturesAt,
                default => $now->copy()->addDay(),
            };

            $currencyCode = $plan->currency_code ?? 'USD';

            $investment = Investment::create([
                'reference' => $reference,
                'user_id' => $user->id,
                'investment_plan_id' => $plan->id,
                'amount' => number_format($amount, 4, '.', ''),
                'currency_code' => $currencyCode,
                'roi_rate' => $plan->roi_rate,
                'roi_frequency' => $plan->roi_frequency,
                'calculation_type' => $plan->calculation_type,
                'capital_return' => $plan->capital_return,
                'total_projected_roi' => $projections['gross_profit'],
                'total_earned_roi' => 0.0000,
                'status' => 'active',
                'started_at' => $now,
                'next_payout_at' => $nextPayoutAt,
                'matures_at' => $maturesAt,
            ]);

            // Lock available balance & transfer to invested balance via ledger
            LedgerService::recordTransaction(
                user: $user,
                type: 'INVESTMENT_DEBIT',
                direction: 'DEBIT',
                amount: $amount,
                currencyCode: $currencyCode,
                relatedModel: $investment,
                userNotes: "Activated investment plan [{$plan->name}] ({$reference})"
            );

            // Process 5% Referral Commission if user was referred
            if (!empty($user->referred_by_id)) {
                $referrer = User::find($user->referred_by_id);
                if ($referrer) {
                    $commissionRate = 5.00;
                    $commissionAmount = number_format($amount * 0.05, 4, '.', '');

                    // Credit referrer via Ledger (updates referral_balance)
                    LedgerService::recordTransaction(
                        user: $referrer,
                        type: 'REFERRAL_COMMISSION',
                        direction: 'CREDIT',
                        amount: $commissionAmount,
                        currencyCode: $currencyCode,
                        relatedModel: $investment,
                        userNotes: "5% Referral Commission from {$user->name}'s investment ({$reference})"
                    );

                    // Record referral commission entry
                    \App\Models\ReferralCommission::create([
                        'referrer_id' => $referrer->id,
                        'referee_id' => $user->id,
                        'investment_id' => $investment->id,
                        'investment_amount' => number_format($amount, 4, '.', ''),
                        'commission_rate' => $commissionRate,
                        'commission_amount' => $commissionAmount,
                        'currency_code' => $currencyCode,
                        'status' => 'credited',
                    ]);
                }
            }

            return $investment;
        });
    }

    /**
     * Scheduled ROI Payout Processor (Idempotent & Transactional)
     */
    public static function processDuePayouts(): int
    {
        $dueInvestments = Investment::where('status', 'active')
            ->where('next_payout_at', '<=', Carbon::now())
            ->get();

        $processedCount = 0;

        foreach ($dueInvestments as $investment) {
            DB::transaction(function () use ($investment, &$processedCount) {
                // Re-lock investment row
                $inv = Investment::where('id', $investment->id)->lockForUpdate()->first();
                if (!$inv || $inv->status !== 'active' || $inv->next_payout_at > Carbon::now()) {
                    return;
                }

                $now = Carbon::now();
                $plan = $inv->plan;
                $amount = (float)$inv->amount;
                $rate = (float)$inv->roi_rate / 100;

                // Calculate single period return
                $earningAmount = match ($inv->calculation_type) {
                    'compound' => ($amount + (float)$inv->total_earned_roi) * $rate,
                    default => $amount * $rate,
                };

                $formattedEarning = number_format($earningAmount, 4, '.', '');

                // Credit earnings via Ledger
                $ledgerEntry = LedgerService::recordTransaction(
                    user: $inv->user,
                    type: 'ROI_EARNING_CREDIT',
                    direction: 'CREDIT',
                    amount: $formattedEarning,
                    currencyCode: $inv->currency_code,
                    relatedModel: $inv,
                    userNotes: "ROI Payout of $" . number_format($earningAmount, 2) . " for investment {$inv->reference}"
                );

                // Record Earning entry
                InvestmentEarning::create([
                    'investment_id' => $inv->id,
                    'user_id' => $inv->user_id,
                    'amount' => $formattedEarning,
                    'calculation_snapshot' => "Rate: {$inv->roi_rate}%, Type: {$inv->calculation_type}",
                    'earned_at' => $now,
                    'ledger_entry_id' => $ledgerEntry->id,
                ]);

                $inv->total_earned_roi = number_format((float)$inv->total_earned_roi + $earningAmount, 4, '.', '');

                // Check if matured
                if ($now->gte($inv->matures_at)) {
                    $inv->status = 'matured';
                    $inv->completed_at = $now;

                    // Return capital if enabled
                    if ($inv->capital_return) {
                        LedgerService::recordTransaction(
                            user: $inv->user,
                            type: 'CAPITAL_RETURN_CREDIT',
                            direction: 'CREDIT',
                            amount: $inv->amount,
                            currencyCode: $inv->currency_code,
                            relatedModel: $inv,
                            userNotes: "Capital principal returned for matured investment {$inv->reference}"
                        );
                    }
                } else {
                    // Set next payout date
                    $inv->next_payout_at = match ($inv->roi_frequency) {
                        'hourly' => Carbon::parse($inv->next_payout_at)->addHour(),
                        'daily' => Carbon::parse($inv->next_payout_at)->addDay(),
                        'weekly' => Carbon::parse($inv->next_payout_at)->addWeek(),
                        'monthly' => Carbon::parse($inv->next_payout_at)->addMonth(),
                        'yearly' => Carbon::parse($inv->next_payout_at)->addYear(),
                        default => Carbon::parse($inv->next_payout_at)->addDay(),
                    };
                }

                $inv->save();
                $processedCount++;
            });
        }

        return $processedCount;
    }
}
