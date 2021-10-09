<div class="dropdown" wire:poll.keep-alive.10000ms="newNotifications">
    <div class="{{$notify ? 'd-block' : 'd-none'}}" style="position: absolute;right: 0"><i class="fas fa-circle text-danger" style="font-size: 7px"></i></div>
    <a id="navbarDropdown" class="nav-link pr-0" href="#" role="button" wire:click="toggleNotifications()">
        <span><i class="far fa-bell"></i></span>
    </a>
    @if ($show)
        <div class="dropdown-menu dropdown-menu-right p-3 d-block" style="top:45px !important;min-width: 280px !important;background: #F5F6FA !important;">
            <div class="d-flex flex-column">
                @forelse($notifications as $notification)
                    <x-notification :notification="$notification" />
                @empty
                    <span style="color: black !important;">No new notifications</span>
                @endforelse
            </div>
        </div>
    @endif
</div>