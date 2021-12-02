<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    protected $table = 'calendar';

    protected $fillable = ['name', 'type', 'start_at', 'end_at', 'is_day_off', 'is_repeatable'];

    protected $casts = ['start_at' => 'date', 'end_at' => 'date', 'is_day_off' => 'boolean', 'is_repeatable' => 'boolean'];

    public static function types(): array
    {
        return [
            1 => [
                'name' => 'Birthday',
                'textColor' => '#fff',
                'backgroundColor' => '#054468',
            ],

            [
                'name' => 'Holiday',
                'textColor' => '#fff',
                'backgroundColor' => '#28A745',
            ],

            [
                'name' => 'Other',
                'textColor' => '#fff',
                'backgroundColor' => '#17A2B8',
            ],
        ];
    }

    public function scopeIsNotDayOff($query)
    {
        return $query->where('is_day_off', 0);
    }

    public function scopeIsDayOff($query)
    {
        return $query->where('is_day_off', 1);
    }

    public function isRepeatable()
    {
        return $this->getAttribute('is_repeatable');
    }
}
