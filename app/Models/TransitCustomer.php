<?php

namespace App\Models;

use App\Traits\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class TransitCustomer extends Authenticatable
{
    use HasFactory, SoftDeletes, Notifiable, MustVerifyEmail;

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
        'telegram_chat_id',
        'telegram_link_code',
        'telegram_link_code_expires_at',
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

    /**
     * Hər müştəriyə bir kod: ya mövcud kodu qaytarır, ya yenisini yaradır (vaxtı bitmir).
     */
    public function getOrCreateTelegramLinkCode(): string
    {
        if (!$this->hasTelegramLinked() && !empty($this->telegram_link_code)) {
            return $this->telegram_link_code;
        }
        $code = (string) random_int(100000, 999999);
        $this->update([
            'telegram_link_code' => $code,
            'telegram_link_code_expires_at' => null,
        ]);
        return $code;
    }

    /**
     * Check if Telegram is linked
     */
    public function hasTelegramLinked(): bool
    {
        return !empty($this->telegram_chat_id);
    }
}
