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
    protected $fillable = ['user_id', 'department_id', 'company_id', 'asan_id', 'phone', 'pin1', 'pin2', 'is_active','puk'];
    protected $with = ['user:id,name,surname', 'company:id,name,has_no_vat'];

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
    public function scopeIsActive($query)
    {
        return $query->where('is_active', 1);
    }
    public function getPhoneAttribute($value): ?string
    {
        return phone_formatter($value, true);
    }

    public function getUserWithCompanyAttribute($value): ?string
    {
        return "{$this->user->getAttribute('fullname')} ({$this->company->getAttribute('name')})";
    }

    /**
     * Bu Asan İmza-nın VAT-siz olub-olmadığını yoxlayır
     * Company ID-yə görə müəyyən edilir
     */
    public function hasNoVat(): bool
    {
        if (!$this->company_id) {
            return false;
        }

        return $this->company->hasNoVat();
    }
}
