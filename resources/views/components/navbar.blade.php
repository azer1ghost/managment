<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo" href="{{ route('dashboard') }}"><img src="{{asset('assets/images/logo.svg')}}" alt="logo"/><h6 class="m-0">Mobil Management</h6></a>
        <a class="navbar-brand brand-logo-mini" href="{{ route('dashboard') }}"><img src="{{asset('assets/images/logo.svg')}}" alt="logo"/></a>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end px-3" x-data>
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <i class="fas fa-bars"></i>
        </button>
{{--        <ul class="navbar-nav mr-lg-2">--}}
{{--            <li class="nav-item nav-search d-none d-lg-block">--}}
{{--                <div class="input-group">--}}
{{--                    <div class="input-group-prepend hover-cursor" id="navbar-search-icon">--}}
{{--                            <span class="input-group-text" id="search">--}}
{{--                              <i class="fas fa-search"></i>--}}
{{--                            </span>--}}
{{--                    </div>--}}
{{--                    <input type="text" class="form-control" id="navbar-search-input" placeholder="Search now" aria-label="search" aria-describedby="search">--}}
{{--                </div>--}}
{{--            </li>--}}
{{--        </ul>--}}

        <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item dropdown">
                <a class="nav-link count-indicator dropdown-toggle" id="notificationsDropdown" href="#" data-toggle="dropdown">
                    <i class="fas fa-comment-dots"></i>
                    @php $messages = \App\Models\Chat::where('to', auth()->id())->where('is_read',0)->get() @endphp
                    @if(count($messages) > 0)  <span class="count" style="color: red"></span> @endif
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list p-2" style="min-width: 400px;max-width: 100%;overflow-wrap: break-word !important;height: 400px;overflow-x: hidden">

                    @if(count($messages) > 0)
                        <div>
                            @foreach($messages as $message)
                                @php $user = \App\Models\User::whereId($message->from)->first() @endphp

                                <a class="text-black" href="{{route('chats.index')}}">
                                    <h4 class="preview-subject font-weight-normal">{{$user->getAttribute('fullname')}}</h4>
                                    <p class="mb-1">Sizə Bir Mesaj Var: @if (strlen($message->message) > 200) {!!substr($message->message, 0, 200) . '...'!!}@else
                                        {{$message->message}} @endif
                                    </p>
                                </a>
                            @endforeach
                        </div>
                    @else
                    <div>
                        <p class="text-black text-center">@lang('translates.general.no_message')</p>
                    </div>
                    @endif
                </div>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link count-indicator dropdown-toggle" id="notificationsDropdown" href="#" data-toggle="dropdown">
                    <i class="far fa-envelope mx-0"></i>
                    @php $notifications = auth()->user()->unreadNotifications->where('type', 'App\Notifications\NotifyStatement')->all() @endphp
                    @if(count($notifications) > 0)  <span class="count" ></span> @endif
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list p-2" style="min-width: 400px;max-width: 100%;overflow-wrap: break-word !important;height: 400px;overflow-x: hidden">

                    @if($notifications)
                        <div>
                            @foreach($notifications as $notification)
                                <a class="text-black" href="{{route('statement')}}">
                                    <h4 class="preview-subject font-weight-normal">{{$notification->data['title']}}</h4>
                                    <p class="mb-1"> @if (strlen($notification->data['body']) > 200) {!!substr($notification->data['body'], 0, 200) . '...'!!}@else
                                        {!! $notification->data['body'] !!} @endif </p>
                                </a>
                            @endforeach
                        </div>
                    @else
                    <div>
                        <p class="text-black text-center">@lang('translates.general.no_announcement')</p>
                    </div>
                    @endif
                </div>
            </li>


            <li class="nav-item dropdown">
                <a class="nav-link count-indicator dropdown-toggle" id="notificationsDropdown" href="#" data-toggle="dropdown">
                    <i class="fas fa-bell mx-0"></i>
                    <span class="count d-none" id="notification-badge"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list p-2" style="min-width: 400px;max-width: 100%;overflow-wrap: break-word !important;height: 400px;overflow-x: hidden">  <!--  -->
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
                                    <p x-text="notification.message" class="mb-1" style="font-size: 14px;"></p>
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
                        Profil
                    </a>
                    @if(request()->hasCookie('user_id'))
                        <a class="dropdown-item" href="{{ route('users.loginAs', request()->cookie('user_id')) }}">
                            <i class="fas fa-arrow-left text-primary"></i>
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
</nav>

 @push('scripts')
     <script>
         Spruce.store('state', {
             notifications: [],
         })

         let notifications = Spruce.store('state').notifications;

         @if(app()->environment('production'))
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
         @endif

         $('#notificationsDropdown').click(function (){
             if(!$('#notification-badge').hasClass('d-none')){
                 $('#notification-badge').addClass('d-none');
             }
         });
     </script>
 @endpush