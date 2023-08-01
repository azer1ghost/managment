<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InternalDocument extends Model
{
    protected $fillable = ['department_id', 'document_name', 'document_code', 'company_id', 'ordering'];

    public function departments(): BelongsTo
    {
        return $this->belongsTo(Department::class,'department_id')->withDefault();
    }
    public function companies(): BelongsTo
    {
        return $this->belongsTo(Company::class,'company_id')->withDefault();
    }
}
