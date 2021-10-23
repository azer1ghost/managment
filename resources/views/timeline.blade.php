<link rel="stylesheet" href="{{asset('assets/css/app.css')}}">
<div id="timeline-container">
    <div class="container">
    <div class="item">
        <div id="timeline">
            <div>
{{--                @dd($updates)--}}
{{--                @foreach($updates as $date => $update)--}}
{{--                    @dd(\Carbon\Carbon::parse($date)->format('Y F d'))--}}
{{--                @endforeach--}}
                @foreach($updates as $date => $update)
                    <section class="year">
                        <h3>{{\Carbon\Carbon::parse($date)->format('Y F d')}}</h3>
                        @foreach($update as $subUpdate)
                            <section>
                                <ul>
                                    <li style="font-weight: 900;font-size: 23px">{{$subUpdate->name}}</li>
                                    @foreach($subUpdate->updates as $subUpdates)
                                        <li>{{$subUpdates->name}}</li>
                                    @endforeach
                                </ul>
                            </section>
                        @endforeach
                    </section>
                @endforeach
            </div>
        </div>
    </div>
</div>
</div>