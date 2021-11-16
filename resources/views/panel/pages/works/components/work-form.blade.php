@push('style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
@endpush
<form action="{{$action}}" method="POST" enctype="multipart/form-data">
    @method($method) @csrf

    <div class="tab-content row mt-4">
        <div class="form-group col-12">
            <div class="row">
                <div class="form-group col-12 col-md-6" wire:ignore>
                    <label for="data-client-type">{{trans('translates.fields.clientName')}}</label><br/>
                    <select name="client_id" class="select2" style="width: 100% !important;">
                        @if(is_numeric(optional($data)->getAttribute('client_id')))
                            <option value="{{optional($data)->getAttribute('service_id')}}">{{optional($data)->getRelationValue('client')->getAttribute('fullname')}}</option>
                        @endif
                    </select>
                </div>

                <div class="form-group col-12 col-md-6" wire:ignore>
                    <label for="data-service_id">@lang('translates.general.work_service')</label>
                    @if(request()->has('service_id') || !is_null($data))
                        @php($service = request()->get('service_id') ?? optional($data)->getAttribute('service_id'))
                        <input disabled type="text" class="form-control" id="data-service_id" value="{{\App\Models\Service::find($service)->name}}">
                        <input type="hidden" name="service_id"  value="{{$service}}">
                    @endif
                </div>

                <div class="form-group col-12 col-md-6">
                    <label for="data-department_id">@lang('translates.general.department_select')</label>
                    <select name="department_id" id="data-department_id" class="form-control" wire:model="selected.department_id">
                        <option value="" selected>Department Select</option>
                        @foreach($departments as $department)
                            <option value="{{$department->getAttribute('id')}}">{{$department->getAttribute('name')}}</option>
                        @endforeach
                    </select>
                </div>

                @if($selected['department_id'])
                    <div class="form-group col-12 col-md-6">
                        <label for="data-user_id">@lang('translates.general.user_select')</label>
                        <select name="user_id" id="data-user_id" class="form-control" wire:model="selected.user_id">
                            <option value="" selected>User Select</option>
                            @foreach($this->department->users()->with('position')->isActive()->get(['id', 'name', 'surname']) as $user)
                                <option value="{{ $user->getAttribute('id') }}">{{ $user->getAttribute('fullname_with_position') }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                @foreach($parameters as $parameter)
                    @switch($parameter->type)
                        @case('text')
                            <div class="form-group col-12 col-md-6">
                                <label for="data-parameter-{{$parameter->id}}">{{$parameter->label}}</label>
                                <input type="text" name="parameters[{{$parameter->id}}]" id="data-parameter-{{$parameter->id}}" class="form-control" placeholder="{{$parameter->placeholder}}" wire:model="workParameters.{{$parameter->name}}">
                            </div>
                            @break
                        @case('select')
                            <div class="form-group col-12 col-md-6">
                                <label for="data-parameter-{{$parameter->id}}">@lang('translates.parameters.types.' . $parameter->name)</label>
                                <select name="parameters[{{$parameter->id}}]" id="data-parameter-{{$parameter->id}}" class="form-control" wire:model="workParameters.{{$parameter->name}}">
                                    <option value="" selected>Select work {{$parameter->name}}</option>
                                    @foreach($parameter->options as $option)
                                        <option value="{{$option->id}}">{{$option->text}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @break
                    @endswitch
                @endforeach

                @if(auth()->user()->hasPermission('editEarning-work'))

                    <div class="form-group col-12 col-md-6" wire:ignore>
                        <div class="d-flex">
                            <div class="btn-group mr-5 flex-column" role="group">
                                <label for="data-earning">@lang('translates.general.work_earning')</label>
                                <div class="d-flex">
                                    <input id="data-earning" type="number" min="0" class="form-control" name="earning" wire:model="earning" style="border-radius: 0 !important;">
                                    <select name="currency" id="" class="form-control" style="border-radius: 0 !important;" wire:model="currency">
                                        @foreach(['USD', 'AZN', 'TRY', 'EUR', 'RUB'] as $currency)
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
                                    <input type="text" class="form-control" name="currency_rate" wire:model="rate" style="border-radius: 0 !important;">
                                    <input disabled type="text" class="form-control" value="AZN" style="border-radius: 0 !important;">
                                </div>
                                @error('currency_rate')
                                <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                @endif

                <div class="form-group col-12" wire:ignore>
                    <label for="data-detail">@lang('translates.general.work_detail')</label>
                    <textarea name="detail" id="data-detail" class="summernote">{{optional($data)->getAttribute('detail')}}</textarea>
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

    @if(is_null($action))
        <script>
            $('form :input').attr('disabled', true)
        </script>
    @endif
    <script>
        $('.select2').select2({
            placeholder: "Search",
            minimumInputLength: 3,
            // width: 'resolve',
            focus: true,
            ajax: {
                delay: 500,
                url: "{{route('services.search')}}",
                dataType: 'json',
                data: function (params) {
                    return {
                        search: params.term,
                    }
                }
            }
        })
        $('.select2').on('select2:open', function (e) {
            document.querySelector('.select2-search__field').focus();
        });

        const summernote = $('.summernote');
        summernote.summernote({
            placeholder: 'Results',
            height: 200,
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
        summernote.summernote('{{is_null($action) ? 'disable' : 'enable'}}');
    </script>
@endpush