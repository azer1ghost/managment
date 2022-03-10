<?php

namespace App\Models;

use Altek\Accountant\Contracts\Recordable;
use Altek\Eventually\Eventually;
use Illuminate\Database\Eloquent\{Factories\HasFactory, Model, Relations\BelongsTo, SoftDeletes};

class Meeting extends Model implements Recordable
{
    use HasFactory, SoftDeletes, \Altek\Accountant\Recordable, Eventually;

    protected $fillable = ['name', 'department_id', 'will_start_at', 'will_end_at'];

    public $dates = ['datetime'];

    protected $casts = ['will_start_at' => 'datetime', 'will_end_at' => 'datetime'];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class)->withDefault();
    }


    protected static function boot()
    {
        parent::boot();

        self::creating(function (Meeting $meeting){

            if($meeting->isClean('will_start_at')){
                $meeting->setAttribute('will_start_at', now());
            }

            if($meeting->isClean('will_end_at')){
                $meeting->setAttribute('will_end_at', now()->addWeek());
            }
        });
    }
}
