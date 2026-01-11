<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;

class BirbankCredential extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'env',
        'username',
        'password',
        'access_token',
        'refresh_token',
        'auth_type',
        'token_expires_at',
        'last_login_at',
    ];

    protected $casts = [
        'token_expires_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    protected $hidden = [
        'password',
        'access_token',
        'refresh_token',
    ];

    /**
     * Get the company that owns the credential.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Encrypt password before saving.
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = $value ? Crypt::encryptString($value) : null;
    }

    /**
     * Decrypt password when retrieving.
     */
    public function getPasswordAttribute($value)
    {
        if (empty($value)) {
            return null;
        }
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            // If decryption fails, return as-is (might be plain text or corrupted)
            return $value;
        }
    }

    /**
     * Encrypt access_token before saving.
     */
    public function setAccessTokenAttribute($value)
    {
        $this->attributes['access_token'] = $value ? Crypt::encryptString($value) : null;
    }

    /**
     * Decrypt access_token when retrieving.
     */
    public function getAccessTokenAttribute($value)
    {
        if (empty($value)) {
            return null;
        }
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            // If decryption fails, return as-is (might be plain text or corrupted)
            return $value;
        }
    }

    /**
     * Encrypt refresh_token before saving.
     */
    public function setRefreshTokenAttribute($value)
    {
        $this->attributes['refresh_token'] = $value ? Crypt::encryptString($value) : null;
    }

    /**
     * Decrypt refresh_token when retrieving.
     */
    public function getRefreshTokenAttribute($value)
    {
        if (empty($value)) {
            return null;
        }
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            // If decryption fails, return as-is (might be plain text or corrupted)
            return $value;
        }
    }

    /**
     * Check if token is expired or will expire soon (within 5 minutes).
     */
    public function isTokenExpired(): bool
    {
        if (!$this->token_expires_at) {
            return false; // If no expiry, assume not expired
        }

        // Check if expired or expires within 5 minutes
        $expiryWithBuffer = $this->token_expires_at->copy()->subMinutes(5);
        return $expiryWithBuffer->isPast();
    }

    /**
     * Check if token exists and is valid.
     */
    public function hasValidToken(): bool
    {
        return !empty($this->access_token) && !$this->isTokenExpired();
    }
}
