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

        $isReuqestNotExcerptedShowingAlert = !request()->routeIs('welcome', 'phone.verification.notice');

        $permissionMustSeeAlert = auth()->user()->hasPermission($this->announcement->getAttribute('permissions'));

        $userMustSeeAlert = in_array(auth()->id(), explode(',', $this->announcement->getAttribute('users')));

        if ($isReuqestNotExcerptedShowingAlert && ( $permissionMustSeeAlert || $userMustSeeAlert)):

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

        endif;
    }
}