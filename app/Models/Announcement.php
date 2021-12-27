<?php

namespace App\Models;

use Altek\Accountant\Contracts\Recordable;
use Altek\Eventually\Eventually;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Announcement extends Model implements Recordable
{
    use HasTranslations, \Altek\Accountant\Recordable, Eventually;

    protected $fillable = ['class', 'title', 'detail', 'repeat_rate', 'status', 'permissions', 'users'];

    public array $translatable = ['title', 'detail'];

    protected $casts = ['will_notify_at' => 'datetime', 'will_end_at' => 'datetime', 'status' => 'boolean'];

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

    public function scopeIsActive($query)
    {
        return $query->where('status', true);
    }

}