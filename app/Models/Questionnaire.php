<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Questionnaire extends Model
{
    protected $fillable = ['client_id', 'source', 'send_email', 'customs', 'novelty_us', 'novelty_customs'];

    public function clients(): BelongsTo
    {
        return $this->belongsTo(Client::class)->withDefault();
    }

}
