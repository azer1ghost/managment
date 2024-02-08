@extends('layouts.main')
@section('content')
    <div class="row">
        <div class="col-12">
            @php
                $emailTemplate = \App\Models\EmailTemplate::where('name', 'template')->first();
            @endphp

            <form action="{{ route('email-templates.update', $emailTemplate->getAttribute('id'))  }}" method="POST">
                @csrf @method('PUT')
                <div class="form-group m-2">
                    <label for="name">@lang('translates.columns.name')</label>
                    <input name="" value=" {!! $emailTemplate->getAttribute('name')  !!}" disabled class="form-control" id="name">
                </div>
                <div>
                    <textarea name="content" id="summernote"  cols="250" rows="10" class="form-control "> {!! $emailTemplate->getAttribute('content')  !!}</textarea>
                    <button type="submit" class="btn btn-success float-right mt-3">@lang('translates.buttons.save')</button>
                    <a class="btn btn-secondary float-right mt-3 mr-2" href="{{ route('email') }}">@lang('translates.buttons.show')</a>
                </div>
            </form>
        </div>
    </div>

@endsection
@section('scripts')
    <script>
        $('#summernote').summernote({
            height: 500,
            minHeight: null,
            maxHeight: null,
            focus: true
        });
    </script>
@endsection