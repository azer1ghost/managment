<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Account extends Model
{
    protected $fillable = ['company_id', 'customCompany', 'name', 'amount', 'currency', 'ordering'];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class)->withDefault();
    }
}
