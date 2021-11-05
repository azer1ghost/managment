<div class="col-12">
    <ul class="nav nav-tabs" role="tablist">
        @foreach(config('app.locales') as $key => $locale)
            <li class="nav-item" role="presentation">
                <a class="nav-link @if($loop->first) active @endif text-dark" data-toggle="tab" href="#data-{{$key}}" role="tab">{{ucfirst($key)}}</a>
            </li>
        @endforeach
    </ul>
    <div class="tab-content pt-3">
        {{$slot}}
    </div>
</div>