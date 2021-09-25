<div class="dropdown" wire:poll.keep-alive.5000ms="newNotifications">
    <a id="navbarDropdown" class="nav-link pr-0" href="#" role="button" wire:click="toggleNotifications()">
        <span><i class="far fa-bell"></i></span>
    </a>
    @if ($show)
        <div class="dropdown-menu dropdown-menu-right p-3 d-block" style="top:45px !important;min-width: 280px !important;background: #F5F6FA !important;">
            <div class="d-flex flex-column">
                @foreach($notifications as $notification)
                    <x-notification :notification="$notification" />
                @endforeach
            </div>
        </div>
    @endif
</div>