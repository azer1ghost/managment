<?php

namespace App\Models;

use Altek\Accountant\Contracts\Recordable;
use Altek\Eventually\Eventually;
use App\Interfaces\DocumentableInterface;
use App\Traits\Documentable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Protocol extends Model implements Recordable, DocumentableInterface
{
    use Documentable, \Altek\Accountant\Recordable, Eventually;
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
    public function getMainColumn(): string
    {
        return $this->getAttribute('protocol_no');
    }

}
