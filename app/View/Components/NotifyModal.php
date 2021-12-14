<?php

namespace App\View\Components;

use App\Models\Announcement;
use Carbon\Carbon;
use Illuminate\View\Component;

class NotifyModal extends Component
{
    public ?Announcement $announcement;
    public ?string $token;

    public function render()
    {
        $this->announcement = Announcement::isActive()->latest('id')->first() ?? new Announcement();
        $this->token = $this->announcement->getAttribute('key');

        if (!request()->routeIs('welcome', 'phone.verification.notice') &&
            (
                auth()->user()->hasPermission($this->announcement->getAttribute('permissions')) ||
                in_array(auth()->id(), explode(',', $this->announcement->getAttribute('users')))
            )
        ){
            $started = $this->announcement->getAttribute('will_notify_at') <= now();
            $notEnded = $this->announcement->getAttribute('will_end_at') >= now();
            $isNotRepeatable = is_null($this->announcement->getAttribute('repeat_rate'));

            if (!$isNotRepeatable){
                $notifyLastClosedTime = Carbon::parse(request()->cookie('notifyLastClosedTime'));
                $lastClosedTime = $notifyLastClosedTime->copy();

                $repeatAt = $lastClosedTime->add($this->announcement->getAttribute('repeat_rate'));
            }

            if($started && $notEnded){
                if($isNotRepeatable || $repeatAt > now()){
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