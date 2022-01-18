@extends('layouts.main')

@section('title', __('translates.navbar.option'))

@section('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
@endsection
@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('options.index')">
            @lang('translates.navbar.option')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @if (!is_null($data))
                {{optional($data)->getAttribute('text')}}
            @else
                @lang('translates.buttons.create')
            @endif
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{$action}}" method="POST" enctype="multipart/form-data">
        @method($method) @csrf
        <input type="hidden" name="id" value="{{optional($data)->getAttribute('id')}}">
        <div class="tab-content form-row mt-4" >
            <x-translate>
                @foreach(config('app.locales') as $key => $locale)
                    <div class="tab-pane fade show @if($loop->first) active @endif" id="data-{{$key}}" role="tabpanel">
                        <div class="row">
                            <x-input::text  name="translate[text][{{$key}}]"  :value="optional($data)->getTranslation('text', $key)"     label="Text"     width="6" class="pr-3" />
                        </div>
                    </div>
                @endforeach
            </x-translate>
        </div>
        @if($action)
            <x-input::submit />
        @endif
    </form>
@endsection

@section('scripts')
<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

@if(is_null($action))
<script>
    $('input').attr('readonly', true)
    $('select').attr('disabled', true)
    $('textarea').attr('readonly', true)
</script>
@endif

<script>
    $('.filterSelector').selectpicker()
</script>

@endsection