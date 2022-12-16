<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'deadline'
    ];

    const OFFER = 1;
    const COMPLAINT = 2;
    const INCONSISTENCY = 3;

    public function users()
    {
        $this->belongsTo(User::class)->withDefault();
    }

    public function departments()
    {
        $this->belongsTo(Department::class)->withDefault();
    }

    public function types()
    {
        return [1 => 1, 2, 3];
    }
}
