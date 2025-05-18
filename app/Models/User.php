<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\UserAddress;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
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
        'phone',
        'password',
        'role',
        'market_points',
        'location',

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
        ];
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function allergies()
    {
        return $this->belongsToMany(Allergy::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class,'user_id');
    }
    public function cart()
    {
        return $this->hasMany(Cart::class); // Adjust as per your cart model
    }
    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }
    public function addresses()
    {
        return $this->hasMany(UserAddress::class);
    }

// Add this method
public function isDelivery()
{
    return $this->role === 'delivery';
}

// Optional helper methods
public function isAdmin()
{
    return $this->role === 'admin';
}

public function isUser()
{
    return $this->role === 'user' || ($this->role !== 'admin' && $this->role !== 'delivery');
}
}
