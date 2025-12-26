<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class TransitCustomer extends Authenticatable
{
    use HasFactory, SoftDeletes, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'type',
        'country',
        'voen',
        'rekvisit',
        'balance',
        'verify_code',
        'phone_verified_at',
        'email_verified_at',
        'default_lang',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'verify_code',
    ];

    protected $casts = [
        'phone_verified_at' => 'datetime',
        'email_verified_at' => 'datetime',
        'balance' => 'decimal:2',
    ];

    /**
     * Set password attribute with hashing
     */
    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['password'] = Hash::make($value);
        }
    }

    /**
     * Get orders for this transit customer
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'transit_customer_id');
    }

    /**
     * Get transactions for this transit customer
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'transit_customer_id');
    }
}
