<div class="dropdown">
    <a id="navbarDropdown" class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span><i class="far fa-bell"></i></span>
    </a>
    <div class="dropdown-menu dropdown-menu-right" style="width: 200px !important;max-width: 100% !important;">
        @foreach($notifications as $notification)
            {{$notification->type}}
        @endforeach
    </div>
</div>