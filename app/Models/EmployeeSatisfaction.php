<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeSatisfaction extends Model
{
    protected $fillable = [
        'type',
        'user_id',
        'department_id',
        'employee',
        'activity',
        'content',
        'reason',
        'result',
        'is_enough',
        'more_time',
        'datetime',
        'deadline',
        'status',
        'effectivity'
    ];

    const OFFER = 1;
    const COMPLAINT = 2;
    const INCONSISTENCY = 3;

    public function users():BelongsTo
    {
       return $this->belongsTo(User::class,'user_id')->withDefault();
    }

    public function departments()
    {
       return $this->belongsTo(Department::class)->withDefault();
    }

    public static function types()
    {
        return [1 => 1, 2, 3];
    }
    public static function statuses()
    {
        return [1 => 1, 2];
    }
}
