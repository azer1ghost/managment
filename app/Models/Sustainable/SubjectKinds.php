<?php

namespace App\Models\Sustainable;

class SubjectKinds
{
    public static function get($subject): \Illuminate\Support\Collection
    {
        $data = [
            'problem' =>  [
                'incompatible'  => (object) ['en' => 'Incompatible',  'az' => 'Uyumsuzluq'],
                'volume_weight' => (object) ['en' => 'Volume Weight', 'az' => 'Həcmi Çəki'],
                'limit'         => (object) ['en' => 'Limit'],
            ],
        ];

        return collect($data[$subject])->map(function ($data){
            return $data->{app()->getLocale()} ?? $data->{config('app.fallback_locale')};
        });
    }
}
