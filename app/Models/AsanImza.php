<?php

namespace App\Models;

use Altek\Accountant\Contracts\Recordable;
use Altek\Eventually\Eventually;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AsanImza extends Model implements Recordable
{
    use SoftDeletes, HasFactory, \Altek\Accountant\Recordable, Eventually;

    protected $table = 'asan_imzalar';
    protected $fillable = ['user_id', 'department_id', 'company_id', 'asan_id', 'phone', 'pin1', 'pin2', 'is_active'];
    protected $with = ['user:id,name,surname', 'company:id,name'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class)->withDefault();
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class)->withDefault();
    }

    public function setPhoneAttribute($value): ?string
    {
        return $this->attributes['phone'] = phone_cleaner($value);
    }

    public function getPhoneAttribute($value): ?string
    {
        return phone_formatter($value, true);
    }

    public function getUserWithCompanyAttribute($value): ?string
    {
        return "{$this->user->getAttribute('fullname')} ({$this->company->getAttribute('name')})";
    }
}
