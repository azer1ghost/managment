<?php

namespace App\Events;

use App\Models\User;
use App\Models\Work;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WorkCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public User $creator;
    public array $receivers = [];
    public string $title, $body = '', $url;

    public function __construct($work)
    {
        $this->url = route('works.show', $work);
        $this->creator = $work->getRelationValue('creator');
        $this->title = trans('translates.works.new');

        if (is_numeric($work->getAttribute('user_id'))){
            $this->receivers[] = $work->getRelationValue('user');
            $this->body = trans('translates.works.content.user');
        }else{
            $this->receivers = $work->getRelationValue('department')->users()
                ->whereNotIn('id', [$this->creator->id])
                ->get()->all();
            $this->body = trans('translates.works.content.department');
        }
    }

}
