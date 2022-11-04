<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InternalRelation extends Model
{
    protected $fillable = ['user_id', 'department_id', 'case', 'applicant', 'reciever', 'tool', 'contact_time'];

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id')->withDefault();
    }

    public function departments(): BelongsTo
    {
        return $this->belongsTo(Department::class,'department_id')->withDefault();
    }
}
