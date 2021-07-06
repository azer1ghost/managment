<?php

namespace App\Models\Sustainable;

class Subjects
{
    public static function get(): \Illuminate\Support\Collection
    {
        $data = [
            'problem'      => (object) ['en' => 'Problem'],
            'tech_support' => (object) ['en' => 'Technical Support', 'az' => 'Texniki Dəstək'],
            'info'         => (object) ['en' => 'Information', 'az' => 'Informasiya'],
        ];

        return collect($data)->map(function ($data){
            return $data->{app()->getLocale()} ?? $data->{config('app.fallback_locale')};
        });
    }
}
