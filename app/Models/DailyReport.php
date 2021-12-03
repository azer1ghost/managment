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

        $week_array[] = $first_day;

        for($i = 0; $i < 5; $i++) {
            $week_array[] = $week_day->addDay()->copy();
        }

        $sunday = $week_day->addDay()->copy();
        foreach (Calendar::currentYear()->get(['start_at', 'end_at', 'is_day_off']) as $dates){
            switch ($dates->getAttribute('is_day_off')){
                case 0:
                    for ($date = $dates->getAttribute('start_at'); $date < $dates->getAttribute('end_at'); $date->addDay()){
                        if($date == $sunday){
                            $week_array[] = $sunday;
                        }
                    }
                    break;
                case 1:
                    for ($date = $dates->getAttribute('start_at'); $date < $dates->getAttribute('end_at'); $date->addDay()){
                        if(($key = array_search($date, $week_array)) !== false){
                            unset($week_array[$key]);
                        }
                    }
                    break;
            }
        }

        return $week_array;
    }
}
