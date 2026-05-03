<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveEntitlement extends Model
{
    protected $fillable = ['user_id', 'year', 'total_days', 'extra_days'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function usedDays(int $year): int
    {
        return AttendanceRecord::where('user_id', $this->user_id)
            ->where('status', 'Ə.M')
            ->whereYear('date', $year)
            ->count();
    }

    public function remainingDays(int $year): int
    {
        return $this->total_days + $this->extra_days - $this->usedDays($year);
    }
}
