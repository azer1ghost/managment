<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'name',
        'surname',
        'father',
        'gender',
        'serial_pattern',
        'serial',
        'fin',
        'phone_coop',
        'phone',
        'phone',
        'company_id',
        'email_coop',
        'email',
        'address',
        'address_coop',
        'voen',
        'position'
    ];

    public function getFullnameAttribute(): string
    {
        return "{$this->getAttribute('name')} {$this->getAttribute('surname')}";
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(CustomerCompany::class);
    }
}