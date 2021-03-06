<?php

namespace App\Models;

use Altek\Accountant\Contracts\Recordable;
use Altek\Eventually\Eventually;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Calendar extends Model implements Recordable
{
    use SoftDeletes, \Altek\Accountant\Recordable, Eventually;

    protected $table = 'calendar';

    protected $fillable = ['name', 'type', 'start_at', 'end_at', 'user_id', 'is_day_off', 'is_repeatable', 'is_private'];

    protected $casts = [
        'is_day_off' => 'boolean',
        'is_repeatable' => 'boolean',
        'is_private' => 'boolean',
    ];

    protected $dates = ['start_at', 'end_at'];

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

    public function scopeCurrentMonth($query)
    {
        return $query->whereMonth('start_at', now()->format('m'));
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
