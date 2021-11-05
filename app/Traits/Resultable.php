<?php

namespace App\Traits;

use App\Models\Result;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait Resultable{

    public function result(): MorphOne
    {
        return $this->morphOne(Result::class, 'resultable');
    }
}