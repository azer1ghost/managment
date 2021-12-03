<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Calendar extends Model
{
    use SoftDeletes;

    protected $table = 'calendar';

    protected $fillable = ['name', 'type', 'start_at', 'end_at', 'user_id', 'is_day_off', 'is_repeatable', 'is_private'];

    protected $casts = [
        'start_at' => 'date',
        'end_at' => 'date',
        'is_day_off' => 'boolean',
        'is_repeatable' => 'boolean',
        'is_private' => 'boolean',
    ];

    public static function eventTypes(): array
    {
        return [
            1 => [
                'name' => trans('translates.calendar.types.1'),
                'textColor' => '#fff',
                'backgroundColor' => '#DC3545',
            ],

            [
                'name' => trans('translates.calendar.types.2'),
                'textColor' => '#fff',
                'backgroundColor' => '#28A745',
            ],

            [
                'name' => trans('translates.calendar.types.3'),
                'textColor' => '#fff',
                'backgroundColor' => '#FFC107',
            ],

            [
                'name' => trans('translates.calendar.types.4'),
                'textColor' => '#fff',
                'backgroundColor' => '#054468',
            ],

            [
                'name' => trans('translates.calendar.types.5'),
                'textColor' => '#fff',
                'backgroundColor' => '#17A2B8',
            ],
        ];
    }

    public function scopeIsNotDayOff($query)
    {
        return $query->where('is_day_off', 0);
    }

    public function scopeIsPublic($query)
    {
        return $query->where('is_private', 0);
    }

    public function scopeCurrentYear($query)
    {
        return $query->whereYear('start_at', now()->format('Y'));
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
