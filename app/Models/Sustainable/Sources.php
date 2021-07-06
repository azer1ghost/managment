<?php

namespace App\Models\Sustainable;

class Sources
{
    public static function get(): \Illuminate\Support\Collection
    {
        $data = [
            'call'      => (object) ['en' => 'Call'],
            'whatsapp'  => (object) ['en' => 'Whatsapp'],
            'facebook'  => (object) ['en' => 'Facebook'],
            'instagram' => (object) ['en' => 'Instagram'],
            'twitter'   => (object) ['en' => 'Twitter'],
            'linkedin'  => (object) ['en' => 'Linkedin'],
        ];

        return collect($data)->map(function ($data){
            return $data->{app()->getLocale()} ?? $data->{config('app.fallback_locale')};
        });
    }
}


