<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;

class CommentCreated
{
    use Dispatchable;

    public User $creator;
    public array $receivers = [];
    public string $title, $body, $url;

    public function __construct($model, $content, $url)
    {
        $this->url = $url;
        $this->title = trans('translates.comments.new');
        $this->body = $content;
        $this->creator = $model->user; // model examples are: tasks, updates ...

        $user = new User(); // single receiver user

        switch ($model->getTable()){
            case 'tasks':
                if($model->taskable->getTable() == 'departments'){
                    $departmentUsersWithoutMe = $model->taskable->users()->whereNotIn('id', [auth()->id()])->get()->all();
                    $this->receivers = $departmentUsersWithoutMe;
                }else{
                    $user = $model->taskable;
                }
                break;
        }

        if(auth()->id() != $user->id){
            $this->receivers[] = $user;
        }

        $this->receivers[] = $this->creator;
    }
}
