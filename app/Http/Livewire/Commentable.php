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
        $newComment = $this->commentable->comments()->create([
            'content' => $this->message
        ]);

        $newComment = Comment::withCount(['viewers', 'comments'])->find($newComment->getAttribute('id'))->toArray();

        array_unshift($this->comments, $newComment);

        $this->message = '';

        $this->reloadComments();
    }

    public function reply($id)
    {
        $comment = Comment::find($id);

        $this->emit('focus-to-message', $comment->user->fullname);
    }

    public function render()
    {
        return view('livewire.commentable');
    }
}


















