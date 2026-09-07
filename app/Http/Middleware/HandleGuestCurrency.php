<?php

namespace App\Http\Middleware;

use App\Services\CurrencyService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleGuestCurrency
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            // Logged in user account currency takes precedence
            $currency = auth()->user()->currency_code ?? 'USD';
            session(['selected_currency' => $currency]);
        } else {
            // Ensure guest currency is initialized
            CurrencyService::getGuestCurrency();
        }

        return $next($request);
    }
}
