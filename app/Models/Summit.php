<?php

namespace App\Models;

use Altek\Accountant\Contracts\Recordable;
use Altek\Eventually\Eventually;
use App\Interfaces\DocumentableInterface;
use App\Traits\Documentable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Summit extends Model implements Recordable, DocumentableInterface

{
    use Documentable, \Altek\Accountant\Recordable,  Eventually;

    protected $fillable = ['club', 'event', 'dresscode', 'status', 'format', 'place'];
    protected $dates = ['date'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_summits_relationship');
    }
    public function getMainColumn(): string
    {
        return $this->getAttribute('id');
    }
}
