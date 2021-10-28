 <ul class="d-flex justify-content-center align-items-center mb-0" x-data>
    @guest
        @if (Route::has('login'))
            <li class="nav-item">
                <a class="nav-link" href="{{ route('login') }}">Login</a>
            </li>
        @endif

        @if (Route::has('register'))
            <li class="nav-item">
                <a class="nav-link" href="{{ route('register') }}">Register</a>
            </li>
        @endif
    @else
        <a class="py-1" href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            {{ __('translates.logout') }}
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    @endguest
    <div class="dropdown">
        <div class="d-none" id="notification-badge" style="position: absolute;right: 0"><i class="fas fa-circle text-danger" style="font-size: 7px"></i></div>
        <a id="notificationsDropdown" class="nav-link pr-0" href="#" role="button" data-toggle="dropdown">
            <span><i class="far fa-bell"></i></span>
        </a>
        <div class="dropdown-menu dropdown-menu-right p-3" style="min-width: 280px !important;background: #F5F6FA !important;">
            <div class="d-flex flex-column">
                <template x-if="$store.state.notifications.length">
                    <template
                            x-for="(notification, index) in $store.state.notifications"
                            :key="index"
                    >
                        <a x-bind:href="notification.url" class="mb-3">
                            <div class="media">
                                <img x-bind:src="notification.user.avatar" class="mr-3 profile" alt="logo" style="width: 30px;height: 30px">
                                <div class="media-body">
                                    <h6 class="my-0" style="font-size: 12px;color: #000">
                                        <span x-text="notification.message"></span>
                                    </h6>
                                    <div class="user">
                                        <p class="mb-3 d-inline" style="font-size: 12px;color: #000 !important;" x-text="notification.user.fullname"></p>
                                        <p class="mb-3 d-inline text-muted" style="font-size: 10px;color: #000 !important;" x-text="notification.content"></p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </template>
                </template>
                <template x-if="!$store.state.notifications.length">
                    <p style="color: black!important;">No notifications yet</p>
                </template>
            </div>
        </div>
    </div>
</ul>
 @push('scripts')
     <script>
         const userID = {{auth()->id()}};
         const notificationsRef = firebase.database().ref().child('notifications');
         const sound = new Audio('{{asset('assets/audio/notify/notify.wav')}}');

         Spruce.store('state', {
             notifications: [],
         })

         let notifications = Spruce.store('state').notifications;
         notificationsRef.orderByChild('notifiable_id').equalTo(userID).on("child_added", (snap, prevChildKey) => {
             let snapVal = snap.val();

             if(notifications.length > 9){
                 notificationsRef.child(prevChildKey).remove();
                 notifications.pop();
             }

             notifications.push(snapVal);
             if(!snapVal.wasPlayed){
                 sound.play();
                 $('#notification-badge').removeClass('d-none');
                 notificationsRef.child(snap.key).update({wasPlayed: true});
             }
             notifications = notifications.reverse();
         });
         $('#notificationsDropdown').click(function (){
             if(!$('#notification-badge').hasClass('d-none')){
                 $('#notification-badge').addClass('d-none');
             }
         });
     </script>
 @endpush