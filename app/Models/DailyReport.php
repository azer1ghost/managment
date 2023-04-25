<?php

namespace App\Models;

use Altek\Accountant\Contracts\Recordable;
use Altek\Eventually\Eventually;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyReport extends Model implements Recordable
{
    use SoftDeletes, \Altek\Accountant\Recordable, Eventually;

    protected $table = 'daily_reports';

    protected $fillable = ['detail', 'date'];

    const TIME_LIMIT = 17;

//    public function getRouteKeyName()
//    {
//        return 'date';
//    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Report::class, 'report_id')->withDefault();
    }

    public static function currentWeek()
    {
        $start_of_week = now()->startOfWeek();

        $days_of_week[] = $start_of_week->copy(); // use carbon copy to avoid affecting the original $start_of_week variable

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
    protected static function thursday()
    {
        $startOfMonth = now()->startOfMonth(); // Bu ayın ilk gününü alır
        $endOfMonth = now()->endOfMonth(); // Bu ayın son gününü alır

        $wednesdays = [];

// Bu ayın tüm günlerini kontrol eder
        for ($date = $startOfMonth; $date->lte($endOfMonth); $date->addDay()) {
            // Eğer gün çarşamba ise diziye ekler
            if ($date->isWednesday()) {
                $wednesdays[] = $date->copy();
            }
        }

        return $wednesdays;
    }

    public static function currentMonth()
    {
        $start_of_year = now()->startOfYear();

        $days_of_month[] = $start_of_year->copy(); // use carbon copy to avoid affecting the original $start_of_week variable

        $day_offs = []; // array of day offs
        $working_days = []; // array of working days

        // Check if the given working day is in the day off range or if the weekend is in the working day range
        foreach (Calendar::currentMonth()->get(['start_at', 'end_at', 'is_day_off']) as $daterange){
            switch ($daterange->getAttribute('is_day_off')){
                case 0:
                    self::checkDay($daterange, function ($date) use (&$working_days){
                        if($date->format('N') == 7){
                            $working_days[] = $date->copy();
                        }
                    });
                    break;
                case 1:
                    self::checkDay($daterange, function ($date) use (&$day_offs){
                        $day_offs[] = $date->copy();
                    });
                    break;
            }
        }

        if ($days_of_month[0]->format('N') == 7 && !in_array($days_of_month[0], $working_days)) {
            unset($days_of_month[0]);
        }else if ($days_of_month[0]->format('N') != 7 && in_array($days_of_month[0], $day_offs)) {
            unset($days_of_month[0]);
        }

        for($i = 0; $i < 365; $i++) {
            $date_of_week =  $start_of_year->addDay()->copy();

            if ($date_of_week->format('N') == 7 && !in_array($date_of_week, $working_days)) continue;
            if ($date_of_week->format('N') != 7 && in_array($date_of_week, $day_offs)) continue;

            $days_of_month[] = $date_of_week;
        }

        return array_values($days_of_month);
    }

    // function to loop over date ranges from the calendar
    private static function checkDay($daterange, $callback)
    {
        for ($date = $daterange->getAttribute('start_at'); $date < $daterange->getAttribute('end_at'); $date->addDay()){
            $callback($date);
        }
    }
}
