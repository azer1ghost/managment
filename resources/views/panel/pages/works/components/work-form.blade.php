@push('style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
@endpush
<form action="{{$action}}" method="POST" enctype="multipart/form-data" id="work-form">
    @if(!is_null($data) && $method != 'PUT')
        @can('update', $data)
            <div class="col-12 my-4 pl-0">
                <a class="btn btn-outline-success" href="{{route('works.edit', $data)}}">Edit</a>
            </div>
        @endcan
    @endif
    @method($method) @csrf
    <div wire:loading.delay class="col-12 mr-2" style="position: absolute;right:20px">
        <div style="position: absolute;right: 0;top: -25px">
            <div class="spinner-border text-primary" role="status"></div>
        </div>
    </div>
    <div class="tab-content row my-5">
        <div class="form-group col-12">
            <div class="row m-0">
                <div class="form-group col-12 col-md-6" wire:ignore>
                    <label for="data-client-type">{{trans('translates.fields.clientName')}}</label><br/>
                    <div class="d-flex align-items-center">
                        <select name="client_id" id="data-client-type" style="width: 100% !important;">
                            @if(is_numeric(optional($data)->getAttribute('client_id')))
                                <option value="{{optional($data)->getAttribute('client_id')}}">{{optional($data)->getRelationValue('client')->getAttribute('fullname_with_voen')}}</option>
                            @endif
                        </select>
                        @if(optional($data)->getAttribute('status') != \App\Models\Work::DONE)
                            <a target="_blank" href="{{route('clients.create', ['type' => \App\Models\Client::LEGAL])}}" class="btn btn-outline-success ml-3">
                                <i class="fa fa-plus"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <div class="form-group col-12 col-md-6" wire:ignore>
                    <label for="data-service_id">@lang('translates.general.work_service')</label>
                    @if(request()->has('service_id') || !is_null($data))
                        @php($service = request()->get('service_id') ?? optional($data)->getAttribute('service_id'))
                        <input disabled type="text" class="form-control" id="data-service_id" value="{{\App\Models\Service::find($service)->name}}">
                        <input type="hidden" @if(empty($this->subServices)) name="service_id"  @endif value="{{$service}}">
                    @endif
                </div>

                @if(!$this->subServices->isEmpty())
                    <div class="form-group col-12 col-md-6" wire:ignore>
                        <label for="data-service_id">@lang('translates.general.work_service_type')</label>
                        <select name="service_id" id="data-service_id" class="form-control">
                            @foreach($this->subServices as $service)
                                <option @if(optional($data)->getAttribute('service_id') === $service->id ) selected @endif
                                value="{{ $service->getAttribute('id') }}"
                                >
                                    {{ $service->getAttribute('name') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="form-group col-12 col-md-6">
                    <label for="data-department_id">@lang('translates.general.department_select')</label>
                    <select name="department_id" id="data-department_id" class="form-control" wire:model="selected.department_id" @if(!auth()->user()->isDeveloper() && !auth()->user()->isDirector()) disabled @endif>
                        <option value="" selected>@lang('translates.general.department_select')</option>
                        @foreach($departments as $department)
                            <option value="{{$department->getAttribute('id')}}">{{$department->getAttribute('name')}}</option>
                        @endforeach
                    </select>
                    @if(!auth()->user()->isDeveloper() && !auth()->user()->isDirector())
                        <input wire:ignore type="hidden" wire:model="selected.department_id" name="department_id">
                    @endif
                </div>

                @if($selected['department_id'])
                    <div class="form-group col-12 col-md-6" wire:key="department-user">
                        <label for="data-user_id">@lang('translates.general.user_select')</label>
                        <select name="user_id" id="data-user_id" class="form-control" wire:model="selected.user_id" @if(!auth()->user()->isDeveloper() && !auth()->user()->isDirector() && !auth()->user()->hasPermission('canRedirect-work')) disabled @endif>
                            <option value="" selected>@lang('translates.general.user_select')</option>
                            @foreach($this->department->users()->orderBy('name')->with('position')->isActive()->get(['id', 'name', 'surname', 'position_id', 'role_id']) as $user)
                                <option value="{{ $user->getAttribute('id') }}">{{ $user->getAttribute('fullname_with_position') }}</option>
                            @endforeach
                        </select>
                        @if(!auth()->user()->isDeveloper() && !auth()->user()->isDirector() && !auth()->user()->hasPermission('canRedirect-work'))
                            <input type="hidden" wire:model="selected.user_id" name="user_id">
                        @endif
                    </div>
                @endif

                @if($this->service->getAttribute('has_asan_imza'))
                    <div class="form-group col-12 col-md-6" wire:key="asan-imza" wire:ignore>
                        <label for="data-asan_imza_id">Asan imza</label>
                        <select name="asan_imza_id" id="data-asan_imza_id" class="select2 form-control">
                            <option value="" selected>Asan imza select</option>
                            @foreach(\App\Models\AsanImza::get() as $asanUser)
                                <option
                                        value="{{ $asanUser->getAttribute('id') }}"
                                        @if(optional($data)->getAttribute('asan_imza_id') == $asanUser->getAttribute('id')) selected @endif
                                >
                                    {{ $asanUser->getRelationValue('user')->getAttribute('fullname') }}
                                    ({{ $asanUser->getRelationValue('company')->getAttribute('name') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="form-group col-12 col-md-3" wire:ignore>
                    <label for="data-hard_level">@lang('translates.general.hard_level_choose')</label>
                    <select name="hard_level" id="data-hard_level" class="form-control">
                        <option disabled selected>@lang('translates.general.hard_level_choose')</option>
                        @foreach($hardLevels as $key => $hard_level)
                            <option @if(optional($data)->getAttribute('hard_level') === $hard_level ) selected
                                    @endif value="{{$hard_level}}">@lang('translates.hard_level.' . $key)</option>
                        @endforeach
                    </select>
                </div>

                @if($method != 'POST')
                    <div class="form-group col-12 col-md-3" wire:ignore>
                        <label for="data-status">@lang('translates.general.status_choose')</label>
                        <select name="status" id="data-status" class="form-control">
                            <option disabled >@lang('translates.general.status_choose')</option>
                            @foreach($statuses as $key => $status)
                                <option
                                        @if(optional($data)->getAttribute('status') === $status ) selected
                                        @endif value="{{$status}}"
                                        @if($status == \App\Models\Work::REJECTED ) disabled
                                        @endif
                                >
                                    @lang('translates.work_status.' . $key)
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                @foreach($parameters as $parameter)
                    @switch($parameter->type)
                        @case('text')
                            <div class="form-group col-12 col-md-3" wire:ignore>
                                <label for="data-parameter-{{$parameter->id}}">{{$parameter->label}}</label>
                                <input type="text" name="parameters[{{$parameter->id}}]" {{$parameter->attributes}} id="data-parameter-{{$parameter->id}}" class="form-control" placeholder="{{$parameter->placeholder}}" wire:model="workParameters.{{$parameter->name}}">
                            </div>
                            @break
                        @case('number')
                            <div class="form-group col-12 col-md-3" wire:ignore>
                                <label for="data-parameter-{{$parameter->id}}">{{$parameter->label}}</label>
                                <input type="number" name="parameters[{{$parameter->id}}]" {{$parameter->attributes}} id="data-parameter-{{$parameter->id}}" class="form-control" placeholder="{{$parameter->placeholder}}" wire:model="workParameters.{{$parameter->name}}">
                            </div>
                        @break
                        @case('select')
                            <div class="form-group col-12 col-md-3" wire:ignore>
                                <label for="data-parameter-{{$parameter->id}}">{{$parameter->label}}</label>
                                <select name="parameters[{{$parameter->id}}]" {{$parameter->attributes}} id="data-parameter-{{$parameter->id}}" class="form-control" wire:model="workParameters.{{$parameter->name}}">
                                    <option value="" selected>{{$parameter->placeholder}}</option>
                                    @foreach($parameter->getRelationValue('options') as $option)
                                        <option value="{{$option->id}}">{{$option->text}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @break
                    @endswitch
                @endforeach

                @if(auth()->user()->hasPermission('editEarning-work') && $method != 'POST' && optional($data)->getAttribute('status') == \App\Models\Work::DONE)
                    <div class="form-group col-12 col-md-6" wire:ignore>
                        <div class="d-flex">
                            <div class="btn-group mr-3 flex-column" role="group">
                                <label for="data-earning">@lang('translates.general.work_earning')</label>
                                <div class="d-flex">
                                    <input id="data-earning" type="number" min="0" class="form-control" name="earning" wire:model="earning" style="border-radius: 0 !important;">
                                    <select name="currency" id="" class="form-control" style="border-radius: 0 !important;" wire:model="currency">
                                        @foreach(['AZN', 'USD', 'TRY', 'EUR', 'RUB'] as $currency)
                                            <option value="{{$currency}}" @if($currency == optional($data)->getAttribute('currency')) selected @endif>{{$currency}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('earning')
                                <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="btn-group flex-column" role="group">
                                <label for="data-earning">@lang('translates.general.rate')</label>
                                <div class="d-flex">
                                    <input type="text" class="form-control" name="currency_rate" wire:model="rate" style="border-radius: 0 !important;" readonly>
                                    <input disabled type="text" class="form-control" value="AZN" style="border-radius: 0 !important;">
                                </div>
                                @error('currency_rate')
                                <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    @if(optional($data)->getAttribute('status') == \App\Models\Work::DONE)
                        <div class="form-group col-12" style="padding-left: 35px" wire:ignore>
                            <input type="checkbox" class="form-check-input" id="data-verified" name="verified" @if(!is_null(optional($data)->getAttribute('verified_at'))) checked @endif>
                            <label class="form-check-label" for="data-verified">@lang('translates.columns.verified')</label>
                        </div>
                        <div class="form-group col-12" style="padding-left: 35px" wire:ignore>
                            <input type="checkbox" class="form-check-input" id="data-rejected" name="rejected" @if(optional($data)->getAttribute('status') == \App\Models\Work::REJECTED) checked @endif>
                            <label class="form-check-label" for="data-rejected">Rejected</label>
                        </div>
                    @endif
                @endif

                <div class="form-group col-12" wire:ignore>
                    <label for="data-detail">@lang('translates.general.work_detail')</label>
                    <textarea wire:ignore name="detail" id="data-detail" class="summernote">{{optional($data)->getAttribute('detail')}}</textarea>
                </div>
            </div>
        </div>
    </div>
    @if($action)
        <x-input::submit :value="__('translates.buttons.save')"/>
    @endif
</form>
@if(!is_null($data))
    <div class="col-12">
        <x-documents :documents="$data->documents"/>
        <x-document-upload :id="$data->id" model="Work"/>
    </div>
@endif
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js" type="text/javascript"></script>

    @if(is_null($action) || optional($data)->getAttribute('status') == \App\Models\Work::DONE)
        <script>
            $('#work-form :input').attr('disabled', true)
        </script>
    @endif

    @if(optional($data)->getAttribute('status') == \App\Models\Work::DONE && auth()->user()->hasPermission('editEarning-work') && $method == 'PUT')
        <script>
            $('input[name="earning"]').attr('disabled', false);
            $('select[name="currency"]').attr('disabled', false);
            $('input[name="currency_rate"]').attr('disabled', false);
            $('input[name="verified"]').attr('disabled', false);
            $('input[name="rejected"]').attr('disabled', false);
            $('input[name="_method"]').attr('disabled', false);
            $('input[name="_token"]').attr('disabled', false);
            $('button[type="submit"]').attr('disabled', false);
        </script>
    @endif

    <script>
        const clientSelect2 = $('select[name="client_id"]');
        const asanImzaSelect2 = $('select[name="asan_imza_id"]');

        asanImzaSelect2.select2({
            placeholder: "Search",
            minimumInputLength: 3,
            // width: 'resolve',
            theme: 'bootstrap4',
            focus: true,
            ajax: {
                delay: 500,
                url: "{{route('asanImza.search')}}",
                dataType: 'json',
                type: 'GET',
                data: function (params) {
                    return {
                        search: params.term,
                    }
                }
            }
        })

        clientSelect2.select2({
            placeholder: "Search",
            minimumInputLength: 3,
            // width: 'resolve',
            theme: 'bootstrap4',
            focus: true,
            ajax: {
                delay: 500,
                url: "{{route('clients.search')}}",
                dataType: 'json',
                type: 'GET',
                data: function (params) {
                    return {
                        search: params.term,
                    }
                }
            }
        })

        clientSelect2.on('select2:open', function (e) {
            document.querySelector('.select2-search__field').focus();
        });
        asanImzaSelect2.on('select2:open', function (e) {
            document.querySelector('.select2-search__field').focus();
        });

        const summernote = $('.summernote');
        summernote.summernote({
            placeholder: 'Detail',
            height: 250,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear', 'italic']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });

        summernote.summernote('{{is_null($action) || optional($data)->getAttribute('status') == \App\Models\Work::DONE ? 'disable' : 'enable'}}');
    </script>
@endpush