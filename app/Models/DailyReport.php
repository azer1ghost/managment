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
        $start_of_week = now()->startOfWeek();

        $days_of_week[] = $start_of_week->copy(); // use carbon copy to avoid affecting the original $week_day variable

        for($i = 0; $i < 5; $i++) {
            $days_of_week[] = $start_of_week->addDay()->copy();
        }

        $sunday = $start_of_week->addDay()->copy(); // save sunday date as it's referenced multiple times

        // Check if the given working day is in the day off range or if the weekend is in the working day range
        foreach (Calendar::currentYear()->get(['start_at', 'end_at', 'is_day_off']) as $daterange){
            switch ($daterange->getAttribute('is_day_off')){
                case 0:
                    self::checkDay($daterange, function ($date) use (&$days_of_week, $sunday){
                        if($date == $sunday){
                            $days_of_week[] = $sunday;
                        }
                    });
                    break;
                case 1:
                    self::checkDay($daterange, function ($date) use (&$days_of_week){
                        if(($key = array_search($date, $days_of_week)) !== false){
                            unset($days_of_week[$key]);
                        }
                    });
                    break;
            }
        }

        return $days_of_week;
    }

    // function to loop over date ranges from the calendar
    private static function checkDay($daterange, $callback)
    {
        for ($date = $daterange->getAttribute('start_at'); $date < $daterange->getAttribute('end_at'); $date->addDay()){
            $callback($date);
        }
    }
}