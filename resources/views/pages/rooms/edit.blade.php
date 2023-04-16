@extends('layouts.main')

@section('title', trans('translates.navbar.room'))
@section('style')

@endsection
@section('content')
    @php
     $department = \App\Models\Department::where('id',request()->get('department_id'))->first()
    @endphp
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('rooms.index')">
            @lang('translates.navbar.room')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
      {{$department->getAttribute('name')}}
        </x-bread-crumb-link>
    </x-bread-crumb>
        <section>
            <div class="row d-flex justify-content-center">
                <div class="col-md-12 col-lg-12 col-xl-10">

                    <div class="card" id="chat1" style="border-radius: 15px;">
                        <div class="card-header d-flex justify-content-between align-items-center p-3 bg-info text-white border-bottom-0"
                                style="border-top-left-radius: 15px; border-top-right-radius: 15px;">
                            <i class="fas fa-angle-left"></i>
                            <p class="mb-0 fw-bold" data-some-property="{{$department->getAttribute('id')}}" id="chatDepartment">{{$department->getAttribute('name')}}</p>
                            <i class="fas fa-angle-right"></i>
                        </div>
                        <div class="card-body">
                            <div class="col-12 p-3" id="scroll" style="height: 500px; overflow-y: scroll">
                                <ul class="list-group" id="chat-messages"  >
                                    <li class="list-group-item"></li>
                                </ul>

                            </div>
                            <hr>
                            <div class="row mb-3">
                                <div class="col-12">
                                    <input aria-label="message" type="text" name="message" class="form-control" id="chat-input" placeholder="Type your message">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
@endsection
@section('scripts')
<script src="{{asset('assets/js/pusher/pusher.js)}}"></script>
<script>
    var chatMessage = document.getElementById("chat-messages");
    chatMessage.addEventListener("DOMNodeInserted", function(event) {
        var element = event.target;
        if (element.tagName === "LI") {
            var objDiv = document.getElementById("scroll");
            objDiv.scrollTop = objDiv.scrollHeight;
        }
    });
    const chatDepartment = document.getElementById('chatDepartment').getAttribute('data-some-property');

            const chatMessages = $('#chat-messages');
            const chatInput = $('#chat-input');

            const pusher = new Pusher('5e68408656b975a4e1e4', {
                cluster: 'mt1'
            });

            const channel = pusher.subscribe('room');
            channel.bind('RoomEvent', function (e) {
                const message = $('<li>').addClass('list-group-item');
                const strong = $('<strong>').text(e.user + ' : ');
                const text = $('<span>').text(e.message);
                message.append(strong).append(text);
                chatMessages.append(message);
            })


            chatInput.on('keyup', function(event) {
                if (event.keyCode === 13) {
                    const message = chatInput.val();
                    const department_id = chatDepartment;

                    axios.post('/module/sendMessage', {
                        message,
                        department_id,
                    }).then(function (response) {
                        console.log(response)
                    }).catch(function (error) {
                        console.log(error)
                    });
                    chatInput.val('')
                    scrollToBottomFunc()
                }
            });


            axios.get('/module/getMessage')
                .then(response => {
                    const chats = response.data;
                    const filteredChats = chats.filter(chat => chat.department_id == chatDepartment);
                    filteredChats.forEach(chat => {
                        const message = $('<li>').addClass('list-group-item');
                        const strong = $('<strong>').text(chat.user + ' : ');
                        const text = $('<span>').text(chat.message);

                        message.append(strong).append(text);
                        chatMessages.append(message);
                    });
                })
                .catch(error => {
                    console.log(error);
                });

</script>

@endsection
