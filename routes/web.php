<?php

use App\Http\Controllers\AdminCurrencyController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminPaymentMethodController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Middleware\EnsureIsAdmin;
use Illuminate\Support\Facades\Route;

// Public Website Routes (17 Homepage Sections & Pages)
Route::controller(PublicController::class)->group(function () {
    Route::match(['get', 'head'], '/', 'home')->name('public.home');
    Route::post('/set-currency', 'setCurrency')->name('public.currency.set');
    Route::get('/about', 'about')->name('public.about');
    Route::get('/plans', 'plans')->name('public.plans');
    Route::get('/calculator', 'calculator')->name('public.calculator');
    Route::post('/calculator/api', 'calculateRoi')->name('public.calculator.api');
    Route::get('/how-it-works', 'howItWorks')->name('public.how-it-works');
    Route::get('/security', 'security')->name('public.security');
    Route::get('/faqs', 'faqs')->name('public.faqs');
    Route::get('/contact', 'contact')->name('public.contact');
    Route::post('/contact', 'submitContact')->name('public.contact.submit');
    Route::get('/privacy-policy', 'privacyPolicy')->name('public.privacy-policy');
    Route::get('/terms-and-conditions', 'terms')->name('public.terms');
    Route::get('/risk-disclosure', 'riskDisclosure')->name('public.risk-disclosure');
    Route::get('/cookie-policy', 'cookiePolicy')->name('public.cookie-policy');
    Route::get('/aml-kyc-policy', 'amlKycPolicy')->name('public.aml-kyc-policy');
});

// Authentication Routes
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login');
    Route::get('/register', 'showRegister')->name('register');
    Route::post('/register', 'register');
    Route::post('/logout', 'logout')->name('logout');
});

// Investor Dashboard Routes (Protected)
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::controller(UserDashboardController::class)->group(function () {
        Route::get('/dashboard', 'dashboard')->name('dashboard');
        Route::get('/investments', 'investments')->name('investments');
        Route::post('/investments', 'storeInvestment')->name('investments.store');
        Route::get('/wallet', 'wallet')->name('wallet');
        Route::get('/referrals', 'referrals')->name('referrals');
        Route::post('/deposit', 'storeDeposit')->name('deposit.store');
        Route::post('/withdrawal', 'storeWithdrawal')->name('withdrawal.store');
        Route::get('/transactions', 'transactions')->name('transactions');
        Route::get('/transactions/export', 'exportTransactions')->name('transactions.export');
        Route::get('/kyc', 'kyc')->name('kyc');
        Route::post('/kyc', 'storeKyc')->name('kyc.store');
        Route::get('/support', 'support')->name('support');
        Route::post('/support', 'storeSupportTicket')->name('support.store');
        Route::post('/support/{ticket}/reply', 'replySupportTicket')->name('support.reply');
        Route::get('/profile', 'profile')->name('profile');
        Route::post('/profile', 'updateProfile')->name('profile.update');
        Route::post('/profile/pin', 'updatePin')->name('profile.pin');
    });
});

// Super-Admin Dashboard Routes (Protected + Admin Middleware)
Route::middleware(['auth', EnsureIsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    Route::controller(AdminDashboardController::class)->group(function () {
        Route::get('/dashboard', 'dashboard')->name('dashboard');
        Route::get('/users', 'users')->name('users');
        Route::post('/users/{user}/status', 'updateUserStatus')->name('users.status');
        Route::post('/users/{user}/adjust-balance', 'adjustBalance')->name('users.adjust-balance');
        Route::post('/users/{user}/set-balance', 'setExactBalance')->name('users.set-balance');
        Route::post('/users/{user}/generate-deposits', 'generateDepositHistory')->name('users.generate-deposits');
        Route::post('/users/{user}/generate-withdrawals', 'generateWithdrawalHistory')->name('users.generate-withdrawals');
        Route::post('/users/{user}/generate-investments', 'generateInvestmentHistory')->name('users.generate-investments');
        Route::get('/kyc', 'kyc')->name('kyc');
        Route::get('/kyc/download/{document}', 'downloadKycDoc')->name('kyc.download');
        Route::post('/kyc/{submission}/approve', 'approveKyc')->name('kyc.approve');
        Route::post('/kyc/{submission}/reject', 'rejectKyc')->name('kyc.reject');
        Route::get('/deposits', 'deposits')->name('deposits');
        Route::post('/deposits/{deposit}/approve', 'approveDeposit')->name('deposits.approve');
        Route::post('/deposits/{deposit}/reject', 'rejectDeposit')->name('deposits.reject');
        Route::get('/withdrawals', 'withdrawals')->name('withdrawals');
        Route::post('/withdrawals/{withdrawal}/approve', 'approveWithdrawal')->name('withdrawals.approve');
        Route::post('/withdrawals/{withdrawal}/reject', 'rejectWithdrawal')->name('withdrawals.reject');
        Route::get('/plans', 'plans')->name('plans');
        Route::post('/plans', 'storePlan')->name('plans.store');
        Route::get('/cms', 'cms')->name('cms');
        Route::post('/cms/branding', 'updateBranding')->name('cms.branding');
        Route::post('/cms/settings', 'updateSettings')->name('cms.settings');
        Route::get('/support', 'support')->name('support');
        Route::post('/support/{ticket}/reply', 'replySupport')->name('support.reply');
        Route::get('/audit-logs', 'auditLogs')->name('audit-logs');
    });

    // Admin Currency Controls
    Route::controller(AdminCurrencyController::class)->prefix('currencies')->name('currencies.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/{currency}', 'updateCurrency')->name('update');
        Route::post('/change-request/{changeRequest}/approve', 'approveChangeRequest')->name('approve-change');
        Route::post('/change-request/{changeRequest}/reject', 'rejectChangeRequest')->name('reject-change');
    });

    // Admin Payment Methods Management
    Route::controller(AdminPaymentMethodController::class)->prefix('payment-methods')->name('payment-methods.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/deposit', 'storeDepositMethod')->name('deposit.store');
        Route::post('/deposit/{method}/toggle', 'toggleDepositMethod')->name('deposit.toggle');
        Route::post('/withdrawal', 'storeWithdrawalMethod')->name('withdrawal.store');
        Route::post('/withdrawal/{method}/toggle', 'toggleWithdrawalMethod')->name('withdrawal.toggle');
    });
});
