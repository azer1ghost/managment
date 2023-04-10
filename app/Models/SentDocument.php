<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SentDocument extends Model
{
    protected $fillable = ['overhead_num', 'organization', 'content', 'note', 'sent_date', 'company_id'];
    protected $dates = ['sent_date'];
    public function companies(): BelongsTo
    {
        return $this->belongsTo(Company::class,'company_id')->withDefault();
    }
}
