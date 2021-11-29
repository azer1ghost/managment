@push('style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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
                        @if(is_numeric(optional($data)->getAttribute('client_id')))
                            @can('update', \App\Models\Client::find(optional($data)->getAttribute('client_id')))
                                <a target="_blank" href="{{route('clients.edit', optional($data)->getAttribute('client_id'))}}" class="btn btn-outline-primary ml-3">
                                    <i class="fa fa-pen"></i>
                                </a>
                            @endcan
                        @endif
                        @if(optional($data)->getAttribute('status') != \App\Models\Work::DONE)
                            <a target="_blank" href="{{route('clients.create', ['type' => \App\Models\Client::PHYSICAL])}}" class="btn btn-outline-success ml-1">
                                <i class="fa fa-plus"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <div class="form-group col-12 col-md-6" wire:ignore>
                    <label for="data-service_id">@lang('translates.general.work_service')</label>
                    @if(request()->has('service_id') || !is_null($data))
                        @php($serviceId = request()->get('service_id') ?? optional($data)->getAttribute('service_id'))
                        @php($service = \App\Models\Service::find($serviceId))
                        <input disabled type="text" class="form-control" id="data-service_id" value="{{$service->name}}">
                        <input type="hidden" @if(empty($this->subServices)) name="service_id"  @endif value="{{$serviceId}}">
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

                @if($this->service->getAttribute('has_asan_imza') && $method != 'POST')
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

                @if(!is_null($data))
                    <x-input::text wire:ignore name="datetime" :label="__('translates.fields.date')" value="{{$data->getAttribute('datetime')->format('Y-m-d H:i')}}" type="text" width="3" class="pr-3" />
                @endif

                @foreach($parameters as $parameter)
                    @if(in_array('hideOnPost', explode(' ', $parameter->attributes)) && $method == 'POST')
                        @continue
                    @endif
                    @switch($parameter->type)
                        @case('text')
                            <div class="form-group col-12 col-md-3" wire:ignore>
                                <label for="data-parameter-{{$parameter->id}}">{{$parameter->label}}</label>
                                <input type="text" data-label="{{$parameter->getTranslation('label', 'az')}}" name="parameters[{{$parameter->id}}]" {{$parameter->attributes}} id="data-parameter-{{$parameter->id}}" class="form-control parameters" placeholder="{{$parameter->placeholder}}" wire:model="workParameters.{{$parameter->name}}">
                            </div>
                            @break
                        @case('number')
                            <div class="form-group col-12 col-md-3" wire:ignore>
                                <label for="data-parameter-{{$parameter->id}}">{{$parameter->label}}</label>
                                <input type="number" data-label="{{$parameter->getTranslation('label', 'az')}}" name="parameters[{{$parameter->id}}]" {{$parameter->attributes}} id="data-parameter-{{$parameter->id}}" class="form-control parameters" placeholder="{{$parameter->placeholder}}" wire:model="workParameters.{{$parameter->name}}">
                            </div>
                        @break
                        @case('select')
                            <div class="form-group col-12 col-md-3" wire:ignore>
                                <label for="data-parameter-{{$parameter->id}}">{{$parameter->label}}</label>
                                <select data-label="{{$parameter->getTranslation('label', 'az')}}" name="parameters[{{$parameter->id}}]" {{$parameter->attributes}} id="data-parameter-{{$parameter->id}}" class="form-control parameters" wire:model="workParameters.{{$parameter->name}}">
                                    <option value="" selected>{{$parameter->placeholder}}</option>
                                    @foreach($parameter->getRelationValue('options') as $option)
                                        <option value="{{$option->id}}" data-value="{{$option->getTranslation('text', 'az')}}">{{$option->text}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @break
                    @endswitch
                @endforeach

                @if(auth()->user()->hasPermission('canVerify-work') && $method != 'POST' && optional($data)->getAttribute('status') == \App\Models\Work::DONE)
                    <div class="form-group col-12" style="padding-left: 35px" wire:ignore>
                        <input type="checkbox" class="form-check-input" id="data-verified" name="verified" @if(!is_null(optional($data)->getAttribute('verified_at'))) checked @endif>
                        <label class="form-check-label" for="data-verified">@lang('translates.columns.verified')</label>
                    </div>
                    <div class="form-group col-12" style="padding-left: 35px" wire:ignore>
                        <input type="checkbox" class="form-check-input" id="data-rejected" name="rejected" @if(optional($data)->getAttribute('status') == \App\Models\Work::REJECTED) checked @endif>
                        <label class="form-check-label" for="data-rejected">@lang('translates.columns.rejected')</label>
                    </div>
                @endif

                <div class="form-group col-12" wire:ignore>
                    <label for="data-detail">@lang('translates.general.work_detail')</label>
                    <textarea wire:ignore name="detail" id="data-detail" class="summernote">{{optional($data)->getAttribute('detail')}}</textarea>
                </div>
            </div>

            @if(optional(optional($data)->user())->exists() && \App\Models\Work::DONE == optional($data)->getAttribute('status'))
                <div class="col-12">
                    <button type="button" class="btn btn-outline-success copy">@lang('translates.buttons.copy')</button>
                </div>
            @endif

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
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    @php($isShow = is_null($action))
    @php($hasNotPermission = !auth()->user()->can('update', $data))
    @php($isDone = optional($data)->getAttribute('status') == \App\Models\Work::DONE)
    @php($isVerified = !is_null(optional($data)->getAttribute('verified_at')))

    @if(($isShow || ($hasNotPermission && $method != 'POST')) || ($hasNotPermission && $isVerified) || ($isDone && $method != 'PUT'))
        <script>
            $('#work-form :input').attr('disabled', true)
        </script>
    @endif

    @if(optional($data)->getAttribute('status') == \App\Models\Work::DONE && auth()->user()->hasPermission('canVerify-work') && $method == 'PUT')
        <script>
            $('input[name="verified"]').attr('disabled', false);
            $('input[name="rejected"]').attr('disabled', false);
            $('input[name="_method"]').attr('disabled', false);
            $('input[name="_token"]').attr('disabled', false);
            $('button[type="submit"]').attr('disabled', false);
        </script>
    @endif

    <script>
        @if($method != 'POST')
            $('#work-form .copy').attr('disabled', false)
        @endif

        $('input[name="datetime"]').daterangepicker({
                opens: 'left',
                locale: {
                    format: "YYYY-MM-DD HH:mm",
                },
                singleDatePicker: true,
                timePicker: true,
                timePicker24Hour: true,
            }, function(start, end, label) {}
        );

        @if(optional(optional($data)->user())->exists() && optional($data)->getAttribute('status') == \App\Models\Work::DONE)
            function html(html, type = 'string')
            {
                let tmp = document.createElement("div");
                tmp.innerHTML = html;

                if(type === 'string'){
                    return tmp.innerText || tmp.textContent || "";
                }

                return tmp;
            }

            function copyToClipboard(element) {
                const $temp = $("<textarea>");
                const brRegex = /<br\s*[\/]?>/gi;
                $("body").append($temp);
                $temp.val($(element).html().replace(brRegex, "\r\n")).select();
                document.execCommand("copy");
                $temp.remove();
            }

            $('.copy').click(function (){
                const service = '{{$data->getRelationValue('service')->getTranslation('name', 'az')}}';
                const user = '{{$data->getRelationValue('user')->getAttribute('fullname')}}';
                const client = '{{$data->getRelationValue('client')->getAttribute('fullname_with_voen')}}';
                const asanImza = '{{$data->getRelationValue('asanImza')->getAttribute('user_with_company')}}';
                const date = '{{$data->getAttribute('datetime')}}';
                const detail = $(html('{{$data->getAttribute('detail')}}')).text();
                let data = `Xidmət: ${service}<br/>Müştəri: ${client}<br/>İcra edən: ${user}<br/>Asan imza: ${asanImza}<br/>Tarix: ${date}<br/>`;

                $(".parameters").map(function (){
                    let value = $(this).is('select') ? $(this).find('option:selected').data('value') : $(this).val();
                    data += `${$(this).data('label')}: ${value}<br/>`;
                });
                data += `${detail ? `Detal: ${detail}` : ''}`;

                copyToClipboard(html(data, 'html'));
            });

        @endif


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
                url: "{{route('asanImza.user.search')}}",
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

        summernote.summernote('{{($isShow || ($hasNotPermission && $method != 'POST')) || ($isDone && $method != 'PUT') || ($isVerified && $method != 'PUT') ? 'disable' : 'enable'}}');
    </script>
@endpush