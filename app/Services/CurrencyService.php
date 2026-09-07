<?php

namespace App\Services;

use App\Models\Currency;
use App\Models\Investment;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Deposit;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class CurrencyService
{
    const BASE_CURRENCY = 'USD';
    const SESSION_KEY = 'selected_currency';
    const COOKIE_KEY = 'guest_currency';

    /**
     * Get active supported currencies (USD, GBP, CAD)
     */
    public static function getActiveCurrencies()
    {
        return Cache::rememberForever('active_currencies', function () {
            return Currency::where('is_active', true)->orderBy('display_position')->get();
        });
    }

    /**
     * Get active guest currency selection (Session -> Cookie -> USD)
     */
    public static function getGuestCurrency(): string
    {
        if (Session::has(self::SESSION_KEY)) {
            return Session::get(self::SESSION_KEY);
        }

        $cookieVal = request()->cookie(self::COOKIE_KEY);
        if ($cookieVal && in_array($cookieVal, ['USD', 'GBP', 'CAD'])) {
            Session::put(self::SESSION_KEY, $cookieVal);
            return $cookieVal;
        }

        return self::BASE_CURRENCY;
    }

    /**
     * Set guest currency in session and return cookie
     */
    public static function setGuestCurrency(string $currencyCode): \Symfony\Component\HttpFoundation\Cookie
    {
        $code = strtoupper($currencyCode);
        if (!in_array($code, ['USD', 'GBP', 'CAD'])) {
            $code = self::BASE_CURRENCY;
        }

        Session::put(self::SESSION_KEY, $code);
        return cookie(self::COOKIE_KEY, $code, 43200); // 30 days
    }

    /**
     * Get current effective currency for request context (User Account > Guest Selection)
     */
    public static function getCurrentCurrency(): string
    {
        if (auth()->check()) {
            return auth()->user()->currency_code ?? self::BASE_CURRENCY;
        }

        return self::getGuestCurrency();
    }

    /**
     * Convert an amount from one currency to another using base exchange rates
     */
    public static function convert(float|string $amount, string $from = 'USD', string $to = 'USD'): string
    {
        $amount = (float)$amount;
        $from = strtoupper($from);
        $to = strtoupper($to);

        if ($from === $to) {
            return number_format($amount, 2, '.', '');
        }

        $rates = Cache::rememberForever('exchange_rates_map', function () {
            return Currency::pluck('exchange_rate_to_default', 'code')->toArray();
        });

        $fromRate = (float)($rates[$from] ?? 1.0);
        $toRate = (float)($rates[$to] ?? 1.0);

        // Convert to base USD first, then to target currency
        $amountInBase = ($from === self::BASE_CURRENCY) ? $amount : ($amount / ($fromRate ?: 1.0));
        $converted = ($to === self::BASE_CURRENCY) ? $amountInBase : ($amountInBase * $toRate);

        return number_format($converted, 2, '.', '');
    }

    /**
     * Format money with currency symbol
     */
    public static function format(float|string $amount, string $currencyCode = 'USD'): string
    {
        $currencies = self::getActiveCurrencies()->keyBy('code');
        $currency = $currencies[$currencyCode] ?? null;

        $symbol = $currency ? $currency->symbol : '$';
        $formattedAmount = number_format((float)$amount, 2);

        return "{$symbol}{$formattedAmount} {$currencyCode}";
    }

    /**
     * Check if a registered user is eligible to change account currency
     */
    public static function canUserChangeCurrency(User $user): bool
    {
        // 1. Check wallet available balance
        $wallet = Wallet::where('user_id', $user->id)->first();
        if ($wallet && (float)$wallet->available_balance > 0) {
            return false;
        }

        // 2. Check active investments
        $activeInvestments = Investment::where('user_id', $user->id)->where('status', 'active')->exists();
        if ($activeInvestments) {
            return false;
        }

        // 3. Check pending deposits or withdrawals
        $pendingDeposits = Deposit::where('user_id', $user->id)->where('status', 'pending')->exists();
        $pendingWithdrawals = Withdrawal::where('user_id', $user->id)->where('status', 'pending')->exists();
        if ($pendingDeposits || $pendingWithdrawals) {
            return false;
        }

        return true;
    }

    /**
     * Clear currency cache
     */
    public static function clearCache(): void
    {
        Cache::forget('active_currencies');
        Cache::forget('exchange_rates_map');
    }
}
