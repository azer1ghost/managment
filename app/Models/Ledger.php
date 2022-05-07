<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

class Ledger extends Model

{
    use Prunable;

    public function prunable()
    {
        return static::where('created_at', '<=', now()->subMonth());
    }

}
