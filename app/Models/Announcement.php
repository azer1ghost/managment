<?php

namespace App\Models;

use Dotenv\Util\Str;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Announcement extends Model
{
    use HasTranslations;

    public array $translatable = ['title', 'detail'];

    protected $casts = ['will_notify_at' => 'datetime', 'will_end_at' => 'datetime'];

    protected static function boot()
    {
        parent::boot();

        self::creating(function (Announcement $announcement){
            $announcement->setAttribute('key', uniqid());

            if($announcement->isClean('will_notify_at')){
                $announcement->setAttribute('will_notify_at', now());
            }

            if($announcement->isClean('will_end_at')){
                $announcement->setAttribute('will_end_at', now()->addWeek());
            }
        });
    }
}