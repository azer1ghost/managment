<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;

class CommentController extends Controller
{
    public function store($content, $url, Model $model)
    {
        $model->comments()->create([
            'content' => $content
        ]);
    }
}