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

        $first_day = $week_day->copy(); // use carbon copy to avoid affecting the original $week_day variable

        $week_array = [];

//        if(!Calendar::isDayOff()->get(['date'])->contains('date', $first_day)){
            $week_array = [$first_day];
//        }

        for($i = 0; $i < 5; $i++) {
            $date = $week_day->addDay()->copy();
//            if(!Calendar::isDayOff()->get(['date'])->contains('date', $date)){
                $week_array[] = $date;
//            }
        }

        $sunday = $week_day->addDay()->copy();

//        if(Calendar::isNotDayOff()->get(['date'])->contains('date', $sunday)){
            $week_array[] = $sunday;
//        }

        return $week_array;
    }
}
