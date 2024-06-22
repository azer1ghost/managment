<style>
    .dropdown-menu {
        display: none;
        position: absolute;
        will-change: transform;
        top: 100%;
        left: 0;
    }
    .dropdown-menu.show {
        display: block;
    }
    .list-content {
        font-size: 14px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .date {
        font-size: 12px;
        color: gray;
    }
    .global-search {
        flex: 1;
    }

</style>

<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo" href="{{ route('dashboard') }}"><img src="{{asset('assets/images/logo.svg')}}" alt="logo"/><h6 class="m-0">Mobil Management</h6></a>
        <a class="navbar-brand brand-logo-mini" href="{{ route('dashboard') }}"><img src="{{asset('assets/images/logo.svg')}}" alt="logo"/></a>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end px-3" x-data>
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <i class="fas fa-bars"></i>
        </button>
        <ul class="navbar-nav mr-lg-2">
            <li class="nav-item nav-search d-none d-lg-block">
                <div class="input-group">
                    <div class="input-group-prepend hover-cursor" id="navbar-search-icon">
                            <a class="input-group-text" href="{{ route('note-index') }}">
                              <i class="fas fa-sticky-note m-2"></i>
                                Notlarım və To-Do Listim
                            </a>
                    </div>
                </div>
            </li>
        </ul>

        <div class="container-fluid">
            <div class="global-search d-flex col-12 position-relative p-0">
                <form class="d-flex flex-grow-1 w-100">
                    <input class="form-control" placeholder="Search anything you want" aria-label="Search" id="searchInput" style="max-width: 100%;">
                </form>
                <ul class="dropdown-menu col-12" id="dropdownMenu"></ul>
            </div>
        </div>

        <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item">
                <div class="custom-control custom-switch" >
                    <input type="checkbox" class="custom-control-input" id="darkMode">
                    <label class="custom-control-label" for="darkMode"><i class="fas fa-moon"></i></label>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link count-indicator dropdown-toggle" id="notificationsDropdown" href="#" data-toggle="dropdown">
                    <i class="fas fa-comment-dots"></i>
                    @php $messages = \App\Models\Chat::where('to', auth()->id())->where('is_read',0)->get() @endphp
                    @php $rooms = \App\Models\Room::where('user', '!=', auth()->user()->getAttribute('fullname'))->where('department_id', auth()->user()->department->id)->latest()->take(5)->get() @endphp
                    @if(count($messages) > 0)  <span class="count" style="color: red"></span> @endif
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list p-2" style="min-width: 400px;max-width: 100%;overflow-wrap: break-word !important;height: 400px;overflow-x: hidden">

                    @if(count($messages) > 0)
                        <div>
                            @foreach($messages as $message)
                                @php $user = \App\Models\User::whereId($message->from)->first() @endphp
                                <a class="text-black" href="{{route('chats.index', ['from' => $message->from])}}">
                                    <h4 class="preview-subject font-weight-normal">{{$user->getAttribute('fullname')}}</h4>
                                    <p class="mb-1">Sizə Bir Mesaj Var: @if (strlen($message->message) > 200) {!!substr($message->message, 0, 200) . '...'!!}@else
                                        {{$message->message}} @endif
                                    </p>
                                </a>
                            @endforeach
                        </div>
                    @else

                    <div>
                        @foreach($rooms as $room)
                            <a class="text-black" href="{{route('rooms.index')}}">
                                <h4 class="preview-subject font-weight-normal">{{$room->user}}</h4>
                                <p class="mb-1">Sizin Departamentə Bir Mesaj Var: @if (strlen($room->message) > 200) {!!substr($room->message, 0, 200) . '...'!!}@else
                                    {{$room->message}} @endif
                                </p>
                            </a>
                        @endforeach
                    </div>

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
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list p-2" style="min-width: 400px;max-width: 100%;overflow-wrap: break-word !important;height: 400px;overflow-x: hidden">
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


     <script>
         document.getElementById('searchInput').addEventListener('input', function() {
             var query = this.value.trim();
             var dropdownMenu = document.getElementById('dropdownMenu');

             if (query.length >= 3) {
                 fetchResults(query);
             } else if (query.length < 3 && query.length >= 1) {
                 let html = '<li class="list-content mx-2">You should enter 3 character at least</li>'
                 populateDropdown(html)
             } else if (query.length === 0) {
                 dropdownMenu.classList.remove('show');
             }
         });

         async function fetchResults(query) {
             try {
                 const response = await fetch('{{ route("global-search") }}', {
                     method: 'POST',
                     headers: {
                         'Content-Type': 'application/json',
                         'X-CSRF-TOKEN': '{{ csrf_token() }}'
                     },
                     body: JSON.stringify({ query: query })
                 });

                 if (response.ok) {
                     const data = await response.json();
                     populateDropdown(data);
                 } else {
                     console.error('Search request failed.');
                 }
             } catch (error) {
                 console.error('Error:', error);
             }
         }

         function populateDropdown(html) {
             var dropdownMenu = document.getElementById('dropdownMenu');
             dropdownMenu.innerHTML = '<li></li>';
             dropdownMenu.innerHTML += html;
             dropdownMenu.classList.add('show');
         }
     </script>

 @endpush