<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Notifications\NewComment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct()
    {
        // trottle function required
    }

    public function store($content, Model $model)
    {
        $model->comments()->create([
            'content' => $content
        ]);

        $user = ($model->getTable() == 'comments') ? $model->user : $model->taskable;

        $user->notify(new NewComment($content));
    }

    public function update(Request $request, Comment $comment)
    {
        //
    }

    public function destroy(Comment $comment)
    {
        //
    }
}