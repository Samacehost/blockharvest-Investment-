<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user->status === 'banned') {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account has been suspended. Please contact support.']);
            }

            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('user.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister(Request $request)
    {
        $refCode = $request->query('ref');
        if ($refCode) {
            session(['ref' => $refCode]);
        } else {
            $refCode = session('ref');
        }

        return view('auth.register', compact('refCode'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:50'],
            'preferred_currency' => ['required', 'string', 'in:USD,GBP,CAD'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => ['accepted'],
            'ref' => ['nullable', 'string'],
        ]);

        $currencyCode = $request->preferred_currency;

        $refCode = $request->input('ref', session('ref'));
        $referredById = null;
        if (!empty($refCode)) {
            $referrer = User::where('referral_code', $refCode)->first();
            if ($referrer) {
                $referredById = $referrer->id;
            }
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'currency_code' => $currencyCode,
            'password' => Hash::make($request->password),
            'role' => 'investor',
            'kyc_status' => 'not_submitted',
            'status' => 'active',
            'referred_by_id' => $referredById,
        ]);

        // Initialize empty wallet in chosen account currency
        Wallet::create([
            'user_id' => $user->id,
            'currency_code' => $currencyCode,
            'available_balance' => 0.0000,
            'invested_balance' => 0.0000,
            'earnings_balance' => 0.0000,
            'referral_balance' => 0.0000,
        ]);

        session()->forget('ref');

        Auth::login($user);

        return redirect(route('user.dashboard'))->with('success', 'Welcome to BlockHarvest! Your account has been created successfully.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('public.home'));
    }
}
