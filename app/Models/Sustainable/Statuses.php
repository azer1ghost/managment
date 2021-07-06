<?php

namespace App\Models\Sustainable;

class Statuses
{
    public static function get(): \Illuminate\Support\Collection
    {
        $data = [
            'active'  => (object) ['en' => 'Active'],
            'actual'  => (object) ['en' => 'Actual'],
            'done'    => (object) ['en' => 'Done'],
            'waiting' => (object) ['en' => 'Waiting'],
        ];

        return collect($data)->map(function ($data){
            return $data->{app()->getLocale()} ?? $data->{config('app.fallback_locale')};
        });
    }
}
