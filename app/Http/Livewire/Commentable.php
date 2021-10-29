<?php

namespace App\Http\Livewire;

use App\Http\Controllers\Modules\CommentController;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class Commentable extends Component
{
    public ?Model $commentable;

    public int $perPage = 3;

    public array $comments = [];

    public string $url = '';

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

    public function mount($commentable, $url)
    {
        $this->commentable = $commentable;
        $this->url = $url;
    }

    protected function updatedMessage($value)
    {
        if ($this->replyableComment && $value == '' || !preg_match("/^@/i", $value)) {
            $this->replyableComment = null;
        }
    }

    public function sendComment()
    {
        if ($this->currentlyEditingComment){
            $this->currentlyEditingComment->update([
               'content' =>  $this->message
            ]);

            $this->currentlyEditingComment = null;
        }
        else {
                $commentableModel = $this->replyableComment ?? $this->commentable;

            (new CommentController())->store($this->message, $this->url, $commentableModel);

            $this->replyableComment = null;
        }

        $this->message = '';
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
        if ($this->commentable){
            $this->reloadComments();
        }

        return view('livewire.commentable');
    }
}


















