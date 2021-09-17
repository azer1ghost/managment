<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'name',
        'surname',
        'father',
        'gender',
        'serial_pattern',
        'serial',
        'phone_coop',
        'phone',
        'address',
        'email_coop',
        'email',
        'company',
    ];

    public function getFullnameAttribute(): string
    {
        return "{$this->getAttribute('name')} {$this->getAttribute('surname')}";
    }
}