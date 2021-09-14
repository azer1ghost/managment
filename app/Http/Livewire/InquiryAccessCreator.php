<?php

namespace App\Http\Livewire;

use App\Models\Inquiry;
use App\Models\User;
use Livewire\Component;

class InquiryAccessCreator extends Component
{
    public Inquiry $inquiry;
    public array $users, $editableUsers;

    public function mount()
    {
        $this->users = User::get(['id', 'name', 'surname'])->toArray();
        $this->editableUsers = $this->inquiry->editableUsers()->get(['id'])->toArray();
    }

    public function addUser()
    {
        $this->editableUsers[] = [
            "id" => null,
            'pivot' => [
                "inquiry_id" => $this->inquiry->getAttribute('id'),
                "user_id" => null,
                'editable_ended_at' => now()->addHour()->format('Y-m-d H:i:s')
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
