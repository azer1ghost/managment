<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Protocol extends Model
{
    protected $fillable = ['protocol_no', 'content', 'signature', 'performer', 'date', 'company_id'];
    protected $dates = ['date'];

    public function signatures(): BelongsTo
    {
        return $this->belongsTo(User::class,'signature')->withDefault();
    }
    public function performers(): BelongsTo
    {
        return $this->belongsTo(User::class,'performer')->withDefault();
    }
    public function companies(): BelongsTo
    {
        return $this->belongsTo(Company::class,'company_id')->withDefault();
    }

}
