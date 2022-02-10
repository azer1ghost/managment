<div class="navbar-menu-wrapper d-flex align-items-center justify-content-end" x-data>
    <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
        <i class="fas fa-bars"></i>
    </button>
    <ul class="navbar-nav mr-lg-2">
        <li class="nav-item nav-search d-none d-lg-block">
            <div class="input-group">
                <div class="input-group-prepend hover-cursor" id="navbar-search-icon">
                            <span class="input-group-text" id="search">
                              <i class="fas fa-search"></i>
                            </span>
                </div>
                <input type="text" class="form-control" id="navbar-search-input" placeholder="Search now" aria-label="search" aria-describedby="search">
            </div>
        </li>
    </ul>
    <ul class="navbar-nav navbar-nav-right">
        <li class="nav-item dropdown">
            <a class="nav-link count-indicator dropdown-toggle" id="notificationsDropdown" href="#" data-toggle="dropdown">
                <i class="fas fa-bell mx-0"></i>
                <span class="count d-none" id="notification-badge"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list p-2">  <!-- style="min-width: 280px;max-width: 100%;height: 400px;overflow: auto" -->
                <template x-if="$store.state.notifications.length">
                    <template x-for="(notification, index) in $store.state.notifications" :key="index">
                        <a x-bind:href="notification.url" class="dropdown-item preview-item">
                            <div class="preview-thumbnail">
                                <div class="preview-icon">
                                    <img x-bind:src="notification.user.avatar" class="mr-3 profile" alt="logo" style="width: 100%;height: 100%">
                                </div>
                            </div>
                            <div class="preview-item-content">
                                <h6 class="preview-subject font-weight-normal" x-text="notification.user.fullname"></h6>
                                <p x-text="notification.message" class="mb-1" style="font-size: 12px"></p>
                                <p class="text-muted" x-text="notification.content"></p>
                            </div>
                        </a>
                    </template>
                </template>
                <template x-if="!$store.state.notifications.length">
                    <p style="color: black!important;">No notifications yet</p>
                </template>
            </div>
        </li>
        <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                <img src="{{image(auth()->user()->getAttribute('avatar'))}}" alt="profile"/>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                <a class="dropdown-item" href="{{route('account')}}">
                    <i class="fas fa-user text-primary"></i>
                    {{auth()->user()->getAttribute('fullname')}}
                </a>
                @if(request()->hasCookie('user_id'))
                    <a class="dropdown-item" href="{{ route('users.loginAs', request()->cookie('user_id')) }}">
                        Back
                    </a>
                @else
                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                        <i class="fas fa-house-leave text-primary"></i>
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                @endif

            </div>
        </li>
    </ul>
    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
        <i class="fas fa-bars"></i>
    </button>
</div>

 @push('scripts')
     <script>
         Spruce.store('state', {
             notifications: [],
         })

         let notifications = Spruce.store('state').notifications;

{{--         @if(app()->environment('production'))--}}
            const userID = {{auth()->id()}};
            const notificationsRef = firebase.database().ref().child('notifications');
            const sound = new Audio('{{asset('assets/audio/notify/notify.wav')}}');

             notificationsRef.orderByChild('receiver_id').equalTo(userID).on("child_added", (snap, prevChildKey) => {
                 let snapVal = snap.val();
                 if(notifications.length > 9){
                     notificationsRef.child(prevChildKey).remove();
                     notifications.pop();
                     notifications = notifications.reverse();
                 }

                 notifications.push(snapVal);
                 if(!snapVal.wasPlayed){
                     sound.play();
                     $('#notification-badge').removeClass('d-none');
                     notificationsRef.child(snap.key).update({wasPlayed: true});
                 }
                 notifications = notifications.reverse();
             });
{{--         @endif--}}

         $('#notificationsDropdown').click(function (){
             if(!$('#notification-badge').hasClass('d-none')){
                 $('#notification-badge').addClass('d-none');
             }
         });
     </script>
 @endpush