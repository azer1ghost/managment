<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ReturnWork extends Model
{
    protected $fillable = ['return_reason', 'main_reason', 'name', 'phone'];
    
}
