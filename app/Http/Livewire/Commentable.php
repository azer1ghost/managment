<?php

namespace App\Http\Livewire;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class Commentable extends Component
{
    public ?Model $commentable;

    public int $perPage = 3;

    public array $comments = [];

    public string $message = '';

    public ?Comment $replyableComment = null;

    protected function reloadComments(){
        $this->comments = optional($this->commentable)
            ->comments()
            ->latest()
            ->paginate($this->perPage)
            ->toArray();
    }

    public function loadMore()
    {
        $this->perPage += 3;

        $this->reloadComments();
    }

    public function mount($commentable)
    {
        $this->commentable = $commentable;

        $this->reloadComments();
    }

    public function sendComment()
    {
        if ($this->replyableComment) {
            $newComment = $this->replyableComment->comments()->create([
                'content' => $this->message
            ]);

            Comment::withCount(['viewers', 'comments'])->find($newComment->getAttribute('id'))->toArray();
        }
        else
        {
            $newComment = $this->commentable->comments()->create([
                'content' => $this->message
            ]);

            Comment::withCount(['viewers', 'comments'])->find($newComment->getAttribute('id'))->toArray();
        }

        $this->message = '';

        $this->replyableComment = null;

        $this->reloadComments();
    }

    public function reply($id)
    {
        $this->replyableComment = Comment::find($id);

        $this->emit('focus-to-message', $this->replyableComment->user->fullname);
    }

    public function render()
    {
        return view('livewire.commentable');
    }
}


















