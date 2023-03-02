<?php

namespace App\Models;

use Altek\Accountant\Contracts\Recordable;
use Altek\Eventually\Eventually;
use App\Interfaces\DocumentableInterface;
use App\Traits\Documentable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Command extends Model implements Recordable, DocumentableInterface

{
    use Documentable, \Altek\Accountant\Recordable,  Eventually;

    protected $fillable = ['executor', 'confirming', 'number', 'content', 'command_date'];

    public function executors(): BelongsTo
    {
        return $this->belongsTo(User::class,'executor')->withDefault();
    }
    public function confirmings(): BelongsTo
    {
        return $this->belongsTo(User::class,'confirming')->withDefault();
    }
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_commands_relationship');
    }
    public function getMainColumn(): string
    {
        return $this->getAttribute('id');
    }
}
