<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryReport extends Model
{
    protected $fillable = [
        'user_id',
        'company_id',
        'working_days',
        'actual_days',
        'salary',
        'prize',
        'occupation',
        'advance',
        'date',
        'note',
    ];
}
