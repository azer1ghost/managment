<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinanceClient extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'voen', 'hn', 'mh', 'code', 'bank', 'bvoen', 'swift', 'orderer'];
}
