<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Referral extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'key',
        'referral_bonus_percentage',
        'total_users',
        'bonus',
        'efficiency',
        'total_earnings',
        'referral_bonus_percentage'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model){
            $model->referral_bonus_percentage = config('default.referral_bonus_percentage');
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isNew(): bool
    {
        return !is_null($this->getAttribute('created_at')) &&
            $this->getAttribute('created_at') == $this->getAttribute('updated_at');
    }

    public function isReal(): bool
    {
        return !is_null($this->getAttribute('created_at'));
    }
}
