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
        'fin',
        'phone_coop',
        'phone',
        'email_coop',
        'email',
        'address',
        'address_coop',
        'company',
        'voen',
        'position'
    ];

    public function getFullnameAttribute(): string
    {
        return "{$this->getAttribute('name')} {$this->getAttribute('surname')}";
    }
}