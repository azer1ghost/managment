<?php

namespace App\View\Components;

use App\Models\Announcement;
use Carbon\Carbon;
use Illuminate\View\Component;

class NotifyModal extends Component
{
    public Announcement $announcement;
    public string $token;

    public function render()
    {
        $this->announcement = Announcement::latest('id')->first();
        $this->token = $this->announcement->getAttribute('key');

        if (!request()->routeIs('welcome')){
            $started = $this->announcement->getAttribute('will_notify_at') <= now();
            $notEnded = $this->announcement->getAttribute('will_end_at') >= now();
            $notifyLastClosedTime = Carbon::parse(request()->cookie('notifyLastClosedTime'));
            $lastClosedTime = $notifyLastClosedTime->copy();

            $repeatAt = $lastClosedTime->add($this->announcement->getAttribute('repeat_rate'));

            if($started && $notEnded){
                if($repeatAt > now()){
                    if($this->token != request()->cookie('notifyToken')){
                        return view('components.notify-modal');
                    }
                }else{
                    return view('components.notify-modal');
                }
            }
        }
    }
}