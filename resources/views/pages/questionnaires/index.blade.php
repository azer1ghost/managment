@extends('layouts.main')

@section('title', __('translates.navbar.questionnaire'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.questionnaire')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{route('questionnaires.index')}}">
        <div class="row d-flex justify-content-between ">
            <div class="col-12 col-md-6 mb-3">
                <label for="client_id">@lang('translates.general.select_client')</label>
                <select name="client" id="client_id" class="form-control custom-select2" style="width: 100% !important;"
                        data-url="{{route('clients.search')}}">
                    <option value="" selected disabled>@lang('translates.general.select_client')</option>
                    @foreach($clients as $client)
                        <option @if(request()->get('client') === $client->getAttribute('id')) selected @endif value="{{$client->getAttribute('id')}}">{{$client->getAttribute('fullname')}}</option>
                    @endforeach
                </select>
            </div>
            @can('create', App\Models\Questionnaire::class)
                <div class="col-2 mt-4">
                    <a class="btn btn-outline-success float-right" href="{{route('questionnaires.create')}}">@lang('translates.buttons.create')</a>
                </div>
            @endcan
            <div class="col-12">
                <table class="table table-responsive-sm table-hover text-center">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">@lang('translates.fields.client')</th>
                        <th scope="col">@lang('translates.fields.created_at')</th>
                        <th scope="col">@lang('translates.columns.actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($questionnaires as $questionnaire)
                        <tr>
                            <th scope="row">{{$loop->iteration}}</th>
                            <td>{{$questionnaire->getRelationValue('client')->getAttribute('fullname')}}</td>
                            <td>{{$questionnaire->getAttribute('datetime')}}</td>
                            <td>
                                <div class="btn-sm-group">
                                    @can('view', $questionnaire)
                                        <a href="{{route('questionnaires.show', $questionnaire)}}" class="btn btn-sm btn-outline-primary">
                                            <i class="fal fa-eye"></i>
                                        </a>
                                    @endcan
                                    @can('update', $questionnaire)
                                        <a href="{{route('questionnaires.edit', $questionnaire)}}" class="btn btn-sm btn-outline-success">
                                            <i class="fal fa-pen"></i>
                                        </a>
                                    @endcan
                                    @can('delete', $questionnaire)
                                        <a href="{{route('questionnaires.destroy', $questionnaire)}}" delete data-name="{{$questionnaire->getAttribute('name')}}" class="btn btn-sm btn-outline-danger" >
                                            <i class="fal fa-trash"></i>
                                        </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <th colspan="20">
                                <div class="row justify-content-center m-3">
                                    <div class="col-7 alert alert-danger text-center" role="alert">@lang('translates.general.empty')</div>
                                </div>
                            </th>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="col-6">
                <div class="float-right">
                    {{$questionnaires->appends(request()->input())->links()}}
                </div>
            </div>
        </div>
    </form>
@endsection
@section('scripts')
    <script>
        $('select').change(function(){
            this.form.submit();
        });
    </script>
@endsection