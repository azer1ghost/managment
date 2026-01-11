<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BirbankTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'env',
        'account_ref',
        'transaction_uid',
        'direction',
        'amount',
        'currency',
        'booked_at',
        'description',
        'counterparty',
        'raw',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'booked_at' => 'datetime',
        'raw' => 'array',
    ];

    /**
     * Get the company that owns the transaction.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
