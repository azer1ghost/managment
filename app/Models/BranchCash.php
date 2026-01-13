<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BranchCash extends Model
{
    protected $fillable = [
        'department_id',
        'date',
        'opening_balance',
        'operations_balance',
        'handover_amount',
        'closing_balance',
        'created_by',
    ];

    protected $dates = ['date'];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(BranchCashItem::class);
    }

    /**
     * Yenidən cəmləri hesabla.
     */
    public function recalculateTotals(): void
    {
        $income = (float) $this->items()
            ->where('direction', 'income')
            ->sum('amount');

        $expense = (float) $this->items()
            ->where('direction', 'expense')
            ->sum('amount');

        $this->operations_balance = $income - $expense;

        $opening = (float) ($this->opening_balance ?? 0);
        $handover = (float) ($this->handover_amount ?? 0);

        $this->closing_balance = $opening + $this->operations_balance - $handover;

        $this->save();
    }
}

