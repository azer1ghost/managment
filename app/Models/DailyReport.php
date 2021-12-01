<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyReport extends Model
{
    protected $table = 'daily_reports';

    protected $fillable = ['detail', 'date'];

    public function getRouteKeyName()
    {
        return 'date';
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Report::class, 'report_id')->withDefault();
    }

    public static function currentWeek()
    {
        $week_day = now()->startOfWeek();

        $week_array = [$week_day->copy()];

        for($i = 0; $i < 5; $i++) {
            $week_array[] = $week_day->addDay()->copy();
        }

        return $week_array;
    }
}
