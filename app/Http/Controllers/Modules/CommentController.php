<?php

namespace App\Http\Controllers\Modules;

use App\Events\CommentCreated;
use App\Events\CommentReplied;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;

class CommentController extends Controller
{
    public function store($content, $url, Model $model)
    {
        $model->comments()->create([
            'content' => $content
        ]);

        if($model->getTable() == 'comments'){
            event(new CommentReplied($model, $content, $url));
        }else{
            event(new CommentCreated($model, $content, $url));
        }

    }
}