<?php

namespace App\Http\Controllers\Modules;

use App\Events\Notification;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct()
    {
        // throttle function required
    }

    public function store($content, $url, Model $model)
    {
        $model->comments()->create([
            'content' => $content
        ]);

        if ($model->getTable() == 'comments'){
            $user = $model->user;
            $creator = User::find($model->commentable->user_id);
        }elseif ($model->getTable() == 'tasks'){
            if($model->taskable()->getTable() == 'departments'){
                foreach (User::where('id', '!=', auth()->id())->where('department_id', $model->taskable_id)->get() as $_user)
                $users[] = $_user;
            }else{
                $user = $model->taskable;
            }
            $creator = User::find($model->user_id);
        }elseif ($model->getTable() == 'updates'){
            $user = $model->user;
            $creator = User::find($model->user_id);
        }
        $users = [$creator];

        if($model->getTable() != 'tasks'){
            if($user->id != auth()->id()) {
                $users[] = $user;
            }
        }

        event(new Notification($user, $users, trans('translates.comments.new'), $content, $url));
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