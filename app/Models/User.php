<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable; 

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'google_id',
        'avatar',
        'phone',
        'password',
        'role',
        'address',
        'is_banned',
        'email_verified',
        'otp_code', 
        'otp_expires_at',
         'otp_attempts',
        'two_factor_secret',
         'two_factor_enabled',
        'two_factor_confirmed_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'otp_code',
        'two_factor_secret',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at'         => 'datetime',
            'password'                  => 'hashed',
            'is_banned'                 => 'boolean',
            'otp_expires_at'          => 'datetime',
            'otp_attempts'            => 'integer',
            'two_factor_enabled'      => 'boolean',
            'two_factor_confirmed_at' => 'datetime',
            
        ];
    }

    // Admin কিনা চেক
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    //গন্তব্য নির্ধারণ
    public function destination()
    {
        return $this->isAdmin()
            ? route('admin.dashboard', [], false)
            : route('shop.index', [], false);
    }

    // Orders relationship
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    //wishlist
    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function wishedProductIds(): array
    {
        return $this->wishlist()->pluck('product_id')->toArray();
    }

    public function hasPassword(): bool
    {
        return !is_null($this->password);
    }

    // ── Email OTP Methods ─────────────────────────────

    public function generateOtp(): string
    {
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $this->update([
            'otp_code'       => bcrypt($otp),
            'otp_expires_at' => now()->addMinutes(10),
            'otp_attempts'   => 0,
        ]);

        return $otp;
    }

    public function verifyOtp(string $otp): bool
    {
        if (!$this->otp_expires_at || $this->otp_expires_at->isPast()) {
            return false;
        }

        if ($this->otp_attempts >= 5) {
            return false;
        }

        $this->increment('otp_attempts');

        if (!\Hash::check($otp, $this->otp_code)) {
            return false;
        }

        $this->update([
            'otp_code'       => null,
            'otp_expires_at' => null,
            'otp_attempts'   => 0,
        ]);

        return true;
    }

    // ── Google Authenticator Methods ──────────────────

    public function hasTwoFactorEnabled(): bool
    {
        return $this->two_factor_enabled
            && !is_null($this->two_factor_secret)
            && !is_null($this->two_factor_confirmed_at);
    }
}
