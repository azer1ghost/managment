<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Relations\MorphOne;

interface ResultableInterface
{
    public function result(): MorphOne;
}