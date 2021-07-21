<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'time', 'client', 'fullname', 'phone', 'subject', 'kind', 'source', 'note', 'redirected', 'status', 'company_id'];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    protected static function parameter($data)
    {
        return Parameter::select(['id','name'])->find($data);
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
