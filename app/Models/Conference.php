<?php

namespace App\Models;

use Altek\Accountant\Contracts\Recordable;
use Altek\Eventually\Eventually;
use Illuminate\{Database\Eloquent\Factories\HasFactory, Database\Eloquent\Model, Database\Eloquent\SoftDeletes};

class Conference extends Model implements Recordable
{
    use HasFactory, SoftDeletes, \Altek\Accountant\Recordable, Eventually;

    protected $fillable = ['name', 'status', 'datetime'];

    public static function statuses(): array
    {
        return ['deger1', 'deger2', 'deger3'];
    }
}
