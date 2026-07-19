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
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_banned' => 'boolean'
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
}
