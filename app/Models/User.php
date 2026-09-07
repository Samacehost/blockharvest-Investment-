<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'transaction_pin',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_enabled' => 'boolean',
            'pin_set_at' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->uuid)) {
                $user->uuid = (string) Str::uuid();
            }
            if (empty($user->name)) {
                $user->name = trim("{$user->first_name} {$user->last_name}");
            }
            if (empty($user->referral_code)) {
                $user->referral_code = 'BH-' . strtoupper(Str::random(7));
            }
        });
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by_id');
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by_id');
    }

    public function referralCommissions()
    {
        return $this->hasMany(ReferralCommission::class, 'referrer_id');
    }

    public function getReferralLinkAttribute(): string
    {
        if (empty($this->referral_code)) {
            $this->referral_code = 'BH-' . strtoupper(Str::random(7));
            $this->saveQuietly();
        }
        return route('register', ['ref' => $this->referral_code]);
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function investments()
    {
        return $this->hasMany(Investment::class);
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }

    public function kycSubmissions()
    {
        return $this->hasMany(KycSubmission::class);
    }

    public function latestKycSubmission()
    {
        return $this->hasOne(KycSubmission::class)->latestOfMany();
    }

    public function supportTickets()
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function ledgerEntries()
    {
        return $this->hasMany(LedgerEntry::class);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['super_admin', 'finance_manager', 'kyc_officer', 'support_agent', 'content_manager', 'auditor']);
    }

    public function hasTransactionPin(): bool
    {
        return !empty($this->transaction_pin);
    }

    public function verifyTransactionPin(?string $pin): bool
    {
        return $this->hasTransactionPin() && !empty($pin) && \Illuminate\Support\Facades\Hash::check((string)$pin, $this->transaction_pin);
    }

    public function getCurrencySymbolAttribute(): string
    {
        return match ($this->currency_code) {
            'GBP' => '£',
            'CAD' => 'C$',
            default => '$',
        };
    }
}
