<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BranchCashItem extends Model
{
    protected $fillable = [
        'branch_cash_id',
        'work_id',
        'direction',
        'description',
        'gb',
        'representative',
        'sb',
        'price',
        'amount',
        'note',
    ];

    public function branchCash(): BelongsTo
    {
        return $this->belongsTo(BranchCash::class);
    }

    public function work(): BelongsTo
    {
        return $this->belongsTo(Work::class);
    }
}

