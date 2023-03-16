<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Questionnaire extends Model
{
    protected $fillable = ['client_id', 'source', 'send_email', 'customs', 'novelty_us', 'novelty_customs'];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class)->withDefault();
    }
    public static function customses()
    {
        return [1 => 1, 2, 3, 4, 5, 6, 7, 8];
    }
    public static function sources()
    {
        return [1 => 1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
    }
}
