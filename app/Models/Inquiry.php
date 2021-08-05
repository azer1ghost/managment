<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inquiry extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'date', 'time',
        'client', 'fullname', 'phone',
        'subject', 'kind', 'contact_method', 'source', 'operation', 'status',
        'note', 'redirected_user_id', 'company_id', 'user_id'
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function backups(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(__CLASS__);
    }

    protected static function parameter($data)
    {
        return \Cache::remember("parameter_$data", 60, function () use ($data) {
            return Parameter::select(['id','name'])->find($data);
        });
    }

    public function getSubjectAttribute($data)
    {
        return self::parameter($data);
    }

    public function getKindAttribute($data)
    {
        return self::parameter($data);
    }

    public function getSourceAttribute($data)
    {
        return self::parameter($data);
    }

    public function getContactMethodAttribute($data)
    {
        return self::parameter($data);
    }

    public function getOperationAttribute($data)
    {
        return self::parameter($data);
    }

    public function getStatusAttribute($data)
    {
        return self::parameter($data);
    }
}
