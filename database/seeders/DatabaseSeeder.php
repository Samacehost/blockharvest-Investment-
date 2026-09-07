<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Setting;
use App\Models\BrandingSetting;
use App\Models\Country;
use App\Models\Currency;
use App\Models\InvestmentPlan;
use App\Models\DepositMethod;
use App\Models\WithdrawalMethod;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\Faq;
use App\Models\Testimonial;
use App\Models\SupportCategory;
use App\Services\LedgerService;
use App\Services\InvestmentEngineService;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Branding Settings
        BrandingSetting::create([
            'primary_color' => '#4F46E5',
            'secondary_color' => '#0EA5E9',
            'accent_color' => '#10B981',
            'button_radius' => '0.5rem',
            'font_family' => 'Inter',
            'light_logo' => '/assets/images/logo-light.svg',
            'dark_logo' => '/assets/images/logo-dark.svg',
            'favicon' => '/assets/images/favicon.svg',
        ]);

        // 2. Dynamic Business & Site Settings
        $settings = [
            ['key' => 'site_name', 'value' => 'BlockHarvest', 'group_name' => 'general'],
            ['key' => 'company_full_name', 'value' => 'BlockHarvest Global Asset Management Ltd', 'group_name' => 'general'],
            ['key' => 'company_tagline', 'value' => 'Automated high-yield portfolio strategies engineered for consistent daily returns.', 'group_name' => 'general'],
            ['key' => 'hero_headline', 'value' => 'Grow Your Wealth with Next-Gen Yield Harvesting', 'group_name' => 'homepage'],
            ['key' => 'hero_subheadline', 'value' => 'Turn your capital into consistent daily returns with automated portfolio strategies. Enjoy high-yielding investment tiers, seamless multi-currency payouts in USD ($), GBP (£), and CAD (C$), and total asset security.', 'group_name' => 'homepage'],
            ['key' => 'support_email', 'value' => 'support@blockharvest.top', 'group_name' => 'contact'],
            ['key' => 'phone_number', 'value' => '+1 (888) 492-9102', 'group_name' => 'contact'],
            ['key' => 'office_address', 'value' => '100 Wall Street, 24th Floor, New York, NY 10005', 'group_name' => 'contact'],
            ['key' => 'default_currency', 'value' => 'USD', 'group_name' => 'general'],
            ['key' => 'kyc_required_for_investment', 'value' => '1', 'group_name' => 'security'],
            ['key' => 'risk_warning_text', 'value' => 'Investment in digital assets and managed portfolios carries risk. Past performance does not guarantee future results. Projections shown are illustrative estimates based on algorithm performance.', 'group_name' => 'legal'],
            ['key' => 'footer_copyright', 'value' => '© ' . date('Y') . ' BlockHarvest Global Asset Management Ltd. All Rights Reserved.', 'group_name' => 'legal'],
        ];

        foreach ($settings as $s) {
            Setting::create($s);
        }

        // 3. Countries
        $countries = [
            ['code' => 'US', 'name' => 'United States', 'phone_code' => '+1'],
            ['code' => 'GB', 'name' => 'United Kingdom', 'phone_code' => '+44'],
            ['code' => 'CA', 'name' => 'Canada', 'phone_code' => '+1'],
            ['code' => 'AU', 'name' => 'Australia', 'phone_code' => '+61'],
            ['code' => 'DE', 'name' => 'Germany', 'phone_code' => '+49'],
            ['code' => 'JP', 'name' => 'Japan', 'phone_code' => '+81'],
            ['code' => 'SG', 'name' => 'Singapore', 'phone_code' => '+65'],
        ];

        foreach ($countries as $c) {
            Country::create($c);
        }

        // 4. Currencies (USD Base, GBP, CAD)
        Currency::create([
            'code' => 'USD',
            'name' => 'United States Dollar',
            'symbol' => '$',
            'country_code' => 'US',
            'flag' => '🇺🇸',
            'exchange_rate_to_default' => 1.000000,
            'is_default' => true,
            'is_active' => true,
            'min_deposit' => 50.0000,
            'max_deposit' => 100000.0000,
            'min_withdrawal' => 20.0000,
            'max_withdrawal' => 50000.0000,
            'display_position' => 1,
        ]);

        Currency::create([
            'code' => 'GBP',
            'name' => 'British Pound Sterling',
            'symbol' => '£',
            'country_code' => 'GB',
            'flag' => '🇬🇧',
            'exchange_rate_to_default' => 0.790000,
            'is_default' => false,
            'is_active' => true,
            'min_deposit' => 40.0000,
            'max_deposit' => 80000.0000,
            'min_withdrawal' => 15.0000,
            'max_withdrawal' => 40000.0000,
            'display_position' => 2,
        ]);

        Currency::create([
            'code' => 'CAD',
            'name' => 'Canadian Dollar',
            'symbol' => 'C$',
            'country_code' => 'CA',
            'flag' => '🇨🇦',
            'exchange_rate_to_default' => 1.360000,
            'is_default' => false,
            'is_active' => true,
            'min_deposit' => 65.0000,
            'max_deposit' => 135000.0000,
            'min_withdrawal' => 25.0000,
            'max_withdrawal' => 65000.0000,
            'display_position' => 3,
        ]);

        // 5. Create Super Admin & Standard Investor Accounts
        $admin = User::create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'admin@blockharvest.top',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
            'kyc_status' => 'approved',
            'email_verified_at' => now(),
        ]);

        $investor = User::create([
            'first_name' => 'Sarah',
            'last_name' => 'Jenkins',
            'email' => 'investor@blockharvest.top',
            'phone' => '+15551234567',
            'password' => Hash::make('password123'),
            'role' => 'investor',
            'kyc_status' => 'approved',
            'email_verified_at' => now(),
        ]);

        // 6. Investment Plans
        $planStarter = InvestmentPlan::create([
            'name' => 'Starter Yield Fund',
            'slug' => 'starter-yield-fund',
            'short_description' => 'Ideal for new investors seeking steady, low-risk daily returns.',
            'description' => 'The Starter Yield Fund allocates capital across high-grade liquid money market assets and delta-neutral yield strategies.',
            'min_amount' => 100.0000,
            'max_amount' => 4999.0000,
            'currency_code' => 'USD',
            'duration_value' => 30,
            'duration_unit' => 'days',
            'roi_rate' => 1.2000, // 1.2% Daily
            'roi_frequency' => 'daily',
            'calculation_type' => 'simple',
            'capital_return' => true,
            'featured' => false,
            'display_order' => 1,
            'is_active' => true,
        ]);

        $planGrowth = InvestmentPlan::create([
            'name' => 'Growth Alpha Fund',
            'slug' => 'growth-alpha-fund',
            'short_description' => 'Balanced portfolio for medium-term capital expansion and daily payouts.',
            'description' => 'Deploys algorithmic market-making and automated liquidity provision across decentralized exchanges.',
            'min_amount' => 5000.0000,
            'max_amount' => 24999.0000,
            'currency_code' => 'USD',
            'duration_value' => 60,
            'duration_unit' => 'days',
            'roi_rate' => 1.8000, // 1.8% Daily
            'roi_frequency' => 'daily',
            'calculation_type' => 'simple',
            'capital_return' => true,
            'featured' => true,
            'popular_badge' => 'MOST POPULAR',
            'display_order' => 2,
            'is_active' => true,
        ]);

        $planInstitutional = InvestmentPlan::create([
            'name' => 'Institutional Compound Tier',
            'slug' => 'institutional-compound-tier',
            'short_description' => 'High-yield compounding structure designed for institutional and accredited wealth.',
            'description' => 'Maximizes yield velocity through daily compound reinvestment into arbitrage strategies.',
            'min_amount' => 25000.0000,
            'max_amount' => 500000.0000,
            'currency_code' => 'USD',
            'duration_value' => 90,
            'duration_unit' => 'days',
            'roi_rate' => 2.4000, // 2.4% Compound Daily
            'roi_frequency' => 'daily',
            'calculation_type' => 'compound',
            'capital_return' => true,
            'featured' => false,
            'display_order' => 3,
            'is_active' => true,
        ]);

        // 7. Deposit & Withdrawal Methods (Currency Scoped: USD, GBP, CAD, ALL)
        DepositMethod::create([
            'name' => 'Bank Wire Transfer (JPMorgan Chase - USD)',
            'code' => 'bank_wire_usd',
            'type' => 'manual',
            'currency_code' => 'USD',
            'min_amount' => 500.0000,
            'max_amount' => 100000.0000,
            'bank_details_json' => [
                'bank_name' => 'JPMorgan Chase Bank, N.A.',
                'account_name' => 'BlockHarvest Asset Mgmt Escrow (USD)',
                'account_number' => '9876543210',
                'routing_number' => '021000021',
                'swift_code' => 'CHASUS33',
                'bank_address' => '270 Park Avenue, New York, NY',
            ],
            'instructions' => 'Wire transfer in USD ($). Include your User Reference in memo.',
        ]);

        DepositMethod::create([
            'name' => 'UK Faster Payments / Wire (Barclays - GBP)',
            'code' => 'bank_wire_gbp',
            'type' => 'manual',
            'currency_code' => 'GBP',
            'min_amount' => 400.0000,
            'max_amount' => 80000.0000,
            'bank_details_json' => [
                'bank_name' => 'Barclays Bank UK PLC',
                'account_name' => 'BlockHarvest UK Treasury Ltd (GBP)',
                'account_number' => '40918273',
                'sort_code' => '20-00-00',
                'swift_code' => 'BARCGB22',
                'bank_address' => '1 Churchill Place, London, E14 5HP',
            ],
            'instructions' => 'Bank transfer in GBP (£). Include your User Reference in memo.',
        ]);

        DepositMethod::create([
            'name' => 'Interac & Wire Transfer (Royal Bank of Canada - CAD)',
            'code' => 'bank_wire_cad',
            'type' => 'manual',
            'currency_code' => 'CAD',
            'min_amount' => 600.0000,
            'max_amount' => 120000.0000,
            'bank_details_json' => [
                'bank_name' => 'Royal Bank of Canada (RBC)',
                'account_name' => 'BlockHarvest Canada Mgmt (CAD)',
                'account_number' => '003928174',
                'transit_number' => '00012',
                'swift_code' => 'ROYCCAT2',
                'bank_address' => '200 Bay Street, Toronto, ON, M5J 2J5',
            ],
            'instructions' => 'Transfer in CAD (C$). Include your User Reference in memo.',
        ]);

        DepositMethod::create([
            'name' => 'USDT (TRC20) Crypto (All Currencies)',
            'code' => 'usdt_trc20',
            'type' => 'manual',
            'currency_code' => 'ALL',
            'min_amount' => 50.0000,
            'max_amount' => 250000.0000,
            'crypto_address_json' => [
                'network' => 'TRON (TRC20)',
                'address' => 'TYD74mKqZ2N1xL8R5vS9wP3jB8fC6mQ4uE',
            ],
            'instructions' => 'Send USDT via TRON network. Available for all currency accounts.',
        ]);

        // Withdrawal Methods
        WithdrawalMethod::create([
            'name' => 'US Bank Wire Payout (USD)',
            'code' => 'bank_wire_payout_usd',
            'currency_code' => 'USD',
            'min_amount' => 100.0000,
            'max_amount' => 50000.0000,
            'fee_flat' => 15.0000,
        ]);

        WithdrawalMethod::create([
            'name' => 'UK Bank Wire Payout (GBP)',
            'code' => 'bank_wire_payout_gbp',
            'currency_code' => 'GBP',
            'min_amount' => 80.0000,
            'max_amount' => 40000.0000,
            'fee_flat' => 10.0000,
        ]);

        WithdrawalMethod::create([
            'name' => 'Canada Bank Wire Payout (CAD)',
            'code' => 'bank_wire_payout_cad',
            'currency_code' => 'CAD',
            'min_amount' => 120.0000,
            'max_amount' => 60000.0000,
            'fee_flat' => 18.0000,
        ]);

        WithdrawalMethod::create([
            'name' => 'USDT (TRC20) Payout (All Currencies)',
            'code' => 'usdt_trc20_payout',
            'currency_code' => 'ALL',
            'min_amount' => 50.0000,
            'max_amount' => 100000.0000,
            'fee_flat' => 2.0000,
        ]);

        // 8. Seed Initial Funds & Investment for Sample Investor
        LedgerService::recordTransaction(
            user: $investor,
            type: 'DEPOSIT_CREDIT',
            direction: 'CREDIT',
            amount: 10000.0000,
            currencyCode: 'USD',
            userNotes: 'Initial Approved Wire Deposit #DEP-9082'
        );

        InvestmentEngineService::createInvestment(
            user: $investor,
            plan: $planGrowth,
            amount: 5000.0000
        );

        // 9. CMS Content (FAQs, Testimonials, Support Categories)
        // FAQs
        Faq::create([
            'category' => 'General',
            'question' => 'How does BlockHarvest generate investment returns?',
            'answer' => 'BlockHarvest employs proprietary algorithmic yield harvesting, delta-neutral arbitrage, automated market-making, and money-market liquidity management to generate steady daily yields.',
            'display_order' => 1,
        ]);

        Faq::create([
            'category' => 'Deposits & Withdrawals',
            'question' => 'How quickly are withdrawal requests processed?',
            'answer' => 'Withdrawal requests undergo automated risk & KYC verification and are processed by our finance desk within 1 to 12 business hours.',
            'display_order' => 2,
        ]);

        Faq::create([
            'category' => 'Multi-Currency & Accounts',
            'question' => 'Which currencies are supported on the platform?',
            'answer' => 'BlockHarvest natively supports United States Dollar (USD - $), British Pound Sterling (GBP - £), and Canadian Dollar (CAD - C$). All internal ledger transactions use server-validated exchange rates.',
            'display_order' => 3,
        ]);

        Faq::create([
            'category' => 'Investment Plans',
            'question' => 'Is my principal capital returned at investment maturity?',
            'answer' => 'Yes! For plans with the Capital Return policy enabled, 100% of your initial principal is credited back to your available balance upon plan maturity alongside your accrued earnings.',
            'display_order' => 4,
        ]);

        Faq::create([
            'category' => 'Security & KYC',
            'question' => 'How are my identity verification (KYC) documents protected?',
            'answer' => 'All KYC documents are stored in non-public encrypted storage vaults and accessed exclusively through 15-minute temporary signed authorization URLs by compliance officers.',
            'display_order' => 5,
        ]);

        Faq::create([
            'category' => 'Investment Plans',
            'question' => 'What is the difference between Simple and Compound interest plans?',
            'answer' => 'Simple interest plans calculate payouts purely on the initial principal amount. Compound plans automatically reinvest daily earnings to accelerate yield velocity over time.',
            'display_order' => 6,
        ]);

        Faq::create([
            'category' => 'Multi-Currency & Accounts',
            'question' => 'Can I change my registered account currency?',
            'answer' => 'You may request an account currency change provided you have zero active investments, zero pending deposits/withdrawals, and zero available wallet balance. Contact support to request a currency adjustment.',
            'display_order' => 7,
        ]);

        Faq::create([
            'category' => 'Deposits & Withdrawals',
            'question' => 'What payment methods are supported for funding?',
            'answer' => 'You can fund your account using International Bank Wire Transfers or USDT (TRC20 network). Additional gateway options are configurable by the super administrator.',
            'display_order' => 8,
        ]);

        // Testimonials
        Testimonial::create([
            'author_name' => 'David Sterling',
            'author_role' => 'Managing Partner, Sterling Capital (USA)',
            'content' => 'BlockHarvest has revolutionized how we deploy idle corporate cash reserves into daily liquid yield strategies with total transparency and double-entry auditing.',
            'rating' => 5,
            'display_order' => 1,
        ]);

        Testimonial::create([
            'author_name' => 'Marcus Vance',
            'author_role' => 'Private Wealth Investor (London, UK)',
            'content' => 'The ability to manage my portfolio directly in GBP while benefiting from automated compounding yields has made BlockHarvest an essential wealth management asset.',
            'rating' => 5,
            'display_order' => 2,
        ]);

        Testimonial::create([
            'author_name' => 'Sophie Tremblay',
            'author_role' => 'Family Office Executive (Toronto, Canada)',
            'content' => 'The platform’s double-entry immutable ledger and private KYC document vault give our compliance committee total peace of mind.',
            'rating' => 5,
            'display_order' => 3,
        ]);

        Testimonial::create([
            'author_name' => 'Elena Rostova',
            'author_role' => 'Senior Asset Strategist (Zurich)',
            'content' => 'Accurate daily payouts, server-validated ROI projections, and prompt customer support make BlockHarvest a benchmark institutional platform.',
            'rating' => 5,
            'display_order' => 4,
        ]);

        Testimonial::create([
            'author_name' => 'James K. Chen',
            'author_role' => 'Managing Director, Apex Quantum (Singapore)',
            'content' => 'Transparent investment plans without fake guaranteed claims. Everything is backed by true database records and audit logs.',
            'rating' => 5,
            'display_order' => 5,
        ]);

        SupportCategory::create(['name' => 'Deposit & Withdrawal Assistance']);
        SupportCategory::create(['name' => 'Investment Plan Query']);
        SupportCategory::create(['name' => 'Account Security & KYC']);
        SupportCategory::create(['name' => 'Multi-Currency & Exchange Rates']);
    }
}
