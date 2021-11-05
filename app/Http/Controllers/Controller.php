<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function translates(&$validated)
    {
        if(array_key_exists('translate', $validated)){
            $translates = $validated['translate'];
            foreach ($translates as $key => $translate) {
                $validated[$key] = $translate;
            }
        }
    }
}
