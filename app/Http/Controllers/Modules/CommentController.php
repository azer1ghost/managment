<?php

namespace App\Http\Controllers\Modules;

use App\Events\Notification;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Department;
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

        switch ($model->getTable()){

            case 'comments':
                $commentModel = $model;
                $user = $commentModel->user; // replyable user (ex: parent comment)
                $creator = $commentModel->commentable->user; // creator of the module (ex: task, update)
                break;

            case 'updates':
                $updateModel = $model;
                $user = auth()->user();
                $creator = $updateModel->user;
                break;

            case 'tasks':
                $taskModel = $model;

                if($taskModel->taskable->getTable() == 'departments'){
                    $user = auth()->user();
                    $departmentUsersWithoutMe = $taskModel->taskable->users()->whereNotIn('id', [auth()->id()])->get();
                    foreach ($departmentUsersWithoutMe as $depUser){
                        $users[] = $depUser;
                    }
                }else{
                    $user = $taskModel->taskable;
                }
                $creator = $taskModel->user;
                break;
        }

        $users[] = $creator;

        if($user->id != auth()->id()) {
            $users[] = $user;
        }

        if($creator->id != auth()->id()) {
            $users[] = $creator;
        }

        event(new Notification($creator, $users, trans('translates.comments.new'), $content, $url));
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