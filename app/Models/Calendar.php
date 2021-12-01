<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    protected $table = 'calendar';

    protected $casts = ['date' => 'date', 'is_day_off' => 'boolean'];

    public function scopeIsNotDayOff($query)
    {
        return $query->where('is_day_off', 0);
    }

    public function scopeIsDayOff($query)
    {
        return $query->where('is_day_off', 1);
    }
}
