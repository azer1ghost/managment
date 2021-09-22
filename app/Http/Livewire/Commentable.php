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

    public ?Comment $currentlyEditingComment = null;

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
    }

    public function mount($commentable)
    {
        $this->commentable = $commentable;
    }

    public function sendComment()
    {
        if ($this->currentlyEditingComment){
            $this->currentlyEditingComment->update([
               'content' =>  $this->message
            ]);
        }
        else {
            $comment = $this->replyableComment ?? $this->commentable;

            $comment->comments()->create([
                'content' => $this->message
            ]);
        }

        $this->message = '';

        $this->replyableComment = null;
    }

    public function reply($id)
    {
        $currentComment = Comment::find($id);

        $this->replyableComment =
            ($currentComment->commentable->getTable() == 'comments') ?
            $currentComment->commentable :
            $currentComment;

        $this->emit('focus-to-message', "@{$currentComment->user->fullname} ");
    }


    public function delete($id)
    {
       $comment = Comment::find($id);

       $comment->comments()->delete();

       $comment->delete();
    }

    public function edit($id)
    {
        $this->currentlyEditingComment = Comment::find($id);

        $this->emit('focus-to-message', $this->currentlyEditingComment->getAttribute('content'));
    }

    public function render()
    {
        $this->reloadComments();
        return view('livewire.commentable');
    }
}


















