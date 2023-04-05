<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesClient extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'user_id', 'voen', 'phone', 'detail', 'email'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function setPhoneAttribute($value): ?string
    {
        return $this->attributes['phone'] = phone_cleaner($value);
    }

    public function getPhoneAttribute($value): ?string
    {
        return phone_formatter($value, true);
    }

    public function getNameWithVoenAttribute($value): ?string
    {
        return "{$this->getAttribute('name')} ({$this->getAttribute('voen')})";
    }
    public function getNameWithPhoneAttribute($value): ?string
    {
        return "{$this->getAttribute('name')} ({$this->getAttribute('phone')})";
    }
    public function getNameWithUserAttribute($value): ?string
    {
        return "{$this->getAttribute('name')} ({$this->getRelationValue('user')->getFullnameAttribute()}) ({$this->getAttribute('phone')})";
    }
}
