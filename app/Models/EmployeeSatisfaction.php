<?php

namespace App\Models;

use Altek\Accountant\Contracts\Recordable;
use Altek\Eventually\Eventually;
use App\Interfaces\DocumentableInterface;
use App\Traits\Documentable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeSatisfaction extends Model implements DocumentableInterface, Recordable
{
    use Documentable, \Altek\Accountant\Recordable,  Eventually;

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
        'effectivity',
        'note',
    ];

    const OFFER = 1;
    const COMPLAINT = 2;
    const INCONSISTENCY = 3;


    public function users():BelongsTo
    {
       return $this->belongsTo(User::class,'user_id')->withDefault();
    }
    public function employees():BelongsTo
    {
       return $this->belongsTo(User::class,'employee')->withDefault();
    }

    public function departments()
    {
       return $this->belongsTo(Department::class,'department_id')->withDefault();
    }

    public static function types()
    {
        return [1 => 1, 2, 3];
    }
    public static function statuses()
    {
        return [1 => 1, 2, 3, 4, 5];
    }
    public function getMainColumn(): string
    {
        return $this->getAttribute('id');
    }
}
