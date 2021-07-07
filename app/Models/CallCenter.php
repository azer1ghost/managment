<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static create($validated)
 */
class CallCenter extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'date',
        'time',
        'client',
        'fullname',
        'phone',
        'subject',
        'source',
        'note',
        'redirected',
        'status',
        'company_id',
        'user_id'
    ];
}
