<?php

namespace App\Http\Livewire;
use App\Models\User;
use Livewire\Component;

class Notification extends Component
{
    public array $notifications = [];
    public bool $show = false;
    public int $currentNotificationsCount = 0;

    public bool $notify = false;

    public function mount()
    {
        $this->getNotifications();
    }

    public function toggleNotifications()
    {
        $this->notify = false;
        $this->show = !$this->show;
    }

    public function getNotifications()
    {
        $notifications = auth()->user()->notifications()
            ->where('channel', 'DATABASE')
            ->latest()->take(5)->get();

        $this->notifications = [];

        foreach ($notifications as $notification){
            $user = User::select(['id', 'name', 'surname', 'avatar'])->find($notification->data['user_id']);

            $this->notifications[] = [
                'id' => $notification->id,
                'read_at' => $notification->read_at,
                'message' => $notification->data['message'],
                'user' => [
                    'avatar' => $user->getAttribute('avatar'),
                    'fullname' => $user->getAttribute('fullname')
                ],
                'content' => $notification->data['content'],
                'url' => $notification->data['url'] ?? '#'
            ];
        }
    }

    public function newNotifications()
    {
        $oldNotificationCount = $this->currentNotificationsCount;
        $this->getNotifications();
        $this->currentNotificationsCount = count($this->notifications);

        if ($this->currentNotificationsCount > $oldNotificationCount){
            $notify = false;
            foreach ($this->notifications as $notification){
                if(is_null($notification['read_at'])){
                    $notify = true;
                    $this->notify = true;
                    break;
                }
            }
            if ($notify){
                $this->notify = true;
                $this->emit('newNotifications');
            }
        }
    }

    public function render()
    {
        return view('livewire.notification');
    }
}
