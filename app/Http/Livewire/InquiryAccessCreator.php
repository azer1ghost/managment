<?php

namespace App\Http\Livewire;

use App\Models\Inquiry;
use App\Models\User;
use Livewire\Component;

class InquiryAccessCreator extends Component
{
    public Inquiry $inquiry;
    public array $users;
    public array $editableUsers;

    public function mount()
    {
        $this->editableUsers = $this->inquiry->editableUsers()->get(['id'])->toArray();
        $this->users = User::get(['id', 'name'])->toArray();
    }

    public function addUser()
    {
        $this->editableUsers[] = [
            "id" => null,
            'pivot' => [
                "inquiry_id" => null,
                "user_id" => null,
                'editable_ended_at' => null
            ]
        ];
    }

    public function removeUser($index)
    {
        unset($this->editableUsers[$index]);
    }

    public function render()
    {
        return view('panel.pages.inquiry.components.access-creator');
    }
}
