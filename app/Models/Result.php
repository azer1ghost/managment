<?php

namespace App\Models;

use Altek\Accountant\Contracts\Recordable;
use Altek\Eventually\Eventually;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Result extends Model implements Recordable
{
    use HasFactory, SoftDeletes, \Altek\Accountant\Recordable, Eventually;

    protected $fillable = ['content'];

    public function resultable(): MorphTo
    {
        return $this->morphTo()->withDefault();
    }
}