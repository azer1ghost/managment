@extends('layouts.main')
@section('content')
    <div class="row">
        <div class="col-12">
            <form action="{{ route('email-templates.store') }}" method="POST">
                @csrf
                <div class="form-group m-2">
                    <label for="name">@lang('translates.columns.name')</label>
                    <input name="name" class="form-control" id="name">
                </div>
                <div>
                    <textarea name="content" id="summernote"  cols="250" rows="10" class="form-control "></textarea>
                    <button type="submit" class="btn btn-success float-right mt-3">@lang('translates.buttons.save')</button>
                </div>
            </form>
        </div>
    </div>
    @php
        $emailTemplate = \App\Models\EmailTemplate::where('name', 'template')->first();
    @endphp
    <a class="btn btn-primary" href="{{ route('email-templates.edit', $emailTemplate->getAttribute('id')) }}">Edit</a>
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