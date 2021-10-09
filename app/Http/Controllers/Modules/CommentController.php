<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\User;
use App\Notifications\NewComment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class CommentController extends Controller
{
    public function __construct()
    {
        // trottle function required
    }

    public function store($content, $url, Model $model)
    {
        $model->comments()->create([
            'content' => $content
        ]);

        if ($model->getTable() == 'comments'){
            $user = $model->user;
            $task_creator = User::find($model->commentable->user_id);
        }else{
            $user = $model->taskable;
            $task_creator = User::find($model->user_id);
        }
        $users = [$task_creator];

        if($user->id != auth()->id()) {
            $users[] = $user;
        }

        Notification::send($users, new NewComment($content, $url));
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