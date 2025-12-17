<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Parameter;

class WorkParameter extends Model
{
    protected $table = 'work_parameter';

    // work_parameter table has no primary key (it's a pivot table)
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'work_id',
        'parameter_id',
        'value',
    ];

    public $timestamps = false;

    public function work()
    {
        return $this->belongsTo(Work::class);
    }

    public function parameter()
    {
        return $this->belongsTo(Parameter::class);
    }
}

