<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyBankAccount extends Model
{
    protected $fillable = [
        'slug',
        'label',
        'company_id',
        'company_display_name',
        'voen',
        'hh',
        'mh',
        'bank_name',
        'bank_kod',
        'bank_voen',
        'swift',
        'who',
        'who_footer',
        'representer',
        'stamp',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
