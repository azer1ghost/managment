<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface DocumentableInterface {

    public function getMainColumn(): string;
    public function documents(): MorphMany;
}