<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\InvestmentPlan;
use App\Models\Faq;
use App\Models\Testimonial;
use App\Models\Currency;
use App\Services\CurrencyService;
use App\Services\InvestmentEngineService;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function home()
    {
        $currentCurrency = CurrencyService::getCurrentCurrency();
        $currencies = CurrencyService::getActiveCurrencies();

        $plans = InvestmentPlan::where('is_active', true)->orderBy('display_order')->get();
        $faqs = Faq::where('is_active', true)->orderBy('display_order')->take(8)->get();
        $testimonials = Testimonial::where('is_active', true)->orderBy('display_order')->get();
        $announcement = Announcement::where('is_active', true)->latest()->first();

        // Convert plan min/max amounts for display in selected currency
        foreach ($plans as $plan) {
            $plan->converted_min = CurrencyService::convert($plan->min_amount, 'USD', $currentCurrency);
            $plan->converted_max = CurrencyService::convert($plan->max_amount, 'USD', $currentCurrency);
            $plan->formatted_min = CurrencyService::format($plan->converted_min, $currentCurrency);
            $plan->formatted_max = CurrencyService::format($plan->converted_max, $currentCurrency);
        }

        return view('public.home', compact('plans', 'faqs', 'testimonials', 'announcement', 'currentCurrency', 'currencies'));
    }

    public function setCurrency(Request $request)
    {
        $request->validate(['currency' => 'required|string|in:USD,GBP,CAD']);

        $cookie = CurrencyService::setGuestCurrency($request->currency);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'currency' => $request->currency]);
        }

        return back()->withCookie($cookie);
    }

    public function about()
    {
        return view('public.about');
    }

    public function plans()
    {
        $currentCurrency = CurrencyService::getCurrentCurrency();
        $plans = InvestmentPlan::where('is_active', true)->orderBy('display_order')->get();

        foreach ($plans as $plan) {
            $plan->converted_min = CurrencyService::convert($plan->min_amount, 'USD', $currentCurrency);
            $plan->converted_max = CurrencyService::convert($plan->max_amount, 'USD', $currentCurrency);
            $plan->formatted_min = CurrencyService::format($plan->converted_min, $currentCurrency);
            $plan->formatted_max = CurrencyService::format($plan->converted_max, $currentCurrency);
        }

        return view('public.plans', compact('plans', 'currentCurrency'));
    }

    public function calculator()
    {
        $currentCurrency = CurrencyService::getCurrentCurrency();
        $plans = InvestmentPlan::where('is_active', true)->orderBy('display_order')->get();

        return view('public.calculator', compact('plans', 'currentCurrency'));
    }

    public function calculateRoi(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:investment_plans,id',
            'amount' => 'required|numeric|min:1',
            'currency' => 'nullable|string|in:USD,GBP,CAD',
        ]);

        $currency = $request->currency ?? CurrencyService::getCurrentCurrency();
        $plan = InvestmentPlan::findOrFail($request->plan_id);

        // Convert input amount to USD base if entered in target currency
        $amountInBaseUsd = CurrencyService::convert($request->amount, $currency, 'USD');

        $projections = InvestmentEngineService::calculateProjections($plan, (float)$amountInBaseUsd);

        // Convert result back to selected display currency
        $convertedGross = CurrencyService::convert($projections['gross_profit'], 'USD', $currency);
        $convertedTotal = CurrencyService::convert($projections['total_projected_return'], 'USD', $currency);
        $convertedPrincipal = CurrencyService::convert($projections['amount'], 'USD', $currency);

        return response()->json([
            'amount' => number_format((float)$convertedPrincipal, 2, '.', ''),
            'gross_profit' => number_format((float)$convertedGross, 2, '.', ''),
            'total_projected_return' => number_format((float)$convertedTotal, 2, '.', ''),
            'currency' => $currency,
            'currency_symbol' => Currency::where('code', $currency)->value('symbol') ?? '$',
            'total_periods' => $projections['total_periods'],
            'roi_frequency' => $projections['roi_frequency'],
            'calculation_type' => $projections['calculation_type'],
            'capital_returned' => $projections['capital_returned'],
        ]);
    }

    public function howItWorks()
    {
        return view('public.how-it-works');
    }

    public function security()
    {
        return view('public.security');
    }

    public function faqs()
    {
        $faqs = Faq::where('is_active', true)->orderBy('display_order')->get();
        return view('public.faqs', compact('faqs'));
    }

    public function contact()
    {
        return view('public.contact');
    }

    public function submitContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        return back()->with('success', 'Thank you for contacting us! Our team will respond shortly.');
    }

    public function privacyPolicy()
    {
        return view('public.privacy-policy');
    }

    public function terms()
    {
        return view('public.terms');
    }

    public function riskDisclosure()
    {
        return view('public.risk-disclosure');
    }

    public function cookiePolicy()
    {
        return view('public.cookie-policy');
    }

    public function amlKycPolicy()
    {
        return view('public.aml-kyc-policy');
    }
}
