<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface DocumentableInterface
{
    public function documents(): MorphMany;
}